<?php

namespace App\Console\Commands;

use App\Models\ChurchServiceSchedule;
use App\Services\Communication\ChurchCommunicationDispatcher;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendChurchServiceReminders extends Command
{
    protected $signature = 'church:send-service-reminders';

    protected $description = 'Queue Sunday service reminders for opted-in church members and guests';

    public function handle(ChurchCommunicationDispatcher $communications): int
    {
        $queued = 0;

        ChurchServiceSchedule::query()
            ->where('is_active', true)
            ->where('day_of_week', 0)
            ->orderBy('id')
            ->chunkById(100, function ($schedules) use ($communications, &$queued) {
                foreach ($schedules as $schedule) {
                    try {
                        $timezone = $schedule->timezone ?: config('app.timezone', 'UTC');
                        $now = Carbon::now($timezone);
                        if ($now->dayOfWeek !== $schedule->day_of_week) {
                            continue;
                        }

                        $serviceAt = $now->copy()->setTimeFromTimeString($schedule->service_time);
                        $dispatchAt = $serviceAt->copy()->subMinutes($schedule->reminder_minutes_before);
                        if ($dispatchAt->gt($now) || $dispatchAt->lt($now->copy()->subMinutes(5))) {
                            continue;
                        }

                        $time = $serviceAt->format('g:i A');
                        $communications->toChurch(
                            (int) $schedule->church_id,
                            'sunday_service',
                            'Sunday service at {church}',
                            [
                                'member' => "Hello {name}, we look forward to worshipping with you at {church} this Sunday at {$time}. We hope to see you there!",
                                'guest' => "Hello {name}, you are warmly invited to join us at {church} this Sunday at {$time}. We would love to welcome you!",
                            ],
                            'service:'.$schedule->id.':'.$serviceAt->toDateString(),
                            null,
                            $serviceAt,
                            ['service_time' => $serviceAt->toIso8601String()]
                        );
                        $queued++;
                    } catch (Throwable $exception) {
                        Log::error('Could not queue church service reminders.', [
                            'schedule_id' => $schedule->id,
                            'error' => $exception->getMessage(),
                        ]);
                    }
                }
            });

        $this->info("Processed {$queued} service schedule(s).");
        return self::SUCCESS;
    }
}
