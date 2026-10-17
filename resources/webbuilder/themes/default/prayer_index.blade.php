@extends('theme::layout')

@section('title', 'Prayer | International Gospel Ministries')
@section('meta_description', 'Share a prayer request with International Gospel Ministries or stand with someone in prayer.')

@push('styles')
<style>
    .prayer-page{--prayer-navy:#082846;--prayer-deep:#061c31;--prayer-gold:#e7bf69;--prayer-ink:#142a40;--prayer-muted:#65778a;--prayer-line:#e5ebf0;--prayer-surface:#fff;background:#f7f8fa;color:var(--prayer-ink)}
    .prayer-wrap{width:min(1190px,calc(100% - 40px));margin:0 auto}
    .prayer-hero{position:relative;overflow:hidden;color:#fff;background:radial-gradient(ellipse at 88% 12%,rgba(231,191,105,.21),transparent 30%),linear-gradient(115deg,var(--prayer-deep),#0d3555 58%,#123e5d)}
    .prayer-hero:after{position:absolute;inset:auto -80px -220px auto;width:510px;height:510px;border:1px solid rgba(231,191,105,.23);border-radius:50%;content:"";box-shadow:0 0 0 35px rgba(231,191,105,.035),0 0 0 75px rgba(231,191,105,.025)}
    .prayer-hero-inner{position:relative;z-index:1;display:grid;grid-template-columns:minmax(0,1fr) 250px;align-items:center;gap:40px;min-height:300px;padding:54px 0}
    .prayer-eyebrow,.prayer-kicker{display:inline-flex;align-items:center;gap:9px;color:#b38232;font-size:11px;font-weight:850;letter-spacing:.14em;text-transform:uppercase}
    .prayer-eyebrow{color:var(--prayer-gold)}
    .prayer-eyebrow:before{width:22px;height:2px;background:currentColor;content:""}
    .prayer-hero h1{max-width:720px;margin:14px 0 12px;color:#fff;font-size:clamp(38px,5.4vw,62px);font-weight:850;line-height:1.03;letter-spacing:-.045em}
    .prayer-hero p{max-width:630px;margin:0;color:rgba(255,255,255,.79);font-size:16px;line-height:1.75}
    .prayer-hero-actions{display:flex;flex-wrap:wrap;gap:11px;margin-top:24px}
    .prayer-button{display:inline-flex;align-items:center;justify-content:center;gap:9px;min-height:46px;padding:0 18px;border:1px solid transparent;border-radius:999px;font-size:13px;font-weight:800;text-decoration:none;transition:transform .18s,background .18s,border-color .18s}
    .prayer-button:hover{transform:translateY(-1px)}
    .prayer-button-gold{color:#17283a;background:var(--prayer-gold)}
    .prayer-button-outline{border-color:rgba(255,255,255,.4);color:#fff;background:rgba(255,255,255,.06)}
    .prayer-hero-mark{display:grid;place-items:center;width:185px;height:185px;margin:auto;border:1px solid rgba(231,191,105,.5);border-radius:50%;color:var(--prayer-gold);background:rgba(255,255,255,.04);box-shadow:0 0 0 15px rgba(255,255,255,.025),inset 0 0 35px rgba(231,191,105,.08)}
    .prayer-hero-mark svg{width:70px;height:70px}
    .prayer-stats{position:relative;z-index:2;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:15px;width:min(610px,100%);margin-top:-27px}
    .prayer-stat{display:flex;align-items:center;gap:14px;min-height:78px;padding:16px 19px;border:1px solid var(--prayer-line);border-radius:13px;background:#fff;box-shadow:0 12px 30px rgba(9,32,53,.07)}
    .prayer-stat-icon{display:grid;place-items:center;width:42px;height:42px;flex:0 0 auto;border-radius:12px;color:#936d2d;background:#fbf3df}
    .prayer-stat-icon svg{width:20px;height:20px}
    .prayer-stat strong{display:block;color:var(--prayer-navy);font-size:22px;line-height:1.1}
    .prayer-stat span:last-child{color:var(--prayer-muted);font-size:12px}
    .prayer-main{display:grid;grid-template-columns:minmax(0,1.55fr) minmax(300px,.8fr);align-items:start;gap:26px;padding:35px 0 68px}
    .prayer-feed-heading{display:flex;align-items:flex-end;justify-content:space-between;gap:15px;margin-bottom:18px}
    .prayer-feed-heading h2,.prayer-form-head h2{margin:5px 0 0;color:var(--prayer-navy);font-size:clamp(23px,3vw,31px);font-weight:850;line-height:1.15;letter-spacing:-.035em}
    .prayer-feed-heading p{margin:6px 0 0;color:var(--prayer-muted);font-size:13px}
    .prayer-filter{display:grid;grid-template-columns:minmax(130px,.8fr) minmax(180px,1.3fr) auto auto;gap:9px;margin-bottom:18px;padding:12px;border:1px solid var(--prayer-line);border-radius:13px;background:#fff}
    .prayer-filter input,.prayer-filter select{width:100%;min-width:0;height:42px;padding:0 12px;border:1px solid #dce4eb;border-radius:8px;color:#26394c;background:#fff;font:inherit;font-size:12px}
    .prayer-filter input:focus,.prayer-filter select:focus,.prayer-form input:focus,.prayer-form select:focus,.prayer-form textarea:focus{outline:3px solid rgba(231,191,105,.27);border-color:#c59a4e}
    .prayer-filter button,.prayer-filter .prayer-clear{display:inline-flex;align-items:center;justify-content:center;min-height:42px;padding:0 14px;border:0;border-radius:8px;color:#fff;background:var(--prayer-navy);font-size:12px;font-weight:800;text-decoration:none;cursor:pointer}
    .prayer-filter .prayer-clear{border:1px solid #dce4eb;color:#536578;background:#fff}
    .prayer-card{overflow:hidden;margin-bottom:14px;border:1px solid var(--prayer-line);border-radius:14px;background:#fff;box-shadow:0 7px 20px rgba(9,32,53,.045);transition:transform .2s,box-shadow .2s}
    .prayer-card:hover{transform:translateY(-2px);box-shadow:0 14px 32px rgba(9,32,53,.09)}
    .prayer-card-top{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:15px 19px;border-bottom:1px solid #edf0f3}
    .prayer-card-meta{display:flex;align-items:center;flex-wrap:wrap;gap:9px;color:#758496;font-size:11px}
    .prayer-category{display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border:1px solid #f0e3bf;border-radius:999px;color:#795922;background:#fff9eb;font-size:11px;font-weight:800}
    .prayer-pin{display:inline-flex;align-items:center;gap:5px;color:#a77927;font-size:11px;font-weight:800}
    .prayer-card-body{padding:19px}
    .prayer-card-body blockquote{margin:0;color:#263b50;font-size:15px;line-height:1.75;white-space:pre-line;overflow-wrap:anywhere}
    .prayer-card-bottom{display:flex;align-items:center;justify-content:space-between;gap:15px;margin-top:17px;padding-top:14px;border-top:1px solid #edf0f3}
    .prayer-together{display:flex;align-items:center;gap:8px;color:#637588;font-size:12px}
    .prayer-together svg{width:17px;height:17px;color:#a47a32}
    .prayer-card .lift-prayer-btn{max-width:210px;min-height:40px;border:0;border-radius:9px;font-weight:800}
    .prayer-card .lift-prayer-btn:hover{transform:translateY(-1px)}
    .prayer-empty{padding:46px 25px;border:1px dashed #cbd6df;border-radius:15px;background:#fff;text-align:center}
    .prayer-empty-icon{display:grid;place-items:center;width:54px;height:54px;margin:0 auto 13px;border-radius:16px;color:#8a672e;background:#fbf2dd}
    .prayer-empty-icon svg{width:25px;height:25px}
    .prayer-empty h3{margin:0;color:var(--prayer-navy);font-size:18px;font-weight:800}
    .prayer-empty p{margin:6px 0 0;color:var(--prayer-muted);font-size:13px}
    .prayer-aside{position:sticky;top:18px;display:grid;gap:15px}
    .prayer-form-card,.prayer-info-card{overflow:hidden;border:1px solid var(--prayer-line);border-radius:15px;background:#fff;box-shadow:0 10px 26px rgba(9,32,53,.06)}
    .prayer-form-head{padding:20px 22px;color:#fff;background:linear-gradient(115deg,var(--prayer-deep),#104265)}
    .prayer-form-head .prayer-kicker{color:var(--prayer-gold)}
    .prayer-form-head h2{margin-top:8px;color:#fff;font-size:23px}
    .prayer-form-head p{margin:7px 0 0;color:rgba(255,255,255,.7);font-size:12px;line-height:1.6}
    .prayer-form{padding:20px 22px}
    .prayer-field{margin-bottom:15px}
    .prayer-field label{display:block;margin-bottom:6px;color:#30465a;font-size:12px;font-weight:800}
    .prayer-field select,.prayer-field textarea{display:block;width:100%;padding:11px 12px;border:1px solid #dce4eb;border-radius:9px;color:#243b50;background:#fff;font:inherit;font-size:13px}
    .prayer-field textarea{min-height:142px;resize:vertical;line-height:1.6}
    .prayer-field-hint{display:flex;justify-content:space-between;gap:8px;margin-top:5px;color:#8491a0;font-size:10px}
    .prayer-submit{display:flex;align-items:center;justify-content:center;gap:9px;width:100%;min-height:46px;border:0;border-radius:9px;color:#182b3d;background:var(--prayer-gold);font-size:13px;font-weight:850;cursor:pointer;transition:background .18s,transform .18s}
    .prayer-submit:hover{transform:translateY(-1px);background:#f0d28f}
    .prayer-form-note{margin:11px 0 0;color:#788696;font-size:10px;line-height:1.55}
    .prayer-form-alert{margin:14px 20px 0;padding:11px 13px;border-radius:9px;font-size:12px;line-height:1.5}
    .prayer-form-alert.success{border:1px solid #bce2cb;color:#21623c;background:#effaf3}
    .prayer-form-alert.error{border:1px solid #f1caca;color:#8d3030;background:#fff5f5}
    .prayer-login{padding:23px;text-align:center}
    .prayer-login p{margin:0 0 15px;color:#66798c;font-size:13px;line-height:1.65}
    .prayer-login .prayer-button{width:100%;margin:4px 0}
    .prayer-info-card{padding:20px 21px}
    .prayer-info-card h3{margin:0 0 14px;color:var(--prayer-navy);font-size:15px;font-weight:850}
    .prayer-steps{display:grid;gap:13px;margin:0;padding:0;list-style:none}
    .prayer-steps li{display:grid;grid-template-columns:29px 1fr;gap:10px;align-items:start}
    .prayer-step-no{display:grid;place-items:center;width:27px;height:27px;border-radius:8px;color:#876329;background:#fbf2dd;font-size:11px;font-weight:850}
    .prayer-steps strong{display:block;color:#2e4357;font-size:12px}
    .prayer-steps span:last-child{display:block;margin-top:3px;color:#7b8998;font-size:11px;line-height:1.5}
    .prayer-verse{padding:19px 21px;border:1px solid #ead9ae;border-radius:14px;background:linear-gradient(130deg,#fffaf0,#fbf2dc)}
    .prayer-verse p{margin:0;color:#594a2e;font-family:Georgia,serif;font-size:14px;font-style:italic;line-height:1.7}
    .prayer-verse strong{display:block;margin-top:9px;color:#987438;font-family:inherit;font-size:11px;font-style:normal}
    .prayer-pagination{margin-top:20px}
    .prayer-pagination nav{display:flex;justify-content:center}
    .prayer-pagination svg{width:16px}
    @media(max-width:900px){.prayer-main{grid-template-columns:minmax(0,1fr) 310px;gap:18px}.prayer-filter{grid-template-columns:1fr 1.2fr}.prayer-filter button,.prayer-filter .prayer-clear{min-height:39px}}
    @media(max-width:700px){.prayer-wrap{width:min(100% - 28px,1190px)}.prayer-hero-inner{grid-template-columns:1fr;min-height:0;padding:43px 0 52px}.prayer-hero-mark{display:none}.prayer-hero p{font-size:14px}.prayer-stats{margin-top:-20px;gap:9px}.prayer-stat{min-height:69px;padding:12px;gap:9px}.prayer-stat-icon{width:35px;height:35px}.prayer-stat strong{font-size:19px}.prayer-stat span:last-child{font-size:10px}.prayer-main{grid-template-columns:1fr;padding:29px 0 48px}.prayer-aside{position:static;grid-row:1}.prayer-feed{grid-row:2}.prayer-feed-heading{align-items:flex-start}.prayer-feed-heading h2{font-size:24px}.prayer-filter{grid-template-columns:1fr 1fr}.prayer-filter input{grid-column:1/-1}.prayer-filter button,.prayer-filter .prayer-clear{width:100%}.prayer-card-top,.prayer-card-body{padding:14px}.prayer-card-body blockquote{font-size:14px}.prayer-card-bottom{align-items:stretch;flex-direction:column}.prayer-card .lift-prayer-btn{max-width:none}.prayer-form-head,.prayer-form{padding:18px}.prayer-info-card{padding:18px}}
    @media(prefers-reduced-motion:reduce){.prayer-card,.prayer-button,.prayer-submit{transition:none}.prayer-card:hover,.prayer-button:hover,.prayer-submit:hover{transform:none}}
</style>
@endpush

@section('content')
<div class="prayer-page">
    <section class="prayer-hero" aria-labelledby="prayer-title">
        <div class="prayer-wrap prayer-hero-inner">
            <div>
                <span class="prayer-eyebrow">A community that cares</span>
                <h1 id="prayer-title">You don’t have to carry it alone.</h1>
                <p>Share what’s on your heart, or join the IGM family in praying for someone. Every request is treated with care and reviewed before it appears on this public board.</p>
                <div class="prayer-hero-actions">
                    <a class="prayer-button prayer-button-gold" href="#submit-prayer">Share a prayer request <span aria-hidden="true">→</span></a>
                    <a class="prayer-button prayer-button-outline" href="#community-prayers">Pray with the community</a>
                </div>
            </div>
            <div class="prayer-hero-mark" aria-hidden="true">
                <svg viewBox="0 0 64 64" fill="none"><path d="M21 53V31c0-2.6 3.8-3.1 4.5-.6l3.2 10.1V14.8c0-3.5 5.3-3.5 5.3 0v18.5-22c0-3.5 5.3-3.5 5.3 0v22-18c0-3.5 5.2-3.5 5.2 0v20.1l3.2-8.6c1.1-2.8 5.3-1.5 4.5 1.4L47 48c-1.1 3.2-4.5 5-7.7 5H21Z" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 9h11M17.5 3.5v11" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/></svg>
            </div>
        </div>
    </section>

    <div class="prayer-wrap">
        <div class="prayer-stats" aria-label="Prayer board activity">
            <div class="prayer-stat"><span class="prayer-stat-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 21s-8-4.5-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 6.5-8 11-8 11Z"/></svg></span><span><strong>{{ number_format($stats['active']) }}</strong><span>Requests on the board</span></span></div>
            <div class="prayer-stat"><span class="prayer-stat-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></span><span><strong>{{ number_format($stats['people_praying']) }}</strong><span>Prayers lifted together</span></span></div>
        </div>

        <div class="prayer-main">
            <section class="prayer-feed" id="community-prayers" aria-labelledby="community-title">
                <div class="prayer-feed-heading">
                    <div><span class="prayer-kicker">Stand together</span><h2 id="community-title">Community prayer board</h2><p>Choose a request and let someone know they are not alone.</p></div>
                </div>

                <form action="{{ route('web.prayer') }}" method="GET" class="prayer-filter" role="search">
                    <input type="search" name="q" value="{{ $search }}" maxlength="100" placeholder="Search prayer requests…" aria-label="Search prayer requests">
                    <select name="category_id" aria-label="Filter by prayer category">
                        <option value="">All prayer needs</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (string) $categoryId === (string) $category->id ? 'selected' : '' }}>{{ $category->emoji }} {{ $category->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit">Find requests</button>
                    @if($search || $categoryId)<a class="prayer-clear" href="{{ route('web.prayer') }}">Clear</a>@endif
                </form>

                @forelse($requests as $item)
                    @include('theme::_card_prayer_item', ['item' => $item, 'liftedPrayerIds' => $liftedPrayerIds])
                @empty
                    <div class="prayer-empty">
                        <span class="prayer-empty-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 21s-8-4.5-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 6.5-8 11-8 11Z"/><path d="M12 8v6m-3-3h6"/></svg></span>
                        @if($search || $categoryId)
                            <h3>No matching requests</h3><p>Try another search or clear the filters to see the full prayer board.</p>
                            <a class="prayer-button prayer-button-gold" style="margin-top:15px" href="{{ route('web.prayer') }}">View all requests</a>
                        @else
                            <h3>The board is quiet right now</h3><p>You can be the first to share a request. The prayer team will review it before it is published.</p>
                        @endif
                    </div>
                @endforelse

                @if($requests->hasPages())<div class="prayer-pagination">{{ $requests->links() }}</div>@endif
            </section>

            <aside class="prayer-aside">
                <section class="prayer-form-card" id="submit-prayer" aria-labelledby="submit-prayer-title">
                    <div class="prayer-form-head"><span class="prayer-kicker">We’re here with you</span><h2 id="submit-prayer-title">Share your request</h2><p>Your request goes to the church team for review before it is shared on the public board.</p></div>

                    @if(session('success'))<div class="prayer-form-alert success" role="status">{{ session('success') }}</div>@endif
                    @if($errors->any())<div class="prayer-form-alert error" role="alert"><strong>Please review your request:</strong><ul style="margin:6px 0 0;padding-left:18px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

                    @guest
                        <p class="prayer-form-alert" style="margin-bottom:0;color:#5d6d7c;background:#f5f7f9">You can submit without an account. Visitor requests are shown to the prayer team as anonymous.</p>
                    @else
                        <p class="prayer-form-alert" style="margin-bottom:0;color:#5d6d7c;background:#f5f7f9">You are signed in. Your name is kept off the public prayer board.</p>
                    @endguest
                    <form action="{{ route('web.prayer.store') }}" method="POST" class="prayer-form">
                        @csrf
                        @if($categories->isNotEmpty())
                            <div class="prayer-field"><label for="prayer-category">Prayer area <span style="color:#8290a0;font-weight:500">(optional)</span></label><select name="category_id" id="prayer-category"><option value="">Choose a category</option>@foreach($categories as $category)<option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->emoji }} {{ $category->name }}</option>@endforeach</select></div>
                        @endif
                        <div class="prayer-field"><label for="prayer-text">Your prayer request <span style="color:#b03b3b">*</span></label><textarea name="text" id="prayer-text" minlength="10" maxlength="500" required placeholder="Share only what you feel comfortable sharing…">{{ old('text') }}</textarea><div class="prayer-field-hint"><span>10–500 characters</span><span><span id="prayer-character-count">{{ mb_strlen(old('text', '')) }}</span>/500</span></div></div>
                        <button type="submit" class="prayer-submit"><span aria-hidden="true">♡</span> Send to the prayer team</button>
                        <p class="prayer-form-note">Please avoid sharing sensitive personal or medical information. Requests are reviewed before publication.</p>
                    </form>
                </section>

                <section class="prayer-info-card" aria-labelledby="prayer-process-title">
                    <h3 id="prayer-process-title">How the prayer board works</h3>
                    <ol class="prayer-steps">
                        <li><span class="prayer-step-no">1</span><span><strong>Share what’s on your heart</strong><span>Choose a category and write a short request.</span></span></li>
                        <li><span class="prayer-step-no">2</span><span><strong>The team reviews it</strong><span>Requests are checked before anything appears publicly.</span></span></li>
                        <li><span class="prayer-step-no">3</span><span><strong>Pray with the community</strong><span>Use “I’m praying” to encourage someone and add to the prayer count.</span></span></li>
                    </ol>
                </section>

                <blockquote class="prayer-verse"><p>“Do not be anxious about anything, but in every situation, by prayer and petition, with thanksgiving, present your requests to God.”</p><strong>Philippians 4:6</strong></blockquote>
            </aside>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var textarea = document.getElementById('prayer-text');
        var counter = document.getElementById('prayer-character-count');
        if (!textarea || !counter) return;
        textarea.addEventListener('input', function () { counter.textContent = textarea.value.length; });
    })();
</script>
@endpush
