<?php

namespace App\Services\Communication;

use App\Jobs\SendCommunicationDelivery;
use App\Models\CommunicationDelivery;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class ChurchCommunicationDispatcher
{
    /** Queue a one-off admin message for explicitly selected users. */
    public function toUsers(
        int $churchId,
        array $userIds,
        string $channel,
        string $title,
        string $body,
        string $idempotencySeed,
        array $metadata = []
    ): int {
        if (!in_array($channel, ['email', 'sms', 'in_app'], true)) {
            throw new \InvalidArgumentException('Unsupported communication channel.');
        }

        $queued = 0;
        User::query()
            ->where('church_id', $churchId)
            ->where('usergroup_id', 5)
            ->whereIn('id', array_values(array_unique(array_map('intval', $userIds))))
            ->whereHas('userprofile', function ($query) {
                $query->where('status', 'active')->whereIn('membership_type', ['member', 'guest']);
            })
            ->with(['userprofile', 'communicationPreference', 'church'])
            ->chunkById(200, function ($users) use ($churchId, $channel, $title, $body, $idempotencySeed, $metadata, &$queued) {
                foreach ($users as $user) {
                    $personalization = array_merge([
                        '{name}' => $this->displayName($user),
                        '{church}' => $user->church?->name ?: 'our church',
                    ], $metadata['tokens'] ?? []);
                    $queued += $this->queueForUser(
                        $user,
                        $churchId,
                        'custom_message',
                        strtr($title, $personalization),
                        strtr($body, $personalization),
                        $idempotencySeed,
                        null,
                        null,
                        array_merge(['url' => url('/'), 'admin_message' => true], $metadata),
                        [$channel]
                    );
                }
            });

        return $queued;
    }

    /** Queue a campaign for the active member, guest, or combined church audience. */
    public function toAudience(
        int $churchId,
        string $audience,
        array $channels,
        string $title,
        string $body,
        string $idempotencySeed,
        array $metadata = []
    ): int {
        if (!in_array($audience, ['all', 'members', 'guests'], true)) {
            throw new \InvalidArgumentException('Unsupported communication audience.');
        }

        $queued = 0;
        foreach (array_unique($channels) as $channel) {
            if (!in_array($channel, ['email', 'sms', 'in_app'], true)) {
                continue;
            }

            User::query()
                ->where('church_id', $churchId)
                ->where('usergroup_id', 5)
                ->whereHas('userprofile', function ($query) use ($audience) {
                    $query->where('status', 'active');
                    $query->whereIn('membership_type', $audience === 'all'
                        ? ['member', 'guest']
                        : [$audience === 'guests' ? 'guest' : 'member']);
                })
                ->select('id')
                ->chunkById(200, function ($users) use ($churchId, $channel, $title, $body, $idempotencySeed, $metadata, &$queued) {
                    $queued += $this->toUsers(
                        $churchId,
                        $users->pluck('id')->all(),
                        $channel,
                        $title,
                        $body,
                        $idempotencySeed,
                        $metadata
                    );
                });
        }

        return $queued;
    }

    /**
     * Send an announcement to active members and guests in one church.
     * Message bodies may be a string or separate `member` and `guest` values.
     */
    public function toChurch(
        int $churchId,
        string $eventType,
        string $title,
        string|array $body,
        string $idempotencySeed,
        ?int $eventId = null,
        ?CarbonInterface $occurrenceDate = null,
        array $metadata = []
    ): void {
        User::query()
            ->where('church_id', $churchId)
            ->where('usergroup_id', 5)
            ->whereHas('userprofile', function ($query) {
                $query->where('status', 'active')
                    ->whereIn('membership_type', ['member', 'guest']);
            })
            ->with(['userprofile', 'communicationPreference', 'church'])
            ->chunkById(200, function ($users) use (
                $churchId,
                $eventType,
                $title,
                $body,
                $idempotencySeed,
                $eventId,
                $occurrenceDate,
                $metadata
            ) {
                foreach ($users as $user) {
                    $membershipType = $user->userprofile?->membership_type ?? 'member';
                    $personalBody = is_array($body)
                        ? ($body[$membershipType] ?? $body['member'] ?? '')
                        : $body;

                    $personalization = array_merge([
                        '{name}' => $this->displayName($user),
                        '{church}' => $user->church?->name ?: 'our church',
                    ], $metadata['tokens'] ?? []);
                    $personalTitle = strtr($title, $personalization);
                    $personalBody = strtr($personalBody, $personalization);

                    $this->queueForUser(
                        $user,
                        $churchId,
                        $eventType,
                        $personalTitle,
                        $personalBody,
                        $idempotencySeed,
                        $eventId,
                        $occurrenceDate,
                        $metadata
                    );
                }
            });
    }

    private function queueForUser(
        User $user,
        int $churchId,
        string $eventType,
        string $title,
        string $body,
        string $seed,
        ?int $eventId,
        ?CarbonInterface $occurrenceDate,
        array $metadata,
        ?array $requestedChannels = null
    ): int {
        $preference = $user->communicationPreference;
        $channels = [];

        if (($preference?->in_app_enabled ?? true) && ($requestedChannels === null || in_array('in_app', $requestedChannels, true))) {
            $channels[] = ['channel' => 'in_app', 'recipient' => (string) $user->id];
        }
        if ($preference?->email_opt_in && filter_var($user->email, FILTER_VALIDATE_EMAIL) && ($requestedChannels === null || in_array('email', $requestedChannels, true))) {
            $channels[] = ['channel' => 'email', 'recipient' => $user->email];
        }
        $smsNumber = $this->internationalPhoneNumber($user->mobile_no, $user->mobile_country_code);
        if ($preference?->sms_opt_in && $smsNumber && config('services.hostpinnacle.enabled') && ($requestedChannels === null || in_array('sms', $requestedChannels, true))) {
            $channels[] = ['channel' => 'sms', 'recipient' => $smsNumber];
        }

        $payload = array_merge($metadata, [
            'title' => $title,
            'body' => $body,
            'event_type' => $eventType,
            'event_id' => $eventId,
            'data' => ['data' => $body, 'type' => $eventType],
            'message_type' => $eventType,
            'message_id' => $eventId ?? 0,
            'url' => $metadata['url'] ?? url('/'),
        ]);

        $queued = 0;
        foreach ($channels as $channel) {
            $key = hash('sha256', implode('|', [
                $seed,
                $user->id,
                $channel['channel'],
            ]));

            $inserted = DB::table('communication_deliveries')->insertOrIgnore([
                'church_id' => $churchId,
                'user_id' => $user->id,
                'event_type' => $eventType,
                'event_id' => $eventId,
                'occurrence_date' => $occurrenceDate?->toDateString(),
                'channel' => $channel['channel'],
                'recipient' => $channel['recipient'],
                'idempotency_key' => $key,
                'status' => 'queued',
                'payload' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
                'scheduled_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($inserted !== 1) {
                continue;
            }

            $queued++;

            $delivery = CommunicationDelivery::where('idempotency_key', $key)->first();
            if ($delivery) {
                SendCommunicationDelivery::dispatch($delivery->id)->afterCommit();
            }
        }

        return $queued;
    }

    private function internationalPhoneNumber(?string $number, ?string $countryCode): ?string
    {
        $number = trim((string) $number);
        if ($number === '') {
            return null;
        }

        $number = preg_replace('/[^0-9+]/', '', $number);
        if (str_starts_with($number, '00')) {
            $number = '+'.substr($number, 2);
        }
        if (str_starts_with($number, '+')) {
            return preg_replace('/\D/', '', $number);
        }

        $countryCode = preg_replace('/\D/', '', (string) $countryCode);
        if ($countryCode === '') {
            return null;
        }

        $number = ltrim($number, '0');
        if (str_starts_with($number, $countryCode)) {
            return $number;
        }

        return $countryCode.$number;
    }

    private function displayName(User $user): string
    {
        $first = trim((string) $user->userprofile?->firstname);
        $last = trim((string) $user->userprofile?->lastname);
        $name = trim($first.' '.$last);

        return $name !== '' ? $name : (trim((string) $user->name) ?: 'there');
    }
}
