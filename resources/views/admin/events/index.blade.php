@extends('layouts.admin.layout')

@push('styles')
    @include('admin.events._admin_styles')
@endpush

@section('content')
@php
    $canCreate = auth()->user()->usergroup_id == 3 || auth()->user()->hasPermission('create-events');
    $statuses = ['all' => 'All events', 'upcoming' => 'Upcoming', 'ongoing' => 'Happening now', 'completed' => 'Past events'];
@endphp
<div class="event-admin event-list">
    <header class="event-heading">
        <div class="event-heading-copy"><p class="event-kicker">Church life · Events</p><h1 class="admin-h1">Events</h1><p class="event-subtitle">Plan gatherings, publish them for your community, and manage attendance and photos from one place.</p></div>
        <div class="event-actions">@if($canCreate)<a href="{{ route('admin.events.new') }}" class="event-primary"><i class="fas fa-plus"></i> Create event</a>@endif</div>
    </header>

    @include('partials.message')

    <div class="event-stats">
        @foreach([['all','Total events','Across all dates'],['upcoming','Upcoming','Still to come'],['ongoing','Happening now','Currently in progress'],['completed','Past events','Finished gatherings']] as [$key,$label,$hint])
        <a href="{{ route('admin.events.index', ['filter' => $key]) }}" class="event-stat" style="text-decoration:none"><span>{{ $label }}</span><strong>{{ $counts[$key] ?? 0 }}</strong><em>{{ $hint }}</em></a>
        @endforeach
    </div>

    <section class="event-panel" style="padding:14px">
        <form class="event-filterbar" method="GET" action="{{ route('admin.events.index') }}">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <label class="event-search"><i class="fas fa-search"></i><input type="search" name="q" value="{{ $search }}" class="tw-form-control" placeholder="Search title, place, organiser…" aria-label="Search events"></label>
            <select class="event-filter-select" name="category" aria-label="Filter by category"><option value="">Every category</option>@foreach($categories as $value => $label)<option value="{{ $value }}" @selected($category === $value)>{{ $label }}</option>@endforeach</select>
            <button class="event-secondary" type="submit"><i class="fas fa-filter"></i> Filter</button>
            @if($search || $category)<a class="event-secondary" href="{{ route('admin.events.index', ['filter' => $filter]) }}">Clear</a>@endif
        </form>

        <nav class="event-filter-pills" aria-label="Event status">
            @foreach($statuses as $key => $label)
            <a class="event-filter-pill {{ $filter === $key ? 'is-active' : '' }}" href="{{ route('admin.events.index', array_filter(['filter' => $key, 'category' => $category, 'q' => $search])) }}">{{ $label }}<span>{{ $counts[$key] ?? 0 }}</span></a>
            @endforeach
            <div style="margin-left:auto;display:flex;gap:6px">
                <button type="button" class="event-filter-pill is-active" id="event-list-view"><i class="fas fa-grip"></i> Cards</button>
                <button type="button" class="event-filter-pill" id="event-calendar-view"><i class="fas fa-calendar-days"></i> Calendar</button>
            </div>
        </nav>

        <div id="events-list-panel">
            @if($events->isEmpty())
                <div class="event-empty"><span class="event-empty-icon"><i class="fas fa-calendar-plus"></i></span><h2 class="event-card-title">{{ $count ? 'No events match these filters' : 'Start planning your next gathering' }}</h2><p class="event-subtitle">{{ $count ? 'Try a different search or category.' : 'Create your first event to share it with members and visitors.' }}</p>@if(!$count && $canCreate)<a href="{{ route('admin.events.new') }}" class="event-primary" style="margin-top:15px"><i class="fas fa-plus"></i> Create first event</a>@elseif($search || $category)<a href="{{ route('admin.events.index', ['filter' => $filter]) }}" class="event-secondary" style="margin-top:15px">Clear filters</a>@endif</div>
            @else
                <div class="event-results">
                    @foreach($events as $event)
                    @php
                        $eventStart = \Carbon\Carbon::parse($event->start_date);
                        $eventEnd = \Carbon\Carbon::parse($event->end_date);
                        $eventStatus = $eventStart->isFuture() ? 'Upcoming' : ($eventEnd->isPast() ? 'Past' : 'Happening');
                        $eventStatusClass = $eventStatus === 'Past' ? 'is-past' : ($event->select_type === 'online' ? 'is-online' : '');
                    @endphp
                    <article class="event-card">
                        <a class="event-card-image" href="{{ route('admin.events.show', $event->id) }}" aria-label="View {{ $event->title }}">
                            @if($event->image)<img src="{{ $event->ImagePath }}" alt="{{ $event->title }}" loading="lazy">@else<span class="event-card-placeholder"><i class="fas fa-calendar-days"></i></span>@endif
                        </a>
                        <div class="event-card-content">
                            <div class="event-card-top"><h2>{{ $event->title }}</h2><span class="event-badge {{ $eventStatusClass }}">{{ $eventStatus }}</span></div>
                            <div class="event-card-meta"><span><i class="fas fa-calendar-day"></i>{{ $eventStart->format('D, M j, Y') }} · {{ $eventStart->format('g:i A') }}</span><span><i class="fas fa-location-dot"></i>{{ $event->location ?: 'Location not set' }}</span></div>
                            <p class="event-card-description">{{ \Illuminate\Support\Str::limit(trim(strip_tags($event->description ?? '')), 120) }}</p>
                            <div class="event-card-footer"><span class="event-type-tag">{{ $event->category ?: 'Uncategorised' }} · {{ $event->select_type ?: 'Private' }}</span><a class="event-card-link" href="{{ route('admin.events.show', $event->id) }}">Manage event <i class="fas fa-arrow-right" style="margin-left:4px"></i></a></div>
                        </div>
                    </article>
                    @endforeach
                </div>
                @if($events->hasPages())<div style="padding-top:18px">{{ $events->links() }}</div>@endif
            @endif
        </div>

        <div id="events-calendar-panel" class="event-calendar-panel" style="display:none"><div id="event-calendar"></div><p id="event-calendar-unavailable" style="display:none;color:#8190a1;font-size:12px;padding:20px;text-align:center">The calendar view could not load. Use the event cards to manage your schedule.</p></div>
    </section>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const listButton = document.getElementById('event-list-view');
    const calendarButton = document.getElementById('event-calendar-view');
    const listPanel = document.getElementById('events-list-panel');
    const calendarPanel = document.getElementById('events-calendar-panel');
    if (!listButton || !calendarButton) return;
    let calendar = null;
    const activate = (showCalendar) => {
        listPanel.style.display = showCalendar ? 'none' : '';
        calendarPanel.style.display = showCalendar ? '' : 'none';
        listButton.classList.toggle('is-active', !showCalendar);
        calendarButton.classList.toggle('is-active', showCalendar);
        if (showCalendar && !calendar) {
            const fc = window.FullCalendarLib;
            const element = document.getElementById('event-calendar');
            if (!fc || !element) { document.getElementById('event-calendar-unavailable').style.display = ''; return; }
            calendar = new fc.Calendar(element, {
                plugins: [fc.dayGridPlugin, fc.timeGridPlugin, fc.interactionPlugin],
                headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
                initialView: 'dayGridMonth', height: 'auto', nowIndicator: true, dayMaxEvents: true,
                events: { url: '{{ url('/admin/events/show') }}', method: 'GET' },
                eventClick: (info) => { if (info.event.id) window.location.href = '{{ url('/admin/events/show/details') }}/' + encodeURIComponent(info.event.id); }
            });
            calendar.render();
        } else if (calendar) calendar.updateSize();
    };
    listButton.addEventListener('click', () => activate(false));
    calendarButton.addEventListener('click', () => activate(true));
})();
</script>
@endpush
