<?php

namespace App\Services\Communication;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class HostPinnacleSmsService
{
    /** Send one SMS using HostPinnacle's REST API. */
    public function send(string $to, string $message): array
    {
        $apiKey = config('services.hostpinnacle.api_key');
        $senderId = config('services.hostpinnacle.sender_id');

        if (!$apiKey || !$senderId) {
            throw new RuntimeException('HostPinnacle SMS is not configured.');
        }

        if (trim($to) === '' || trim($message) === '') {
            throw new RuntimeException('An SMS recipient and message are required.');
        }

        $response = Http::asForm()
            ->acceptJson()
            ->withHeaders(['apikey' => $apiKey])
            ->connectTimeout(10)
            ->timeout(30)
            ->post(config('services.hostpinnacle.endpoint'), [
                'sendMethod' => 'quick',
                'mobile' => $to,
                'msg' => $message,
                'senderid' => $senderId,
                'msgType' => preg_match('/[^\x00-\x7F]/', $message) ? 'unicode' : 'text',
                'duplicatecheck' => 'true',
                'output' => 'json',
            ]);

        if (!$response->successful()) {
            throw new RuntimeException('HostPinnacle SMS request failed with HTTP '.$response->status().'.');
        }

        $result = $response->json();
        if (!is_array($result) || strtolower((string) ($result['status'] ?? '')) !== 'success') {
            throw new RuntimeException('HostPinnacle did not accept the SMS request.');
        }

        return $result;
    }
}
