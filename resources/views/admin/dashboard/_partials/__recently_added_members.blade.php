<section class="cd-component">
    <header class="cd-component-heading"><div><span class="cd-component-icon cd-icon-teal"><i class="fas fa-user-plus"></i></span><div><h3>Recently added</h3><p>New people in your community</p></div></div><a href="{{ url('/admin/members') }}" aria-label="Open members"><i class="fas fa-arrow-right"></i></a></header>
    <div class="cd-member-list">
        @forelse($dashboard['recentMember'] as $recentMembers)
            @php $memberName = $recentMembers->FullName ?: ($recentMembers->name ?: 'Member'); @endphp
            <a class="cd-member-row" href="{{ url('/admin/member/show/' . $recentMembers->name) }}">
                <img src="{{ $recentMembers->userprofile?->AvatarPath ?: url('uploads/user/avatar/default-user.jpg') }}" alt="" onerror="this.onerror=null;this.src='{{ url('uploads/user/avatar/default-user.jpg') }}'">
                <span class="cd-member-name"><strong>{{ ucwords($memberName) }}</strong><small>{{ $recentMembers->userprofile?->city?->name ?: 'New member' }}</small></span>
                <time datetime="{{ $recentMembers->created_at?->toDateString() }}">{{ $recentMembers->created_at?->format('M j') }}</time>
            </a>
        @empty
            <div class="cd-component-empty"><span><i class="fas fa-user-group"></i></span><strong>No new members yet</strong><small>New registrations will appear here.</small></div>
        @endforelse
    </div>
</section>
