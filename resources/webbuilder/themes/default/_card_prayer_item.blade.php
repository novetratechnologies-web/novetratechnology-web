@php
    $alreadyLifted = in_array((int) $item->id, $liftedPrayerIds ?? [], true);
@endphp

<article class="prayer-card" aria-label="Prayer request shared {{ $item->created_at->diffForHumans() }}">
    <div class="prayer-card-top">
        <div class="prayer-card-meta">
            @if($item->category)
                <span class="prayer-category"><span aria-hidden="true">{{ $item->category->emoji }}</span>{{ $item->category->name }}</span>
            @else
                <span class="prayer-category">Prayer request</span>
            @endif
            <time datetime="{{ $item->created_at->toIso8601String() }}">{{ $item->created_at->format('M j, Y') }}</time>
        </div>
        @if($item->pinned_at)<span class="prayer-pin"><span aria-hidden="true">✦</span> Featured</span>@endif
    </div>
    <div class="prayer-card-body">
        <blockquote>{{ $item->text }}</blockquote>
        <div class="prayer-card-bottom">
            <div class="prayer-together"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg><span><strong data-prayer-total="{{ $item->id }}">{{ number_format($item->total_participant_count) }}</strong> {{ Illuminate\Support\Str::plural('person', $item->total_participant_count) }} praying</span></div>
            @if($alreadyLifted)
                <button class="lift-prayer-btn" type="button" disabled aria-disabled="true" style="background:#eef4ef;color:#2f7049;cursor:default">✓ You’re praying</button>
            @else
                @include('theme::_lift_prayer_btn', ['item' => $item])
            @endif
        </div>
    </div>
</article>
