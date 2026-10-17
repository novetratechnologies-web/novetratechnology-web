<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

class FcmHttpV1Client
{
    private const MESSAGING_SCOPE = 'https://www.googleapis.com/auth/firebase.messaging';

    /**
     * Send a message payload to FCM and return Google's response body.
     */
    public function send(array $message): array
    {
        $serviceAccount = $this->serviceAccount();
        $projectId = config('fcm.project_id') ?: ($serviceAccount['project_id'] ?? null);

        if (! is_string($projectId) || $projectId === '') {
            throw new RuntimeException('Set FCM_PROJECT_ID or include project_id in the service account JSON.');
        }

        if (empty($message['token'])) {
            throw new InvalidArgumentException('An FCM device token is required.');
        }

        $response = Http::withToken($this->accessToken($serviceAccount, $projectId))
            ->acceptJson()
            ->timeout((float) config('fcm.timeout', 30))
            ->post(sprintf(
                'https://fcm.googleapis.com/v1/projects/%s/messages:send',
                rawurlencode($projectId)
            ), [
                'message' => $message,
            ]);

        $response->throw();

        $body = $response->json();

        return is_array($body) ? $body : [];
    }

    /** @return array<string, mixed> */
    private function serviceAccount(): array
    {
        $path = config('fcm.credentials');

        if (! is_string($path) || $path === '' || ! is_file($path) || ! is_readable($path)) {
            throw new RuntimeException('Set FIREBASE_CREDENTIALS to a readable Firebase service account JSON file.');
        }

        $credentials = json_decode((string) file_get_contents($path), true);

        if (! is_array($credentials)) {
            throw new RuntimeException('The Firebase service account file is not valid JSON.');
        }

        return $credentials;
    }

    /** @param array<string, mixed> $serviceAccount */
    private function accessToken(array $serviceAccount, string $projectId): string
    {
        $cacheKey = 'fcm_http_v1_token_' . hash(
            'sha256',
            $projectId . '|' . ($serviceAccount['client_email'] ?? '')
        );
        $cachedToken = Cache::get($cacheKey);

        if (is_string($cachedToken) && $cachedToken !== '') {
            return $cachedToken;
        }

        $credentials = new ServiceAccountCredentials(self::MESSAGING_SCOPE, $serviceAccount);
        $tokenResponse = $credentials->fetchAuthToken();
        $accessToken = $tokenResponse['access_token'] ?? null;

        if (! is_string($accessToken) || $accessToken === '') {
            throw new RuntimeException('Google did not return an FCM access token.');
        }

        $lifetime = max(60, (int) ($tokenResponse['expires_in'] ?? 3600) - 60);
        Cache::put($cacheKey, $accessToken, $lifetime);

        return $accessToken;
    }
}
