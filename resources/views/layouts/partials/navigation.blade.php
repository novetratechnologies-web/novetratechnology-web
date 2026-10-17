@php
    $navigationUser = Auth::user();
    $navigationProfile = $navigationUser?->userprofile;
    $navigationName = $navigationUser?->FullName ?: $navigationUser?->name ?: 'Administrator';
    $navigationPage = ucwords(str_replace(['-', '_'], ' ', Request::segment(2) ?? 'dashboard'));
@endphp

<nav class="admin-topbar" aria-label="Admin header">
    <div class="admin-topbar-branding">
        <button type="button" class="admin-mobile-nav-toggle" onclick="toggleAdminMobileNav()" aria-label="Open navigation" aria-controls="res_sidebar" aria-expanded="false"><i class="fas fa-bars"></i></button>
        <a class="admin-topbar-logo" href="{{ url('/admin/dashboard') }}" aria-label="IGM Church admin dashboard"><img src="{{ asset('uploads/brand/igm-church-logo.png') }}" alt="IGM Church"></a>
        <span class="admin-topbar-divider"></span>
        <div class="admin-topbar-context"><span>CHURCH ADMINISTRATION</span><strong>{{ $navigationPage }}</strong></div>
    </div>

    <div class="admin-topbar-actions">
        <a class="admin-site-link" href="{{ route('web.home') }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-arrow-up-right-from-square"></i><span>View website</span></a>
        <div class="admin-notification-control"><notification url="{{ url('/') }}" mode="admin"></notification></div>
        @if($navigationUser)
            <div class="admin-account-menu" data-account-menu>
                <button type="button" class="admin-account-trigger" data-account-trigger aria-expanded="false" aria-haspopup="true">
                    @if($navigationProfile?->avatar)
                        <img src="{{ $navigationProfile->AvatarPath }}" alt="" class="admin-account-avatar">
                    @else
                        <span class="admin-account-avatar admin-account-initial">{{ strtoupper(substr($navigationName, 0, 1)) }}</span>
                    @endif
                    <span class="admin-account-identity"><strong>{{ $navigationName }}</strong><small>Administrator</small></span><i class="fas fa-chevron-down"></i>
                </button>
                <div class="admin-account-dropdown" data-account-dropdown hidden>
                    <div class="admin-account-dropdown-heading"><strong>{{ $navigationName }}</strong><span>{{ $navigationUser->email }}</span></div>
                    <a href="{{ url('/admin/editprofile') }}"><i class="fas fa-user-pen"></i> Edit profile</a>
                    <a href="{{ url('/admin/changepassword') }}"><i class="fas fa-lock"></i> Change password</a>
                    <a href="{{ url('/admin/changeavatar') }}"><i class="fas fa-image"></i> Change photo</a>
                    <form action="{{ route('admin.logout') }}" method="POST">@csrf<button type="submit"><i class="fas fa-arrow-right-from-bracket"></i> Sign out</button></form>
                </div>
            </div>
        @endif
    </div>
</nav>



@push('scripts')
<script>
function toggleAdminMobileNav(forceClose){const drawer=document.getElementById('res_sidebar');const trigger=document.querySelector('.admin-mobile-nav-toggle');if(!drawer)return;const open=drawer.classList.contains('hidden');if(forceClose===true||!open){drawer.classList.add('hidden');drawer.classList.remove('block');document.body.classList.remove('admin-nav-open');trigger?.setAttribute('aria-expanded','false');trigger?.setAttribute('aria-label','Open navigation');}else{drawer.classList.remove('hidden');drawer.classList.add('block');document.body.classList.add('admin-nav-open');trigger?.setAttribute('aria-expanded','true');trigger?.setAttribute('aria-label','Close navigation');}}
document.addEventListener('click',function(event){const account=event.target.closest('[data-account-menu]');document.querySelectorAll('[data-account-menu]').forEach(function(menu){const dropdown=menu.querySelector('[data-account-dropdown]');const trigger=menu.querySelector('[data-account-trigger]');if(!dropdown||!trigger)return;if(account===menu&&event.target.closest('[data-account-trigger]')){const shouldOpen=dropdown.hidden;dropdown.hidden=!shouldOpen;trigger.setAttribute('aria-expanded',String(shouldOpen));}else if(account!==menu){dropdown.hidden=true;trigger.setAttribute('aria-expanded','false');}});if(event.target.closest('[data-mobile-sidebar-close]'))toggleAdminMobileNav(true);if(event.target.id==='res_sidebar')toggleAdminMobileNav(true);});
document.addEventListener('keydown',function(event){if(event.key==='Escape'){toggleAdminMobileNav(true);document.querySelectorAll('[data-account-dropdown]').forEach(function(dropdown){dropdown.hidden=true;dropdown.parentElement.querySelector('[data-account-trigger]')?.setAttribute('aria-expanded','false');});}});
</script>
@endpush
