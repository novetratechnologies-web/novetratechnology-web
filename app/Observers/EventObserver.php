<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use App\Models\Events;
use App\Services\Communication\ChurchCommunicationDispatcher;
use Carbon\Carbon;
use Exception;
use Log;

class EventObserver
{
    /**
     * Handle the events "created" event.
     *
     * @param  \App\Models\Events  $events
     * @return void
     */
    public function created(Events $events)
    {
        //
        try
        {
            $update=[
                'created_by'=>Auth::id(),
                'updated_by'=>Auth::id(),
            ];

            Events::where('id',$events->id)->update($update);

            // Imports and seeders may create events, but should not notify real users.
            if (!app()->runningInConsole() && $events->church_id) {
                $start = $events->start_date
                    ? Carbon::parse($events->start_date)
                    : null;
                $details = array_filter([
                    $events->title,
                    $start?->format('D, j M Y g:i A'),
                    $events->location,
                ]);

                app(ChurchCommunicationDispatcher::class)->toChurch(
                    (int) $events->church_id,
                    'new_event',
                    'New event at {church}: '.($events->title ?: 'Event'),
                    'Hello {name}, {church} has a new event'.($details ? ': '.implode(' · ', $details) : '.'),
                    'event:'.$events->id,
                    (int) $events->id,
                    $start,
                    ['url' => url('/event/'.$events->id)]
                );
            }
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the events "updated" event.
     *
     * @param  \App\Models\Events  $events
     * @return void
     */
    public function updated(Events $events)
    {
        //
        try
        {
            $update=[
                'updated_by'=>Auth::id(),
            ];
            Events::where('id',$events->id)->update($update);
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());
            //dd($e->getMessage());
        }
    }

    /**
     * Handle the events "deleted" event.
     *
     * @param  \App\Models\Events  $events
     * @return void
     */
    public function deleted(Events $events)
    {
        //
    }

    /**
     * Handle the events "restored" event.
     *
     * @param  \App\Models\Events  $events
     * @return void
     */
    public function restored(Events $events)
    {
        //
    }

    /**
     * Handle the events "force deleted" event.
     *
     * @param  \App\Models\Events  $events
     * @return void
     */
    public function forceDeleted(Events $events)
    {
        //
    }
}
