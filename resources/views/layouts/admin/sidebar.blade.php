<div class="admin-sidebar hidden shrink-0 md:block">
    @include('layouts.admin.menu')
</div>

<div id="res_sidebar" class="res_sidebar hidden" role="dialog" aria-modal="true" aria-label="Mobile admin navigation" onclick="if(event.target===this)toggleAdminMobileNav(true)">
    <div class="mobile-admin-drawer">
        <div class="mobile-admin-drawer-head"><span>Navigation</span><button type="button" onclick="toggleAdminMobileNav(true)" aria-label="Close navigation"><i class="fas fa-xmark"></i></button></div>
        @include('layouts.admin.menu', ['mobileNavigation' => true])
    </div>
</div>
