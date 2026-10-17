<section class="cd-component cd-offerings">
    <header class="cd-component-heading"><div><span class="cd-component-icon cd-icon-teal"><i class="fas fa-hand-holding-heart"></i></span><div><h3>Offering collection</h3><p>Recent recorded giving</p></div></div><a href="{{ url('/admin/funds') }}" class="cd-heading-link">View giving <i class="fas fa-arrow-right"></i></a></header>
    <div class="cd-giving-total"><span>Total recorded</span><strong>{{ number_format($dashboard['total_fund'], 0) }}</strong></div>
    <div class="cd-giving-list">
        @forelse($dashboard['fundlist'] as $fund)
            @php
                $fundMember = $fund->membership === 'member' ? $fund->user : null;
                $giverName = $fundMember?->FullName ?: ($fundMember?->name ?: data_get($fund->data, 'first_name', 'Guest giver'));
            @endphp
            <div class="cd-giving-row"><span class="cd-giving-avatar"><i class="fas fa-heart"></i></span><span class="cd-giving-person"><strong>{{ ucwords($giverName) }}</strong><small>{{ ucwords($fund->method ?: 'Offering') }}</small></span><strong class="cd-giving-amount">{{ number_format((float) $fund->amount, 0) }}</strong></div>
        @empty
            <div class="cd-component-empty"><span><i class="fas fa-hand-holding-heart"></i></span><strong>No offerings recorded</strong><small>New giving records will appear here.</small></div>
        @endforelse
    </div>
</section>
