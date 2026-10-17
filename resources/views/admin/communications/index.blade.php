@extends('layouts.admin.layout')

@push('styles')
<style>
    .cc-shell{--ink:#16213b;--muted:#74809a;--line:#e7ebf2;--blue:#3158d8;--canvas:#f5f7fb;min-height:calc(100vh - 80px);margin:-12px -20px;padding:30px clamp(16px,3vw,42px) 55px;background:var(--canvas);color:var(--ink);font-family:Inter,"Segoe UI",Arial,sans-serif}
    .cc-wrap{max-width:1500px;margin:auto}.cc-head{display:flex;align-items:center;justify-content:space-between;gap:20px;margin-bottom:22px}.cc-eyebrow{display:flex;align-items:center;gap:8px;color:#3158d8;font-size:11px;font-weight:800;letter-spacing:.14em;text-transform:uppercase}.cc-head h1{margin:7px 0 5px;color:#111b33;font-size:clamp(28px,3vw,39px);font-weight:800;letter-spacing:-.04em;line-height:1.12}.cc-head p{margin:0;color:var(--muted);font-size:14px}.cc-actions{display:flex;gap:9px;flex-wrap:wrap}.cc-btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;border:1px solid transparent;border-radius:11px;padding:11px 15px;font-size:13px;font-weight:750;text-decoration:none;transition:.18s ease;cursor:pointer}.cc-btn:hover{transform:translateY(-1px);text-decoration:none}.cc-btn-light{border-color:#dfe5ef;background:#fff;color:#394761}.cc-btn-dark{background:#17233f;color:#fff}.cc-btn-primary{background:#3158d8;color:white;box-shadow:0 7px 17px #3158d833}.cc-btn-primary:hover{background:#2548bd;color:#fff}.cc-btn-danger{border-color:#f2d4d5;background:#fff8f8;color:#b4232d;padding:7px 10px;font-size:11px}
    .cc-flash{margin:0 0 16px;border:1px solid #a9e4c6;border-radius:12px;background:#ecfbf2;padding:13px 16px;color:#167147;font-size:13px;font-weight:650}.cc-error{margin:0 0 16px;border:1px solid #ffd2d2;border-radius:12px;background:#fff5f5;padding:14px 18px;color:#a4242e;font-size:13px}.cc-error ul{margin:5px 0 0;padding-left:20px}
    .cc-alarm{position:relative;display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:18px;overflow:hidden;margin-bottom:20px;border:1px solid #283965;border-radius:19px;background:linear-gradient(112deg,#172440 0%,#233d72 58%,#3158a4 100%);padding:20px 24px;color:#fff;box-shadow:0 12px 30px #14244620}.cc-alarm:after{position:absolute;right:20%;top:-130px;width:320px;height:320px;border:1px solid #ffffff12;border-radius:50%;content:"";box-shadow:0 0 0 35px #ffffff08,0 0 0 75px #ffffff05;pointer-events:none}.cc-alarm-icon{display:grid;width:48px;height:48px;place-items:center;border:1px solid #ffffff35;border-radius:15px;background:#ffffff12;color:#c9d8ff;font-size:19px}.cc-alarm-title{display:flex;align-items:center;gap:9px;margin-bottom:4px;font-size:15px;font-weight:800}.cc-live-dot{width:8px;height:8px;border-radius:50%;background:#61e5a5;box-shadow:0 0 0 4px #61e5a522;animation:cc-pulse 1.8s infinite}.cc-alarm-copy{position:relative;z-index:1;color:#d6dff3;font-size:12px;line-height:1.55}.cc-alarm-number{position:relative;z-index:1;text-align:right}.cc-alarm-number strong{display:block;font-size:30px;line-height:1}.cc-alarm-number span{display:block;margin-top:5px;color:#c3d1ea;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase}.cc-stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:13px;margin-bottom:21px}.cc-stat{display:flex;align-items:center;gap:13px;border:1px solid var(--line);border-radius:15px;background:#fff;padding:15px 17px;box-shadow:0 3px 10px #15274b05}.cc-stat-icon{display:grid;width:39px;height:39px;flex:none;place-items:center;border-radius:12px;background:#eef2ff;color:#4b63c9}.cc-stat:nth-child(2) .cc-stat-icon{background:#eaf9f2;color:#1a9a68}.cc-stat:nth-child(3) .cc-stat-icon{background:#fff1ed;color:#d56b43}.cc-stat-label{color:var(--muted);font-size:11px;font-weight:650}.cc-stat strong{display:block;margin-top:2px;color:#17223a;font-size:22px;font-weight:800;line-height:1.1}
    .cc-grid{display:grid;grid-template-columns:minmax(0,1.65fr) minmax(300px,.85fr);align-items:start;gap:18px}.cc-panel{border:1px solid var(--line);border-radius:17px;background:#fff;box-shadow:0 5px 20px #15274b07}.cc-panel-pad{padding:23px}.cc-panel-head{display:flex;align-items:flex-start;justify-content:space-between;gap:15px;margin-bottom:21px}.cc-panel-head h2{margin:0;color:#17223a;font-size:18px;font-weight:800;letter-spacing:-.02em}.cc-panel-head p{margin:5px 0 0;color:var(--muted);font-size:12px}.cc-tag{display:inline-flex;align-items:center;gap:6px;border-radius:99px;background:#eff3ff;padding:6px 10px;color:#4560c2;font-size:10px;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.cc-step{margin-top:20px}.cc-step:first-of-type{margin-top:0}.cc-step-title{display:flex;align-items:center;gap:9px;margin:0 0 11px;color:#26344d;font-size:12px;font-weight:800}.cc-step-n{display:grid;width:23px;height:23px;place-items:center;border-radius:8px;background:#edf1ff;color:#405cc4;font-size:10px;font-weight:850}.cc-field-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}.cc-field{display:block;min-width:0;color:#526078;font-size:11px;font-weight:750}.cc-input,.cc-select,.cc-textarea{display:block;width:100%;margin-top:6px;border:1px solid #dfe4ed;border-radius:10px;background:#fff;padding:11px 12px;color:#202b43;font-family:inherit;font-size:13px;font-weight:500;outline:none;transition:border-color .16s,box-shadow .16s}.cc-input:focus,.cc-select:focus,.cc-textarea:focus{border-color:#6480e4;box-shadow:0 0 0 3px #3158d818}.cc-textarea{min-height:130px;resize:vertical;line-height:1.6}.cc-help{display:block;margin-top:5px;color:#8a94a8;font-size:10px;font-weight:500;line-height:1.5}.cc-counter{float:right;font-variant-numeric:tabular-nums}.cc-template-start{display:grid;grid-template-columns:1fr auto;gap:9px}.cc-channel-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:9px}.cc-channel{position:relative;display:block;cursor:pointer}.cc-channel input{position:absolute;opacity:0}.cc-channel-card{display:flex;min-height:77px;align-items:flex-start;gap:9px;border:1px solid #e2e6ee;border-radius:11px;padding:11px;transition:.16s}.cc-channel input:checked+.cc-channel-card{border-color:#6681e4;background:#f4f6ff;box-shadow:inset 0 0 0 1px #6681e4}.cc-channel-icon{display:grid;width:28px;height:28px;flex:none;place-items:center;border-radius:8px;background:#f0f2f8;color:#63708b;font-size:12px}.cc-channel input:checked+.cc-channel-card .cc-channel-icon{background:#e1e8ff;color:#3158d8}.cc-channel-name{display:block;color:#26334b;font-size:11px;font-weight:800}.cc-channel-hint{display:block;margin-top:3px;color:#8993a6;font-size:9px;line-height:1.35}.cc-repeat{border:1px solid #dce5ff;border-radius:13px;background:linear-gradient(130deg,#f6f8ff,#fbfcff);padding:14px}.cc-repeat-top{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:11px}.cc-repeat-top strong{color:#28385b;font-size:12px}.cc-repeat-switch{display:inline-flex;align-items:center;gap:7px;color:#3158d8;font-size:10px;font-weight:800;cursor:pointer}.cc-repeat-switch input{accent-color:#3158d8}.cc-repeat-row{display:grid;grid-template-columns:1fr 95px 1fr;gap:9px}.cc-repeat-preview{display:flex;align-items:center;gap:8px;margin-top:10px;border-radius:9px;background:#fff;padding:9px 10px;color:#64708a;font-size:10px}.cc-repeat-preview i{color:#5875d5}.cc-event-details{margin-top:12px;border-top:1px solid #edf0f5;padding-top:12px}.cc-details-summary{color:#4b5872;font-size:11px;font-weight:750;cursor:pointer}.cc-checkline{display:flex;align-items:flex-start;gap:9px;border:1px dashed #d6deef;border-radius:10px;background:#fafbff;padding:12px;color:#526078;font-size:11px;line-height:1.45}.cc-checkline input{margin-top:2px;accent-color:#3158d8}.cc-bottom{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:18px;border-top:1px solid #eef0f5;padding-top:16px}.cc-bottom-note{color:#8a94a8;font-size:10px}.cc-side-stack{display:grid;gap:17px}.cc-preview{overflow:hidden;border:1px solid #dbe2ee;border-radius:15px;background:#f9fbff}.cc-preview-head{display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #e7ebf2;padding:12px 14px}.cc-preview-head strong{color:#34425f;font-size:11px}.cc-preview-head span{color:#8993a5;font-size:9px}.cc-preview-device{padding:16px}.cc-preview-top{display:flex;align-items:center;gap:9px;margin-bottom:14px}.cc-church-mark{display:grid;width:30px;height:30px;place-items:center;border-radius:10px;background:#e7edff;color:#4562cb;font-size:12px}.cc-preview-top strong{display:block;color:#34415c;font-size:10px}.cc-preview-top span{display:block;margin-top:2px;color:#a1a8b7;font-size:9px}.cc-bubble{border:1px solid #e8ebf2;border-radius:4px 13px 13px 13px;background:#fff;padding:12px;color:#536078;font-size:10px;line-height:1.6;white-space:pre-wrap}.cc-next{padding:17px}.cc-next-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:11px}.cc-next-head h3{margin:0;color:#26334c;font-size:13px;font-weight:800}.cc-next-head span{color:#929bad;font-size:10px}.cc-campaign-list{display:grid;gap:9px}.cc-campaign{border:1px solid #edf0f5;border-radius:11px;padding:11px}.cc-campaign-top{display:flex;align-items:flex-start;justify-content:space-between;gap:8px}.cc-campaign-title{margin:0;color:#2b3750;font-size:11px;font-weight:800;line-height:1.4}.cc-status{display:inline-flex;flex:none;align-items:center;gap:5px;border-radius:99px;background:#ecf8f1;padding:5px 7px;color:#238458;font-size:9px;font-weight:800}.cc-status.failed{background:#fff0f0;color:#b8343c}.cc-campaign-date{margin:7px 0 0;color:#53617c;font-size:10px;font-weight:700}.cc-campaign-meta{display:flex;align-items:center;flex-wrap:wrap;gap:5px;margin-top:7px}.cc-chip{border-radius:6px;background:#f1f3f7;padding:4px 6px;color:#768198;font-size:9px;font-weight:750}.cc-campaign-actions{display:flex;align-items:center;gap:12px;margin-top:8px}.cc-link-button{border:0;background:none;padding:0;color:#3158d8;font-family:inherit;font-size:10px;font-weight:800;cursor:pointer}.cc-link-button.red{color:#b6333a}.cc-empty{border:1px dashed #dce2ec;border-radius:11px;padding:22px 14px;text-align:center;color:#8993a6;font-size:11px;line-height:1.55}.cc-templates{margin-top:19px;padding:21px}.cc-template-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px}.cc-template-card{display:flex;flex-direction:column;min-height:145px;border:1px solid #e9edf4;border-radius:12px;background:#fff;padding:13px;transition:.16s}.cc-template-card:hover{transform:translateY(-2px);border-color:#cdd7f7;box-shadow:0 7px 18px #253d7a0e}.cc-template-type{color:#6878bd;font-size:9px;font-weight:850;letter-spacing:.08em;text-transform:uppercase}.cc-template-card h3{margin:7px 0 4px;color:#26334b;font-size:12px;font-weight:850}.cc-template-card p{display:-webkit-box;overflow:hidden;margin:0;color:#8791a4;font-size:10px;line-height:1.5;-webkit-box-orient:vertical;-webkit-line-clamp:2}.cc-template-bottom{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-top:auto;padding-top:13px}.cc-template-bottom form{margin:0}.cc-save-hint{margin-top:9px;border-radius:9px;background:#f6f8fc;padding:9px;color:#7e899e;font-size:9px;line-height:1.5}
    @keyframes cc-pulse{50%{box-shadow:0 0 0 8px #61e5a510;opacity:.7}}@media(max-width:1000px){.cc-grid{grid-template-columns:1fr}.cc-side-stack{grid-template-columns:1fr 1fr}.cc-template-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:650px){.cc-shell{margin:-12px -20px;padding:21px 13px 40px}.cc-head{align-items:flex-start;flex-direction:column}.cc-actions{width:100%}.cc-actions .cc-btn{flex:1}.cc-alarm{grid-template-columns:auto 1fr;gap:12px;padding:16px}.cc-alarm-number{grid-column:2;text-align:left}.cc-alarm-number strong{display:inline;font-size:22px}.cc-alarm-number span{display:inline;margin-left:7px}.cc-stats{gap:8px}.cc-stat{align-items:flex-start;flex-direction:column;gap:8px;padding:11px}.cc-stat-icon{width:32px;height:32px}.cc-stat strong{font-size:19px}.cc-panel-pad{padding:16px}.cc-field-grid,.cc-repeat-row{grid-template-columns:1fr}.cc-channel-grid{grid-template-columns:1fr}.cc-channel-card{min-height:0}.cc-side-stack{grid-template-columns:1fr}.cc-template-grid{grid-template-columns:1fr}.cc-template-card{min-height:120px}.cc-bottom{align-items:stretch;flex-direction:column}.cc-bottom .cc-btn{width:100%}}
</style>
@endpush

@section('content')
@php
    $oldRecurrence = old('recurrence', 'once');
    $activeChannels = old('channels', ['in_app']);
@endphp
<div class="cc-shell">
    <div class="cc-wrap">
        <header class="cc-head">
            <div>
                <div class="cc-eyebrow"><i class="fa-solid fa-signal"></i> Church communications</div>
                <h1>Keep your community connected.</h1>
                <p>Plan thoughtful messages, automate your rhythm, and see every delivery in one place.</p>
            </div>
            <nav class="cc-actions" aria-label="Communication tools">
                <a class="cc-btn cc-btn-light" href="{{ route('admin.communication.services') }}"><i class="fa-regular fa-calendar"></i> Service times</a>
                <a class="cc-btn cc-btn-dark" href="{{ route('admin.communication.deliveries') }}"><i class="fa-solid fa-arrow-up-right-from-square"></i> Delivery history</a>
            </nav>
        </header>

        @if (session('successmessage'))<div class="cc-flash"><i class="fa-solid fa-circle-check"></i> &nbsp;{{ session('successmessage') }}</div>@endif
        @if ($errors->any())<div class="cc-error"><strong>Some details need attention</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <section class="cc-alarm" aria-live="polite">
            <div class="cc-alarm-icon"><i class="fa-regular fa-bell"></i></div>
            <div class="cc-alarm-copy">
                <div class="cc-alarm-title"><span class="cc-live-dot"></span>{{ $activeRecurringCount ? 'Your recurring communication is active' : 'Set a communication rhythm once' }}</div>
                {{ $activeRecurringCount ? 'Your recurring message schedule will keep running automatically. You can pause it by cancelling its campaign below.' : 'Create one recurring campaign and the system will send it on the cadence you choose—no need to schedule that message every day.' }}
            </div>
            <div class="cc-alarm-number"><strong>{{ $activeRecurringCount }}</strong><span>active repeat{{ $activeRecurringCount === 1 ? '' : 's' }}</span></div>
        </section>

        <section class="cc-stats" aria-label="Communication summary">
            <div class="cc-stat"><div class="cc-stat-icon"><i class="fa-regular fa-clock"></i></div><div><span class="cc-stat-label">Scheduled campaigns</span><strong>{{ $scheduledCampaignCount }}</strong></div></div>
            <div class="cc-stat"><div class="cc-stat-icon"><i class="fa-solid fa-paper-plane"></i></div><div><span class="cc-stat-label">Deliveries sent</span><strong>{{ $deliveryStats['sent'] ?? 0 }}</strong></div></div>
            <div class="cc-stat"><div class="cc-stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div><div><span class="cc-stat-label">Needs attention</span><strong>{{ $deliveryStats['failed'] ?? 0 }}</strong></div></div>
        </section>

        <div class="cc-grid">
            <main class="cc-panel cc-panel-pad">
                <div class="cc-panel-head">
                    <div><h2>Create a campaign</h2><p>Choose your audience, write a message, and set a one-time or repeating schedule.</p></div>
                    <span class="cc-tag"><i class="fa-solid fa-wand-magic-sparkles"></i> Message builder</span>
                </div>

                <form method="POST" action="{{ route('admin.communication.campaigns.store') }}" id="campaign-form">
                    @csrf
                    <div class="cc-step">
                        <h3 class="cc-step-title"><span class="cc-step-n">01</span> Start with a template</h3>
                        <div class="cc-template-start">
                            <select id="template-picker" class="cc-select" style="margin:0">
                                <option value="">Choose a ready-made or saved template</option>
                                <optgroup label="Ready-made">
                                    @foreach ($systemTemplates as $index => $template)<option value="system-{{ $index }}">{{ $template['category'] }} · {{ $template['name'] }}</option>@endforeach
                                </optgroup>
                                @if ($templates->isNotEmpty())
                                    <optgroup label="Saved by your church">@foreach ($templates as $template)<option value="saved-{{ $template->id }}">{{ $template->category }} · {{ $template->name }}</option>@endforeach</optgroup>
                                @endif
                            </select>
                            <a class="cc-btn cc-btn-light" href="#template-library"><i class="fa-regular fa-bookmark"></i> Library</a>
                        </div>
                        <span class="cc-help">Personalize with {name}, {church}, {event}, {date}, and {time}.</span>
                    </div>

                    <div class="cc-step">
                        <h3 class="cc-step-title"><span class="cc-step-n">02</span> Who and what</h3>
                        <div class="cc-field-grid">
                            <label class="cc-field">Audience
                                <select class="cc-select" name="audience" required>
                                    <option value="all" @selected(old('audience', 'all') === 'all')>All active members and guests</option>
                                    <option value="members" @selected(old('audience') === 'members')>Members only</option>
                                    <option value="guests" @selected(old('audience') === 'guests')>Guests only</option>
                                </select>
                            </label>
                            <label class="cc-field">Message title / email subject
                                <input class="cc-input" id="campaign-title" name="title" value="{{ old('title') }}" maxlength="191" required placeholder="A clear, welcoming subject">
                            </label>
                        </div>
                        <label class="cc-field" style="margin-top:12px">Message
                            <textarea class="cc-textarea" id="campaign-body" name="body" maxlength="5000" required placeholder="Write a thoughtful message. For example: Hello {name}, we look forward to seeing you at {church}.">{{ old('body') }}</textarea>
                            <span class="cc-help">Keep SMS concise. Longer text may be split into multiple SMS segments. <span class="cc-counter"><span id="body-count">0</span>/5000</span></span>
                        </label>
                        <details class="cc-event-details" {{ old('event_name') || old('event_at') ? 'open' : '' }}>
                            <summary class="cc-details-summary"><i class="fa-regular fa-calendar-days"></i> &nbsp;Add event details for {event}, {date}, and {time}</summary>
                            <div class="cc-field-grid" style="margin-top:10px">
                                <label class="cc-field">Event name<input class="cc-input" name="event_name" value="{{ old('event_name') }}" maxlength="191" placeholder="Youth conference"></label>
                                <label class="cc-field">Event date and time<input class="cc-input" type="datetime-local" name="event_at" value="{{ old('event_at') }}"></label>
                            </div>
                        </details>
                    </div>

                    <div class="cc-step">
                        <h3 class="cc-step-title"><span class="cc-step-n">03</span> Where to send it</h3>
                        <div class="cc-channel-grid">
                            @foreach (['email' => ['Email', 'fa-envelope', 'Consent required'], 'sms' => ['Text message', 'fa-comment-sms', 'Consent + HostPinnacle'], 'in_app' => ['App notification', 'fa-mobile-screen-button', 'Member notification inbox']] as $value => [$label, $icon, $hint])
                                <label class="cc-channel"><input type="checkbox" name="channels[]" value="{{ $value }}" @checked(in_array($value, $activeChannels, true))><span class="cc-channel-card"><span class="cc-channel-icon"><i class="fa-solid {{ $icon }}"></i></span><span><span class="cc-channel-name">{{ $label }}</span><span class="cc-channel-hint">{{ $hint }}</span></span></span></label>
                            @endforeach
                        </div>
                        <span class="cc-help">People who have opted out of email or SMS will be skipped for that channel.</span>
                    </div>

                    <div class="cc-step">
                        <h3 class="cc-step-title"><span class="cc-step-n">04</span> Set the schedule</h3>
                        <div class="cc-field-grid">
                            <label class="cc-field">First send
                                <input class="cc-input" type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" required>
                            </label>
                            <label class="cc-field">Time zone
                                <input class="cc-input" name="timezone" value="{{ old('timezone', $timezone) }}" required placeholder="Africa/Nairobi">
                            </label>
                        </div>
                        <div class="cc-repeat" style="margin-top:12px">
                            <div class="cc-repeat-top"><strong><i class="fa-regular fa-bell"></i> &nbsp;Repeat automatically</strong><label class="cc-repeat-switch"><input id="repeat-enabled" type="checkbox" {{ $oldRecurrence !== 'once' ? 'checked' : '' }}> Make this a recurring alert</label></div>
                            <input type="hidden" name="recurrence" id="recurrence" value="{{ $oldRecurrence }}">
                            <div id="repeat-options" style="{{ $oldRecurrence === 'once' ? 'display:none' : '' }}">
                                <div class="cc-repeat-row">
                                    <label class="cc-field">Repeat
                                        <select class="cc-select" id="repeat-frequency" aria-label="Repeat frequency">
                                            <option value="daily" @selected($oldRecurrence === 'daily')>Every day</option>
                                            <option value="weekly" @selected($oldRecurrence === 'weekly')>Every week</option>
                                            <option value="monthly" @selected($oldRecurrence === 'monthly')>Every month</option>
                                        </select>
                                    </label>
                                    <label class="cc-field">Interval
                                        <input class="cc-input" type="number" id="repeat-interval" name="repeat_interval" min="1" max="52" value="{{ old('repeat_interval', 1) }}">
                                    </label>
                                    <label class="cc-field">Stop after <span style="font-weight:500;color:#8993a6">(optional)</span>
                                        <input class="cc-input" type="date" name="repeat_until" value="{{ old('repeat_until') }}">
                                    </label>
                                </div>
                                <div class="cc-repeat-preview"><i class="fa-regular fa-bell"></i><span id="repeat-summary">This message will repeat automatically.</span></div>
                            </div>
                            <div class="cc-repeat-preview" id="once-summary" style="{{ $oldRecurrence !== 'once' ? 'display:none' : '' }}"><i class="fa-regular fa-clock"></i><span>One-time message. Choose recurring alert to automate future reminders.</span></div>
                        </div>
                    </div>

                    <div class="cc-step">
                        <label class="cc-checkline"><input type="checkbox" name="save_as_template" value="1" id="save-as-template" @checked(old('save_as_template'))><span><strong>Save this message as a reusable template</strong><br>Keep it in your church library for the next campaign.</span></label>
                        <input type="hidden" name="template_id" id="template-id" value="{{ old('template_id') }}">
                        <div class="cc-field-grid" id="template-save-fields" style="display:{{ old('save_as_template') ? 'grid' : 'none' }};margin-top:10px">
                            <label class="cc-field">Template name<input class="cc-input" name="template_name" id="template-name" value="{{ old('template_name') }}" maxlength="100" placeholder="Sunday invitation"></label>
                            <label class="cc-field">Category<input class="cc-input" name="template_category" id="template-category" value="{{ old('template_category', 'General') }}" maxlength="40" placeholder="Events, Guests, Care…"></label>
                        </div>
                    </div>

                    <div class="cc-bottom"><span class="cc-bottom-note"><i class="fa-regular fa-circle-check"></i> Consent is checked per recipient before delivery.</span><button class="cc-btn cc-btn-primary" id="schedule-button" type="submit"><i class="fa-regular fa-calendar-check"></i> <span>Schedule message</span></button></div>
                </form>
            </main>

            <aside class="cc-side-stack">
                <section class="cc-preview">
                    <div class="cc-preview-head"><strong><i class="fa-regular fa-eye"></i> &nbsp;Message preview</strong><span>Personalized sample</span></div>
                    <div class="cc-preview-device"><div class="cc-preview-top"><span class="cc-church-mark"><i class="fa-solid fa-church"></i></span><span><strong>Your church</strong><span>Notification preview</span></span></div><div class="cc-bubble" id="message-preview">Your message will appear here as you write.</div></div>
                </section>

                <section class="cc-panel cc-next">
                    <div class="cc-next-head"><h3><i class="fa-regular fa-bell"></i> &nbsp;Next in your schedule</h3><span>{{ $campaigns->count() }} shown</span></div>
                    <div class="cc-campaign-list">
                        @forelse ($campaigns as $campaign)
                            <article class="cc-campaign">
                                <div class="cc-campaign-top"><h4 class="cc-campaign-title">{{ $campaign->title }}</h4><span class="cc-status {{ $campaign->status === 'failed' ? 'failed' : '' }}"><i class="fa-solid {{ $campaign->status === 'failed' ? 'fa-circle-exclamation' : 'fa-circle-check' }}"></i>{{ $campaign->status === 'scheduled' && $campaign->recurrence !== 'once' ? 'Repeating' : ucfirst($campaign->status) }}</span></div>
                                <p class="cc-campaign-date"><i class="fa-regular fa-clock"></i> &nbsp;{{ $campaign->scheduled_at?->timezone($campaign->timezone)->format('D, M j · g:i A') }} <span style="font-weight:500;color:#929bad">{{ $campaign->timezone }}</span></p>
                                <div class="cc-campaign-meta">@foreach ($campaign->channels ?? [] as $channel)<span class="cc-chip">{{ strtoupper(str_replace('_', '-', $channel)) }}</span>@endforeach<span class="cc-chip">{{ ucfirst($campaign->audience) }}</span>@if($campaign->recurrence !== 'once')<span class="cc-chip">Every {{ $campaign->repeat_interval }} {{ $campaign->recurrence === 'daily' ? 'day(s)' : ($campaign->recurrence === 'weekly' ? 'week(s)' : 'month(s)') }}</span>@endif</div>
                                @if ($campaign->status === 'failed' && $campaign->failure_reason)<p class="cc-help" style="color:#b8343c">{{ \Illuminate\Support\Str::limit($campaign->failure_reason, 120) }}</p>@endif
                                @if ($campaign->status === 'scheduled')<div class="cc-campaign-actions"><form method="POST" action="{{ route('admin.communication.campaigns.destroy', $campaign->id) }}">@csrf @method('DELETE')<button class="cc-link-button red" onclick="return confirm('Cancel this message schedule?')">Pause / cancel</button></form></div>
                                @elseif ($campaign->status === 'failed')<div class="cc-campaign-actions"><form method="POST" action="{{ route('admin.communication.campaigns.retry', $campaign->id) }}">@csrf<button class="cc-link-button">Retry campaign</button></form></div>
                                @elseif ($campaign->status === 'sent')<span class="cc-help">{{ $campaign->run_count }} run(s) · {{ $campaign->queued_deliveries }} deliveries queued</span>@endif
                            </article>
                        @empty
                            <div class="cc-empty"><i class="fa-regular fa-calendar-xmark" style="font-size:20px"></i><br>No message schedules yet.<br>Your next message will appear here.</div>
                        @endforelse
                    </div>
                </section>
            </aside>
        </div>

        <section class="cc-panel cc-templates" id="template-library">
            <div class="cc-panel-head"><div><h2>Message library</h2><p>Ready-made starting points and templates saved by your church. Every message remains editable.</p></div><span class="cc-tag"><i class="fa-regular fa-bookmark"></i> {{ count($systemTemplates) + $templates->count() }} templates</span></div>
            <div class="cc-template-grid">
                @foreach ($systemTemplates as $template)
                    <article class="cc-template-card"><span class="cc-template-type">{{ $template['category'] }} · Ready-made</span><h3>{{ $template['name'] }}</h3><p>{{ $template['body'] }}</p><div class="cc-template-bottom"><span class="cc-help">Editable before scheduling</span><button type="button" class="cc-link-button use-message-template" data-template="{{ base64_encode(json_encode($template)) }}">Use template <i class="fa-solid fa-arrow-right"></i></button></div></article>
                @endforeach
                @foreach ($templates as $template)
                    <article class="cc-template-card"><span class="cc-template-type">{{ $template->category }} · Your church</span><h3>{{ $template->name }}</h3><p>{{ $template->body }}</p><div class="cc-template-bottom"><form method="POST" action="{{ route('admin.communication.templates.destroy', $template->id) }}">@csrf @method('DELETE')<button class="cc-link-button red" onclick="return confirm('Delete this church template?')">Delete</button></form><button type="button" class="cc-link-button use-message-template" data-template="{{ base64_encode(json_encode(['id' => $template->id, 'name' => $template->name, 'category' => $template->category, 'title' => $template->title, 'body' => $template->body, 'channels' => $template->channels ?? ['in_app']])) }}">Use template <i class="fa-solid fa-arrow-right"></i></button></div></article>
                @endforeach
            </div>
            <div class="cc-save-hint"><i class="fa-regular fa-lightbulb"></i> &nbsp;To update a church template, choose it, make your edits, then turn on “Save this message as a reusable template” before scheduling.</div>
        </section>
    </div>
</div>

<script type="application/json" id="system-communication-templates">@json($systemTemplates)</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('campaign-form');
    const body = document.getElementById('campaign-body');
    const preview = document.getElementById('message-preview');
    const title = document.getElementById('campaign-title');
    const counter = document.getElementById('body-count');
    const picker = document.getElementById('template-picker');
    const savedTemplates = @json($savedTemplatePayload);
    const systemTemplates = JSON.parse(document.getElementById('system-communication-templates').textContent);
    const recurring = document.getElementById('repeat-enabled');
    const options = document.getElementById('repeat-options');
    const recurrence = document.getElementById('recurrence');
    const frequency = document.getElementById('repeat-frequency');
    const interval = document.getElementById('repeat-interval');
    const repeatSummary = document.getElementById('repeat-summary');
    const saveTemplate = document.getElementById('save-as-template');
    const saveFields = document.getElementById('template-save-fields');
    const submitLabel = document.querySelector('#schedule-button span');

    function updatePreview() {
        counter.textContent = body.value.length;
        const titleText = title.value.trim() || 'Your message title';
        const bodyText = body.value.trim() || 'Your message will appear here as you write.';
        preview.textContent = titleText + '\n\n' + bodyText.replaceAll('{name}', 'Sarah').replaceAll('{church}', 'Grace Community Church').replaceAll('{event}', 'Community Sunday').replaceAll('{date}', 'Sunday, November 8').replaceAll('{time}', '9:00 AM');
    }
    function updateRepeat() {
        const enabled = recurring.checked;
        options.style.display = enabled ? 'block' : 'none';
        document.getElementById('once-summary').style.display = enabled ? 'none' : 'flex';
        recurrence.value = enabled ? frequency.value : 'once';
        submitLabel.textContent = enabled ? 'Start recurring alert' : 'Schedule one-time message';
        interval.required = enabled;
        const unit = frequency.value === 'daily' ? 'day' : (frequency.value === 'weekly' ? 'week' : 'month');
        const amount = Math.max(1, Number(interval.value || 1));
        repeatSummary.textContent = 'This message will repeat every ' + (amount === 1 ? '' : amount + ' ') + unit + (amount === 1 ? '' : 's') + '. You will not need to schedule it again.';
    }
    function applyTemplate(template) {
        if (!template) return;
        title.value = template.title || '';
        body.value = template.body || '';
        document.getElementById('template-name').value = template.name || '';
        document.getElementById('template-category').value = template.category || 'General';
        document.getElementById('template-id').value = template.id || '';
        document.querySelectorAll('input[name="channels[]"]').forEach((box) => { box.checked = (template.channels || ['in_app']).includes(box.value); });
        updatePreview();
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    body.addEventListener('input', updatePreview);
    title.addEventListener('input', updatePreview);
    recurring.addEventListener('change', updateRepeat);
    frequency.addEventListener('change', updateRepeat);
    interval.addEventListener('input', updateRepeat);
    saveTemplate.addEventListener('change', () => { saveFields.style.display = saveTemplate.checked ? 'grid' : 'none'; });
    picker.addEventListener('change', () => {
        const [kind, key] = (picker.value || '').split('-');
        if (kind === 'system') applyTemplate(systemTemplates[Number(key)]);
        if (kind === 'saved') applyTemplate(savedTemplates.find((item) => String(item.id) === key));
    });
    document.querySelectorAll('.use-message-template').forEach((button) => button.addEventListener('click', () => {
        try { applyTemplate(JSON.parse(atob(button.dataset.template))); } catch (error) { console.error('Unable to load message template.', error); }
    }));
    updatePreview();
    updateRepeat();
});
</script>
@endsection
