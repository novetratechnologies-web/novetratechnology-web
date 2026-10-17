<header id="igm-site-header" x-data="{ open: false, profileOpen: false, moreOpen: false }" @keydown.escape.window="open = false; profileOpen = false; moreOpen = false">
    <nav id="igm-site-nav" aria-label="Main navigation">
        <div class="igm-nav-inner">
            <div class="igm-brand">
                @include('theme::_logo')
            </div>

            <div class="igm-nav-links" id="igm-primary-navigation">
                <a href="{{ route('web.home') }}" @if(request()->routeIs('web.home')) aria-current="page" @endif>Home</a>
                <a href="{{ route('web.about') }}" @if(request()->routeIs('web.about')) aria-current="page" @endif>About us</a>
                <a href="{{ route('web.ministries') }}" @if(request()->routeIs('web.ministries')) aria-current="page" @endif>Ministries</a>
                <a href="{{ route('web.events') }}" @if(request()->routeIs('web.events', 'web.event')) aria-current="page" @endif>Events</a>
                <a href="{{ route('web.sermons') }}" @if(request()->routeIs('web.sermons', 'web.sermon')) aria-current="page" @endif>Sermons</a>
                <a href="{{ route('web.gallery') }}" @if(request()->routeIs('web.gallery', 'web.gallery.show')) aria-current="page" @endif>Gallery</a>
                <a href="{{ route('web.posts') }}" @if(request()->routeIs('web.posts', 'web.post')) aria-current="page" @endif>Blog</a>
                <a href="{{ route('web.contact') }}" @if(request()->routeIs('web.contact')) aria-current="page" @endif>Contact</a>
                <div class="igm-nav-more" @click.outside="moreOpen = false">
                    <button type="button" @click="moreOpen = !moreOpen" :aria-expanded="moreOpen" aria-haspopup="true" @if(request()->routeIs('web.live', 'web.prayer', 'web.bulletins', 'web.faq', 'web.help')) aria-current="page" @endif>
                        More <svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="m5 7.5 5 5 5-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <div class="igm-more-menu" x-show="moreOpen" x-transition x-cloak>
                        <a href="{{ route('web.live') }}">Watch live</a>
                        <a href="{{ route('web.prayer') }}">Prayer</a>
                        <a href="{{ route('web.bulletins') }}">Bulletins</a>
                        <a href="{{ route('web.faq') }}">Frequently asked questions</a>
                        <a href="{{ route('web.help') }}">Get help</a>
                    </div>
                </div>
            </div>

            <div class="igm-nav-actions">
                <a class="igm-give-link" href="{{ route('donate') }}">Give</a>

                @auth
                    @php
                        $navUser = auth()->user();
                        $isStaff = in_array((int) $navUser->usergroup_id, [1, 2, 3, 4], true);
                        $isGuest = optional($navUser->userprofile)->membership_type === 'guest';
                        $displayName = trim((optional($navUser->userprofile)->firstname ?? '') . ' ' . (optional($navUser->userprofile)->lastname ?? '')) ?: $navUser->name;
                    @endphp
                    <div class="igm-profile-wrap">
                        <button class="igm-profile-button" type="button" @click="profileOpen = !profileOpen" :aria-expanded="profileOpen" aria-haspopup="true">
                            @if(optional($navUser->userprofile)->avatar)
                                <img src="{{ $navUser->userprofile->AvatarPath }}" alt="" class="igm-avatar">
                            @else
                                <span class="igm-avatar igm-avatar-initial" aria-hidden="true">{{ strtoupper(substr($displayName, 0, 1)) }}</span>
                            @endif
                            <span class="igm-profile-name">{{ \Illuminate\Support\Str::limit($displayName, 18) }}</span>
                            <svg class="igm-chevron" :class="profileOpen ? 'is-open' : ''" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="m5 7.5 5 5 5-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>

                        <div class="igm-profile-menu" x-show="profileOpen" x-transition @click.outside="profileOpen = false" x-cloak>
                            <div class="igm-profile-summary"><strong>{{ $displayName }}</strong><span>{{ $navUser->email }}</span><small>{{ $isStaff ? 'Church administration' : ($isGuest ? 'Guest account' : 'Church member') }}</small></div>
                            @if($isStaff)
                                <a class="igm-account-link" href="{{ route('dashboard') }}"><span class="igm-account-link-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 13h7V4H4v9Zm9 7h7v-9h-7v9ZM4 20h7v-5H4v5Zm9-11h7V4h-7v5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg></span>Administration portal</a>
                                <a class="igm-account-link" href="{{ url('/admin/editprofile') }}"><span class="igm-account-link-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 21a8 8 0 0 0-16 0m8-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span>Admin profile</a>
                            @else
                                <span class="igm-profile-menu-label">YOUR CHURCH ACCOUNT</span>
                                <a class="igm-account-link" href="{{ route('member.home') }}"><span class="igm-account-link-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 21a8 8 0 0 0-16 0m8-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span>My profile</a>
                                <a class="igm-account-link" href="{{ route('member.home') }}#my-church"><span class="igm-account-link-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 21h18M5 21V10l7-6 7 6v11M10 21v-6h4v6m-2-17V1m-3 4h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></span>My church</a>
                                <a class="igm-account-link" href="{{ route('member.mygrouplist') }}"><span class="igm-account-link-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m7-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span>My communities</a>
                                <a class="igm-account-link" href="{{ url('/member/home#membership-card') }}"><span class="igm-account-link-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.7"/><circle cx="9" cy="11" r="2" stroke="currentColor" stroke-width="1.5"/><path d="M6 16a3 3 0 0 1 6 0m3-5h3m-3 3h3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></span>Membership card</a>
                                <span class="igm-profile-menu-label">SETTINGS &amp; TOOLS</span>
                                <a class="igm-account-link" href="{{ route('communication-preferences.edit') }}"><span class="igm-account-link-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><circle cx="9" cy="6" r="2" fill="white" stroke="currentColor" stroke-width="1.7"/><circle cx="15" cy="12" r="2" fill="white" stroke="currentColor" stroke-width="1.7"/><circle cx="8" cy="18" r="2" fill="white" stroke="currentColor" stroke-width="1.7"/></svg></span>Communication settings</a>
                                <a class="igm-account-link" href="{{ route('member.change-password') }}"><span class="igm-account-link-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2" stroke="currentColor" stroke-width="1.7"/><path d="M8 10V7a4 4 0 1 1 8 0v3m-4 4v3" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span>Change password</a>
                                <a class="igm-account-link" href="{{ route('member.donate') }}"><span class="igm-account-link-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg></span>Give to the church</a>
                            @endif
                            <form method="POST" action="{{ $isStaff ? route('admin.logout') : ($isGuest ? route('web.guest.logout') : route('logout')) }}">
                                @csrf
                                <button type="submit">Sign out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a class="igm-login-link" href="{{ route('web.guest.login') }}">Log in</a>
                    <a class="igm-register-link" href="{{ route('web.guest.register') }}">Join us</a>
                @endauth

                <button class="igm-menu-toggle" type="button" @click="open = !open" :aria-expanded="open" aria-controls="igm-mobile-navigation" :aria-label="open ? 'Close navigation menu' : 'Open navigation menu'">
                    <svg x-show="!open" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    <svg x-show="open" x-cloak viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </button>
            </div>
        </div>

        <div class="igm-mobile-menu" id="igm-mobile-navigation" x-show="open" x-collapse x-cloak>
            <div class="igm-mobile-links">
                <a href="{{ route('web.home') }}" @click="open = false" @if(request()->routeIs('web.home')) aria-current="page" @endif>Home</a>
                <a href="{{ route('web.about') }}" @click="open = false" @if(request()->routeIs('web.about')) aria-current="page" @endif>About us</a>
                <a href="{{ route('web.ministries') }}" @click="open = false" @if(request()->routeIs('web.ministries')) aria-current="page" @endif>Ministries</a>
                <a href="{{ route('web.events') }}" @click="open = false" @if(request()->routeIs('web.events', 'web.event')) aria-current="page" @endif>Events</a>
                <a href="{{ route('web.sermons') }}" @click="open = false" @if(request()->routeIs('web.sermons', 'web.sermon')) aria-current="page" @endif>Sermons</a>
                <a href="{{ route('web.live') }}" @click="open = false" @if(request()->routeIs('web.live')) aria-current="page" @endif>Watch live</a>
                <a href="{{ route('web.gallery') }}" @click="open = false" @if(request()->routeIs('web.gallery', 'web.gallery.show')) aria-current="page" @endif>Gallery</a>
                <a href="{{ route('web.posts') }}" @click="open = false" @if(request()->routeIs('web.posts', 'web.post')) aria-current="page" @endif>Blog</a>
                <a href="{{ route('web.contact') }}" @click="open = false" @if(request()->routeIs('web.contact')) aria-current="page" @endif>Contact</a>
                <a href="{{ route('web.prayer') }}" @click="open = false" @if(request()->routeIs('web.prayer')) aria-current="page" @endif>Prayer</a>
                <a href="{{ route('web.bulletins') }}" @click="open = false" @if(request()->routeIs('web.bulletins')) aria-current="page" @endif>Bulletins</a>
                <a href="{{ route('web.faq') }}" @click="open = false" @if(request()->routeIs('web.faq')) aria-current="page" @endif>FAQs</a>
                <a href="{{ route('web.help') }}" @click="open = false" @if(request()->routeIs('web.help')) aria-current="page" @endif>Get help</a>
            </div>
            <div class="igm-mobile-actions">
                <a class="igm-give-link" href="{{ route('donate') }}" @click="open = false">Give</a>
                @auth
                    @if($isStaff)
                        <a href="{{ route('dashboard') }}" @click="open = false">Administration portal</a>
                        <a href="{{ url('/admin/editprofile') }}" @click="open = false">Admin profile</a>
                    @else
                        <a href="{{ route('member.home') }}" @click="open = false">My profile</a>
                        <a href="{{ route('member.home') }}#my-church" @click="open = false">My church</a>
                        <a href="{{ route('member.mygrouplist') }}" @click="open = false">My communities</a>
                        <a href="{{ url('/member/home#membership-card') }}" @click="open = false">Membership card</a>
                        <a href="{{ route('communication-preferences.edit') }}" @click="open = false">Communication settings</a>
                        <a href="{{ route('member.change-password') }}" @click="open = false">Change password</a>
                        <a href="{{ route('member.donate') }}" @click="open = false">Give to the church</a>
                    @endif
                    <form method="POST" action="{{ $isStaff ? route('admin.logout') : ($isGuest ? route('web.guest.logout') : route('logout')) }}">
                        @csrf
                        <button type="submit">Sign out</button>
                    </form>
                @else
                    <a href="{{ route('web.guest.login') }}" @click="open = false">Log in</a>
                    <a href="{{ route('web.guest.register') }}" @click="open = false">Join us</a>
                @endauth
            </div>
        </div>
    </nav>
</header>
