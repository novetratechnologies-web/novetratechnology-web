@php
    $profileUser = auth()->user();
    $profileDetails = $profileUser?->userprofile;
    $profileName = trim(($profileDetails?->firstname ?? '').' '.($profileDetails?->lastname ?? '')) ?: ($profileUser?->name ?? 'Administrator');
    $profileAvatar = $profileDetails?->AvatarPath;
    $profileInitials = collect(preg_split('/\s+/', $profileName))->filter()->take(2)->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');
@endphp

<div class="admin-profile-page">
    <header class="admin-profile-hero">
        <div class="admin-profile-hero-copy">
            <span class="admin-profile-eyebrow">ACCOUNT SETTINGS</span>
            <h1>Your profile</h1>
            <p>Manage your personal details and keep your IGM Church admin account secure.</p>
        </div>
        <div class="admin-profile-identity">
            @if($profileAvatar)
                <img src="{{ $profileAvatar }}" alt="{{ $profileName }}" class="admin-profile-avatar" onerror="this.onerror=null;this.src='{{ asset('uploads/user/avatar/default-user.jpg') }}'">
            @else
                <span class="admin-profile-avatar admin-profile-avatar-initials">{{ $profileInitials ?: 'IG' }}</span>
            @endif
            <div><strong>{{ $profileName }}</strong><span>{{ $profileUser?->email }}</span></div>
        </div>
    </header>

    <nav class="admin-profile-nav" aria-label="Profile settings">
        <a class="{{ $activeProfileTab === 'details' ? 'is-active' : '' }}" href="{{ url('/admin/editprofile') }}"><i class="fa-regular fa-user"></i><span>Personal details</span></a>
        <a class="{{ $activeProfileTab === 'photo' ? 'is-active' : '' }}" href="{{ url('/admin/changeavatar') }}"><i class="fa-regular fa-image"></i><span>Profile photo</span></a>
        <a class="{{ $activeProfileTab === 'security' ? 'is-active' : '' }}" href="{{ url('/admin/changepassword') }}"><i class="fa-solid fa-shield-halved"></i><span>Password &amp; security</span></a>
    </nav>
</div>

@push('scripts')
<script>
window.addEventListener('admin-profile-photo-updated', function (event) {
    const avatarUrl = event.detail && event.detail.avatar;
    if (!avatarUrl) return;
    document.querySelectorAll('.admin-profile-identity .admin-profile-avatar, .admin-account-avatar').forEach(function (current) {
        if (current.tagName === 'IMG') {
            current.src = avatarUrl;
            return;
        }
        const image = document.createElement('img');
        image.src = avatarUrl;
        image.alt = '';
        image.className = current.className.replace('admin-profile-avatar-initials', '').replace('admin-account-initial', '').trim();
        current.replaceWith(image);
    });
});
</script>
@endpush
