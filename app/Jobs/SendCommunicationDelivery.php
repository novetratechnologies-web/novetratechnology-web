<?php

namespace App\Jobs;

use App\Models\CommunicationDelivery;
use App\Notifications\ChurchCommunicationNotification;
use App\Services\Communication\HostPinnacleSmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Throwable;

class SendCommunicationDelivery implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 5;
    public int $timeout = 60;

    public function __construct(public int $deliveryId)
    {
    }

    public function backoff(): array
    {
        return [30, 120, 600, 1800];
    }

    public function handle(HostPinnacleSmsService $sms): void
    {
        $delivery = CommunicationDelivery::with('user')->find($this->deliveryId);
        if (!$delivery || $delivery->status === 'sent') {
            return;
        }

        $delivery->update(['status' => 'processing', 'failure_reason' => null]);

        try {
            $payload = $delivery->payload ?? [];
            $providerId = null;

            if ($delivery->channel === 'email') {
                Mail::raw($payload['body'] ?? '', function (Message $message) use ($delivery, $payload) {
                    $message->to($delivery->recipient)
                        ->subject($payload['title'] ?? 'Church update');
                });
            } elseif ($delivery->channel === 'sms') {
                $response = $sms->send($delivery->recipient, $payload['body'] ?? '');
                $providerId = $response['transactionId'] ?? null;
            } elseif ($delivery->channel === 'in_app') {
                if (!$delivery->user) {
                    throw new \RuntimeException('The notification recipient no longer exists.');
                }
                Notification::send($delivery->user, new ChurchCommunicationNotification($payload));
            } else {
                throw new \RuntimeException('Unsupported communication channel.');
            }

            $delivery->update([
                'status' => 'sent',
                'provider_message_id' => $providerId,
                'sent_at' => now(),
                'failure_reason' => null,
            ]);
        } catch (Throwable $exception) {
            $delivery->update([
                'status' => 'queued',
                'failure_reason' => mb_substr($exception->getMessage(), 0, 4000),
            ]);
            throw $exception;
        }
    }

    public function failed(Throwable $exception): void
    {
        CommunicationDelivery::whereKey($this->deliveryId)->update([
            'status' => 'failed',
            'failure_reason' => mb_substr($exception->getMessage(), 0, 4000),
        ]);
    }
}
