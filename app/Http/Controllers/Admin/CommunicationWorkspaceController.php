<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunicationCampaign;
use App\Models\CommunicationDelivery;
use App\Models\CommunicationTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunicationWorkspaceController extends Controller
{
    private const PRESET_TEMPLATES = [
        ['name' => 'Welcome a guest', 'category' => 'Guests', 'title' => 'You are welcome at {church}', 'body' => 'Hello {name}, thank you for visiting {church}. We would love to see you again. Please reply if you would like us to get in touch.'],
        ['name' => 'Sunday service invitation', 'category' => 'Service', 'title' => 'Join us this Sunday at {church}', 'body' => 'Hello {name}, you are warmly invited to worship with us this Sunday. We look forward to welcoming you at {church}.'],
        ['name' => 'New event invitation', 'category' => 'Events', 'title' => 'You are invited: {event}', 'body' => 'Hello {name}, join us for {event} at {church} on {date} at {time}. We hope you can make it.'],
        ['name' => 'Event reminder', 'category' => 'Events', 'title' => 'Reminder: {event}', 'body' => 'Hello {name}, this is a friendly reminder about {event} at {church} on {date} at {time}. We look forward to seeing you.'],
        ['name' => 'Pastoral check-in', 'category' => 'Care', 'title' => 'Checking in from {church}', 'body' => 'Hello {name}, we are thinking of you and wanted to check in. Please let us know if there is a way we can support or pray with you.'],
        ['name' => 'General church update', 'category' => 'General', 'title' => 'An update from {church}', 'body' => 'Hello {name}, here is an update from {church}: '],
    ];

    public function index()
    {
        $this->ensureChurchAdmin();
        $churchId = (int) Auth::user()->church_id;

        $campaigns = CommunicationCampaign::where('church_id', $churchId)
            ->whereIn('status', ['scheduled', 'sending', 'failed'])
            ->orderBy('scheduled_at')->limit(15)->get();
        $templates = CommunicationTemplate::where('church_id', $churchId)
            ->orderBy('name')->get();
        $savedTemplatePayload = $templates->map(function ($template) {
            return [
                'id' => $template->id,
                'name' => $template->name,
                'category' => $template->category,
                'title' => $template->title,
                'body' => $template->body,
                'channels' => $template->channels ?? ['in_app'],
            ];
        })->values();
        $scheduledCampaignCount = CommunicationCampaign::where('church_id', $churchId)
            ->where('status', 'scheduled')->count();
        $activeRecurringCount = CommunicationCampaign::where('church_id', $churchId)
            ->where('status', 'scheduled')->where('recurrence', '!=', 'once')->count();
        $deliveryStats = CommunicationDelivery::where('church_id', $churchId)
            ->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('admin.communications.index', [
            'campaigns' => $campaigns,
            'templates' => $templates,
            'savedTemplatePayload' => $savedTemplatePayload,
            'scheduledCampaignCount' => $scheduledCampaignCount,
            'activeRecurringCount' => $activeRecurringCount,
            'systemTemplates' => self::PRESET_TEMPLATES,
            'deliveryStats' => $deliveryStats,
            'timezone' => config('app.timezone', 'UTC'),
        ]);
    }

    public function storeCampaign(Request $request)
    {
        $this->ensureChurchAdmin();
        $data = $request->validate([
            'title' => ['required', 'string', 'max:191'],
            'body' => ['required', 'string', 'max:5000'],
            'event_name' => ['nullable', 'string', 'max:191'],
            'event_at' => ['nullable', 'date'],
            'audience' => ['required', 'in:all,members,guests'],
            'channels' => ['required', 'array', 'min:1'],
            'channels.*' => ['required', 'in:email,sms,in_app'],
            'recurrence' => ['required', 'in:once,daily,weekly,monthly'],
            'repeat_interval' => ['required_unless:recurrence,once', 'nullable', 'integer', 'min:1', 'max:52'],
            'repeat_until' => ['nullable', 'date_format:Y-m-d'],
            'timezone' => ['required', 'timezone'],
            'scheduled_at' => ['required', 'date'],
            'save_as_template' => ['nullable', 'boolean'],
            'template_name' => ['nullable', 'required_if:save_as_template,1', 'string', 'max:100'],
            'template_category' => ['nullable', 'string', 'max:40'],
            'template_id' => ['nullable', 'integer'],
        ]);

        $scheduledAt = Carbon::parse($data['scheduled_at'], $data['timezone']);
        if (!$scheduledAt->isFuture()) {
            return back()->withInput()->withErrors(['scheduled_at' => 'Choose a date and time in the future.']);
        }

        $interval = (int) ($data['repeat_interval'] ?? 1);
        if (($data['recurrence'] ?? 'once') === 'daily' && $interval > 30) {
            return back()->withInput()->withErrors(['repeat_interval' => 'Daily repeat intervals can be from 1 to 30 days.']);
        }
        if (($data['recurrence'] ?? 'once') === 'monthly' && $interval > 24) {
            return back()->withInput()->withErrors(['repeat_interval' => 'Monthly repeat intervals can be from 1 to 24 months.']);
        }

        if (!empty($data['repeat_until'])) {
            $repeatUntil = Carbon::createFromFormat('Y-m-d', $data['repeat_until'], $data['timezone'])->endOfDay();
            if ($repeatUntil->lt($scheduledAt)) {
                return back()->withInput()->withErrors(['repeat_until' => 'The repeat end date must be on or after the first send.']);
            }
        }

        $campaign = CommunicationCampaign::create([
            'church_id' => (int) Auth::user()->church_id,
            'created_by' => Auth::id(),
            'title' => $data['title'],
            'body' => $data['body'],
            'event_name' => $data['event_name'] ?? null,
            'event_at' => !empty($data['event_at'])
                ? Carbon::parse($data['event_at'], $data['timezone'])->setTimezone(config('app.timezone'))
                : null,
            'audience' => $data['audience'],
            'channels' => array_values(array_unique($data['channels'])),
            'timezone' => $data['timezone'],
            'scheduled_at' => $scheduledAt->setTimezone(config('app.timezone')),
            'recurrence' => $data['recurrence'],
            'repeat_interval' => $data['recurrence'] === 'once' ? 1 : $interval,
            'repeat_until' => $data['recurrence'] === 'once' ? null : ($data['repeat_until'] ?? null),
            'status' => 'scheduled',
        ]);

        if ($request->boolean('save_as_template')) {
            $template = CommunicationTemplate::firstOrNew([
                'id' => $data['template_id'] ?? null,
                'church_id' => (int) Auth::user()->church_id,
            ]);
            $template->fill([
                'created_by' => Auth::id(),
                'name' => $data['template_name'],
                'category' => $data['template_category'] ?? 'General',
                'title' => $data['title'],
                'body' => $data['body'],
                'channels' => array_values(array_unique($data['channels'])),
            ])->save();
        }

        return redirect()->route('admin.communication.index')
            ->with('successmessage', 'Your message is scheduled. Delivery starts at the selected time.');
    }

    public function destroyCampaign(int $id)
    {
        $this->ensureChurchAdmin();
        $campaign = CommunicationCampaign::where('church_id', Auth::user()->church_id)->findOrFail($id);
        abort_unless($campaign->status === 'scheduled', 409, 'Only scheduled messages can be cancelled.');
        $campaign->update(['status' => 'cancelled']);

        return redirect()->route('admin.communication.index')->with('successmessage', 'Scheduled message cancelled.');
    }

    public function retryCampaign(int $id)
    {
        $this->ensureChurchAdmin();
        $campaign = CommunicationCampaign::where('church_id', Auth::user()->church_id)->findOrFail($id);
        abort_unless($campaign->status === 'failed', 409, 'Only failed messages can be retried.');
        $campaign->update(['status' => 'scheduled', 'scheduled_at' => now()->addMinute(), 'failure_reason' => null]);

        return redirect()->route('admin.communication.index')->with('successmessage', 'Message queued for retry in about one minute.');
    }

    public function destroyTemplate(int $id)
    {
        $this->ensureChurchAdmin();
        CommunicationTemplate::where('church_id', Auth::user()->church_id)->findOrFail($id)->delete();

        return redirect()->route('admin.communication.index')->with('successmessage', 'Template removed.');
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
