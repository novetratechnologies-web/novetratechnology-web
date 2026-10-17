@extends('layouts.admin.layout')

@push('styles')
<style>
    .cs-page{--ink:#16213b;--muted:#74809a;--line:#e7ebf2;min-height:calc(100vh - 80px);margin:-12px -20px;padding:30px clamp(16px,3vw,42px) 55px;background:#f5f7fb;color:var(--ink);font-family:Inter,"Segoe UI",Arial,sans-serif}.cs-wrap{max-width:1250px;margin:auto}.cs-head{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px}.cs-kicker{color:#3158d8;font-size:10px;font-weight:850;letter-spacing:.14em;text-transform:uppercase}.cs-head h1{margin:7px 0 5px;color:#121d36;font-size:clamp(27px,3vw,36px);font-weight:850;letter-spacing:-.04em}.cs-head p{margin:0;color:var(--muted);font-size:13px}.cs-actions{display:flex;gap:8px;flex-wrap:wrap}.cs-button{display:inline-flex;align-items:center;justify-content:center;gap:8px;border:1px solid #dfe5ef;border-radius:10px;background:white;padding:10px 13px;color:#394761;font-size:12px;font-weight:750;text-decoration:none;transition:.16s}.cs-button:hover{transform:translateY(-1px);text-decoration:none}.cs-button-primary{border-color:#3158d8;background:#3158d8;color:#fff}.cs-flash,.cs-errors{margin-bottom:15px;border-radius:11px;padding:12px 15px;font-size:12px}.cs-flash{border:1px solid #a9e4c6;background:#ecfbf2;color:#167147}.cs-errors{border:1px solid #ffd2d2;background:#fff5f5;color:#a4242e}.cs-errors ul{margin:5px 0 0;padding-left:19px}.cs-hero{display:grid;grid-template-columns:1fr auto;align-items:center;gap:18px;margin-bottom:17px;border:1px solid #293c70;border-radius:18px;background:linear-gradient(115deg,#172440,#294b86);padding:22px 24px;color:white;box-shadow:0 10px 28px #1724411a}.cs-hero-label{display:flex;align-items:center;gap:8px;color:#bdcbec;font-size:10px;font-weight:800;letter-spacing:.12em;text-transform:uppercase}.cs-hero h2{margin:9px 0 5px;font-size:21px;font-weight:800;letter-spacing:-.02em}.cs-hero p{max-width:700px;margin:0;color:#d0dbf1;font-size:12px;line-height:1.55}.cs-next{min-width:190px;border:1px solid #ffffff2e;border-radius:13px;background:#ffffff0d;padding:13px 15px}.cs-next span{display:block;color:#bdcbec;font-size:9px;font-weight:750;letter-spacing:.08em;text-transform:uppercase}.cs-next strong{display:block;margin-top:5px;font-size:18px;font-weight:800}.cs-next small{display:block;margin-top:3px;color:#d0dbf1;font-size:10px}.cs-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:19px}.cs-stat{display:flex;align-items:center;gap:12px;border:1px solid var(--line);border-radius:13px;background:#fff;padding:14px 16px}.cs-stat-icon{display:grid;width:36px;height:36px;place-items:center;border-radius:11px;background:#eef2ff;color:#4c64c7}.cs-stat:nth-child(2) .cs-stat-icon{background:#e9f9f1;color:#1a9867}.cs-stat:nth-child(3) .cs-stat-icon{background:#fff2ea;color:#d47a3d}.cs-stat span{display:block;color:#8490a6;font-size:10px;font-weight:700}.cs-stat strong{display:block;margin-top:3px;color:#1c2943;font-size:19px;font-weight:850}.cs-main{display:grid;grid-template-columns:minmax(0,.9fr) minmax(0,1.1fr);align-items:start;gap:16px}.cs-panel{border:1px solid var(--line);border-radius:15px;background:#fff;padding:20px;box-shadow:0 5px 18px #15274b06}.cs-panel-head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:16px}.cs-panel-head h3{margin:0;color:#1d2942;font-size:15px;font-weight:850}.cs-panel-head p{margin:5px 0 0;color:#8490a6;font-size:11px;line-height:1.5}.cs-fields{display:grid;grid-template-columns:1fr 1fr;gap:11px}.cs-field{display:block;color:#526078;font-size:10px;font-weight:750}.cs-input{display:block;width:100%;margin-top:6px;border:1px solid #dfe4ed;border-radius:9px;background:#fff;padding:10px 11px;color:#202b43;font-family:inherit;font-size:12px;outline:none}.cs-input:focus{border-color:#6480e4;box-shadow:0 0 0 3px #3158d818}.cs-toggle{display:flex;align-items:flex-start;gap:9px;margin-top:13px;border:1px solid #e5eaf2;border-radius:10px;background:#fafbfe;padding:11px;color:#59667e;font-size:10px;line-height:1.45}.cs-toggle input{margin-top:2px;accent-color:#3158d8}.cs-submit-row{display:flex;justify-content:flex-end;margin-top:14px}.cs-services{display:grid;gap:10px}.cs-service{border:1px solid #e8ecf3;border-radius:12px;background:#fff;padding:14px}.cs-service-top{display:flex;align-items:flex-start;justify-content:space-between;gap:12px}.cs-time{display:flex;align-items:center;gap:10px}.cs-time-icon{display:grid;width:38px;height:38px;place-items:center;border-radius:11px;background:#eef2ff;color:#4563cc;font-size:14px}.cs-time strong{display:block;color:#1b2841;font-size:17px;font-weight:850}.cs-time span{display:block;margin-top:2px;color:#8490a6;font-size:10px}.cs-badge{display:inline-flex;align-items:center;gap:5px;border-radius:99px;background:#eaf8f1;padding:6px 8px;color:#238456;font-size:9px;font-weight:800}.cs-badge.off{background:#f1f3f6;color:#7d8798}.cs-details{margin-top:12px;border-top:1px solid #eff1f5;padding-top:10px}.cs-details summary{color:#53617b;font-size:10px;font-weight:800;cursor:pointer}.cs-edit{margin-top:12px}.cs-card-actions{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-top:12px}.cs-muted{color:#8994a7;font-size:10px}.cs-delete{border:0;background:none;color:#b4333a;font-family:inherit;font-size:10px;font-weight:800;cursor:pointer}.cs-empty{border:1px dashed #dce2ec;border-radius:12px;padding:29px 15px;text-align:center;color:#8590a5;font-size:11px;line-height:1.6}.cs-empty i{display:block;margin-bottom:9px;color:#7e90d8;font-size:21px}@media(max-width:850px){.cs-main{grid-template-columns:1fr}.cs-hero{grid-template-columns:1fr}.cs-next{min-width:0}}@media(max-width:580px){.cs-page{margin:-12px -20px;padding:21px 13px 40px}.cs-head{align-items:flex-start;flex-direction:column}.cs-actions{width:100%}.cs-actions .cs-button{flex:1}.cs-stats{gap:7px}.cs-stat{align-items:flex-start;flex-direction:column;gap:7px;padding:10px}.cs-stat-icon{width:31px;height:31px}.cs-stat strong{font-size:17px}.cs-fields{grid-template-columns:1fr}.cs-panel{padding:15px}.cs-hero{padding:17px}}
</style>
@endpush

@section('content')
<div class="cs-page"><div class="cs-wrap">
    <header class="cs-head">
        <div><div class="cs-kicker"><i class="fa-regular fa-calendar-check"></i> Communication settings</div><h1>Sunday service reminders</h1><p>Set the service rhythm once. Members and guests receive a reminder before each active Sunday service.</p></div>
        <nav class="cs-actions"><a class="cs-button" href="{{ route('admin.communication.index') }}"><i class="fa-solid fa-arrow-left"></i> Communications</a><a class="cs-button" href="{{ route('admin.communication.deliveries') }}"><i class="fa-solid fa-paper-plane"></i> Delivery history</a></nav>
    </header>

    @if (session('successmessage'))<div class="cs-flash"><i class="fa-solid fa-circle-check"></i> &nbsp;{{ session('successmessage') }}</div>@endif
    @if ($errors->any())<div class="cs-errors"><strong>Please review these details</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    @php($nextReminder = $schedules->where('is_active', true)->sortBy('next_reminder_at')->first())
    <section class="cs-hero"><div><div class="cs-hero-label"><i class="fa-regular fa-bell"></i> Weekly service rhythm</div><h2>Welcome people before they arrive.</h2><p>Active schedules send an automated invitation before service. Email and SMS are sent only to people who opted in; in-app notifications follow their saved preferences.</p></div><div class="cs-next"><span>Next reminder dispatch</span><strong>{{ $nextReminder?->next_reminder_at?->format('D, M j · g:i A') ?? 'Not scheduled' }}</strong><small>{{ $nextReminder?->timezone ? $nextReminder->timezone.' · ' : '' }}{{ $activeScheduleCount }} active service time{{ $activeScheduleCount === 1 ? '' : 's' }}</small></div></section>

    <section class="cs-stats">
        <div class="cs-stat"><div class="cs-stat-icon"><i class="fa-regular fa-clock"></i></div><div><span>Active schedules</span><strong>{{ $activeScheduleCount }}</strong></div></div>
        <div class="cs-stat"><div class="cs-stat-icon"><i class="fa-solid fa-bell"></i></div><div><span>Saved service times</span><strong>{{ $schedules->count() }}</strong></div></div>
        <div class="cs-stat"><div class="cs-stat-icon"><i class="fa-solid fa-repeat"></i></div><div><span>Reminder cadence</span><strong>Every Sunday</strong></div></div>
    </section>

    <div class="cs-main">
        <section class="cs-panel">
            <div class="cs-panel-head"><div><h3>Add a Sunday service</h3><p>Set the local service time and how early reminders should arrive.</p></div><i class="fa-solid fa-plus" style="color:#536ed0"></i></div>
            <form method="POST" action="{{ route('admin.communication.services.store') }}">
                @csrf
                <div class="cs-fields">
                    <label class="cs-field">Service time<input class="cs-input" type="time" name="service_time" value="{{ old('service_time') }}" required></label>
                    <label class="cs-field">Time zone<input class="cs-input" name="timezone" value="{{ old('timezone', config('app.timezone', 'Africa/Nairobi')) }}" placeholder="Africa/Nairobi" required></label>
                    <label class="cs-field" style="grid-column:1/-1">Send reminder before service (minutes)<input class="cs-input" type="number" name="reminder_minutes_before" min="0" max="10080" value="{{ old('reminder_minutes_before', 120) }}" required><span class="cs-muted">Example: 120 minutes sends the invitation two hours before service.</span></label>
                </div>
                <label class="cs-toggle"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))><span><strong>Activate reminders for this service</strong><br>Turn this off to keep the service time saved without sending reminders.</span></label>
                <div class="cs-submit-row"><button class="cs-button cs-button-primary" type="submit"><i class="fa-solid fa-plus"></i> Add service time</button></div>
            </form>
        </section>

        <section class="cs-panel">
            <div class="cs-panel-head"><div><h3>Your Sunday services</h3><p>Edit a schedule or pause reminders at any time.</p></div><span class="cs-badge"><i class="fa-solid fa-circle"></i> {{ $activeScheduleCount }} active</span></div>
            @if ($schedules->isEmpty())
                <div class="cs-empty"><i class="fa-regular fa-calendar-plus"></i>No service times saved yet.<br>Add your first Sunday service to begin automated reminders.</div>
            @else
                <div class="cs-services">
                    @foreach ($schedules as $schedule)
                        <article class="cs-service">
                            <div class="cs-service-top"><div class="cs-time"><span class="cs-time-icon"><i class="fa-regular fa-clock"></i></span><span><strong>{{ \Carbon\Carbon::parse($schedule->service_time)->format('g:i A') }}</strong><span>{{ $schedule->timezone }} · {{ $schedule->reminder_minutes_before }} min before</span></span></div><span class="cs-badge {{ $schedule->is_active ? '' : 'off' }}"><i class="fa-solid fa-circle"></i>{{ $schedule->is_active ? 'Active' : 'Paused' }}</span></div>
                            <p class="cs-muted" style="margin:10px 0 0"><i class="fa-regular fa-bell"></i> Next reminder dispatch: {{ $schedule->next_reminder_at?->format('D, M j · g:i A') }}</p>
                            <details class="cs-details"><summary><i class="fa-solid fa-sliders"></i> &nbsp;Edit service reminder</summary>
                                <form class="cs-edit" method="POST" action="{{ route('admin.communication.services.update', $schedule->id) }}">
                                    @csrf @method('PUT')
                                    <div class="cs-fields">
                                        <label class="cs-field">Service time<input class="cs-input" type="time" name="service_time" value="{{ substr($schedule->service_time, 0, 5) }}" required></label>
                                        <label class="cs-field">Time zone<input class="cs-input" name="timezone" value="{{ $schedule->timezone }}" required></label>
                                        <label class="cs-field" style="grid-column:1/-1">Reminder minutes before<input class="cs-input" type="number" min="0" max="10080" name="reminder_minutes_before" value="{{ $schedule->reminder_minutes_before }}" required></label>
                                    </div>
                                    <label class="cs-toggle"><input type="checkbox" name="is_active" value="1" @checked($schedule->is_active)><span>Keep automated reminders active</span></label>
                                    <div class="cs-card-actions"><button class="cs-button cs-button-primary" type="submit">Save changes</button><button class="cs-delete" type="submit" form="delete-service-{{ $schedule->id }}" onclick="return confirm('Remove this Sunday service time?')">Remove service</button></div>
                                </form>
                            </details>
                            <form id="delete-service-{{ $schedule->id }}" method="POST" action="{{ route('admin.communication.services.destroy', $schedule->id) }}" style="display:none">@csrf @method('DELETE')</form>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</div></div>
@endsection
