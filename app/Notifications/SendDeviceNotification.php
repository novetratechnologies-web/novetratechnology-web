<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\FcmHttpV1Channel;

class SendDeviceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public array $data;
    public string $token;

    public function __construct(array $data, string $token)
    {
        $this->queue = 'notification';
        $this->data  = $data;
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return [FcmHttpV1Channel::class];
    }

    public function toFcmHttpV1($notifiable): array
    {
        $type = (string) ($this->data['type'] ?? 'Notification');
        $message = (string) ($this->data['message'] ?? '');
        $data = array_filter([
            'id' => $this->data['id'] ?? null,
            'type' => $type,
            'message' => $message,
        ], static fn ($value) => $value !== null);

        return [
            'token' => $this->token,
            'notification' => [
                'title' => $type,
                'body' => $message,
            ],
            'data' => array_map(static fn ($value) => (string) $value, $data),
            'android' => [
                'notification' => [
                    'color' => '#0A0A0A',
                    'sound' => 'default',
                ],
                'fcm_options' => [
                    'analytics_label' => 'analytics',
                ],
            ],
            'apns' => [
                'payload' => [
                    'aps' => [
                        'sound' => 'default',
                    ],
                ],
                'fcm_options' => [
                    'analytics_label' => 'analytics_ios',
                ],
            ],
        ];
    }
}
