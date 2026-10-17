@extends('theme::layout')

@section('title', 'Contact Us | IGM Church')
@section('meta_description', 'Contact International Gospel Ministries in Migori, request prayer, or plan a Sunday visit.')

@push('styles')
<style>
    .igm-contact{--contact-navy:#082846;--contact-deep:#061d34;--contact-gold:#d8ad5d;--contact-ink:#172e45;--contact-muted:#68798a;--contact-paper:#f7f7f4;background:var(--contact-paper);color:var(--contact-ink)}
    .igm-contact *{box-sizing:border-box}.contact-wrap{width:min(1160px,calc(100% - 40px));margin:0 auto}.contact-hero{position:relative;overflow:hidden;background:radial-gradient(ellipse at 85% 20%,rgba(216,173,93,.24),transparent 30%),linear-gradient(120deg,var(--contact-deep),var(--contact-navy));color:#fff;padding:66px 0 54px}.contact-hero:after{content:"";position:absolute;width:360px;height:360px;right:-140px;bottom:-245px;border:1px solid rgba(240,208,138,.25);border-radius:50%;box-shadow:0 0 0 32px rgba(240,208,138,.045),0 0 0 65px rgba(240,208,138,.03)}.contact-hero-inner{position:relative;z-index:1;max-width:760px}.contact-kicker,.contact-label{display:inline-flex;align-items:center;gap:9px;color:#f0d08a;font-size:10px;font-weight:850;letter-spacing:.16em;text-transform:uppercase}.contact-kicker:before{content:"";width:25px;height:1px;background:currentColor}.contact-hero h1{margin:15px 0 12px;color:#fff;font-size:clamp(38px,6vw,64px);line-height:1.03;letter-spacing:-.045em}.contact-hero p{max-width:650px;margin:0;color:rgba(255,255,255,.77);font-size:15px;line-height:1.8}.contact-crumb{display:flex;gap:10px;align-items:center;margin-top:24px;color:rgba(255,255,255,.62);font-size:12px}.contact-crumb a{color:#fff;text-decoration:none}.contact-crumb a:hover{color:#f0d08a}
    .contact-content{padding:42px 0 78px}.contact-section-kicker{margin:0 0 8px;color:#a27832;font-size:10px;font-weight:850;letter-spacing:.15em;text-transform:uppercase}.contact-section-title{margin:0;color:var(--contact-navy);font-size:clamp(25px,3.2vw,35px);line-height:1.15;letter-spacing:-.035em}.contact-section-copy{margin:9px 0 0;color:var(--contact-muted);font-size:13px;line-height:1.7}.contact-main-grid{display:grid;grid-template-columns:minmax(0,1.1fr) minmax(320px,.9fr);gap:22px;align-items:start;margin-top:24px}.contact-panel{background:#fff;border:1px solid #e6e8e8;border-radius:17px;box-shadow:0 9px 28px rgba(8,40,70,.055)}.contact-form-panel{padding:clamp(22px,3.2vw,34px)}.contact-form-heading{margin-bottom:23px}.contact-form-heading h2{margin:0;color:var(--contact-navy);font-size:22px;letter-spacing:-.025em}.contact-form-heading p{margin:7px 0 0;color:var(--contact-muted);font-size:13px;line-height:1.6}.contact-alert{padding:13px 15px;border-radius:10px;margin-bottom:18px;font-size:13px;line-height:1.55}.contact-alert-success{border:1px solid #bde4d0;background:#eefaf3;color:#17603b}.contact-alert-error{border:1px solid #f0c8c4;background:#fff4f2;color:#9b3932}.contact-alert ul{margin:0;padding-left:19px}.contact-form{display:grid;gap:16px}.contact-field label{display:block;margin-bottom:7px;color:#334c62;font-size:12px;font-weight:750}.contact-field label span{color:#c5544d}.contact-field input,.contact-field textarea{display:block;width:100%;border:1px solid #dce2e6;border-radius:10px;background:#fff;padding:12px 13px;color:var(--contact-ink);font:inherit;font-size:13px;outline:none;transition:border-color .18s,box-shadow .18s}.contact-field input{min-height:46px}.contact-field textarea{min-height:138px;resize:vertical;line-height:1.6}.contact-field input:focus,.contact-field textarea:focus{border-color:#c39a4c;box-shadow:0 0 0 3px rgba(216,173,93,.16)}.contact-field input::placeholder,.contact-field textarea::placeholder{color:#9ba7b0}.contact-submit{display:flex;justify-content:space-between;align-items:center;gap:12px;width:100%;min-height:49px;border:0;border-radius:10px;background:var(--contact-navy);padding:0 17px;color:#fff;font:inherit;font-size:13px;font-weight:800;cursor:pointer;transition:background .2s,transform .2s}.contact-submit:hover{background:#10466d;transform:translateY(-1px)}.contact-submit span:last-child{color:#f0d08a;font-size:18px}.contact-form-note{margin:0;color:#8794a1;font-size:11px;line-height:1.6}
    .contact-side{display:grid;gap:16px}.contact-info-panel{padding:23px}.contact-info-heading{display:flex;align-items:center;gap:12px;margin-bottom:17px}.contact-icon{display:grid;place-items:center;flex:0 0 40px;height:40px;border-radius:12px;background:#f5f0e4;color:#9a712e}.contact-icon svg{width:19px;height:19px}.contact-info-heading h2{margin:0;color:var(--contact-navy);font-size:16px}.contact-info-heading p{margin:3px 0 0;color:var(--contact-muted);font-size:11px}.contact-info-list{display:grid;gap:12px}.contact-info-item{display:flex;gap:10px;align-items:flex-start;color:#52677a;font-size:12px;line-height:1.6}.contact-info-item svg{width:15px;height:15px;flex:none;margin-top:2px;color:#a77a2d}.contact-info-item a{color:#244762;text-decoration:none;font-weight:700;overflow-wrap:anywhere}.contact-info-item a:hover{color:#a27832;text-decoration:underline}.contact-phone-list{display:grid;gap:7px}.contact-social-links{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:13px}.contact-social-link{display:flex;align-items:center;gap:8px;min-height:39px;border:1px solid #e8ebec;border-radius:9px;padding:7px 10px;color:#415a6f;text-decoration:none;font-size:11px;font-weight:700;transition:background .18s,border .18s}.contact-social-link:hover{border-color:#d9bd7d;background:#fbf7ed}.contact-social-mark{display:grid;place-items:center;width:21px;height:21px;border-radius:7px;background:#edf2f5;color:#26445d;font-size:10px;font-weight:900}.contact-service-panel{padding:23px;background:linear-gradient(145deg,#fff,#faf8f2)}.contact-service-panel h2{margin:0;color:var(--contact-navy);font-size:16px}.contact-service-panel>p{margin:5px 0 15px;color:var(--contact-muted);font-size:11px}.contact-service-list{display:grid;gap:9px}.contact-service-row{display:grid;grid-template-columns:88px 1fr;gap:11px;align-items:center;border-top:1px solid #e9e8e2;padding-top:9px}.contact-service-time{color:#97702e;font-size:11px;font-weight:850;white-space:nowrap}.contact-service-name{color:#344d62;font-size:12px;font-weight:700}
    .contact-prayer{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:28px;align-items:center;margin-top:28px;padding:clamp(23px,4vw,38px);overflow:hidden;border-radius:18px;background:radial-gradient(ellipse at 95% 15%,rgba(216,173,93,.17),transparent 34%),linear-gradient(120deg,var(--contact-deep),var(--contact-navy));color:#fff}.contact-prayer .contact-label{margin-bottom:9px}.contact-prayer h2{margin:0 0 13px;color:#fff;font-size:clamp(22px,3vw,30px);letter-spacing:-.03em}.contact-prayer blockquote{margin:0;color:rgba(255,255,255,.82);font-size:14px;line-height:1.8}.contact-prayer blockquote strong{display:block;margin-top:8px;color:#f0d08a;font-size:13px}.contact-prayer-mark{display:grid;place-items:center;width:86px;height:86px;border:1px solid rgba(240,208,138,.45);border-radius:50%;color:#f0d08a;font-size:39px;font-family:Georgia,serif}
    .contact-location-grid{display:grid;grid-template-columns:.82fr 1.18fr;gap:20px;margin-top:28px}.contact-location-copy{padding:25px}.contact-location-copy h2{margin:0;color:var(--contact-navy);font-size:21px}.contact-location-copy p{margin:9px 0 18px;color:var(--contact-muted);font-size:13px;line-height:1.7}.contact-location-address{display:flex;gap:11px;align-items:flex-start;border-top:1px solid #edf0f1;padding-top:15px;color:#405b71;font-size:12px;font-weight:700;line-height:1.6}.contact-location-address svg{width:17px;height:17px;flex:none;color:#a77a2d}.contact-map{min-height:300px;overflow:hidden;border:1px solid #e6e8e8;border-radius:17px;background:#e8eceb;box-shadow:0 9px 28px rgba(8,40,70,.055)}.contact-map iframe{display:block;width:100%;height:100%;min-height:300px;border:0}
    .contact-community{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:24px;align-items:center;margin-top:28px;padding:clamp(22px,3vw,30px);border:1px solid #e6e8e8;border-radius:17px;background:#fff;box-shadow:0 9px 28px rgba(8,40,70,.045)}.contact-community h2{margin:0;color:var(--contact-navy);font-size:22px;letter-spacing:-.025em}.contact-community p{max-width:680px;margin:8px 0 0;color:var(--contact-muted);font-size:13px;line-height:1.7}.contact-community-actions{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:9px}.contact-community-button{display:inline-flex;align-items:center;justify-content:center;min-height:42px;border:1px solid #dce2e5;border-radius:9px;padding:0 14px;color:#36546b;text-decoration:none;font-size:11px;font-weight:800;transition:background .18s,border .18s}.contact-community-button:hover{border-color:#d8bd80;background:#fbf7ed}.contact-community-button.primary{border-color:var(--contact-gold);background:var(--contact-gold);color:var(--contact-deep)}.contact-community-button.primary:hover{background:#efcc7b}
    @media(max-width:850px){.contact-main-grid{grid-template-columns:1fr}.contact-side{grid-template-columns:1fr 1fr}.contact-location-grid{grid-template-columns:1fr}.contact-map,.contact-map iframe{min-height:330px}}
    @media(max-width:600px){.contact-wrap{width:min(100% - 28px,1160px)}.contact-hero{padding:50px 0 41px}.contact-content{padding:28px 0 55px}.contact-side{grid-template-columns:1fr}.contact-prayer{grid-template-columns:1fr}.contact-prayer-mark{width:58px;height:58px;font-size:28px;grid-row:1}.contact-community{grid-template-columns:1fr}.contact-community-actions{justify-content:flex-start}.contact-service-row{grid-template-columns:82px 1fr}.contact-map,.contact-map iframe{min-height:280px}}
</style>
@endpush

@section('content')
<main class="igm-contact">
    <section class="contact-hero">
        <div class="contact-wrap contact-hero-inner">
            <span class="contact-kicker">International Gospel Ministries</span>
            <h1>We’d Love to Hear From You.</h1>
            <p>Do you have a prayer request, a question, or something you would like to know about us? Would you like to visit? Our doors and hearts are open.</p>
            <nav class="contact-crumb" aria-label="Breadcrumb"><a href="{{ route('web.home') }}">Home</a><span>/</span><span>Contact us</span></nav>
        </div>
    </section>

    @if($topwidget->isNotEmpty())
        <div class="contact-wrap" style="padding-top:24px">
            @foreach($topwidget as $widget){!! $widget->content !!}@endforeach
        </div>
    @endif

    <div class="contact-wrap contact-content">
        <header>
            <p class="contact-section-kicker">We’re here for you</p>
            <h2 class="contact-section-title">Get in touch with IGM</h2>
            <p class="contact-section-copy">Reach our team, ask for prayer, or plan a visit to our Migori headquarters.</p>
        </header>

        <div class="contact-main-grid">
            <section class="contact-panel contact-form-panel" aria-labelledby="contact-form-title">
                <div class="contact-form-heading"><h2 id="contact-form-title">Send us a message</h2><p>Share your question or prayer request. Our team will be glad to hear from you.</p></div>

                @if(session('success'))
                    <div class="contact-alert contact-alert-success" role="status">✓ {{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="contact-alert contact-alert-error" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif

                <form id="contact-form" class="contact-form" action="{{ route('web.contact.store') }}" method="POST" @if(config('settings.contact_captcha_status') == '1') data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY', '') }}" @endif>
                    @csrf
                    <div class="contact-field">
                        <label for="contact-fullname">Full name <span>*</span></label>
                        <input id="contact-fullname" type="text" name="fullname" value="{{ old('fullname') }}" autocomplete="name" maxlength="255" required placeholder="Your name">
                    </div>
                    <div class="contact-field">
                        <label for="contact-email">Email address <span>*</span></label>
                        <input id="contact-email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" required placeholder="you@example.com">
                    </div>
                    <div class="contact-field">
                        <label for="contact-mobile">Phone number <span style="color:#8794a1;font-weight:500">(optional)</span></label>
                        <input id="contact-mobile" type="tel" name="mobile" value="{{ old('mobile') }}" autocomplete="tel" maxlength="30" placeholder="+254">
                    </div>
                    <div class="contact-field">
                        <label for="contact-query">Your message or prayer request <span>*</span></label>
                        <textarea id="contact-query" name="query" rows="6" maxlength="3000" required placeholder="How can we help or pray with you?">{{ old('query') }}</textarea>
                    </div>
                    @if(config('settings.contact_captcha_status') == '1')
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                    @endif
                    <button class="contact-submit" type="submit"><span>Send your message</span><span aria-hidden="true">→</span></button>
                    <p class="contact-form-note">Your message is shared with the church team so we can respond and pray with you.</p>
                </form>
            </section>

            <aside class="contact-side" aria-label="Church contact details">
                <section class="contact-panel contact-info-panel">
                    <div class="contact-info-heading">
                        <span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></span>
                        <div><h2>Headquarters</h2><p>Visit us in Migori</p></div>
                    </div>
                    <div class="contact-info-list">
                        <div class="contact-info-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg><span>Migori HQ, Town opposite Teachers Training College (TTC), Migori, Kenya.</span></div>
                    </div>
                </section>

                <section class="contact-panel contact-info-panel">
                    <div class="contact-info-heading">
                        <span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 11.2 19a19.4 19.4 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 8.9a16 16 0 0 0 6 6l1.2-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.8 2.1Z"/></svg></span>
                        <div><h2>Prayer &amp; inquiries</h2><p>Call or email our team</p></div>
                    </div>
                    <div class="contact-info-list">
                        <div class="contact-info-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 11.2 19a19.4 19.4 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 8.9a16 16 0 0 0 6 6l1.2-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.8 2.1Z"/></svg><span class="contact-phone-list"><a href="tel:+254722696239">+254 722 696 239</a><a href="tel:+254700088222">+254 700 088 222</a></span></div>
                        <div class="contact-info-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg><a href="mailto:info@internationalgospelministries.com">info@internationalgospelministries.com</a></div>
                    </div>
                </section>

                <section class="contact-panel contact-service-panel">
                    <h2>Order of Sunday services</h2><p>We look forward to worshipping with you.</p>
                    <div class="contact-service-list">
                        <div class="contact-service-row"><span class="contact-service-time">7:00–8:00 AM</span><span class="contact-service-name">Morning Intercession</span></div>
                        <div class="contact-service-row"><span class="contact-service-time">9:00–10:00 AM</span><span class="contact-service-name">First Service Teachings</span></div>
                        <div class="contact-service-row"><span class="contact-service-time">10:00 AM–1:00 PM</span><span class="contact-service-name">Main Celebration Service</span></div>
                    </div>
                </section>

                <section class="contact-panel contact-info-panel">
                    <div class="contact-info-heading">
                        <span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/><path d="M9 12s2 2 3 2 3-3 3-3"/></svg></span>
                        <div><h2>Connect with us</h2><p>Follow IGM and Bishop Walter Ogwada</p></div>
                    </div>
                    <div class="contact-social-links">
                        <a class="contact-social-link" href="https://www.youtube.com/results?search_query=Bishop+Walter+Ogwada" target="_blank" rel="noopener noreferrer"><span class="contact-social-mark">▶</span><span>YouTube<br><small>Bishop Walter Ogwada</small></span></a>
                        <a class="contact-social-link" href="https://www.facebook.com/BishopWalterOgwada" target="_blank" rel="noopener noreferrer"><span class="contact-social-mark">f</span><span>Facebook<br><small>BishopWalterOgwada</small></span></a>
                        <a class="contact-social-link" href="https://www.tiktok.com/@BishopWalterOgwada" target="_blank" rel="noopener noreferrer"><span class="contact-social-mark">♪</span><span>TikTok<br><small>BishopWalterOgwada</small></span></a>
                        <a class="contact-social-link" href="https://www.instagram.com/BishopWalterOgwada/" target="_blank" rel="noopener noreferrer"><span class="contact-social-mark">◎</span><span>Instagram<br><small>BishopWalterOgwada</small></span></a>
                    </div>
                </section>
            </aside>
        </div>

        <section class="contact-prayer" aria-labelledby="contact-prayer-title">
            <div><span class="contact-label">Prayer &amp; care</span><h2 id="contact-prayer-title">How can we pray for you?</h2><blockquote>“Do not be anxious about anything, but in every situation, by prayer and petition, with thanksgiving, present your requests to God.” – Philippians 4:6<strong>“We are standing in faith with you.” — IGM Prayer Team</strong></blockquote></div>
            <div class="contact-prayer-mark" aria-hidden="true">✦</div>
        </section>

        <section class="contact-location-grid" aria-labelledby="contact-map-title">
            <div class="contact-panel contact-location-copy">
                <p class="contact-section-kicker">Find us in Migori</p><h2 id="contact-map-title">Plan your visit</h2>
                <p>Join us at our headquarters for a Sunday service. Our church family will be happy to welcome you.</p>
                <div class="contact-location-address"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg><span>Migori HQ, Town opposite Teachers Training College (TTC), Migori, Kenya.</span></div>
            </div>
            <div class="contact-map"><iframe title="Map showing IGM Church in Migori" src="https://maps.google.com/maps?q=WFG9%2BQ5C%2C%20Migori&t=m&z=15&output=embed&iwloc=near" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div>
        </section>

        <section class="contact-community" aria-labelledby="contact-community-title">
            <div><p class="contact-section-kicker">Stay connected</p><h2 id="contact-community-title">Join our community</h2><p>Connect with International Gospel Ministries, worship with us, and keep up with our services, events, and community activities. To receive ministry updates and invitations by email, contact us at <a href="mailto:info@internationalgospelministries.com" style="color:#8c6728;font-weight:750">info@internationalgospelministries.com</a>.</p></div>
            <div class="contact-community-actions"><a class="contact-community-button" href="{{ route('web.ministries') }}">Explore ministries</a><a class="contact-community-button" href="{{ route('web.events') }}">See events</a><a class="contact-community-button primary" href="#contact-form">Get started</a></div>
        </section>
    </div>

    @if($bottomwidget->isNotEmpty())
        <div class="contact-wrap" style="padding-bottom:30px">
            @foreach($bottomwidget as $widget){!! $widget->content !!}@endforeach
        </div>
    @endif
</main>
@endsection

@push('scripts')
@php $recaptchaKey = env('GOOGLE_RECAPTCHA_KEY', ''); @endphp
@if($recaptchaKey && config('settings.contact_captcha_status') == '1')
    <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaKey }}"></script>
    <script>
        (function () {
            const form = document.getElementById('contact-form');
            const tokenInput = document.getElementById('g-recaptcha-response');
            if (!form || !tokenInput || !window.grecaptcha) return;
            form.addEventListener('submit', function (event) {
                if (tokenInput.value) return;
                event.preventDefault();
                grecaptcha.ready(function () {
                    grecaptcha.execute(form.dataset.sitekey, { action: 'contact' }).then(function (token) {
                        tokenInput.value = token;
                        form.submit();
                    });
                });
            });
        })();
    </script>
@endif
@endpush
