<?php

namespace Tests\Unit;

use App\Notifications\SendDeviceNotification;
use PHPUnit\Framework\TestCase;

class SendDeviceNotificationTest extends TestCase
{
    public function test_payload_maps_notification_fields_for_both_mobile_platforms(): void
    {
        $payload = (new SendDeviceNotification([
            'id' => 42,
            'type' => 'Sunday service',
            'message' => 'Service begins at 10:00.',
        ], 'unit-test-token'))->toFcmHttpV1(new \stdClass());

        self::assertSame('unit-test-token', $payload['token']);
        self::assertSame('Sunday service', $payload['notification']['title']);
        self::assertSame('Service begins at 10:00.', $payload['notification']['body']);
        self::assertSame('42', $payload['data']['id']);
        self::assertArrayHasKey('android', $payload);
        self::assertArrayHasKey('apns', $payload);
    }
}
