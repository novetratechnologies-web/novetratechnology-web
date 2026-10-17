<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChurchServiceSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunicationSettingsController extends Controller
{
    public function index()
    {
        $this->ensureChurchAdmin();

        $schedules = ChurchServiceSchedule::where('church_id', Auth::user()->church_id)
            ->where('day_of_week', 0)
            ->orderBy('service_time')
            ->get();

        $schedules->each(function (ChurchServiceSchedule $schedule) {
            $localNow = Carbon::now($schedule->timezone ?: config('app.timezone'));
            $next = $localNow->copy()->startOfDay();
            if ($localNow->dayOfWeek !== 0 || $schedule->service_time <= $localNow->format('H:i:s')) {
                $next->next(Carbon::SUNDAY);
            }
            $schedule->next_service_at = $next->setTimeFromTimeString($schedule->service_time);
            $reminderAt = $schedule->next_service_at->copy()->subMinutes($schedule->reminder_minutes_before);
            if ($reminderAt->lte($localNow)) {
                $schedule->next_service_at = $schedule->next_service_at->addWeek();
                $reminderAt = $schedule->next_service_at->copy()->subMinutes($schedule->reminder_minutes_before);
            }
            $schedule->next_reminder_at = $reminderAt;
        });

        $activeScheduleCount = $schedules->where('is_active', true)->count();

        return view('admin.communications.services', compact('schedules', 'activeScheduleCount'));
    }

    public function store(Request $request)
    {
        $this->ensureChurchAdmin();

        $data = $request->validate([
            'service_time' => ['required', 'date_format:H:i'],
            'timezone' => ['required', 'timezone'],
            'reminder_minutes_before' => ['required', 'integer', 'min:0', 'max:10080'],
        ]);

        $data['day_of_week'] = 0;
        $data['is_active'] = $request->boolean('is_active');

        Auth::user()->church->serviceSchedules()->create($data);

        return redirect()->route('admin.communication.services')
            ->with('successmessage', 'Sunday service schedule saved.');
    }

    public function update(Request $request, int $id)
    {
        $this->ensureChurchAdmin();

        $data = $request->validate([
            'service_time' => ['required', 'date_format:H:i'],
            'timezone' => ['required', 'timezone'],
            'reminder_minutes_before' => ['required', 'integer', 'min:0', 'max:10080'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $schedule = ChurchServiceSchedule::where('church_id', Auth::user()->church_id)
            ->where('day_of_week', 0)
            ->findOrFail($id);
        $schedule->update($data);

        return redirect()->route('admin.communication.services')
            ->with('successmessage', 'Sunday service schedule updated.');
    }

    public function destroy(int $id)
    {
        $this->ensureChurchAdmin();

        ChurchServiceSchedule::where('church_id', Auth::user()->church_id)
            ->where('day_of_week', 0)
            ->findOrFail($id)
            ->delete();

        return redirect()->route('admin.communication.services')
            ->with('successmessage', 'Sunday service schedule removed.');
    }

    private function ensureChurchAdmin(): void
    {
        abort_unless(
            Auth::check()
                && in_array((int) Auth::user()->usergroup_id, [3, 4], true)
                && Auth::user()->church_id,
            403
        );
    }
}
