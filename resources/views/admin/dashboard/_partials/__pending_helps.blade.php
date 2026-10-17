<section class="cd-component">
    <header class="cd-component-heading"><div><span class="cd-component-icon cd-icon-rose"><i class="fas fa-hand-holding-heart"></i></span><div><h3>Care requests</h3><p>People who may need support</p></div></div><a href="{{ url('/admin/helps') }}" class="cd-heading-link">{{ $dashboard['pendingHelpCount'] }} open <i class="fas fa-arrow-right"></i></a></header>
    <div class="cd-request-list">
        @forelse($dashboard['pendingHelps'] as $help)
            @php $helpName = $help->user?->FullName ?: ($help->user?->name ?: 'Guest'); @endphp
            <article class="cd-request-row cd-request-help">
                <span class="cd-request-avatar">{{ strtoupper(substr($helpName, 0, 1)) }}</span>
                <div class="cd-request-body"><div class="cd-request-meta"><strong>{{ $helpName }}</strong><time>{{ $help->created_at?->diffForHumans() }}</time></div>
                    <h4>{{ $help->title ?: 'Support request' }}</h4><p>{{ \Str::limit($help->description, 92) }}</p><a href="{{ url('/admin/help/edit/' . $help->id) }}">Review request <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>
        @empty
            <div class="cd-component-empty"><span><i class="fas fa-check"></i></span><strong>All caught up</strong><small>There are no pending care requests.</small></div>
        @endforelse
    </div>
    @if($dashboard['pendingHelpCount'] > 5)<a class="cd-more-link" href="{{ url('/admin/helps') }}">View {{ $dashboard['pendingHelpCount'] - 5 }} more requests</a>@endif
</section>
