<style>
    [x-cloak] { display:none !important; }
    #igm-site-header { position:relative; z-index:50; color:#fff; }
    #igm-site-nav { position:relative; background:#082846; border-bottom:1px solid rgba(255,255,255,.12); box-shadow:0 8px 26px rgba(4,25,44,.13); }
    .igm-nav-inner { width:min(1240px,100%); min-height:76px; margin:0 auto; padding:10px 28px; display:flex; align-items:center; justify-content:space-between; gap:22px; }
    .igm-brand { display:flex; align-items:center; flex:0 0 auto; color:#fff; text-decoration:none; }
    .igm-brand img { display:block; max-width:190px; max-height:48px; width:auto; object-fit:contain; }
    .igm-brand a { color:#fff!important; text-decoration:none; }
    .igm-nav-links { display:flex; align-items:center; justify-content:center; gap:clamp(12px,1.55vw,23px); margin-left:auto; }
    .igm-nav-links a { position:relative; padding:12px 0; color:#e7edf4; font-size:12px; line-height:1; font-weight:650; white-space:nowrap; text-decoration:none; transition:color .18s ease; }
    .igm-nav-links a:hover,.igm-nav-links a:focus-visible,.igm-nav-links a[aria-current="page"] { color:#f1cb77; }
    .igm-nav-links a[aria-current="page"]::after { content:""; position:absolute; right:0; bottom:3px; left:0; height:2px; border-radius:2px; background:#edc875; }
.igm-nav-more { position:relative; }
.igm-nav-more > button { display:flex; align-items:center; gap:4px; padding:12px 0; border:0; color:#e7edf4; background:transparent; font-size:12px; line-height:1; font-weight:650; cursor:pointer; }
.igm-nav-more > button:hover,.igm-nav-more > button[aria-expanded="true"],.igm-nav-more > button[aria-current="page"] { color:#f1cb77; }
.igm-nav-more > button svg { width:14px; height:14px; }
.igm-more-menu { position:absolute; top:calc(100% + 5px); right:-12px; z-index:60; width:225px; padding:7px; border:1px solid #e8ebef; border-radius:12px; background:#fff; box-shadow:0 16px 36px rgba(5,25,43,.2); }
.igm-more-menu a { display:block; padding:10px 11px; border-radius:7px; color:#344c63!important; font-size:12px; text-decoration:none; }
.igm-more-menu a:hover { color:#082846!important; background:#f5f6f7; }
    .igm-nav-actions { display:flex; align-items:center; justify-content:flex-end; gap:10px; flex:0 0 auto; }
    .igm-nav-actions a,.igm-mobile-actions a { text-decoration:none; }
    .igm-give-link { display:inline-flex; align-items:center; justify-content:center; min-height:40px; padding:0 18px; border:1px solid #edc875; border-radius:999px; color:#172d45!important; background:#edc875; font-size:12px; font-weight:800; white-space:nowrap; transition:background .18s ease,transform .18s ease; }
    .igm-give-link:hover { background:#f4d996; transform:translateY(-1px); }
    .igm-login-link { padding:10px 4px; color:#edf2f6!important; font-size:12px; font-weight:700; white-space:nowrap; }
    .igm-login-link:hover { color:#f1cb77!important; }
    .igm-register-link { display:inline-flex; align-items:center; justify-content:center; min-height:40px; padding:0 15px; border:1px solid rgba(255,255,255,.42); border-radius:999px; color:#fff!important; font-size:12px; font-weight:750; white-space:nowrap; }
    .igm-register-link:hover { color:#f1cb77!important; border-color:#edc875; }
    .igm-profile-wrap { position:relative; }
    .igm-profile-button { display:flex; align-items:center; gap:8px; padding:4px 7px; border:1px solid transparent; border-radius:999px; color:#fff; background:transparent; cursor:pointer; }
    .igm-profile-button:hover,.igm-profile-button[aria-expanded="true"] { border-color:rgba(255,255,255,.18); background:rgba(255,255,255,.07); }
    .igm-avatar { display:block; width:34px; height:34px; border-radius:50%; object-fit:cover; border:2px solid rgba(237,200,117,.85); }
    .igm-avatar-initial { display:grid; place-items:center; color:#112d48; background:#edc875; font-size:14px; font-weight:800; }
    .igm-profile-name { max-width:130px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-size:12px; font-weight:700; }
    .igm-chevron { width:15px; height:15px; color:#d6e0e9; transition:transform .18s ease; }
    .igm-chevron.is-open { transform:rotate(180deg); }
    .igm-profile-menu { position:absolute; top:calc(100% + 9px); right:0; z-index:60; width:292px; max-height:min(76vh,560px); overflow-y:auto; padding:7px; border:1px solid #e8ebef; border-radius:13px; color:#19344f; background:#fff; box-shadow:0 18px 40px rgba(5,25,43,.2); }
    .igm-profile-summary { display:flex; flex-direction:column; gap:3px; padding:11px 12px; border-bottom:1px solid #edf0f3; }
    .igm-profile-summary strong { color:#112d48; font-size:13px; }
    .igm-profile-summary span { overflow:hidden; color:#728095; font-size:11px; text-overflow:ellipsis; white-space:nowrap; }
    .igm-profile-summary small { color:#a87927; font-size:10px; font-weight:750; }
    .igm-profile-menu-label { display:block; padding:10px 11px 5px; color:#8290a0; font-size:9px; font-weight:800; letter-spacing:.11em; }
    .igm-profile-menu .igm-account-link { display:flex; align-items:center; gap:10px; width:100%; min-height:40px; padding:6px 10px; border:0; border-radius:8px; color:#344c63!important; background:transparent; text-align:left; font-size:12px; font-weight:650; text-decoration:none; cursor:pointer; }
    .igm-account-link-icon { display:grid; flex:0 0 28px; width:28px; height:28px; place-items:center; border-radius:8px; color:#9b712b; background:#f8f2e6; }
    .igm-account-link-icon svg { width:15px; height:15px; }
    .igm-profile-menu a:hover,.igm-profile-menu form button:hover { color:#082846!important; background:#f5f6f7; }
    .igm-profile-menu .igm-account-link:hover .igm-account-link-icon { color:#fff; background:#a77b32; }
    .igm-profile-menu form button { color:#ad4141!important; }
    .igm-profile-menu form { margin-top:5px; padding-top:5px; border-top:1px solid #edf0f3; }
    .igm-menu-toggle { display:none; width:42px; height:42px; align-items:center; justify-content:center; border:1px solid rgba(255,255,255,.25); border-radius:11px; color:#fff; background:rgba(255,255,255,.06); cursor:pointer; }
    .igm-menu-toggle:hover,.igm-menu-toggle:focus-visible { border-color:#edc875; color:#f1cb77; }
    .igm-menu-toggle svg { width:22px; height:22px; }
.igm-mobile-menu { position:absolute; top:100%; right:0; left:0; z-index:55; max-height:calc(100vh - 64px); overflow-y:auto; border-top:1px solid rgba(255,255,255,.11); background:#071f37; box-shadow:0 18px 28px rgba(4,20,36,.23); }
    .igm-mobile-links,.igm-mobile-actions { width:min(100%,720px); margin:0 auto; padding:14px 22px; }
    .igm-mobile-links { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:5px 9px; }
    .igm-mobile-links a,.igm-mobile-actions > a:not(.igm-give-link) { display:flex; align-items:center; min-height:44px; padding:10px 12px; border-radius:8px; color:#e7edf4; font-size:14px; font-weight:650; text-decoration:none; }
    .igm-mobile-links a:hover,.igm-mobile-links a[aria-current="page"],.igm-mobile-actions > a:not(.igm-give-link):hover { color:#f1cb77; background:rgba(255,255,255,.07); }
    .igm-mobile-actions { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); align-items:stretch; gap:6px; padding-top:12px; border-top:1px solid rgba(255,255,255,.1); }
    .igm-mobile-actions > a:not(.igm-give-link) { min-height:42px; padding:9px 11px; font-size:12px; }
    .igm-mobile-actions > .igm-give-link { grid-column:1 / -1; }
    .igm-mobile-actions form { grid-column:1 / -1; margin:3px 0 0; border-top:1px solid rgba(255,255,255,.1); }
    .igm-mobile-actions form button { min-height:40px; padding:0 12px; border:0; color:#f4b6b6; background:transparent; font-size:13px; font-weight:700; cursor:pointer; }
    .igm-topline { min-height:30px; display:flex; align-items:center; justify-content:center; padding:6px 18px; color:#e9edf2; background:#061d34; font-size:11px; letter-spacing:.025em; text-align:center; }
    .igm-topline strong { color:#f1cb77; font-weight:750; }
    @media (max-width:1150px) {
        .igm-nav-inner { min-height:70px; padding-right:22px; padding-left:22px; }
        .igm-nav-links { display:none; }
        .igm-menu-toggle { display:inline-flex; }
    }
    @media (max-width:640px) {
        .igm-nav-inner { min-height:64px; padding:8px 16px; gap:10px; }
        .igm-brand img { max-width:155px; max-height:42px; }
        .igm-profile-wrap,.igm-login-link,.igm-register-link { display:none; }
        .igm-nav-actions { gap:8px; }
        .igm-give-link { min-height:38px; padding:0 14px; font-size:11px; }
        .igm-menu-toggle { width:40px; height:40px; }
        .igm-mobile-links,.igm-mobile-actions { padding-right:16px; padding-left:16px; }
        .igm-mobile-links { grid-template-columns:1fr 1fr; }
        .igm-topline { min-height:28px; padding:5px 10px; font-size:9px; }
    }
</style>
