<section class="cd-component">
    <header class="cd-component-heading"><div><span class="cd-component-icon cd-icon-gold"><i class="fas fa-hands-praying"></i></span><div><h3>Prayer requests</h3><p>Pastoral care follow-up</p></div></div><a href="{{ url('/admin/prayerboard') }}" class="cd-heading-link">{{ $dashboard['pendingPrayerCount'] }} open <i class="fas fa-arrow-right"></i></a></header>
    <div class="cd-request-list">
        @forelse($dashboard['pendingPrayers'] as $prayer)
            <article class="cd-request-row cd-request-prayer">
                <span class="cd-request-avatar">{{ strtoupper(substr($prayer->submitter_name ?: 'G', 0, 1)) }}</span>
                <div class="cd-request-body"><div class="cd-request-meta"><strong>{{ $prayer->submitter_name ?: 'Guest' }}</strong><time>{{ $prayer->created_at?->diffForHumans() }}</time></div>
                    @if($prayer->category)<span class="cd-request-category">{{ $prayer->category->emoji }} {{ $prayer->category->name }}</span>@endif
                    <p>{{ \Str::limit($prayer->text, 92) }}</p><a href="{{ url('/admin/prayerboard/' . $prayer->id) }}">Review request <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>
        @empty
            <div class="cd-component-empty"><span><i class="fas fa-check"></i></span><strong>All caught up</strong><small>There are no pending prayer requests.</small></div>
        @endforelse
    </div>
    @if($dashboard['pendingPrayerCount'] > 5)<a class="cd-more-link" href="{{ url('/admin/prayerboard') }}">View {{ $dashboard['pendingPrayerCount'] - 5 }} more requests</a>@endif
</section>
