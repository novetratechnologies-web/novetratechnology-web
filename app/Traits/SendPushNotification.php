<?php

namespace App\Traits;

use App\Services\FcmHttpV1Client;

/**
 * Trait SendPushNotification
 *
 * Handles Firebase Cloud Messaging (FCM) push notifications including:
 * - Configuring FCM server and sender IDs
 * - Building notification payloads and options
 * - Sending push notifications to user devices
 * - Tracking notification delivery status
 *
 * @package App\Traits
 */
trait SendPushNotification

{
  /**
   * Send a Firebase Cloud Messaging (FCM) push notification.
   *
   * Sends a push notification to a user's device(s) using FCM with
   * customizable title, message, and data payload.
   *
   * @param array $array The notification array containing 'type' and 'message'
   * @param string $usertoken The device token to send the notification to
   *
   * @return object The FCM downstreamResponse object with delivery status
   */
  public function sendNotification(array $array, string $usertoken): object
  {
    $type = (string) ($array['type'] ?? 'Notification');
    $message = (string) ($array['message'] ?? '');
    $result = app(FcmHttpV1Client::class)->send([
      'token' => $usertoken,
      'notification' => [
        'title' => $type,
        'body' => $message,
      ],
      'data' => [
        'type' => $type,
        'message' => $message,
      ],
    ]);

    return (object) [
      'status' => true,
      'message' => $result['name'] ?? 'Notification sent',
    ];
  }
}
