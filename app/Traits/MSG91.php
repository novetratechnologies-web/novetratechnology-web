<?php

namespace App\Traits;

use App\Services\Communication\HostPinnacleSmsService;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Backwards-compatible SMS method names while all outbound SMS uses HostPinnacle.
 * The historical trait name remains to avoid breaking existing call sites.
 */
trait MSG91
{
    public function sendSMS(string $content, string $to): string|false
    {
        return $this->sendThroughConfiguredSms($content, $to);
    }

    public function sendPrivateSMS(string $content, string $to): string|false
    {
        return $this->sendThroughConfiguredSms($content, $to);
    }

    public function getOTP($OTP, $mobileNumber): string|false
    {
        return $this->sendThroughConfiguredSms('Your verification code is: '.$OTP, (string) $mobileNumber);
    }

    private function sendThroughConfiguredSms(string $message, string $to): string|false
    {
        if (!config('services.hostpinnacle.enabled')) {
            return false;
        }

        try {
            $response = app(HostPinnacleSmsService::class)->send($to, $message);
            return json_encode($response, JSON_UNESCAPED_SLASHES) ?: false;
        } catch (Throwable $e) {
            Log::warning('Church SMS delivery failed.', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
