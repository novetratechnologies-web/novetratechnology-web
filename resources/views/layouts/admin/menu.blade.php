@php
    $navUser = auth()->user();
    $isChurchAdmin = (int) ($navUser?->usergroup_id ?? 0) === 3;
    $navSegment = Request::segment(2) ?? 'dashboard';
    $peopleSegments = ['members','member','guests','guest','subadmins','subadmin','groups','group','prayerboard','prayercategories','prayercategory','helps','messages','message'];
    $contentSegments = ['events','event','sermons','sermon','bulletins','bulletin','gallery','mediafiles','mediafile','quotes','quote'];
    $financeSegments = ['payaccounts','payaccount','funds','fund','donations','donation','paymentgateways','paymentgateway'];
    $communicationSegments = ['communications','campaigns','emails','email','campaign','subscribers','subscriber','mailinglists','email-templates','mailinglist','mailqueues','mailqueue','smtps','smtp','newsletter','rules','rule','mails-delivered','mail-delivered','webhooks','webhook','contacts','contact','feedbacks','feedback'];
    $websiteSegments = ['pages','page','page-categories','pageCategory','posts','post','post-categories','postCategory','faq','faq-categories','widgets','google-analytics','hero-banners'];
    $settingsSegments = ['settings','countries','country','states','state','cities','city'];
    $emailSegments = ['campaigns','emails','email','campaign','subscribers','subscriber','mailinglists','email-templates','mailinglist','mailqueues','mailqueue','smtps','smtp','newsletter','rules','rule','mails-delivered','mail-delivered','webhooks','webhook'];
@endphp

<aside class="admin-side-nav" aria-label="Admin navigation">
    <div class="admin-side-brand"><span class="admin-side-brand-icon"><i class="fas fa-cross"></i></span><div><strong>Church admin</strong><small>Workspace navigation</small></div></div>
    <nav class="admin-side-scroll">
        <a class="admin-side-link {{ $navSegment === 'dashboard' ? 'is-active' : '' }}" href="{{ url('/admin/dashboard') }}" data-mobile-sidebar-close><i class="fas fa-gauge-high"></i><span>Dashboard</span></a>

        <p class="admin-side-group-label">CHURCH LIFE</p>
        @if($isChurchAdmin)<a class="admin-side-link {{ in_array($navSegment,['branches','branch']) ? 'is-active' : '' }}" href="{{ url('/admin/branches') }}" data-mobile-sidebar-close><i class="fas fa-code-branch"></i><span>Branches</span></a>@endif
        @if($isChurchAdmin || $navUser->hasPermission('read-members'))
            <details class="admin-side-group" {{ in_array($navSegment, ['members','member','guests','guest','subadmins','subadmin']) ? 'open' : '' }}>
                <summary><i class="fas fa-users"></i><span>People</span><i class="fas fa-chevron-down admin-side-chevron"></i></summary>
                <div class="admin-side-subnav">
                    @if($isChurchAdmin || $navUser->hasPermission('read-members'))
                        <a class="{{ in_array($navSegment,['members','member']) ? 'is-active' : '' }}" href="{{ url('/admin/members') }}" data-mobile-sidebar-close>Members</a>
                        <a class="{{ in_array($navSegment,['guests','guest']) ? 'is-active' : '' }}" href="{{ url('/admin/guests') }}" data-mobile-sidebar-close>Guests</a>
                    @endif
                    @if($isChurchAdmin)<a class="{{ in_array($navSegment,['subadmins','subadmin']) ? 'is-active' : '' }}" href="{{ url('/admin/subadmins') }}" data-mobile-sidebar-close>Sub-admins</a>@endif
                </div>
            </details>
        @endif
        @if($isChurchAdmin || $navUser->hasPermission('read-groups'))<a class="admin-side-link {{ in_array($navSegment,['groups','group']) ? 'is-active' : '' }}" href="{{ url('/admin/groups') }}" data-mobile-sidebar-close><i class="fas fa-people-group"></i><span>Groups</span></a>@endif
        @if($isChurchAdmin || $navUser->hasPermission('read-events'))<a class="admin-side-link {{ in_array($navSegment,['events','event']) ? 'is-active' : '' }}" href="{{ url('/admin/events') }}" data-mobile-sidebar-close><i class="fas fa-calendar-days"></i><span>Events</span></a>@endif
        @if($isChurchAdmin || $navUser->hasPermission('read-prayers'))<a class="admin-side-link {{ in_array($navSegment,['prayerboard','prayercategories','prayercategory']) ? 'is-active' : '' }}" href="{{ url('/admin/prayerboard') }}" data-mobile-sidebar-close><i class="fas fa-hands-praying"></i><span>Prayer board</span></a>@endif
        @if($isChurchAdmin || $navUser->hasPermission('read-helps'))<a class="admin-side-link {{ $navSegment === 'helps' ? 'is-active' : '' }}" href="{{ url('/admin/helps') }}" data-mobile-sidebar-close><i class="fas fa-life-ring"></i><span>Help requests</span></a>@endif

        <p class="admin-side-group-label">CONTENT &amp; MEDIA</p>
        @if($isChurchAdmin || $navUser->hasPermission('read-sermons'))<a class="admin-side-link {{ in_array($navSegment,['sermons','sermon']) ? 'is-active' : '' }}" href="{{ url('/admin/sermons') }}" data-mobile-sidebar-close><i class="fas fa-microphone-lines"></i><span>Sermons</span></a>@endif
        @if($isChurchAdmin || $navUser->hasPermission('read-bulletins'))<a class="admin-side-link {{ in_array($navSegment,['bulletins','bulletin']) ? 'is-active' : '' }}" href="{{ url('/admin/bulletins') }}" data-mobile-sidebar-close><i class="fas fa-newspaper"></i><span>Bulletins</span></a>@endif
        @if($isChurchAdmin || $navUser->hasPermission('read-gallery'))<a class="admin-side-link {{ $navSegment === 'gallery' ? 'is-active' : '' }}" href="{{ url('/admin/gallery') }}" data-mobile-sidebar-close><i class="fas fa-images"></i><span>Gallery</span></a>@endif
        @if($isChurchAdmin || $navUser->hasPermission('read-files'))<a class="admin-side-link {{ in_array($navSegment,['mediafiles','mediafile']) ? 'is-active' : '' }}" href="{{ url('/admin/mediafiles') }}" data-mobile-sidebar-close><i class="fas fa-photo-film"></i><span>Media library</span></a>@endif
        @if($isChurchAdmin || $navUser->hasPermission('read-quotes'))<a class="admin-side-link {{ in_array($navSegment,['quotes','quote']) ? 'is-active' : '' }}" href="{{ url('/admin/quotes') }}" data-mobile-sidebar-close><i class="fas fa-quote-left"></i><span>Quotes &amp; verses</span></a>@endif

        @if($isChurchAdmin || $navUser->hasPermission('read-members'))<a class="admin-side-link {{ in_array($navSegment,['messages','message']) ? 'is-active' : '' }}" href="{{ url('/admin/messages') }}" data-mobile-sidebar-close><i class="fas fa-envelope"></i><span>Member messages</span></a>@endif

        @php($showEmailTools = $isChurchAdmin || $navUser->hasPermission('manage-email-blaster'))
        @if($showEmailTools || $isChurchAdmin)
            <p class="admin-side-group-label">COMMUNICATION</p>
        @endif
        @if($isChurchAdmin)
            <a class="admin-side-link {{ $navSegment === 'communications' ? 'is-active' : '' }}" href="{{ route('admin.communication.index') }}" data-mobile-sidebar-close><i class="fas fa-bullhorn"></i><span>Communications</span></a>
        @endif
        @if($showEmailTools)
            <details class="admin-side-group" {{ in_array($navSegment,$emailSegments) ? 'open' : '' }}>
                <summary><i class="fas fa-paper-plane"></i><span>Email tools</span><i class="fas fa-chevron-down admin-side-chevron"></i></summary>
                <div class="admin-side-subnav">
                    <a class="{{ in_array($navSegment,['campaigns','campaign']) ? 'is-active' : '' }}" href="{{ url('/admin/campaigns') }}" data-mobile-sidebar-close>Campaigns</a>
                    <a class="{{ in_array($navSegment,['emails','email']) ? 'is-active' : '' }}" href="{{ url('/admin/emails') }}" data-mobile-sidebar-close>Emails</a>
                    <a class="{{ in_array($navSegment,['subscribers','subscriber']) ? 'is-active' : '' }}" href="{{ url('/admin/subscribers') }}" data-mobile-sidebar-close>Subscribers</a>
                    <a class="{{ in_array($navSegment,['mailinglists','mailinglist']) ? 'is-active' : '' }}" href="{{ url('/admin/mailinglists') }}" data-mobile-sidebar-close>Mailing lists</a>
                    <a class="{{ $navSegment === 'newsletter' ? 'is-active' : '' }}" href="{{ url('/admin/newsletter/send') }}" data-mobile-sidebar-close>Send newsletter</a>
                    <span class="admin-side-subheading">DELIVERY &amp; SETTINGS</span>
                    <a class="{{ in_array($navSegment,['rules','rule']) ? 'is-active' : '' }}" href="{{ url('/admin/rules') }}" data-mobile-sidebar-close>Rules</a>
                    <a class="{{ in_array($navSegment,['mails-delivered','mail-delivered']) ? 'is-active' : '' }}" href="{{ url('/admin/mails-delivered') }}" data-mobile-sidebar-close>Delivered mail</a>
                    <a class="{{ in_array($navSegment,['mailqueues','mailqueue']) ? 'is-active' : '' }}" href="{{ url('/admin/mailqueues') }}" data-mobile-sidebar-close>Mail queues</a>
                    <a class="{{ in_array($navSegment,['smtps','smtp']) ? 'is-active' : '' }}" href="{{ url('/admin/smtps') }}" data-mobile-sidebar-close>SMTP</a>
                    <a class="{{ in_array($navSegment,['webhooks','webhook']) ? 'is-active' : '' }}" href="{{ url('/admin/webhooks') }}" data-mobile-sidebar-close>Webhooks</a>
                </div>
            </details>
        @endif
        @if($isChurchAdmin || $navUser->hasPermission('read-contacts'))<a class="admin-side-link {{ in_array($navSegment,['contacts','contact']) ? 'is-active' : '' }}" href="{{ url('/admin/contacts') }}" data-mobile-sidebar-close><i class="fas fa-inbox"></i><span>Contact requests</span></a>@endif
        @if($isChurchAdmin || $navUser->hasPermission('read-feedbacks'))<a class="admin-side-link {{ in_array($navSegment,['feedbacks','feedback']) ? 'is-active' : '' }}" href="{{ url('/admin/feedbacks') }}" data-mobile-sidebar-close><i class="fas fa-comment-dots"></i><span>Feedback</span></a>@endif

        @if($isChurchAdmin || $navUser->hasPermission('read-payments') || $navUser->hasPermission('read-funds'))
            <p class="admin-side-group-label">FINANCE</p>
            <details class="admin-side-group" {{ in_array($navSegment,$financeSegments) ? 'open' : '' }}>
                <summary><i class="fas fa-hand-holding-dollar"></i><span>Giving &amp; finance</span><i class="fas fa-chevron-down admin-side-chevron"></i></summary>
                <div class="admin-side-subnav">
                    @if($isChurchAdmin || $navUser->hasPermission('read-funds'))<a class="{{ in_array($navSegment,['donations','donation']) ? 'is-active' : '' }}" href="{{ url('/admin/donations') }}" data-mobile-sidebar-close>Donations</a>@endif
                    @if($isChurchAdmin || $navUser->hasPermission('read-payments'))<a class="{{ in_array($navSegment,['payaccounts','payaccount']) ? 'is-active' : '' }}" href="{{ url('/admin/payaccounts') }}" data-mobile-sidebar-close>Pay accounts</a><a class="{{ in_array($navSegment,['paymentgateways','paymentgateway']) ? 'is-active' : '' }}" href="{{ url('/admin/paymentgateways') }}" data-mobile-sidebar-close>Payment gateways</a>@endif
                    @if($isChurchAdmin || $navUser->hasPermission('read-funds'))<a class="{{ in_array($navSegment,['funds','fund']) ? 'is-active' : '' }}" href="{{ url('/admin/funds') }}" data-mobile-sidebar-close>Funds</a>@endif
                </div>
            </details>
        @endif

        @if($isChurchAdmin || $navUser->hasPermission('read-reports') || $navUser->hasPermission('manage-cms') || $isChurchAdmin)
            <p class="admin-side-group-label">MANAGEMENT</p>
        @endif
        @if($isChurchAdmin || $navUser->hasPermission('read-reports'))<a class="admin-side-link {{ in_array($navSegment,['reports','report']) ? 'is-active' : '' }}" href="{{ url('/admin/reports') }}" data-mobile-sidebar-close><i class="fas fa-chart-pie"></i><span>Reports</span></a>@endif
        <a class="admin-side-link {{ $navSegment === 'activity' ? 'is-active' : '' }}" href="{{ url('/admin/activity') }}" data-mobile-sidebar-close><i class="fas fa-clock-rotate-left"></i><span>Activity log</span></a>

        @if($isChurchAdmin || $navUser->hasPermission('manage-cms'))
            <details class="admin-side-group" {{ in_array($navSegment,$websiteSegments) ? 'open' : '' }}>
                <summary><i class="fas fa-globe"></i><span>Website content</span><i class="fas fa-chevron-down admin-side-chevron"></i></summary>
                <div class="admin-side-subnav">
                    <a class="{{ in_array($navSegment,['hero-banners']) ? 'is-active' : '' }}" href="{{ route('admin.hero-banners.index') }}" data-mobile-sidebar-close>Home banners &amp; live</a>
                    <a class="{{ in_array($navSegment,['pages','page']) ? 'is-active' : '' }}" href="{{ url('/admin/pages') }}" data-mobile-sidebar-close>Pages</a>
                    <a class="{{ in_array($navSegment,['posts','post']) ? 'is-active' : '' }}" href="{{ url('/admin/posts') }}" data-mobile-sidebar-close>Posts</a>
                    <a class="{{ $navSegment === 'faq' ? 'is-active' : '' }}" href="{{ url('/admin/faq') }}" data-mobile-sidebar-close>FAQs</a>
                    <a class="{{ $navSegment === 'widgets' ? 'is-active' : '' }}" href="{{ url('/admin/widgets') }}" data-mobile-sidebar-close>Code snippets</a>
                    <span class="admin-side-subheading">CATEGORIES</span>
                    <a class="{{ $navSegment === 'page-categories' ? 'is-active' : '' }}" href="{{ url('/admin/page-categories') }}" data-mobile-sidebar-close>Page categories</a>
                    <a class="{{ $navSegment === 'post-categories' ? 'is-active' : '' }}" href="{{ url('/admin/post-categories') }}" data-mobile-sidebar-close>Post categories</a>
                    <a class="{{ $navSegment === 'faq-categories' ? 'is-active' : '' }}" href="{{ url('/admin/faq-categories') }}" data-mobile-sidebar-close>FAQ categories</a>
                </div>
            </details>
        @endif
        @if($isChurchAdmin)
            <a class="admin-side-link {{ in_array($navSegment,['settings','countries','country','states','state','cities','city']) ? 'is-active' : '' }}" href="{{ url('/admin/settings/generalsettings') }}" data-mobile-sidebar-close><i class="fas fa-gear"></i><span>Settings</span></a>
            <details class="admin-side-group" {{ in_array($navSegment,['countries','country','states','state','cities','city']) ? 'open' : '' }}>
                <summary><i class="fas fa-database"></i><span>Location data</span><i class="fas fa-chevron-down admin-side-chevron"></i></summary>
                <div class="admin-side-subnav"><a class="{{ in_array($navSegment,['countries','country']) ? 'is-active' : '' }}" href="{{ url('/admin/countries') }}" data-mobile-sidebar-close>Countries</a><a class="{{ in_array($navSegment,['states','state']) ? 'is-active' : '' }}" href="{{ url('/admin/states') }}" data-mobile-sidebar-close>States</a><a class="{{ in_array($navSegment,['cities','city']) ? 'is-active' : '' }}" href="{{ url('/admin/cities') }}" data-mobile-sidebar-close>Cities</a></div>
            </details>
        @endif
    </nav>
    <div class="admin-side-footer"><span class="admin-side-footer-mark">IGM</span><span><b>IGM Church</b><small>Church management</small></span></div>
</aside>

@if(!($mobileNavigation ?? false))

@endif
