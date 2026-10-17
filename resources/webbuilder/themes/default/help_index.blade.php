@extends('theme::layout')

@section('title', 'Community Help & Support')

@push('styles')
<style>
    .help-page { --help-navy:#082846; --help-gold:#edc875; --help-ink:#122d49; --help-muted:#687c91; color:var(--help-ink); }
    .help-hero { position:relative; overflow:hidden; color:#fff; background:linear-gradient(115deg,#061d34 0%,#0b3455 62%,#16476b 100%); }
    .help-hero::before,.help-hero::after { position:absolute; content:""; pointer-events:none; border:1px solid rgba(237,200,117,.2); border-radius:50%; }
    .help-hero::before { width:390px; height:390px; right:-55px; top:-230px; box-shadow:0 0 0 35px rgba(237,200,117,.035),0 0 0 75px rgba(237,200,117,.025); }
    .help-hero::after { width:170px; height:170px; right:22%; bottom:-125px; }
    .help-hero-inner { position:relative; z-index:1; width:min(1160px,100%); margin:auto; padding:47px 28px 37px; }
    .help-breadcrumbs { display:flex; align-items:center; gap:9px; color:#bdcbd8; font-size:11px; }
    .help-breadcrumbs a { color:#f3d68f; text-decoration:none; }
    .help-kicker { display:inline-flex; align-items:center; gap:8px; margin-top:26px; color:var(--help-gold); font-size:10px; font-weight:850; letter-spacing:.17em; text-transform:uppercase; }
    .help-kicker::before { width:22px; height:1px; content:""; background:var(--help-gold); }
    .help-hero h1 { max-width:720px; margin:9px 0 12px; color:#fff; font-size:clamp(34px,5vw,54px); line-height:1.05; font-weight:850; letter-spacing:-.04em; }
    .help-hero-copy { max-width:670px; margin:0; color:#d2dce6; font-size:15px; line-height:1.75; }
    .help-hero-tags { display:flex; flex-wrap:wrap; gap:9px; margin-top:22px; }
    .help-hero-tags span { padding:8px 12px; border:1px solid rgba(255,255,255,.18); border-radius:999px; color:#e7edf3; background:rgba(255,255,255,.06); font-size:10px; font-weight:700; }
    .help-container { width:min(1160px,100%); margin:auto; padding:34px 28px 60px; }
    .help-intro { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:19px; }
    .help-intro h2 { margin:5px 0 5px; color:var(--help-ink); font-size:24px; font-weight:850; letter-spacing:-.03em; }
    .help-intro p { max-width:640px; margin:0; color:var(--help-muted); font-size:13px; line-height:1.65; }
    .help-label { color:#a77b32; font-size:9px; font-weight:850; letter-spacing:.15em; text-transform:uppercase; }
    .help-layout { display:grid; grid-template-columns:minmax(0,1.45fr) minmax(260px,.75fr); align-items:start; gap:18px; }
    .help-panel { border:1px solid #e5ebf0; border-radius:18px; background:#fff; box-shadow:0 12px 32px rgba(10,38,65,.055); }
    .help-form-panel { padding:clamp(20px,3vw,30px); }
    .help-form-head { display:flex; align-items:flex-start; gap:13px; margin-bottom:22px; }
    .help-form-icon { display:grid; width:44px; height:44px; flex:0 0 44px; place-items:center; border-radius:14px; color:#8b6628; background:#fbf3df; }
    .help-form-icon svg { width:21px; height:21px; }
    .help-form-head h3 { margin:1px 0 5px; color:var(--help-ink); font-size:17px; font-weight:800; }
    .help-form-head p { margin:0; color:var(--help-muted); font-size:11px; line-height:1.6; }
    .help-alert { margin:0 0 16px; padding:12px 14px; border:1px solid; border-radius:11px; font-size:12px; line-height:1.55; }
    .help-alert-success { border-color:#b7e2ca; color:#226342; background:#f0faf4; }
    .help-alert-error { border-color:#f0caca; color:#9b3838; background:#fff5f5; }
    .help-form-grid { display:grid; grid-template-columns:1fr 1fr; gap:15px; }
    .help-field { display:flex; flex-direction:column; gap:7px; }
    .help-field-full { grid-column:1 / -1; }
    .help-field label { color:#304961; font-size:11px; font-weight:750; }
    .help-required { color:#b45445; }
    .help-field input,.help-field textarea { width:100%; border:1px solid #dce5ec; border-radius:10px; outline:none; color:#17324e; background:#fbfcfd; font:inherit; font-size:12px; transition:border-color .16s ease,box-shadow .16s ease,background .16s ease; }
    .help-field input { min-height:44px; padding:0 13px; }
    .help-field textarea { min-height:125px; padding:12px 13px; resize:vertical; line-height:1.6; }
    .help-field input:focus,.help-field textarea:focus { border-color:#bd974b; background:#fff; box-shadow:0 0 0 3px rgba(237,200,117,.2); }
    .help-field input::placeholder,.help-field textarea::placeholder { color:#9aa7b4; }
    .help-field-error { color:#a33b3b; font-size:10px; }
    .help-form-footer { display:flex; align-items:center; justify-content:space-between; gap:14px; margin-top:18px; }
    .help-private-note { display:flex; align-items:center; gap:8px; color:#77879a; font-size:10px; line-height:1.5; }
    .help-private-note svg { width:15px; height:15px; flex:0 0 15px; color:#458065; }
    .help-submit { display:inline-flex; align-items:center; justify-content:center; gap:9px; min-height:44px; padding:0 17px; border:0; border-radius:10px; color:#172e45; background:var(--help-gold); font-size:11px; font-weight:850; cursor:pointer; transition:transform .16s ease,background .16s ease; }
    .help-submit:hover { transform:translateY(-1px); background:#f4d995; }
    .help-submit svg { width:15px; height:15px; }
    .help-signin { padding:24px; text-align:center; }
    .help-signin-mark { display:grid; width:54px; height:54px; margin:0 auto 13px; place-items:center; border-radius:17px; color:#8b6628; background:#fbf3df; }
    .help-signin-mark svg { width:25px; height:25px; }
    .help-signin h3 { margin:0 0 7px; color:var(--help-ink); font-size:17px; font-weight:800; }
    .help-signin p { margin:0 auto 17px; color:var(--help-muted); font-size:11px; line-height:1.7; }
    .help-button-primary,.help-button-secondary { display:flex; align-items:center; justify-content:center; min-height:42px; margin-top:8px; padding:0 14px; border-radius:10px; font-size:11px; font-weight:800; text-decoration:none; transition:background .16s ease; }
    .help-button-primary { color:#172e45; background:var(--help-gold); }
    .help-button-primary:hover { background:#f4d995; }
    .help-button-secondary { border:1px solid #dfe6ed; color:#38516a; background:#fff; }
    .help-button-secondary:hover { background:#f5f8fa; }
    .help-info-panel { overflow:hidden; }
    .help-info-top { padding:23px; color:#fff; background:linear-gradient(140deg,#092945,#124466); }
    .help-info-top .help-label { color:var(--help-gold); }
    .help-info-top h3 { margin:7px 0 8px; color:#fff; font-size:18px; font-weight:800; }
    .help-info-top p { margin:0; color:#d0dce6; font-size:11px; line-height:1.7; }
    .help-info-list { display:grid; gap:0; padding:7px 19px; }
    .help-info-item { display:flex; gap:11px; padding:14px 3px; border-bottom:1px solid #edf1f4; }
    .help-info-item:last-child { border-bottom:0; }
    .help-info-item > span { display:grid; width:30px; height:30px; flex:0 0 30px; place-items:center; border-radius:9px; color:#9a722e; background:#faf4e6; }
    .help-info-item svg { width:15px; height:15px; }
    .help-info-item strong { display:block; margin:1px 0 3px; color:#304961; font-size:10px; }
    .help-info-item p { margin:0; color:#78889a; font-size:10px; line-height:1.55; }
    .help-info-bottom { margin:0 19px 19px; padding:13px; border:1px solid #e4ebef; border-radius:11px; color:#587087; background:#f7fafb; font-size:10px; line-height:1.65; }
    .help-community { margin-top:40px; }
    .help-community-heading { display:flex; align-items:flex-end; justify-content:space-between; gap:18px; margin-bottom:17px; }
    .help-community-heading h2 { margin:5px 0; color:var(--help-ink); font-size:23px; font-weight:850; letter-spacing:-.03em; }
    .help-community-heading p { margin:0; color:var(--help-muted); font-size:12px; }
    .help-community-count { padding:7px 11px; border-radius:999px; color:#805e20; background:#fbf3df; font-size:10px; font-weight:800; white-space:nowrap; }
    .help-request-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; }
    .help-request-card { position:relative; padding:19px; border:1px solid #e6ebf0; border-radius:15px; background:#fff; box-shadow:0 8px 22px rgba(10,38,65,.04); }
    .help-request-card::before { position:absolute; top:17px; bottom:17px; left:0; width:3px; border-radius:0 3px 3px 0; content:""; background:#edc875; }
    .help-request-meta { display:flex; align-items:center; gap:8px; color:#8b6628; font-size:9px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; }
    .help-request-card h3 { margin:10px 0 7px; color:var(--help-ink); font-size:15px; font-weight:800; }
    .help-request-card > p { margin:0; color:#65798d; font-size:11px; line-height:1.7; white-space:pre-line; }
    .help-request-contact { margin-top:13px; padding-top:11px; border-top:1px solid #edf0f3; color:#718297; font-size:10px; line-height:1.5; overflow-wrap:anywhere; }
    .help-empty { padding:35px 18px; border:1px dashed #cfd9e1; border-radius:15px; color:#75869a; background:rgba(255,255,255,.7); text-align:center; }
    .help-empty svg { width:30px; height:30px; margin:0 auto 9px; color:#b48b40; }
    .help-empty strong { display:block; margin-bottom:4px; color:#36506a; font-size:13px; }
    .help-pagination { margin-top:22px; }
    @media(max-width:800px) { .help-layout { grid-template-columns:minmax(0,1fr) 255px; gap:13px; } .help-form-panel { padding:20px; } .help-info-top { padding:19px; } .help-info-list { padding-right:14px; padding-left:14px; } }
    @media(max-width:680px) { .help-hero-inner { padding:34px 20px 27px; } .help-kicker { margin-top:21px; } .help-hero h1 { font-size:36px; } .help-hero-copy { font-size:13px; } .help-container { padding:25px 16px 45px; } .help-intro { display:block; } .help-intro h2 { font-size:22px; } .help-layout { grid-template-columns:1fr; } .help-form-grid { grid-template-columns:1fr; gap:13px; } .help-field-full { grid-column:auto; } .help-info-panel { display:grid; grid-template-columns:1fr; } .help-info-top { padding:20px; } .help-community { margin-top:32px; } .help-community-heading { align-items:flex-start; } .help-request-grid { grid-template-columns:1fr; } }
    @media(max-width:420px) { .help-form-footer { align-items:stretch; flex-direction:column; } .help-submit { width:100%; } .help-community-heading { flex-direction:column; } }
</style>
@endpush

@section('content')
<div class="help-page">
    <section class="help-hero">
        <div class="help-hero-inner">
            <nav class="help-breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('web.home') }}">Home</a><span aria-hidden="true">›</span><span>Community help</span>
            </nav>
            <span class="help-kicker">CARE &amp; COMMUNITY</span>
            <h1>We’re here to help carry the load.</h1>
            <p class="help-hero-copy">Ask for support, share a practical need, or find a way to stand with someone in our church family. Every request is handled with care.</p>
            <div class="help-hero-tags"><span>Prayer and pastoral care</span><span>Practical support</span><span>Community connection</span></div>
        </div>
    </section>

    <div class="help-container">
        <div class="help-intro">
            <div><span class="help-label">HOW CAN WE SUPPORT YOU?</span><h2>Request help or find a way to serve</h2><p>Send a request to the church team. Approved community needs are shared below so others can respond and support one another.</p></div>
        </div>

        <div class="help-layout">
            <section class="help-panel help-form-panel">
                @if(session('success'))
                    <div class="help-alert help-alert-success" role="status">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="help-alert help-alert-error" role="alert">
                        <strong>Please review the form:</strong>
                        <ul style="margin:6px 0 0;padding-left:17px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                @auth
                    <div class="help-form-head">
                        <span class="help-form-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21s-8-4.6-8-11a4.5 4.5 0 0 1 8-2.9A4.5 4.5 0 0 1 20 10c0 6.4-8 11-8 11Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M8.5 12h7m-3.5-3.5v7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span>
                        <div><h3>Tell us what you need</h3><p>Your request will be reviewed by the church team before it is shared with the wider community.</p></div>
                    </div>
                    <form action="{{ route('web.help.store') }}" method="POST">
                        @csrf
                        <div class="help-form-grid">
                            <div class="help-field help-field-full">
                                <label for="help-title">Request title <span class="help-required">*</span></label>
                                <input id="help-title" type="text" name="title" value="{{ old('title') }}" maxlength="255" required placeholder="A short summary of the support you need">
                                @error('title')<span class="help-field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="help-field help-field-full">
                                <label for="help-description">Tell us more <span class="help-required">*</span></label>
                                <textarea id="help-description" name="description" maxlength="3000" required placeholder="Share the details that will help us understand how to support you.">{{ old('description') }}</textarea>
                                @error('description')<span class="help-field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="help-field help-field-full">
                                <label for="help-contact">How can we reach you? <span class="help-required">*</span></label>
                                <input id="help-contact" type="text" name="contact_details" value="{{ old('contact_details') }}" maxlength="500" required autocomplete="tel" placeholder="Phone number, email, or another preferred contact">
                                @error('contact_details')<span class="help-field-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="help-form-footer">
                            <span class="help-private-note"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 10V7a5 5 0 0 1 10 0v3m-11 0h12a2 2 0 0 1 2 2v8H4v-8a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>Only approved requests are published for the community.</span>
                            <button class="help-submit" type="submit">Send help request <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                        </div>
                    </form>
                @else
                    <div class="help-signin">
                        <span class="help-signin-mark"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-8 9a8 8 0 0 1 16 0" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span>
                        <h3>Join the church community</h3>
                        <p>Sign in or create a free guest account to send a private help request and follow up with the church team.</p>
                        <a href="{{ route('web.guest.register') }}" class="help-button-primary">Create a free account</a>
                        <a href="{{ route('web.guest.login') }}" class="help-button-secondary">I already have an account</a>
                    </div>
                @endauth
            </section>

            <aside class="help-panel help-info-panel">
                <div class="help-info-top"><span class="help-label">YOU ARE NOT ALONE</span><h3>Care starts with listening.</h3><p>Our church community wants to walk alongside you with compassion, respect, and practical support.</p></div>
                <div class="help-info-list">
                    <div class="help-info-item"><span><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z" stroke="currentColor" stroke-width="1.7"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></span><div><strong>Your request is reviewed</strong><p>The church team checks each request before community publication.</p></div></div>
                    <div class="help-info-item"><span><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21s-8-4.5-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 6.5-8 11-8 11Z" stroke="currentColor" stroke-width="1.7"/></svg></span><div><strong>Support one another</strong><p>Browse approved requests and respond with care where you can.</p></div></div>
                    <div class="help-info-item"><span><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 5h16v11H8l-4 4V5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M8 9h8m-8 3h5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span><div><strong>We’ll follow up</strong><p>Include a contact method so the church team can reach you directly.</p></div></div>
                </div>
                <div class="help-info-bottom">If you or someone else is in immediate danger, contact local emergency services directly.</div>
            </aside>
        </div>

        <section class="help-community">
            <div class="help-community-heading">
                <div><span class="help-label">A CHURCH THAT CARES</span><h2>Community help requests</h2><p>Needs approved by the church team for the community to support.</p></div>
                @if($requests->total() > 0)<span class="help-community-count">{{ $requests->total() }} {{ \Illuminate\Support\Str::plural('request', $requests->total()) }}</span>@endif
            </div>
            @if($requests->count())
                <div class="help-request-grid">
                    @foreach($requests as $item)
                        <article class="help-request-card">
                            <div class="help-request-meta"><span>Community support</span><span aria-hidden="true">·</span><time datetime="{{ $item->created_at->toDateString() }}">{{ $item->created_at->diffForHumans() }}</time></div>
                            <h3>{{ $item->title }}</h3>
                            <p>{{ $item->description }}</p>
                            @if($item->contact_details)<div class="help-request-contact"><strong>Get in touch:</strong> {{ $item->contact_details }}</div>@endif
                        </article>
                    @endforeach
                </div>
                <div class="help-pagination">{{ $requests->links() }}</div>
            @else
                <div class="help-empty"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21s-8-4.5-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 6.5-8 11-8 11Z" stroke="currentColor" stroke-width="1.5"/><path d="M9 12h6m-3-3v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg><strong>No community requests just now</strong><span>When a request is approved, it will appear here.</span></div>
            @endif
        </section>
    </div>
</div>
@endsection
