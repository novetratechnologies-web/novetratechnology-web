@extends('theme::layout')

@section('title', $event->title . ' | IGM Church')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($event->description ?? ''), 160))

@push('styles')
<style>
    .event-detail{--navy:#082846;--deep:#061d34;--gold:#d8ad5d;--ink:#142b43;--muted:#637487;--paper:#f8f7f3;color:var(--ink);background:var(--paper);min-height:70vh;padding:42px 0 78px}
    .event-detail *{box-sizing:border-box}.event-detail a{color:inherit;text-decoration:none}.event-detail-wrap{width:min(1080px,calc(100% - 40px));margin:0 auto}
    .event-detail-crumb{display:flex;align-items:center;gap:9px;color:#778697;font-size:12px;margin-bottom:20px}.event-detail-crumb a:hover{color:#986f26}
    .event-detail-hero{height:clamp(250px,40vw,460px);position:relative;overflow:hidden;border-radius:20px;background:linear-gradient(130deg,#d9dedc,#b4c0c2);box-shadow:0 16px 42px rgba(8,40,70,.12)}.event-detail-hero img{width:100%;height:100%;object-fit:cover;display:block}.event-detail-hero:after{content:"";position:absolute;inset:35% 0 0;background:linear-gradient(transparent,rgba(4,23,41,.55));pointer-events:none}.event-detail-date{position:absolute;z-index:1;left:24px;top:24px;padding:11px 14px;background:#fff;border-radius:11px;box-shadow:0 8px 20px rgba(0,0,0,.14);text-align:center;min-width:65px}.event-detail-date span{display:block;color:#946c25;text-transform:uppercase;font-size:10px;letter-spacing:.08em;font-weight:900}.event-detail-date strong{display:block;color:var(--navy);font-size:23px;line-height:1.12}.event-detail-hero-title{position:absolute;z-index:1;left:clamp(20px,5vw,52px);right:24px;bottom:clamp(22px,5vw,42px);color:white}.event-detail-hero-title h1{margin:10px 0 0;color:#fff;font-size:clamp(30px,5vw,52px);line-height:1.05;letter-spacing:-.04em;max-width:780px}.event-detail-tags{display:flex;gap:8px;flex-wrap:wrap}.event-detail-tag{border-radius:99px;padding:6px 11px;background:rgba(255,255,255,.95);color:#805d23;font-size:10px;text-transform:capitalize;font-weight:800}.event-detail-tag.dark{background:rgba(5,29,51,.72);color:#f1d491;border:1px solid rgba(241,212,145,.3)}
    .event-detail-columns{display:grid;grid-template-columns:minmax(0,1fr) 310px;gap:25px;align-items:start;margin-top:25px}.event-detail-main,.event-detail-side{background:white;border:1px solid #e9e7df;border-radius:16px;box-shadow:0 8px 24px rgba(8,40,70,.045)}.event-detail-main{padding:clamp(22px,4vw,38px)}.event-detail-main h2,.event-gallery-heading h2{color:var(--navy);font-size:22px;letter-spacing:-.025em;margin:0 0 14px}.event-description{font-size:15px;color:#526477;line-height:1.8;white-space:pre-line;overflow-wrap:anywhere}.event-detail-side{padding:21px;position:sticky;top:22px}.event-detail-side h2{color:var(--navy);font-size:17px;margin:0 0 17px}.event-info{display:grid;gap:15px}.event-info-row{display:flex;gap:11px;align-items:flex-start}.event-info-icon{flex:0 0 35px;height:35px;border-radius:10px;display:grid;place-items:center;background:#f5f0e4;color:#976f28;font-weight:900}.event-info-row strong{display:block;font-size:12px;color:#3b5269;margin:0 0 4px}.event-info-row span{display:block;font-size:12px;line-height:1.5;color:var(--muted);overflow-wrap:anywhere}.event-detail-back{display:flex;justify-content:center;align-items:center;gap:9px;margin-top:21px;min-height:45px;border-radius:10px;background:var(--navy);color:white!important;font-size:12px;font-weight:800;transition:background .2s}.event-detail-back:hover{background:#123e64}.event-detail-gallery{margin-top:28px}.event-gallery-heading{display:flex;justify-content:space-between;align-items:end;gap:15px;margin-bottom:14px}.event-gallery-heading h2{margin:0}.event-gallery-heading span{font-size:11px;color:var(--muted)}.event-photo-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:11px}.event-photo{height:170px;display:block;overflow:hidden;border-radius:11px;background:#e6e8e5}.event-photo img{width:100%;height:100%;object-fit:cover;transition:transform .35s}.event-photo:hover img{transform:scale(1.05)}
    .event-detail-video{margin-top:24px;padding:clamp(18px,3vw,30px);border:1px solid #e9e7df;border-radius:16px;background:#fff;box-shadow:0 8px 24px rgba(8,40,70,.045)}.event-detail-video h2{margin:0 0 15px;color:var(--navy);font-size:22px}.event-video-frame{width:100%;aspect-ratio:16/9;overflow:hidden;border-radius:12px;background:#071b2b}.event-video-frame iframe{display:block;width:100%;height:100%;border:0}
    @media(max-width:800px){.event-detail-columns{grid-template-columns:1fr}.event-detail-side{position:static}.event-info{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(max-width:560px){.event-detail{padding:25px 0 50px}.event-detail-wrap{width:min(100% - 28px,1080px)}.event-detail-hero{height:280px;border-radius:14px}.event-detail-date{left:14px;top:14px}.event-detail-columns{gap:14px;margin-top:15px}.event-detail-main,.event-detail-side{border-radius:13px;padding:20px}.event-info{grid-template-columns:1fr}.event-photo-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}.event-photo{height:135px}.event-gallery-heading{align-items:start;flex-direction:column}}
</style>
@endpush

@section('content')
@php
    $eventStart = \Carbon\Carbon::parse($event->start_date);
    $nextOccurrence = $event->repeats == 1 ? $event->nextOccurrenceDate() : null;
    $displayStart = $nextOccurrence ?: $eventStart;
    $isPastEvent = $eventStart->lt(\Carbon\Carbon::today());
@endphp
<article class="event-detail">
    <div class="event-detail-wrap">
        <nav class="event-detail-crumb" aria-label="Breadcrumb"><a href="{{ route('web.home') }}">Home</a><span>/</span><a href="{{ route('web.events') }}">Events</a><span>/</span><span>{{ \Illuminate\Support\Str::limit($event->title, 45) }}</span></nav>

        <header class="event-detail-hero">
            <img src="{{ $event->ImagePath }}" alt="{{ $event->title }}">
            <div class="event-detail-date"><span>{{ $displayStart->format('M') }}</span><strong>{{ $displayStart->format('d') }}</strong></div>
            <div class="event-detail-hero-title">
                <div class="event-detail-tags">
                    @if($event->category)<span class="event-detail-tag">{{ $event->category }}</span>@endif
                    @if($isPastEvent)<span class="event-detail-tag dark">Past event</span>@endif
                    @if($event->repeats == 1)<span class="event-detail-tag dark">Recurring</span>@endif
                    @if($event->select_type === 'online')<span class="event-detail-tag dark">Online event</span>@endif
                    @if($event->allDay)<span class="event-detail-tag dark">All day</span>@endif
                </div>
                <h1>{{ $event->title }}</h1>
            </div>
        </header>

        <div class="event-detail-columns">
            <div>
                <section class="event-detail-main">
                    <h2>About this event</h2>
                    @if($event->description)
                        <div class="event-description">{{ strip_tags($event->description) }}</div>
                    @else
                        <p class="event-description">Join us for this gathering at IGM Church. We look forward to seeing you.</p>
                    @endif
                </section>

                @if($event->video_embed_url)
                    <section class="event-detail-video" aria-labelledby="event-video-title">
                        <h2 id="event-video-title">Watch the recording</h2>
                        <div class="event-video-frame"><iframe src="{{ $event->video_embed_url }}" title="{{ $event->title }} recording" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe></div>
                    </section>
                @endif

                @if($event->gallery->isNotEmpty())
                    <section class="event-detail-gallery" aria-labelledby="event-gallery-title">
                        <div class="event-gallery-heading"><h2 id="event-gallery-title">Moments from this event</h2><span>{{ $event->gallery->count() }} {{ \Illuminate\Support\Str::plural('photo', $event->gallery->count()) }}</span></div>
                        <div class="event-photo-grid">
                            @foreach($event->gallery as $photo)
                                <a class="event-photo" href="{{ $photo->FullPath }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ $photo->FullPath }}" alt="{{ $event->title }} event photo" loading="lazy">
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside class="event-detail-side" aria-label="Event information">
                <h2>Event information</h2>
                <div class="event-info">
                    <div class="event-info-row"><span class="event-info-icon" aria-hidden="true">◷</span><div><strong>{{ $event->repeats == 1 && $nextOccurrence ? 'Next gathering' : 'Date and time' }}</strong><span>@if($event->allDay){{ $displayStart->format('l, F j, Y') }} · All day @else{{ $displayStart->format('l, F j, Y') }}<br>{{ $displayStart->format('g:i A') }}@endif</span></div></div>
                    @if($event->end_date && $event->repeats != 1)
                        <div class="event-info-row"><span class="event-info-icon" aria-hidden="true">↗</span><div><strong>Ends</strong><span>{{ \Carbon\Carbon::parse($event->end_date)->format($event->allDay ? 'l, F j, Y' : 'l, F j, Y · g:i A') }}</span></div></div>
                    @endif
                    @if($event->location)<div class="event-info-row"><span class="event-info-icon" aria-hidden="true">⌖</span><div><strong>Location</strong><span>{{ $event->location }}</span></div></div>@endif
                    @if($event->organised_by)<div class="event-info-row"><span class="event-info-icon" aria-hidden="true">✦</span><div><strong>Hosted by</strong><span>{{ $event->organised_by }}</span></div></div>@endif
                    @if($event->repeats == 1 && $event->freq_term)
                        <div class="event-info-row"><span class="event-info-icon" aria-hidden="true">↻</span><div><strong>Schedule</strong><span>Repeats every {{ max(1, (int) $event->freq) }} {{ $event->freq_term }}{{ (int) $event->freq > 1 ? 's' : '' }}@if($event->end_date)<br>Until {{ \Carbon\Carbon::parse($event->end_date)->format('M j, Y') }}@endif</span></div></div>
                    @endif
                </div>
                <a class="event-detail-back" href="{{ route('web.events') }}"><span aria-hidden="true">←</span> Explore all events</a>
            </aside>
        </div>
    </div>
</article>
@endsection
