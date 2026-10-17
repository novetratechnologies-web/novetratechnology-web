@extends('theme::layout')

@section('title', $_church->name ?? config('app.name'))

@push('styles')
<style>
    :root { --igm-navy:#082846; --igm-navy-deep:#061d34; --igm-gold:#d8ad5d; --igm-gold-dark:#a97e32; --igm-ivory:#f8f6f0; --igm-ink:#102d4d; --igm-muted:#66758a; }
    body { background:#fff; color:var(--igm-ink); font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif; }
    .igm-home { overflow:hidden; }
    .igm-wrap { width:min(1120px, calc(100% - 40px)); margin:0 auto; }
    .igm-kicker { color:var(--igm-gold-dark); text-transform:uppercase; letter-spacing:.15em; font-size:10px; line-height:1.4; font-weight:800; }
    .igm-section-title { color:var(--igm-navy); font-size:clamp(25px,3vw,37px); font-weight:800; letter-spacing:-.035em; line-height:1.12; margin:8px 0 0; }
    .igm-section-copy { margin:9px 0 0; color:var(--igm-muted); font-size:14px; line-height:1.75; }
    .igm-link { display:inline-flex; align-items:center; gap:8px; color:var(--igm-gold-dark); font-weight:750; font-size:12px; text-decoration:none; transition:gap .2s ease,color .2s ease; }
    .igm-link:hover { color:var(--igm-navy); gap:12px; }
    .igm-button { display:inline-flex; align-items:center; justify-content:center; gap:9px; min-height:44px; padding:0 21px; border:1px solid transparent; border-radius:999px; font-size:12px; font-weight:800; text-decoration:none; transition:transform .2s ease,background .2s ease,border-color .2s ease; }
    .igm-button:hover { transform:translateY(-2px); text-decoration:none; }
    .igm-button-gold { color:#102944; background:#edc875; box-shadow:0 8px 22px rgba(216,173,93,.25); }
    .igm-button-outline { color:#fff; border-color:rgba(255,255,255,.62); background:rgba(7,31,54,.18); }
    .igm-button-outline:hover { color:#102944; background:#fff; }
    .igm-hero { position:relative; isolation:isolate; min-height:445px; display:flex; align-items:center; background:#17324b; color:#fff; }
    .igm-hero::before { content:""; position:absolute; inset:0; z-index:-2; background-image:var(--hero-image); background-size:cover; background-position:center 42%; transform:scale(1.01); }
    .igm-hero-slide[data-hero-focus="right"]::before { background-position:right center; }
    .igm-hero::after { content:""; position:absolute; inset:0; z-index:-1; background:linear-gradient(90deg,rgba(4,23,42,.90) 0%,rgba(6,30,53,.74) 42%,rgba(8,35,60,.25) 100%),linear-gradient(0deg,rgba(4,24,43,.28),transparent 46%); }
    .igm-hero-content { max-width:1120px; width:min(1120px,calc(100% - 40px)); margin:0 auto; padding:67px 0 73px; }
    .igm-hero-copy { max-width:590px; }
    .igm-hero-eyebrow { color:#f1cb77; text-transform:uppercase; font-size:11px; font-weight:800; letter-spacing:.17em; }
    .igm-hero h1 { margin:16px 0 0; max-width:680px; color:#fff; font-size:clamp(38px,6.2vw,70px); line-height:.99; letter-spacing:-.055em; font-weight:850; }
    .igm-hero h1 span { display:block; color:#edc875; margin-top:7px; }
    .igm-hero p { max-width:470px; margin:18px 0 0; color:#e7edf4; font-size:15px; line-height:1.7; }
    .igm-hero-actions { display:flex; flex-wrap:wrap; gap:11px; margin-top:25px; }
    .igm-hero-mark { position:absolute; right:max(5vw,calc((100vw - 1120px)/2)); bottom:31px; width:115px; height:2px; background:linear-gradient(90deg,#edc875 0 26%,rgba(255,255,255,.36) 26%); }
    .igm-hero-carousel { position:relative; width:100%; min-height:445px; overflow:hidden; }
    .igm-hero-slide { position:absolute; inset:0; display:flex; align-items:center; opacity:0; visibility:hidden; pointer-events:none; transition:opacity .75s ease,transform .75s ease; transform:translateX(2%); }
    .igm-hero-slide.is-active { z-index:1; opacity:1; visibility:visible; pointer-events:auto; transform:translateX(0); }
    .igm-hero-slide[data-transition="fade"],.igm-hero-slide[data-transition="fade"].is-active { transform:none; }
    .igm-hero-controls { position:absolute; z-index:3; right:max(20px,calc((100vw - 1120px)/2)); bottom:22px; display:flex; align-items:center; gap:8px; }
    .igm-hero-control,.igm-hero-dot { display:grid; place-items:center; width:34px; height:34px; padding:0; color:#fff; background:rgba(5,25,44,.58); border:1px solid rgba(255,255,255,.45); border-radius:50%; cursor:pointer; }
    .igm-hero-dots { display:flex; align-items:center; gap:7px; margin:0 4px; }
    .igm-hero-dot { width:9px; height:9px; border:0; background:rgba(255,255,255,.58); transition:width .2s ease,background .2s ease; }
    .igm-hero-dot[aria-current="true"] { width:24px; border-radius:999px; background:#edc875; }
    .igm-hero-live { display:grid; grid-template-columns:1fr minmax(320px,.92fr); align-items:center; gap:40px; width:100%; min-height:445px; padding:45px max(20px,calc((100vw - 1120px)/2)); color:#fff; background:radial-gradient(ellipse at 85% 20%,rgba(39,81,113,.5),transparent 45%),linear-gradient(135deg,#082846,#061d34); }
    .igm-live-label { display:inline-flex; align-items:center; gap:9px; color:#ffb4ad; font-size:11px; font-weight:850; letter-spacing:.15em; text-transform:uppercase; }
    .igm-live-label::before { content:""; width:9px; height:9px; border-radius:50%; background:#f04f46; box-shadow:0 0 0 5px rgba(240,79,70,.15); animation:igm-live-pulse 1.5s infinite; }
    @keyframes igm-live-pulse { 50% { box-shadow:0 0 0 9px rgba(240,79,70,0); } }
    .igm-hero-live h1 { margin:18px 0 0; color:#fff; font-size:clamp(34px,5vw,58px); line-height:1.03; letter-spacing:-.05em; font-weight:850; }
    .igm-hero-live p { max-width:500px; color:#ced9e5; font-size:15px; line-height:1.7; }
    .igm-live-player { aspect-ratio:16/9; width:100%; overflow:hidden; border:1px solid rgba(255,255,255,.24); border-radius:14px; background:#020a12; box-shadow:0 20px 50px rgba(0,0,0,.28); }
    .igm-live-player iframe, .igm-live-player video { width:100%; height:100%; border:0; display:block; object-fit:contain; }
    @media (max-width:720px) { .igm-hero-carousel { min-height:440px; } .igm-hero-slide { min-height:440px; } .igm-hero-controls { right:16px; bottom:14px; } .igm-hero-live { grid-template-columns:1fr; gap:20px; padding:42px 20px 26px; } }
    .igm-quicklinks { background:#fbfaf7; border-top:1px solid #eee8da; border-bottom:1px solid #eee8da; }
    .igm-quickgrid { display:grid; grid-template-columns:repeat(3,1fr); padding:22px 0; }
    .igm-quick { display:flex; align-items:center; gap:15px; padding:8px 30px; border-right:1px solid #ddd8ce; text-decoration:none; }
    .igm-quick:first-child { padding-left:0; }
    .igm-quick:last-child { border:0; padding-right:0; }
    .igm-round-icon { flex:0 0 43px; width:43px; height:43px; display:grid; place-items:center; border-radius:50%; color:#fff; background:linear-gradient(145deg,#c9a24e,#9c742b); box-shadow:0 7px 18px rgba(169,126,50,.2); }
    .igm-round-icon svg { width:19px; height:19px; }
    .igm-quick strong { display:block; color:var(--igm-navy); font-size:13px; }
    .igm-quick small { display:block; max-width:220px; margin-top:4px; color:#718095; font-size:11px; line-height:1.5; }
    .igm-mission { display:grid; grid-template-columns:1.1fr .9fr; align-items:center; gap:48px; padding:49px 0; }
    .igm-mission-photo { min-height:265px; border-radius:8px; overflow:hidden; background:#d8d4c9; box-shadow:0 14px 34px rgba(15,38,62,.12); }
    .igm-mission-photo img { width:100%; height:100%; min-height:265px; object-fit:cover; display:block; }
    .igm-mission-copy { padding:8px 10px; }
    .igm-mission-copy .igm-section-title { max-width:440px; }
    .igm-message { padding:25px 0 40px; background:var(--igm-ivory); }
    .igm-message-card { display:grid; grid-template-columns:1fr 1fr; align-items:center; gap:35px; padding:10px; background:#fff; border:1px solid #eeeae2; border-radius:9px; box-shadow:0 14px 35px rgba(17,42,66,.07); }
    .igm-message-image { position:relative; min-height:225px; overflow:hidden; border-radius:6px; background:#344958; }
    .igm-message-image img { width:100%; height:100%; min-height:225px; object-fit:cover; display:block; }
    .igm-play { position:absolute; inset:0; margin:auto; width:56px; height:56px; display:grid; place-items:center; color:var(--igm-navy); background:#edc875; border:4px solid rgba(255,255,255,.8); border-radius:50%; box-shadow:0 8px 25px rgba(0,0,0,.2); text-decoration:none; }
    .igm-play svg { width:17px; height:17px; margin-left:3px; }
    .igm-message-copy { padding:16px 25px 16px 0; }
    .igm-pill { display:inline-flex; padding:5px 10px; color:#fff; background:#b38b43; border-radius:999px; font-size:9px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; }
    .igm-message-copy h3 { margin:13px 0 0; color:var(--igm-navy); font-size:24px; line-height:1.2; font-weight:800; letter-spacing:-.03em; }
    .igm-message-copy p { color:var(--igm-muted); font-size:13px; line-height:1.7; }
    .igm-message-meta { color:#768295; font-size:11px; }
    .igm-message-links { display:flex; flex-wrap:wrap; gap:12px 20px; margin-top:14px; }
    .igm-community { padding:46px 0 49px; }
    .igm-section-heading { display:flex; justify-content:space-between; align-items:end; gap:24px; margin-bottom:20px; }
    .igm-card-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:20px; }
    .igm-community-card { overflow:hidden; border:1px solid #eeece7; border-radius:8px; background:#fff; box-shadow:0 10px 24px rgba(17,42,66,.07); transition:transform .2s ease,box-shadow .2s ease; }
    .igm-community-card:hover { transform:translateY(-4px); box-shadow:0 18px 36px rgba(17,42,66,.12); }
    .igm-community-card img { width:100%; height:150px; object-fit:cover; display:block; background:#e5e1d6; }
    .igm-card-placeholder,.igm-message-placeholder,.igm-gallery-placeholder { display:grid; place-items:center; width:100%; height:150px; color:#edc875; background:radial-gradient(circle at 80% 15%,rgba(216,173,93,.2),transparent 35%),linear-gradient(135deg,#0c3455,#061d34); font-size:26px; font-weight:850; letter-spacing:.08em; }
    .igm-event-photo .igm-card-placeholder { height:100%; min-height:165px; }
    .igm-message-placeholder { height:100%; min-height:225px; font-size:15px; text-transform:uppercase; }
    .igm-gallery-placeholder { height:100%; min-height:130px; }
    .igm-community-card-body { padding:16px 18px 19px; }
    .igm-community-card h3 { margin:0; color:var(--igm-navy); font-size:16px; font-weight:800; }
    .igm-community-card p { min-height:42px; margin:7px 0 11px; color:var(--igm-muted); font-size:12px; line-height:1.65; }
    .igm-home-ministries { padding-top:55px; padding-bottom:58px; }
    .igm-home-ministries .igm-section-heading { align-items:end; margin-bottom:24px; }
    .igm-home-ministries .igm-section-copy { max-width:590px; }
    .igm-ministries-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:19px; }
    .igm-ministry-card { min-width:0; overflow:hidden; border:1px solid #e8e9e6; border-radius:17px; background:#fff; box-shadow:0 12px 30px rgba(12,39,63,.07); transition:transform .24s ease,box-shadow .24s ease,border-color .24s ease; }
    .igm-ministry-card:hover { transform:translateY(-5px); border-color:#dfc98f; box-shadow:0 20px 38px rgba(12,39,63,.12); }
    .igm-ministry-image { position:relative; display:block; height:205px; overflow:hidden; isolation:isolate; background:#183853; color:#fff; text-decoration:none; }
    .igm-ministry-image::after { position:absolute; z-index:1; inset:35% 0 0; content:""; background:linear-gradient(180deg,transparent,rgba(5,24,43,.66)); pointer-events:none; }
    .igm-ministry-image img { position:absolute; z-index:0; inset:0; display:block; width:100%; height:100%; object-fit:cover; transition:transform .55s cubic-bezier(.2,.7,.25,1); }
    .igm-ministry-card:hover .igm-ministry-image img { transform:scale(1.055); }
    .igm-ministry-placeholder { position:absolute; z-index:0; inset:0; display:grid; width:100%; height:100%; place-items:center; color:#edc875; background:radial-gradient(circle at 78% 18%,rgba(237,200,117,.24),transparent 29%),linear-gradient(135deg,#164567,#061d34); font-size:50px; font-weight:850; }
    .igm-ministry-tag { position:absolute; z-index:2; top:14px; left:14px; padding:7px 9px; border:1px solid rgba(255,255,255,.26); border-radius:999px; color:#fff; background:rgba(5,25,43,.42); backdrop-filter:blur(8px); font-size:8px; font-weight:850; letter-spacing:.12em; text-transform:uppercase; }
    .igm-ministry-open { position:absolute; z-index:2; right:14px; bottom:14px; display:grid; width:35px; height:35px; place-items:center; border:1px solid rgba(255,255,255,.44); border-radius:50%; color:#112d48; background:#edc875; transition:transform .2s ease; }
    .igm-ministry-open svg { width:15px; height:15px; }
    .igm-ministry-card:hover .igm-ministry-open { transform:translateX(3px); }
    .igm-ministry-body { padding:18px 19px 19px; }
    .igm-ministry-body h3 { margin:0; color:var(--igm-navy); font-size:17px; font-weight:850; letter-spacing:-.025em; }
    .igm-ministry-body p { min-height:59px; margin:8px 0 15px; color:#68798c; font-size:11px; line-height:1.7; }
    .igm-ministry-link { display:flex; align-items:center; justify-content:space-between; padding-top:12px; border-top:1px solid #edf0f2; color:#926a25; font-size:10px; font-weight:850; text-decoration:none; }
    .igm-ministry-link:hover { color:var(--igm-navy); }
    @media(max-width:950px) { .igm-ministries-grid { gap:13px; } .igm-ministry-image { height:175px; } .igm-ministry-body { padding:15px; } }
    @media(max-width:640px) { .igm-home-ministries { padding-top:37px; padding-bottom:40px; } .igm-ministries-grid { grid-template-columns:1fr; gap:13px; } .igm-ministry-card { display:grid; grid-template-columns:minmax(115px,36%) minmax(0,1fr); border-radius:14px; } .igm-ministry-image { height:100%; min-height:178px; } .igm-ministry-body { display:flex; flex-direction:column; justify-content:center; padding:15px; } .igm-ministry-body p { min-height:0; margin:7px 0 12px; } .igm-ministry-tag { top:9px; left:9px; padding:5px 7px; font-size:7px; } .igm-ministry-open { right:9px; bottom:9px; width:30px; height:30px; } }
    @media(max-width:420px) { .igm-ministry-card { grid-template-columns:1fr; } .igm-ministry-image { height:170px; min-height:170px; } }
    .igm-events { padding:44px 0 49px; color:#fff; background:radial-gradient(ellipse at 75% 15%,rgba(39,81,113,.35),transparent 45%),linear-gradient(135deg,#082846,#061d34); }
    .igm-events .igm-kicker { color:#edc875; }
    .igm-events .igm-section-title { color:#fff; }
    .igm-events .igm-section-copy { color:#c2cfdb; }
    .igm-events .igm-link { color:#edc875; }
    .igm-events-empty { display:flex; align-items:center; justify-content:space-between; gap:18px; padding:20px 22px; border:1px solid rgba(255,255,255,.16); border-radius:10px; color:#d1dce6; background:rgba(255,255,255,.055); font-size:12px; }
    .igm-events-empty strong { color:#fff; }
    .igm-event-card { display:grid; grid-template-columns:34% 1fr; min-height:165px; overflow:hidden; color:var(--igm-ink); background:#fff; border:1px solid rgba(255,255,255,.22); border-radius:8px; box-shadow:0 13px 27px rgba(0,0,0,.2); }
    .igm-event-photo { min-height:165px; background:#283d50; }
    .igm-event-photo img { width:100%; height:100%; min-height:165px; object-fit:cover; display:block; }
    .igm-event-info { position:relative; padding:19px 18px 16px 74px; }
    .igm-datebox { position:absolute; left:17px; top:18px; width:44px; overflow:hidden; color:#fff; text-align:center; border-radius:6px; background:#b28b43; box-shadow:0 5px 13px rgba(0,0,0,.13); }
    .igm-datebox span { display:block; padding:4px 2px 2px; background:#d8ad5d; font-size:9px; font-weight:800; text-transform:uppercase; }
    .igm-datebox strong { display:block; padding:1px 0 5px; font-size:20px; line-height:1.1; }
    .igm-event-info h3 { margin:0; color:var(--igm-navy); font-size:15px; line-height:1.35; font-weight:800; }
    .igm-event-info p { margin:8px 0; color:#6c798a; font-size:11px; line-height:1.55; }
    .igm-event-detail { display:flex; align-items:center; gap:7px; color:#718095; font-size:10px; margin-top:5px; }
    .igm-event-status { display:inline-flex; align-items:center; gap:5px; margin-bottom:8px; padding:4px 7px; border-radius:999px; color:#26724e; background:#eaf6ef; font-size:8px; font-weight:850; letter-spacing:.08em; text-transform:uppercase; }
    .igm-event-status.is-past { color:#68788a; background:#eef1f4; }
    .igm-testimony { position:relative; min-height:245px; display:flex; align-items:center; background:#f7f5ef; overflow:hidden; }
    .igm-testimony::after { content:""; position:absolute; inset:0 0 0 48%; background:radial-gradient(ellipse at 85% 50%,rgba(216,173,93,.14),transparent 55%),linear-gradient(90deg,#f7f5ef,rgba(247,245,239,.25)); }
    .igm-testimony-copy { position:relative; z-index:1; width:min(1120px,calc(100% - 40px)); margin:0 auto; padding:40px 0; }
    .igm-testimony blockquote { max-width:450px; margin:12px 0 0; color:var(--igm-navy); font-size:22px; line-height:1.45; font-weight:650; letter-spacing:-.02em; }
    .igm-testimony cite { display:block; margin-top:14px; color:#65748a; font-size:12px; font-style:normal; }
    .igm-gallery { padding:54px 0 58px; background:linear-gradient(180deg,#fbfaf7 0%,#f4f2ec 100%); }
    .igm-gallery .igm-section-heading { align-items:end; margin-bottom:24px; }
    .igm-gallery .igm-section-copy { max-width:560px; }
    .igm-gallery-grid { display:grid; grid-template-columns:minmax(0,1.28fr) minmax(270px,.72fr); grid-template-rows:210px 210px; gap:15px; }
    .igm-gallery-grid.is-count-1 { grid-template-columns:1fr; grid-template-rows:350px; }
    .igm-gallery-grid.is-count-2 { grid-template-columns:repeat(2,minmax(0,1fr)); grid-template-rows:290px; }
    .igm-gallery-tile { position:relative; display:block; min-width:0; min-height:0; overflow:hidden; isolation:isolate; border:1px solid rgba(255,255,255,.75); border-radius:18px; background:#d8d5cb; box-shadow:0 14px 34px rgba(13,39,63,.12); color:#fff; text-decoration:none; transform:translateZ(0); }
    .igm-gallery-grid:not(.is-count-2):not(.is-count-1) .igm-gallery-tile:first-child { grid-row:span 2; }
    .igm-gallery-tile img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; display:block; transition:transform .55s cubic-bezier(.2,.7,.25,1); }
    .igm-gallery-tile:hover img,.igm-gallery-tile:focus-visible img { transform:scale(1.055); }
    .igm-gallery-tile:focus-visible { outline:3px solid #c99b45; outline-offset:3px; }
    .igm-gallery-shade { position:absolute; inset:0; z-index:0; background:linear-gradient(180deg,rgba(4,23,42,.05) 12%,rgba(4,23,42,.08) 36%,rgba(4,23,42,.86) 100%); }
    .igm-gallery-tile::after { position:absolute; z-index:0; inset:0; border:1px solid rgba(255,255,255,.16); border-radius:inherit; content:""; pointer-events:none; }
    .igm-gallery-tag { position:absolute; z-index:1; top:16px; left:17px; display:inline-flex; align-items:center; gap:7px; padding:7px 10px; border:1px solid rgba(255,255,255,.33); border-radius:999px; color:#fff; background:rgba(5,25,43,.42); backdrop-filter:blur(8px); font-size:8px; font-weight:850; letter-spacing:.12em; text-transform:uppercase; }
    .igm-gallery-tag::before { width:6px; height:6px; border-radius:50%; content:""; background:#edc875; }
    .igm-gallery-caption { position:absolute; z-index:1; right:65px; bottom:20px; left:21px; }
    .igm-gallery-caption small { display:block; margin-bottom:6px; color:#f1d58e; font-size:8px; font-weight:850; letter-spacing:.14em; text-transform:uppercase; }
    .igm-gallery-caption strong { display:block; overflow:hidden; color:#fff; font-size:clamp(14px,1.6vw,20px); line-height:1.2; font-weight:800; letter-spacing:-.02em; text-overflow:ellipsis; }
    .igm-gallery-feature .igm-gallery-caption strong { max-width:520px; font-size:clamp(22px,3vw,34px); }
    .igm-gallery-caption em { display:block; margin-top:7px; color:rgba(255,255,255,.76); font-size:10px; font-style:normal; }
    .igm-gallery-open { position:absolute; z-index:2; right:17px; bottom:17px; display:grid; width:37px; height:37px; place-items:center; border:1px solid rgba(255,255,255,.4); border-radius:50%; color:#132e48; background:#edc875; box-shadow:0 5px 14px rgba(0,0,0,.16); transition:transform .2s ease,background .2s ease; }
    .igm-gallery-open svg { width:16px; height:16px; }
    .igm-gallery-tile:hover .igm-gallery-open { transform:translateX(2px); background:#f5d992; }
    .igm-gallery-placeholder { position:absolute; inset:0; display:grid; place-content:center; justify-items:center; gap:9px; min-height:0; height:auto; color:#edc875; background:radial-gradient(circle at 80% 15%,rgba(216,173,93,.2),transparent 35%),linear-gradient(135deg,#0c3455,#061d34); font-size:11px; font-weight:800; letter-spacing:.12em; }
    .igm-gallery-placeholder svg { width:31px; height:31px; }
    .igm-connect { padding:26px 0; color:#fff; background:linear-gradient(110deg,#0a2d4d,#061d34); }
    .igm-connect-row { display:flex; justify-content:space-between; align-items:center; gap:24px; }
    .igm-connect h2 { margin:5px 0 0; color:#fff; font-size:20px; font-weight:800; }
    .igm-connect p { margin:5px 0 0; color:#c3cfdb; font-size:12px; }
    #igm-site-footer.igm-home-footer { margin-top:0; background:#061d34; border-top:1px solid rgba(237,200,117,.35); }
    @media (max-width:1023px) {
        .igm-hero { min-height:405px; }
        .igm-mission { gap:28px; }
        .igm-card-grid { gap:13px; }
        .igm-event-card { grid-template-columns:31% 1fr; }
        .igm-event-info { padding-left:67px; }
    }
    @media (max-width:640px) {
        .igm-topline { font-size:9px; }
        .igm-wrap,.igm-hero-content,.igm-testimony-copy { width:min(100% - 32px,1120px); }
        .igm-hero { min-height:440px; }
        .igm-hero::before { background-position:61% center; }
        .igm-hero-slide[data-hero-focus="right"]::before { background-position:right center; }
        .igm-hero::after { background:linear-gradient(90deg,rgba(4,23,42,.87),rgba(6,30,53,.53)),linear-gradient(0deg,rgba(4,24,43,.35),transparent 60%); }
        .igm-hero-content { padding:58px 0; }
        .igm-hero h1 { font-size:clamp(39px,12vw,58px); }
        .igm-hero p { font-size:13px; }
        .igm-quickgrid { grid-template-columns:1fr; padding:8px 0; }
        .igm-quick,.igm-quick:first-child,.igm-quick:last-child { padding:13px 0; border-right:0; border-bottom:1px solid #e6e0d4; }
        .igm-quick:last-child { border-bottom:0; }
        .igm-mission,.igm-message-card { grid-template-columns:1fr; gap:20px; }
        .igm-mission { padding:35px 0; }
        .igm-mission-photo,.igm-mission-photo img { min-height:210px; }
        .igm-message { padding:22px 0 32px; }
        .igm-message-card { gap:2px; padding:8px; }
        .igm-message-copy { padding:18px 14px 20px; }
        .igm-section-heading { align-items:flex-start; flex-direction:column; gap:10px; }
        .igm-card-grid { grid-template-columns:1fr; }
        .igm-community-card { display:grid; grid-template-columns:38% 1fr; }
        .igm-community-card img { height:100%; min-height:145px; }
        .igm-community-card-body { padding:15px; }
        .igm-community-card p { min-height:0; }
        .igm-events { padding:36px 0; }
        .igm-event-card { grid-template-columns:1fr; }
        .igm-events-empty { align-items:flex-start; flex-direction:column; }
        .igm-event-photo,.igm-event-photo img { min-height:155px; height:155px; }
        .igm-event-photo .igm-card-placeholder { min-height:155px; }
        .igm-event-info { min-height:130px; }
        .igm-testimony { min-height:300px; align-items:flex-start; }
        .igm-testimony::after { inset:40% 0 0; background:linear-gradient(0deg,rgba(247,245,239,.04),#f7f5ef 15%),radial-gradient(ellipse at 70% 70%,rgba(216,173,93,.15),transparent 55%); }
        .igm-testimony blockquote { max-width:100%; font-size:18px; }
        .igm-connect-row { align-items:flex-start; flex-direction:column; }
    }
    @media (max-width:850px) {
        .igm-gallery-grid,.igm-gallery-grid.is-count-2 { grid-template-columns:repeat(2,minmax(0,1fr)); grid-template-rows:245px 190px; }
        .igm-gallery-grid:not(.is-count-2):not(.is-count-1) .igm-gallery-tile:first-child { grid-column:span 2; grid-row:auto; }
        .igm-gallery-grid.is-count-1 { grid-template-columns:1fr; grid-template-rows:300px; }
    }
    @media (max-width:640px) {
        .igm-gallery { padding:36px 0 40px; }
        .igm-gallery .igm-section-heading { margin-bottom:17px; }
        .igm-gallery-grid,.igm-gallery-grid.is-count-1,.igm-gallery-grid.is-count-2 { grid-template-columns:1fr; grid-template-rows:none; gap:12px; }
        .igm-gallery-tile,.igm-gallery-grid.is-count-1 .igm-gallery-tile { min-height:215px; }
        .igm-gallery-grid:not(.is-count-2):not(.is-count-1) .igm-gallery-tile:first-child { grid-column:auto; grid-row:auto; min-height:270px; }
        .igm-gallery-feature .igm-gallery-caption strong { font-size:25px; }
    }
    @media (prefers-reduced-motion:reduce) { .igm-home *, .igm-home *::before, .igm-home *::after { transition:none!important; animation:none!important; scroll-behavior:auto!important; } }
</style>
@endpush

@section('content')
@php
    $churchName = $_church->name ?? config('app.name');
    $heroAttachment = $heroPost ? ($heroPost->attachment_path[0]['path'] ?? null) : null;
    $heroImage = $heroAttachment ?: ($latestSermon->cover_image_path ?? null);
    $heroTitle = $heroPost->title ?? 'A place to belong.';
    $heroExcerpt = $heroPost ? \Illuminate\Support\Str::limit(trim(strip_tags($heroPost->description)), 150) : ($_church->quotes ?? 'Find a welcoming community, grow in faith, and discover a place to serve.');
@endphp

<div class="igm-home">
    @if($liveStream)
    <section class="igm-hero-live" aria-label="Live church service">
        <div><span class="igm-live-label">Live now</span><h1>{{ $liveStream->title }}</h1><p>{{ $liveStream->message ?: 'Join our church family for worship, prayer, and the Word.' }}</p><div class="igm-hero-actions"><a class="igm-button igm-button-gold" href="{{ route('web.live') }}">Watch live <span aria-hidden="true">↗</span></a><a class="igm-button igm-button-outline" href="{{ route('web.events') }}">See what is coming up</a></div></div>
        @if($liveStream->embed_url)<div class="igm-live-player"><iframe src="{{ $liveStream->embed_url }}" title="{{ $liveStream->title }}" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen loading="eager" referrerpolicy="strict-origin-when-cross-origin"></iframe></div>@elseif($liveStream->playback_url)<div class="igm-live-player"><video data-live-hls data-stream-url="{{ $liveStream->playback_url }}" controls playsinline autoplay muted preload="none" aria-label="{{ $liveStream->title ?: 'IGM Church live stream' }}"></video></div>@else<div class="igm-live-player grid place-items-center"><a class="igm-button igm-button-gold" href="{{ route('web.live') }}">Open live stream ↗</a></div>@endif
    </section>
    @elseif($heroBanners->isNotEmpty())
    <section class="igm-hero-carousel" aria-label="Church welcome banners" data-carousel>
        @foreach($heroBanners as $index => $banner)
        <article class="igm-hero igm-hero-slide {{ $index === 0 ? 'is-active' : '' }}" data-hero-slide data-transition="{{ $banner->transition }}" data-duration="{{ $banner->duration_seconds }}" @if(str_contains($banner->image_path ?? '', 'welcome-leadership-cutout')) data-hero-focus="right" @endif aria-hidden="{{ $index === 0 ? 'false' : 'true' }}" style="--hero-image:url('{{ $banner->ImageUrl }}')">
            <div class="igm-hero-content"><div class="igm-hero-copy"><div class="igm-hero-eyebrow">{{ $banner->eyebrow ?: 'Welcome to '.$churchName }}</div><h1>{{ $banner->title }}@if($banner->highlight)<span>{{ $banner->highlight }}</span>@endif</h1>@if($banner->description)<p>{{ $banner->description }}</p>@endif
                @if($banner->primary_label || $banner->secondary_label)<div class="igm-hero-actions">@if($banner->primary_label && $banner->primary_url)<a class="igm-button igm-button-gold" href="{{ $banner->primary_url }}">{{ $banner->primary_label }} <span aria-hidden="true">→</span></a>@endif @if($banner->secondary_label && $banner->secondary_url)<a class="igm-button igm-button-outline" href="{{ $banner->secondary_url }}">{{ $banner->secondary_label }}</a>@endif</div>@endif
            </div></div>
        </article>
        @endforeach
        @if($heroBanners->count() > 1)<div class="igm-hero-controls" aria-label="Banner controls"><button class="igm-hero-control" type="button" data-hero-prev aria-label="Previous banner">‹</button><div class="igm-hero-dots">@foreach($heroBanners as $index => $banner)<button type="button" class="igm-hero-dot" data-hero-dot="{{ $index }}" aria-label="Show banner {{ $index + 1 }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>@endforeach</div><button class="igm-hero-control" type="button" data-hero-next aria-label="Next banner">›</button><button class="igm-hero-control" type="button" data-hero-pause aria-label="Pause banner rotation">Ⅱ</button></div>@endif
    </section>
    @else
    <section class="igm-hero" style="--hero-image:url('{{ $heroImage }}')"><div class="igm-hero-content"><div class="igm-hero-copy"><div class="igm-hero-eyebrow">Welcome to {{ $churchName }}</div><h1>{{ $heroTitle }}<span>A faith to grow.</span></h1><p>{{ $heroExcerpt }}</p><div class="igm-hero-actions"><a class="igm-button igm-button-gold" href="{{ route('web.contact') }}">Plan your visit <span aria-hidden="true">→</span></a><a class="igm-button igm-button-outline" href="{{ route('web.sermons') }}"><span aria-hidden="true">▶</span> Watch online</a></div></div></div><div class="igm-hero-mark" aria-hidden="true"></div></section>
    @endif

    <section class="igm-events">
        <div class="igm-wrap">
            <div class="igm-section-heading">
                <div><div class="igm-kicker">{{ $showingPastEvents ? 'Recent gatherings' : 'Upcoming events' }}</div><h2 class="igm-section-title">{{ $showingPastEvents ? 'See what we have shared' : "Don't miss what's next" }}</h2><p class="igm-section-copy">{{ $showingPastEvents ? 'A look back at recent moments from our church community.' : 'Be part of what God is doing. Join us at an upcoming gathering.' }}</p></div>
                <a class="igm-button igm-button-outline" href="{{ route('web.events') }}">View all events <span aria-hidden="true">→</span></a>
            </div>
            @if($upcomingEvents->isNotEmpty())
            <div class="igm-card-grid">
                @foreach($upcomingEvents as $event)
                    <article class="igm-event-card">
                        <a class="igm-event-photo" href="{{ route('web.event', $event->id) }}" aria-label="View {{ $event->title }}">
                            @if($event->image_path)<img src="{{ $event->image_path }}" alt="{{ $event->title }}" loading="lazy">@else<div class="igm-card-placeholder" aria-hidden="true">Event</div>@endif
                        </a>
                        <div class="igm-event-info">
                            <div class="igm-datebox"><span>{{ \Carbon\Carbon::parse($event->start_date)->format('M') }}</span><strong>{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</strong></div>
                            <span class="igm-event-status {{ $showingPastEvents ? 'is-past' : '' }}">{{ $showingPastEvents ? 'Past event' : 'Upcoming' }}</span>
                            <h3>{{ $event->title }}</h3>
                            @if($event->description)<p>{{ \Illuminate\Support\Str::limit(trim(strip_tags($event->description)), 78) }}</p>@endif
                            <div class="igm-event-detail"><span aria-hidden="true">◷</span> {{ \Carbon\Carbon::parse($event->start_date)->format('D, g:i A') }}</div>
                            @if($event->location)<div class="igm-event-detail"><span aria-hidden="true">⌖</span> {{ \Illuminate\Support\Str::limit($event->location, 38) }}</div>@endif
                        </div>
                    </article>
                @endforeach
            </div>
            @else
                <div class="igm-events-empty"><strong>There are no events to display right now.</strong><span>Check the events page for the latest church gatherings.</span></div>
            @endif
        </div>
    </section>

    @if($latestSermon)
    <section class="igm-message">
        <div class="igm-wrap igm-message-card">
            <div class="igm-message-image">
                @if($latestSermon->cover_image_path)
                    <img src="{{ $latestSermon->cover_image_path }}" alt="{{ $latestSermon->title }}" loading="lazy">
                @else
                    <div class="igm-message-placeholder" aria-hidden="true"><span>Latest message</span></div>
                @endif
                <a class="igm-play" href="{{ route('web.sermon', $latestSermon->id) }}" aria-label="Watch {{ $latestSermon->title }}"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.8c0-.8.9-1.3 1.6-.9l9.1 5.2a1.1 1.1 0 0 1 0 1.8l-9.1 5.2c-.7.4-1.6-.1-1.6-.9V5.8Z"/></svg></a>
            </div>
            <div class="igm-message-copy">
                <span class="igm-pill">Latest message</span>
                <h3>{{ $latestSermon->title }}</h3>
                <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags($latestSermon->description ?? '')), 150) }}</p>
                <div class="igm-message-links"><a class="igm-link" href="{{ route('web.sermon', $latestSermon->id) }}">Watch the message <span aria-hidden="true">→</span></a><a class="igm-link" href="{{ route('web.sermons') }}">See all sermons <span aria-hidden="true">→</span></a></div>
            </div>
        </div>
    </section>
    @endif

    @if($galleries->isNotEmpty())
    <section class="igm-wrap igm-gallery">
        <div class="igm-section-heading">
            <div><div class="igm-kicker">Photo stories</div><h2 class="igm-section-title">Life together, in pictures</h2><p class="igm-section-copy">Worship, fellowship, and service shared by the {{ $churchName }} community.</p></div>
            <a class="igm-button igm-button-gold" href="{{ route('web.gallery') }}">Explore all galleries <span aria-hidden="true">→</span></a>
        </div>
        <div class="igm-gallery-grid is-count-{{ $galleries->count() }}">
            @foreach($galleries as $gallery)
                @php
                    $galleryPhoto = $gallery->photos->first();
                    $galleryImage = $galleryPhoto ? $galleryPhoto->FullPath : ($gallery->full_path ?: null);
                @endphp
                <a class="igm-gallery-tile {{ $loop->first ? 'igm-gallery-feature' : '' }}" href="{{ route('web.gallery.show', $gallery->id) }}" aria-label="Open gallery: {{ $gallery->name }}">
                    @if($galleryImage)
                        <img src="{{ $galleryImage }}" alt="{{ $gallery->name }}" loading="lazy" decoding="async">
                    @else
                        <div class="igm-gallery-placeholder" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.6"/><circle cx="8.5" cy="9" r="1.5" stroke="currentColor" stroke-width="1.5"/><path d="m5 18 5-5 3 3 2-2 4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg><span>Photos coming soon</span></div>
                    @endif
                    <span class="igm-gallery-shade" aria-hidden="true"></span>
                    <span class="igm-gallery-tag">Church gallery</span>
                    <span class="igm-gallery-caption"><small>{{ $loop->first ? 'Featured collection' : 'Photo collection' }}</small><strong>{{ $gallery->name }}</strong><em>Open this album</em></span>
                    <span class="igm-gallery-open" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    @if($communities->isNotEmpty())
    <section class="igm-wrap igm-community igm-home-ministries">
        <div class="igm-section-heading">
            <div><div class="igm-kicker">Find your people</div><h2 class="igm-section-title">Community for every season</h2><p class="igm-section-copy">Discover the groups and ministries already part of {{ $churchName }} and find a place to connect, grow, and serve.</p></div>
            <a class="igm-button igm-button-gold" href="{{ route('web.ministries') }}">Explore all ministries <span aria-hidden="true">→</span></a>
        </div>
        <div class="igm-ministries-grid">
            @foreach($communities as $community)
                <article class="igm-ministry-card">
                    <a class="igm-ministry-image" href="{{ route('web.ministries') }}" aria-label="Explore {{ $community->name }}">
                        @if($community->cover_image)
                            <img src="{{ $community->CoverImagePath }}" alt="{{ $community->name }}" loading="lazy" decoding="async">
                        @else
                            <span class="igm-ministry-placeholder" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($community->name, 0, 1)) }}</span>
                        @endif
                        <span class="igm-ministry-tag">Community</span>
                        <span class="igm-ministry-open" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    </a>
                    <div class="igm-ministry-body">
                        <h3>{{ $community->name }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags($community->description ?? '')), 95) }}</p>
                        <a class="igm-ministry-link" href="{{ route('web.ministries') }}">Explore this community <span aria-hidden="true">→</span></a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
    @endif

    @if($recentPosts->isNotEmpty())
    <section class="igm-wrap igm-community igm-home-blogs">
        <div class="igm-section-heading">
            <div><div class="igm-kicker">Church news</div><h2 class="igm-section-title">Stories and updates</h2><p class="igm-section-copy">The latest news and encouragement from our church family.</p></div>
            <a class="igm-link" href="{{ route('web.posts') }}">Read all updates <span aria-hidden="true">→</span></a>
        </div>
        <div class="igm-card-grid">
            @foreach($recentPosts as $post)
                @php $postImage = $post->attachment_path[0]['path'] ?? null; @endphp
                <article class="igm-community-card">
                    <a href="{{ route('web.post', $post->id) }}">@if($postImage)<img src="{{ $postImage }}" alt="{{ $post->title }}" loading="lazy">@else<div class="igm-card-placeholder" aria-hidden="true">News</div>@endif</a>
                    <div class="igm-community-card-body">
                        <span class="igm-kicker">{{ optional($post->post_created_at)->format('M j, Y') }}</span>
                        <h3 style="margin-top:7px">{{ $post->title }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags($post->description ?? '')), 92) }}</p>
                        <a class="igm-link" href="{{ route('web.post', $post->id) }}">Read story <span aria-hidden="true">→</span></a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
    @endif

    <section class="igm-quicklinks" aria-label="Explore {{ $churchName }}">
        <div class="igm-wrap igm-quickgrid">
            <a class="igm-quick" href="{{ route('web.events') }}">
                <span class="igm-round-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 3v3m10-3v3M4 9h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Z"/><path d="m9 14 2 2 4-4"/></svg></span>
                <span><strong>{{ $serviceSchedule->isNotEmpty() ? 'Worship with us' : 'Sunday worship' }}</strong><small>@if($serviceSchedule->isNotEmpty()){{ $serviceSchedule->map(fn ($service) => \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::SUNDAY)->addDays((int) $service->day_of_week)->format('l').' · '.$service->service_time)->implode('  |  ') }}@else Join us for worship, prayer, and the Word.@endif</small></span>
            </a>
            <a class="igm-quick" href="{{ route('web.prayer') }}">
                <span class="igm-round-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/><path d="M12 8v8m-4-4h8"/></svg></span>
                <span><strong>Request prayer</strong><small>Share what is on your heart with our prayer team.</small></span>
            </a>
            <a class="igm-quick" href="{{ route('web.contact') }}">
                <span class="igm-round-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></span>
                <span><strong>Plan your visit</strong><small>Service times, directions, and how to connect with us.</small></span>
            </a>
        </div>
    </section>

    @if($_church && $_church->quotes)
    <section class="igm-testimony">
        <div class="igm-testimony-copy">
            <div class="igm-kicker">Our heart as a church</div>
            <blockquote>“{{ $_church->quotes }}”</blockquote>
            <cite>{{ $churchName }}</cite>
        </div>
    </section>
    @endif

    <section class="igm-connect">
        <div class="igm-wrap igm-connect-row">
            <div><div class="igm-kicker">Stay connected</div><h2>There is a place for you here.</h2><p>Get connected with {{ $churchName }} and stay up to date with church life.</p></div>
            <a class="igm-button igm-button-gold" href="{{ route('web.guest.register') }}">Join our community <span aria-hidden="true">→</span></a>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
(() => {
    const carousel = document.querySelector('[data-carousel]');
    if (!carousel) return;

    const slides = Array.from(carousel.querySelectorAll('[data-hero-slide]'));
    const dots = Array.from(carousel.querySelectorAll('[data-hero-dot]'));
    const pauseButton = carousel.querySelector('[data-hero-pause]');
    if (slides.length < 2) return;

    let current = 0;
    let timer = null;
    let paused = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const show = (next) => {
        slides[current].classList.remove('is-active');
        slides[current].setAttribute('aria-hidden', 'true');
        if (dots[current]) dots[current].setAttribute('aria-current', 'false');
        current = (next + slides.length) % slides.length;
        slides[current].classList.add('is-active');
        slides[current].setAttribute('aria-hidden', 'false');
        if (dots[current]) dots[current].setAttribute('aria-current', 'true');
        schedule();
    };
    const schedule = () => {
        window.clearTimeout(timer);
        if (paused || document.hidden) return;
        const seconds = Number(slides[current].dataset.duration || 6);
        timer = window.setTimeout(() => show(current + 1), seconds * 1000);
    };

    carousel.querySelector('[data-hero-prev]')?.addEventListener('click', () => show(current - 1));
    carousel.querySelector('[data-hero-next]')?.addEventListener('click', () => show(current + 1));
    dots.forEach((dot) => dot.addEventListener('click', () => show(Number(dot.dataset.heroDot))));
    pauseButton?.addEventListener('click', () => {
        paused = !paused;
        pauseButton.textContent = paused ? '▶' : 'Ⅱ';
        pauseButton.setAttribute('aria-label', paused ? 'Resume banner rotation' : 'Pause banner rotation');
        schedule();
    });
    carousel.addEventListener('mouseenter', () => window.clearTimeout(timer));
    carousel.addEventListener('mouseleave', schedule);
    carousel.addEventListener('focusin', () => window.clearTimeout(timer));
    carousel.addEventListener('focusout', (event) => { if (!carousel.contains(event.relatedTarget)) schedule(); });
    document.addEventListener('visibilitychange', schedule);
    schedule();
})();
</script>
@endpush
