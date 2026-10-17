@php
    $memberProfile = $user->userprofile;
    $memberDisplayName = trim(($memberProfile?->firstname ?? '').' '.($memberProfile?->lastname ?? '')) ?: ($user->name ?: 'Church member');
    $memberFirstName = $memberProfile?->firstname ?: explode(' ', $memberDisplayName)[0];
    $memberInitials = collect(preg_split('/\s+/', $memberDisplayName))->filter()->take(2)->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');
    $memberKind = $memberProfile?->membership_type === 'guest' ? 'Guest account' : 'Member account';
@endphp

<section class="member-account-hero">
    <div class="member-account-hero-copy">
        <span class="member-account-eyebrow">INTERNATIONAL GOSPEL MINISTRIES <span>·</span> {{ strtoupper($memberKind) }}</span>
        <h1>Welcome back, {{ $memberFirstName }}</h1>
        <p>Your church account keeps your profile, community and IGM updates together.</p>
        <div class="member-account-hero-actions">
            <a href="#member-profile"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 21a8 8 0 0 0-16 0m8-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>My profile</a>
            <a href="#my-church"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 21h18M5 21V10l7-6 7 6v11m-7-17V1m-3 4h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>My church</a>
            <a href="{{ route('communication-preferences.edit') }}"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><circle cx="9" cy="6" r="2" fill="white" stroke="currentColor" stroke-width="1.7"/><circle cx="15" cy="12" r="2" fill="white" stroke="currentColor" stroke-width="1.7"/></svg>Settings</a>
        </div>
    </div>
    <div class="member-account-identity">
        @if($memberProfile?->AvatarPath)
            <img src="{{ $memberProfile->AvatarPath }}" alt="{{ $memberDisplayName }}" class="member-account-avatar" onerror="this.onerror=null;this.src='{{ asset('uploads/user/avatar/default-user.jpg') }}'">
        @else
            <span class="member-account-avatar member-account-avatar-initials">{{ $memberInitials ?: 'IG' }}</span>
        @endif
        <div><strong>{{ $memberDisplayName }}</strong><span>{{ $user->email }}</span><small>{{ $memberKind }}</small></div>
    </div>
</section>
