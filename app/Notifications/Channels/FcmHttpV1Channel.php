<?php

namespace App\Notifications\Channels;

use App\Services\FcmHttpV1Client;
use Illuminate\Notifications\Notification;
use LogicException;

class FcmHttpV1Channel
{
    public function __construct(private FcmHttpV1Client $client)
    {
    }

    /**
     * Send the notification through Google's FCM HTTP v1 API.
     */
    public function send($notifiable, Notification $notification): array
    {
        if (! method_exists($notification, 'toFcmHttpV1')) {
            throw new LogicException('The notification does not define an FCM HTTP v1 payload.');
        }

        return $this->client->send($notification->toFcmHttpV1($notifiable));
    }
}
