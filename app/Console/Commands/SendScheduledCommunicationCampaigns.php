<?php

namespace App\Console\Commands;

use App\Models\CommunicationCampaign;
use App\Services\Communication\ChurchCommunicationDispatcher;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendScheduledCommunicationCampaigns extends Command
{
    protected $signature = 'church:send-scheduled-campaigns';
    protected $description = 'Queue due church communication campaigns';

    public function handle(ChurchCommunicationDispatcher $communications): int
    {
        $now = now();
        $count = 0;

        CommunicationCampaign::query()
            ->where(function ($query) use ($now) {
                $query->where(function ($scheduled) use ($now) {
                    $scheduled->where('status', 'scheduled')->where('scheduled_at', '<=', $now);
                })->orWhere(function ($stale) use ($now) {
                    $stale->where('status', 'sending')->where('updated_at', '<=', $now->copy()->subMinutes(10));
                });
            })
            ->orderBy('id')
            ->chunkById(50, function ($campaigns) use ($communications, &$count) {
                foreach ($campaigns as $campaign) {
                    $claimed = CommunicationCampaign::whereKey($campaign->id)
                        ->where(function ($query) use ($campaign) {
                            $query->where('status', 'scheduled')
                                ->orWhere(function ($stale) use ($campaign) {
                                    $stale->where('status', 'sending')->where('updated_at', '<=', now()->subMinutes(10));
                                });
                        })
                        ->update(['status' => 'sending', 'updated_at' => now()]);

                    if (!$claimed) {
                        continue;
                    }

                    try {
                        $campaign->refresh();
                        $occurrenceAt = $campaign->scheduled_at->copy();
                        $eventAt = $campaign->event_at?->timezone($campaign->timezone);
                        $queued = $communications->toAudience(
                            (int) $campaign->church_id,
                            $campaign->audience,
                            $campaign->channels ?? [],
                            $campaign->title,
                            $campaign->body,
                            'communication-campaign:'.$campaign->id.':'.$occurrenceAt->copy()->timezone($campaign->timezone)->format('YmdHi'),
                            [
                                'url' => url('/'),
                                'campaign_id' => $campaign->id,
                                'tokens' => [
                                    '{event}' => $campaign->event_name ?: 'our upcoming event',
                                    '{date}' => $eventAt?->format('l, F j') ?: 'soon',
                                    '{time}' => $eventAt?->format('g:i A') ?: 'the scheduled time',
                                ],
                            ]
                        );

                        $nextAt = $this->nextOccurrence($campaign, $occurrenceAt);
                        $repeatUntil = $campaign->repeat_until?->toDateString();
                        $nextDateInCampaignTimezone = $nextAt?->copy()->timezone($campaign->timezone)->toDateString();
                        $seriesContinues = $nextAt && (!$repeatUntil || $nextDateInCampaignTimezone <= $repeatUntil);

                        $campaign->update([
                            'status' => $seriesContinues ? 'scheduled' : 'sent',
                            'scheduled_at' => $seriesContinues ? $nextAt->setTimezone(config('app.timezone')) : $campaign->scheduled_at,
                            'run_count' => $campaign->run_count + 1,
                            'queued_deliveries' => $campaign->queued_deliveries + $queued,
                            'processed_at' => now(),
                            'failure_reason' => null,
                        ]);
                        $count++;
                    } catch (Throwable $exception) {
                        $campaign->update([
                            'status' => 'failed',
                            'failure_reason' => mb_substr($exception->getMessage(), 0, 4000),
                        ]);
                        Log::error('Scheduled communication campaign could not be queued.', [
                            'campaign_id' => $campaign->id,
                            'error' => $exception->getMessage(),
                        ]);
                    }
                }
            });

        $this->info("Processed {$count} scheduled communication campaign(s).");
        return self::SUCCESS;
    }

    private function nextOccurrence(CommunicationCampaign $campaign, Carbon $occurrenceAt): ?Carbon
    {
        $interval = max(1, (int) $campaign->repeat_interval);
        $next = $occurrenceAt->copy()->timezone($campaign->timezone);
        $next = match ($campaign->recurrence) {
            'daily' => $next->addDays($interval),
            'weekly' => $next->addWeeks($interval),
            'monthly' => $next->addMonthsNoOverflow($interval),
            default => null,
        };

        // If the scheduler was offline, skip missed occurrences instead of sending a burst.
        while ($next && $next->lte(now($campaign->timezone))) {
            $next = match ($campaign->recurrence) {
                'daily' => $next->addDays($interval),
                'weekly' => $next->addWeeks($interval),
                'monthly' => $next->addMonthsNoOverflow($interval),
                default => null,
            };
        }

        return $next?->setTimezone(config('app.timezone'));
    }
}
