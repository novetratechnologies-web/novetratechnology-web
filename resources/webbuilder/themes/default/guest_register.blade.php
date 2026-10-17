@extends('theme::layout')

@section('title', 'Create your IGM account')

@push('styles')
<style>
    .igr{--navy:#082848;--gold:#d8a83d;--ink:#14263d;--muted:#687991;--line:#e5eaf0;max-width:1120px;margin:0 auto;padding:44px 20px 72px;color:var(--ink);font-family:inherit}.igr *{box-sizing:border-box}.igr-hero{position:relative;overflow:hidden;border-radius:24px;background:linear-gradient(110deg,#06213d 0%,#103b62 64%,#1c5579 100%);padding:35px 42px;color:#fff}.igr-hero:after{content:"";position:absolute;width:300px;height:300px;right:-75px;top:-150px;border:1px solid #ffffff20;border-radius:50%;box-shadow:0 0 0 30px #ffffff09,0 0 0 70px #ffffff08}.igr-kicker{color:#f1c75f;font-size:11px;font-weight:800;letter-spacing:.17em;text-transform:uppercase}.igr-hero h1{position:relative;z-index:1;margin:9px 0 7px;color:#fff;font-size:clamp(29px,4vw,43px);font-weight:850;letter-spacing:-.04em;line-height:1.12}.igr-hero p{position:relative;z-index:1;max-width:650px;margin:0;color:#d8e4ef;font-size:14px;line-height:1.7}.igr-grid{display:grid;grid-template-columns:minmax(260px,.85fr) minmax(0,1.35fr);gap:22px;align-items:start;margin-top:22px}.igr-side,.igr-form{border:1px solid var(--line);border-radius:19px;background:#fff;box-shadow:0 10px 30px #102d4b0a}.igr-side{padding:25px}.igr-side h2{margin:8px 0;color:#102c4c;font-size:21px;font-weight:820;line-height:1.25}.igr-side>p{margin:0;color:var(--muted);font-size:13px;line-height:1.75}.igr-perks{display:grid;gap:13px;margin:23px 0}.igr-perk{display:flex;gap:11px;align-items:flex-start}.igr-icon{display:grid;width:34px;height:34px;flex:none;place-items:center;border-radius:11px;background:#fff5dc;color:#ae7d18;font-size:15px}.igr-perk strong{display:block;color:#203752;font-size:12px;font-weight:800}.igr-perk span{display:block;margin-top:3px;color:#78869a;font-size:11px;line-height:1.5}.igr-scripture{border-left:3px solid var(--gold);border-radius:0 10px 10px 0;background:#fbf8f0;padding:13px 14px;color:#55677d;font-size:12px;font-style:italic;line-height:1.6}.igr-scripture b{display:block;margin-top:6px;color:#9a721d;font-size:10px;font-style:normal}.igr-form{overflow:hidden}.igr-form-head{border-bottom:1px solid var(--line);padding:22px 26px 17px}.igr-form-head h2{margin:0;color:#112d4c;font-size:19px;font-weight:850}.igr-form-head p{margin:5px 0 0;color:#78869a;font-size:12px}.igr-form-body{padding:23px 26px 26px}.igr-flash{margin-bottom:15px;border:1px solid #bbebd0;border-radius:11px;background:#effaf3;padding:12px;color:#216a43;font-size:12px}.igr-errors{margin-bottom:16px;border:1px solid #ffd1ce;border-radius:11px;background:#fff5f4;padding:12px 14px;color:#a6332e;font-size:12px}.igr-errors ul{margin:5px 0 0;padding-left:19px}.igr-fields{display:grid;grid-template-columns:1fr 1fr;gap:14px 13px}.igr-field{min-width:0}.igr-field-full{grid-column:1/-1}.igr-label{display:block;margin-bottom:6px;color:#394b61;font-size:11px;font-weight:780}.igr-label i{color:#bd4040;font-style:normal}.igr-input,.igr-select{width:100%;min-height:45px;border:1px solid #dce3eb;border-radius:10px;background:#fff;padding:10px 12px;color:#20334a;font-family:inherit;font-size:13px;font-weight:500;outline:none;transition:.16s}.igr-input:focus,.igr-select:focus{border-color:#5881a4;box-shadow:0 0 0 3px #184c7614}.igr-input::placeholder{color:#a2adba}.igr-hint{display:block;margin-top:5px;color:#8793a3;font-size:10px;line-height:1.5}.igr-phone{display:grid;grid-template-columns:minmax(145px,.8fr) minmax(0,1.3fr);gap:9px}.igr-submit{display:flex;width:100%;min-height:48px;align-items:center;justify-content:center;gap:9px;margin-top:20px;border:0;border-radius:11px;background:linear-gradient(100deg,#d4a03a,#f0c35a);box-shadow:0 7px 16px #bd8c2a25;color:#142944;font-size:13px;font-weight:850;cursor:pointer;transition:.18s}.igr-submit:hover{transform:translateY(-1px);filter:saturate(1.08)}.igr-login{margin:16px 0 0;text-align:center;color:#77869a;font-size:12px}.igr-login a{color:#174a75;font-weight:800;text-decoration:none}.igr-login a:hover{text-decoration:underline}.igr-branch-note{display:flex;gap:9px;align-items:flex-start;margin-top:9px;border-radius:9px;background:#f4f7fa;padding:10px;color:#738196;font-size:10px;line-height:1.5}.igr-branch-note i{margin-top:2px;color:#a77b24}@media(max-width:760px){.igr{padding:25px 14px 46px}.igr-hero{padding:27px 23px;border-radius:18px}.igr-grid{grid-template-columns:1fr;gap:15px}.igr-side{padding:20px}.igr-side h2{font-size:18px}.igr-perks{grid-template-columns:1fr 1fr;gap:14px}.igr-form-head{padding:19px 19px 15px}.igr-form-body{padding:19px}.igr-fields{gap:12px}}@media(max-width:480px){.igr-fields{grid-template-columns:1fr}.igr-field-full{grid-column:auto}.igr-perks{grid-template-columns:1fr}.igr-phone{grid-template-columns:1fr}.igr-hero p{font-size:13px}}
</style>
@endpush

@section('content')
<main class="igr">
    <header class="igr-hero">
        <span class="igr-kicker">International Gospel Ministries · Migori</span>
        <h1>There’s a place for you here.</h1>
        <p>Create your free guest account to connect with IGM, share prayer requests, follow church updates, and receive content from the branch you attend.</p>
    </header>

    @if($blocked ?? false)
        @include('theme::_access_blocked', ['blockedTitle' => 'Guest Registration is Currently Closed', 'blockedMessage' => 'New guest registrations are not being accepted at this time. Please contact the church office to be added manually.'])
    @else
    <div class="igr-grid">
        <aside class="igr-side">
            <span class="igr-kicker">Welcome to IGM</span>
            <h2>Grow in faith. Stay connected.</h2>
            <p>Your account helps us welcome and serve you well. You do not need to be a member to register.</p>
            <div class="igr-perks">
                <div class="igr-perk"><span class="igr-icon"><i class="fa-solid fa-hands-praying"></i></span><div><strong>Share prayer requests</strong><span>Let our prayer team stand with you.</span></div></div>
                <div class="igr-perk"><span class="igr-icon"><i class="fa-solid fa-bell"></i></span><div><strong>Get relevant updates</strong><span>See services, events, and news from your branch.</span></div></div>
                <div class="igr-perk"><span class="igr-icon"><i class="fa-solid fa-play"></i></span><div><strong>Watch and read</strong><span>Follow sermons, livestreams, and church stories.</span></div></div>
                <div class="igr-perk"><span class="igr-icon"><i class="fa-solid fa-people-group"></i></span><div><strong>Find your community</strong><span>Connect with people and ministries at your campus.</span></div></div>
            </div>
            <div class="igr-scripture">“Therefore welcome one another as Christ has welcomed you, for the glory of God.”<b>Romans 15:7</b></div>
        </aside>

        <section class="igr-form">
            <div class="igr-form-head"><h2>Create your account</h2><p>All fields marked <i style="color:#bd4040;font-style:normal">*</i> are required.</p></div>
            <div class="igr-form-body">
                @if(session('auth_notice'))<div class="igr-flash">{{ session('auth_notice') }}</div>@endif
                @if($errors->any())<div class="igr-errors"><strong>Please review the details below.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

                <form id="guest-register-form" method="POST" action="{{ route('web.guest.register.store') }}" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}">
                    @csrf
                    <div class="igr-fields">
                        <div class="igr-field"><label class="igr-label" for="firstname">First name <i>*</i></label><input class="igr-input" id="firstname" type="text" name="firstname" value="{{ old('firstname') }}" autocomplete="given-name" maxlength="50" required placeholder="Your first name"></div>
                        <div class="igr-field"><label class="igr-label" for="lastname">Last name</label><input class="igr-input" id="lastname" type="text" name="lastname" value="{{ old('lastname') }}" autocomplete="family-name" maxlength="50" placeholder="Your last name"></div>
                        <div class="igr-field"><label class="igr-label" for="gender">Gender <i>*</i></label><select class="igr-select" id="gender" name="gender" required><option value="">Choose an option</option><option value="male" @selected(old('gender')==='male')>Male</option><option value="female" @selected(old('gender')==='female')>Female</option><option value="other" @selected(old('gender')==='other')>Prefer not to say</option></select></div>
                        <div class="igr-field"><label class="igr-label" for="date_of_birth">Date of birth <i>*</i></label><input class="igr-input" id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" autocomplete="bday" max="{{ now()->subDay()->format('Y-m-d') }}" required></div>
                        <div class="igr-field igr-field-full"><label class="igr-label" for="branch_id">Your IGM branch <i>*</i></label><select class="igr-select" id="branch_id" name="branch_id" required><option value="">Select the church branch you attend</option>@foreach($branches as $branch)<option value="{{ $branch->id }}" data-address="{{ $branch->address }}" @selected((string)old('branch_id', $defaultBranchId)===(string)$branch->id)>{{ $branch->name }}{{ $branch->is_headquarters ? ' · Headquarters' : '' }}{{ !$branch->is_active ? ' · Inactive' : '' }}</option>@endforeach</select><div class="igr-branch-note"><i class="fa-solid fa-location-dot"></i><span id="igr-branch-address">Choose your usual campus. Your feed will prioritize that branch while still showing church-wide updates. Headquarters can manage information across all branches.</span></div></div>
                        <div class="igr-field igr-field-full"><label class="igr-label" for="mobile_no">Mobile number <i>*</i></label><div class="igr-phone"><select class="igr-select" name="mobile_country_code" id="mobile_country_code" autocomplete="tel-country-code" required>@forelse($countries as $country)@php($prefix=ltrim((string)$country->tel_prefix,'+'))<option value="{{ $prefix }}" data-region="{{ strtoupper($country->short_name ?? '') }}" @selected(ltrim((string)old('mobile_country_code',$defaultCountryCode),'+')===$prefix)>{{ $country->name }} (+{{ $prefix }})</option>@empty<option value="254" selected>Kenya (+254)</option>@endforelse</select><input class="igr-input" id="mobile_no" type="tel" name="mobile_no" value="{{ old('mobile_no') }}" autocomplete="tel-national" inputmode="tel" maxlength="30" required placeholder="e.g. 0712 345 678"></div><span class="igr-hint">Enter your number as you normally dial it in the selected country. We’ll validate its numbering rules.</span></div>
                        <div class="igr-field igr-field-full"><label class="igr-label" for="email">Email address <i>*</i></label><input class="igr-input" id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" maxlength="150" required placeholder="you@example.com"></div>
                        <div class="igr-field"><label class="igr-label" for="password">Password <i>*</i></label><input class="igr-input" id="password" type="password" name="password" autocomplete="new-password" minlength="8" required placeholder="At least 8 characters"></div>
                        <div class="igr-field"><label class="igr-label" for="password_confirmation">Confirm password <i>*</i></label><input class="igr-input" id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required placeholder="Enter it again"></div>
                    </div>
                    @if(config('settings.guest_register_captcha_status')==="1")<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">@error('g-recaptcha-response')<span class="igr-hint" style="color:#bd4040">{{ $message }}</span>@enderror @endif
                    <button class="igr-submit" type="submit">Create my account <i class="fa-solid fa-arrow-right"></i></button>
                </form>
                <p class="igr-login">Already registered? <a href="{{ route('web.guest.login') }}">Sign in to your account</a></p>
            </div>
        </section>
    </div>
    @endif
</main>
@endsection

@push('scripts')
@php($recaptchaKey = env('GOOGLE_RECAPTCHA_KEY', ''))
@if($recaptchaKey && config('settings.guest_register_captcha_status')==="1")
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaKey }}"></script>
<script>grecaptcha.ready(function(){const form=document.getElementById('guest-register-form');if(!form)return;form.addEventListener('submit',function(e){e.preventDefault();grecaptcha.execute(form.dataset.sitekey,{action:'guest_register'}).then(function(token){document.getElementById('g-recaptcha-response').value=token;form.submit();});});});</script>
@endif
<script>
(()=>{const select=document.getElementById('branch_id'),note=document.getElementById('igr-branch-address');if(select&&note){const update=()=>{const option=select.options[select.selectedIndex],address=option?.dataset.address;note.textContent=address?`${address} · Your feed will prioritize this branch and include church-wide updates.`:'Choose your usual campus. Your feed will prioritize that branch while still showing church-wide updates. Headquarters can manage information across all branches.';};select.addEventListener('change',update);update();}})();
</script>
@endpush
