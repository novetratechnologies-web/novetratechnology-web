@extends('theme::layout')

@section('title', 'Events | IGM Church')
@section('meta_description', 'Find worship services, gatherings, and community events at IGM Church.')

@push('styles')
<style>
    .event-index{--navy:#082846;--deep:#061d34;--gold:#d8ad5d;--gold-light:#f0d08a;--ink:#142b43;--muted:#637487;--paper:#f8f7f3;color:var(--ink);background:var(--paper)}
    .event-index *{box-sizing:border-box}.event-index a{color:inherit;text-decoration:none}
    .event-wrap{width:min(1160px,calc(100% - 40px));margin:auto}
    .event-hero{position:relative;overflow:hidden;background:radial-gradient(ellipse at 85% 20%,rgba(216,173,93,.24),transparent 33%),linear-gradient(120deg,var(--deep),var(--navy));color:#fff;padding:70px 0 58px}
    .event-hero:after{content:"";position:absolute;right:-105px;bottom:-275px;width:480px;height:480px;border:1px solid rgba(240,208,138,.15);border-radius:50%;box-shadow:0 0 0 38px rgba(240,208,138,.035),0 0 0 78px rgba(240,208,138,.025)}
    .event-hero-inner{position:relative;z-index:1;max-width:720px}.event-eyebrow{display:inline-flex;align-items:center;gap:9px;color:var(--gold-light);font-size:11px;font-weight:800;letter-spacing:.17em;text-transform:uppercase}.event-eyebrow:before{content:"";width:24px;height:1px;background:currentColor}
    .event-hero h1{font-size:clamp(38px,6vw,66px);line-height:1.02;letter-spacing:-.045em;margin:16px 0 13px;color:#fff}.event-hero p{max-width:570px;margin:0;color:rgba(255,255,255,.76);font-size:16px;line-height:1.75}
    .event-breadcrumb{display:flex;gap:9px;align-items:center;margin-top:26px;color:rgba(255,255,255,.57);font-size:12px}.event-breadcrumb a:hover{color:var(--gold-light)}
    .event-content{padding:46px 0 82px}.event-tools{display:grid;grid-template-columns:minmax(220px,1fr) 190px 230px auto;gap:12px;padding:14px;background:white;border:1px solid #e8e6df;border-radius:16px;box-shadow:0 10px 30px rgba(8,40,70,.05);margin-bottom:48px}
    .event-control{min-height:48px;border:1px solid #e5e7e9;border-radius:10px;background:#fff;padding:0 14px;color:var(--ink);font:inherit;font-size:14px;outline:none}.event-control:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(216,173,93,.15)}.event-search{display:flex;align-items:center;gap:10px}.event-search span{color:#8290a0}.event-search input{width:100%;border:0;outline:0;font:inherit;font-size:14px;color:var(--ink)}
    .event-button{border:0;border-radius:10px;background:var(--gold);color:var(--deep);font-weight:800;font-size:13px;padding:0 22px;min-height:48px;cursor:pointer;transition:background .2s,transform .2s}.event-button:hover{background:var(--gold-light);transform:translateY(-1px)}.event-section{margin:0 0 58px}.event-section-head{display:flex;align-items:end;justify-content:space-between;gap:20px;margin-bottom:22px}.event-section-kicker{margin:0 0 7px;color:#a67c34;font-size:10px;font-weight:800;letter-spacing:.15em;text-transform:uppercase}.event-section h2{margin:0;color:var(--navy);font-size:clamp(25px,3vw,34px);letter-spacing:-.035em}.event-count{font-size:12px;color:var(--muted)}
    .event-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:20px}.event-card{overflow:hidden;background:#fff;border:1px solid #e9e8e3;border-radius:15px;box-shadow:0 7px 24px rgba(8,40,70,.045);transition:transform .22s,box-shadow .22s}.event-card:hover{transform:translateY(-4px);box-shadow:0 16px 34px rgba(8,40,70,.12)}.event-card-image{height:204px;position:relative;background:#dfe4e5;overflow:hidden}.event-card-image img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s}.event-card:hover .event-card-image img{transform:scale(1.04)}.event-image-placeholder{width:100%;height:100%;display:grid;place-items:center;background:linear-gradient(135deg,#e8e5dc,#d5d9d6);color:var(--navy);font-size:34px}.event-date-chip{position:absolute;left:15px;bottom:15px;width:58px;padding:8px 5px;text-align:center;background:#fff;border-radius:10px;box-shadow:0 5px 16px rgba(0,0,0,.14)}.event-date-chip span{display:block;color:#a67c34;text-transform:uppercase;font-size:9px;letter-spacing:.08em;font-weight:900}.event-date-chip strong{display:block;color:var(--navy);font-size:21px;line-height:1.1}.event-card-body{padding:18px 18px 17px}.event-card-meta{display:flex;flex-wrap:wrap;gap:7px;margin-bottom:10px}.event-tag{display:inline-flex;align-items:center;border-radius:99px;background:#f5f0e4;color:#8b6726;padding:5px 9px;font-size:10px;font-weight:800;text-transform:capitalize}.event-tag-recurring{background:#edf2f4;color:#405c72}.event-card h3{font-size:19px;line-height:1.3;letter-spacing:-.02em;margin:0 0 9px;color:var(--navy)}.event-card-description{font-size:13px;line-height:1.6;color:var(--muted);margin:0 0 14px}.event-detail-line{display:flex;gap:9px;align-items:flex-start;color:#516477;font-size:12px;line-height:1.5;margin-top:7px}.event-detail-icon{color:#a67c34;font-weight:900;min-width:14px}.event-card-link{display:inline-flex;align-items:center;gap:8px;color:#946c25;font-size:12px;font-weight:800;margin-top:16px}.event-card-link span{transition:transform .2s}.event-card:hover .event-card-link span{transform:translateX(4px)}
    .event-empty{background:#fff;border:1px dashed #d9d8d0;border-radius:16px;padding:50px 24px;text-align:center}.event-empty-icon{width:52px;height:52px;border-radius:50%;display:grid;place-items:center;background:#f5f0e4;color:#936d2c;font-size:21px;margin:0 auto 13px}.event-empty h3{color:var(--navy);margin:0 0 7px;font-size:19px}.event-empty p{color:var(--muted);margin:0;font-size:14px}.event-clear{display:inline-block;color:#946c25;font-weight:800;margin-top:15px;font-size:13px}
    .event-pagination{margin-top:24px}.event-pagination nav{display:flex;justify-content:center}.event-pagination nav>div:first-child{display:none}.event-pagination nav>div:last-child{display:flex;align-items:center;gap:5px}.event-pagination a,.event-pagination span[aria-current]{border-radius:8px!important}
    .event-archive{display:grid;gap:14px}.event-archive-month{padding:0 20px 20px;border-top:1px solid #efeee9}.event-archive-month h3{color:#916c2d;font-size:12px;letter-spacing:.1em;text-transform:uppercase;margin:17px 0 11px}.event-archive-list{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:9px}.event-archive-item{display:flex;gap:12px;align-items:center;background:#faf9f6;border-radius:10px;padding:10px;min-width:0}.event-archive-item img{width:62px;height:54px;object-fit:cover;border-radius:7px}.event-archive-item strong{display:block;color:var(--navy);font-size:13px}.event-archive-item small{display:block;color:var(--muted);font-size:11px;margin-top:4px}
    .event-section-intro{margin:8px 0 0;color:var(--muted);font-size:13px;line-height:1.6}.event-archive-year{display:grid;gap:14px}.event-archive-year-title{margin:0;padding-bottom:10px;border-bottom:1px solid #e6e2d8;color:#8c682b;font-size:13px;letter-spacing:.12em}.event-archive-month{padding:0;border:0}.event-archive-month h4{margin:3px 0 12px;color:#435c72;font-size:13px}.event-archive-list{grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}.event-card-past{border-color:#e9e1d0}.event-past-badge{position:absolute;top:14px;right:14px;z-index:1;padding:6px 10px;border-radius:99px;background:rgba(7,35,60,.9);color:#f3d38b;font-size:10px;font-weight:850;letter-spacing:.04em;text-transform:uppercase}.event-tag-video{background:#e9f0f4;color:#37566f}
    @media(max-width:800px){.event-grid,.event-archive-list{grid-template-columns:repeat(2,minmax(0,1fr))}.event-tools{grid-template-columns:1fr 1fr}.event-search{grid-column:1/-1}.event-button{grid-column:1/-1}.event-hero{padding:54px 0 44px}}
    @media(max-width:560px){.event-wrap{width:min(100% - 28px,1160px)}.event-content{padding:27px 0 54px}.event-grid,.event-archive-list{grid-template-columns:1fr;gap:14px}.event-card-image{height:205px}.event-tools{grid-template-columns:1fr;padding:11px}.event-search,.event-button{grid-column:auto}.event-section-head{align-items:start;flex-direction:column;gap:8px}.event-archive-month{padding:0}}
</style>
@endpush

@section('content')
<div class="event-index">
    <section class="event-hero">
        <div class="event-wrap event-hero-inner">
            <span class="event-eyebrow">Life together at IGM</span>
            <h1>Gather, grow<br>and make a difference.</h1>
            <p>Find a place to worship, connect with others and take part in what God is doing in our church and community.</p>
            <nav class="event-breadcrumb" aria-label="Breadcrumb"><a href="{{ route('web.home') }}">Home</a><span>/</span><span>Events</span></nav>
        </div>
    </section>

    <div class="event-wrap event-content">
        <form class="event-tools" method="GET" action="{{ route('web.events') }}" role="search">
            <label class="event-control event-search"><span aria-hidden="true">⌕</span><input type="search" name="q" value="{{ $search }}" placeholder="Search events, places, or details" aria-label="Search events"></label>
            <select class="event-control" name="period" aria-label="Filter by event date">
                <option value="all" @selected($period === 'all')>All events</option>
                <option value="upcoming" @selected($period === 'upcoming')>Upcoming</option>
                <option value="past" @selected($period === 'past')>Past events</option>
            </select>
            <select class="event-control" name="category" aria-label="Filter by category">
                <option value="">All event types</option>
                @foreach($categories as $eventCategory)
                    <option value="{{ $eventCategory }}" @selected($category === $eventCategory)>{{ ucfirst($eventCategory) }}</option>
                @endforeach
            </select>
            <button class="event-button" type="submit">Find events</button>
        </form>

        @if($period !== 'past' && ($upcoming->isNotEmpty() || $period === 'upcoming' || !$completed))
        <section class="event-section" aria-labelledby="upcoming-title">
            <div class="event-section-head">
                <div><p class="event-section-kicker">Save your place</p><h2 id="upcoming-title">Upcoming events</h2></div>
                <span class="event-count">{{ $upcoming->total() }} {{ \Illuminate\Support\Str::plural('event', $upcoming->total()) }}</span>
            </div>

            @if($upcoming->isNotEmpty())
                <div class="event-grid">
                    @foreach($upcoming as $event)
                        @php
                            $nextOccurrence = $event->repeats == 1 ? $event->nextOccurrenceDate() : null;
                            $displayDate = $nextOccurrence ?: \Carbon\Carbon::parse($event->start_date);
                        @endphp
                        <a class="event-card" href="{{ route('web.event', $event->id) }}">
                            <div class="event-card-image">
                                <img src="{{ $event->ImagePath }}" alt="{{ $event->title }}" loading="lazy">
                                <div class="event-date-chip"><span>{{ $displayDate->format('M') }}</span><strong>{{ $displayDate->format('d') }}</strong></div>
                            </div>
                            <div class="event-card-body">
                                <div class="event-card-meta">
                                    @if($event->category)<span class="event-tag">{{ $event->category }}</span>@endif
                                    @if($event->repeats == 1)<span class="event-tag event-tag-recurring">Recurring</span>@endif
                                    @if($event->select_type === 'online')<span class="event-tag event-tag-recurring">Online</span>@endif
                                </div>
                                <h3>{{ $event->title }}</h3>
                                @if($event->description)<p class="event-card-description">{{ \Illuminate\Support\Str::limit(strip_tags($event->description), 112) }}</p>@endif
                                <div class="event-detail-line"><span class="event-detail-icon">◷</span><span>@if($event->allDay)All day · @endif{{ $displayDate->format('l, F j') }}@unless($event->allDay) · {{ $displayDate->format('g:i A') }}@endunless</span></div>
                                @if($event->location)<div class="event-detail-line"><span class="event-detail-icon">⌖</span><span>{{ $event->location }}</span></div>@endif
                                <span class="event-card-link">Event details <span aria-hidden="true">→</span></span>
                            </div>
                        </a>
                    @endforeach
                </div>
                @if($upcoming->hasPages())<div class="event-pagination">{{ $upcoming->links() }}</div>@endif
            @else
                <div class="event-empty"><span class="event-empty-icon" aria-hidden="true">✦</span><h3>No upcoming events found</h3><p>Try another search or check back soon for new ways to connect.</p>@if($search || $category)<a class="event-clear" href="{{ route('web.events') }}">Clear filters</a>@endif</div>
            @endif
        </section>
        @endif

        @if(count($completed) && $period !== 'upcoming')
            <section class="event-section" aria-labelledby="past-title">
                <div class="event-section-head"><div><p class="event-section-kicker">Watch again · remember together</p><h2 id="past-title">Past events</h2><p class="event-section-intro">Recordings and highlights from our gatherings. Past events stay here alongside what’s coming next.</p></div><span class="event-count">{{ collect($completed)->flatten(2)->count() }} events</span></div>
                <div class="event-archive">
                    @foreach($completed as $year => $months)
                        <section class="event-archive-year" aria-label="Events in {{ $year }}">
                            <h3 class="event-archive-year-title">{{ $year }}</h3>
                            @foreach($months as $month => $events)
                                <div class="event-archive-month">
                                    <h4>{{ $month }}</h4>
                                    <div class="event-archive-list">
                                        @foreach($events as $pastEvent)
                                            <a class="event-card event-card-past" href="{{ route('web.event', $pastEvent->id) }}">
                                                <div class="event-card-image">
                                                    <img src="{{ $pastEvent->ImagePath }}" alt="{{ $pastEvent->title }}" loading="lazy">
                                                    <span class="event-past-badge">Past event</span>
                                                    <div class="event-date-chip"><span>{{ \Carbon\Carbon::parse($pastEvent->start_date)->format('M') }}</span><strong>{{ \Carbon\Carbon::parse($pastEvent->start_date)->format('d') }}</strong></div>
                                                </div>
                                                <div class="event-card-body">
                                                    <div class="event-card-meta">
                                                        @if($pastEvent->category)<span class="event-tag">{{ $pastEvent->category }}</span>@endif
                                                        @if($pastEvent->video_url)<span class="event-tag event-tag-video">Video recording</span>@endif
                                                    </div>
                                                    <h3>{{ $pastEvent->title }}</h3>
                                                    @if($pastEvent->description)<p class="event-card-description">{{ \Illuminate\Support\Str::limit(strip_tags($pastEvent->description), 112) }}</p>@endif
                                                    <div class="event-detail-line"><span class="event-detail-icon">◷</span><span>{{ \Carbon\Carbon::parse($pastEvent->start_date)->format('l, F j, Y') }}</span></div>
                                                    @if($pastEvent->location)<div class="event-detail-line"><span class="event-detail-icon">⌖</span><span>{{ $pastEvent->location }}</span></div>@endif
                                                    <span class="event-card-link">{{ $pastEvent->video_url ? 'Watch recording' : 'Event details' }} <span aria-hidden="true">→</span></span>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </section>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
@endsection
