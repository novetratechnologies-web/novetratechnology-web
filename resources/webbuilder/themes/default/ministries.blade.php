@extends('theme::layout')

@section('title', 'Ministries | ' . ($_church->name ?? config('app.name')))

@push('styles')
<style>
    .igm-ministry-page { --ministry-navy:#082846; --ministry-gold:#a97e32; --ministry-muted:#66758a; background:#fbfaf7; min-height:70vh; padding:56px 20px 72px; }
    .igm-ministry-wrap { width:min(1120px,100%); margin:0 auto; }
    .igm-ministry-heading { max-width:680px; margin:0 auto 34px; text-align:center; }
    .igm-ministry-heading span { color:var(--ministry-gold); text-transform:uppercase; font-size:10px; font-weight:800; letter-spacing:.16em; }
    .igm-ministry-heading h1 { margin:10px 0 0; color:var(--ministry-navy); font-size:clamp(32px,5vw,48px); line-height:1.08; letter-spacing:-.04em; font-weight:800; }
    .igm-ministry-heading p { margin:13px 0 0; color:var(--ministry-muted); font-size:15px; line-height:1.7; }
    .igm-ministry-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:20px; }
    .igm-ministry-card { overflow:hidden; border:1px solid #ebe8e1; border-radius:12px; background:#fff; box-shadow:0 10px 30px rgba(17,42,66,.07); }
    .igm-ministry-card img { display:block; width:100%; height:190px; object-fit:cover; background:#e5e1d6; }
    .igm-ministry-body { padding:20px; }
    .igm-ministry-type { color:var(--ministry-gold); font-size:10px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; }
    .igm-ministry-body h2 { margin:7px 0 0; color:var(--ministry-navy); font-size:19px; font-weight:800; }
    .igm-ministry-body p { min-height:60px; margin:9px 0 15px; color:var(--ministry-muted); font-size:13px; line-height:1.65; }
    .igm-ministry-body a { display:inline-flex; gap:8px; color:var(--ministry-gold); font-size:12px; font-weight:800; text-decoration:none; }
    .igm-ministry-empty { padding:34px; border:1px solid #ebe8e1; border-radius:12px; background:#fff; color:var(--ministry-muted); text-align:center; }
    .igm-ministry-empty a { display:inline-flex; margin-top:14px; padding:11px 18px; border-radius:999px; color:#142d47; background:#edc875; font-size:12px; font-weight:800; text-decoration:none; }
    @media(max-width:760px) { .igm-ministry-grid { grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; } .igm-ministry-card img { height:155px; } }
    @media(max-width:520px) { .igm-ministry-page { padding:38px 16px 52px; } .igm-ministry-grid { grid-template-columns:1fr; } .igm-ministry-card { display:grid; grid-template-columns:38% 1fr; } .igm-ministry-card img { width:100%; height:100%; min-height:180px; } .igm-ministry-body { padding:15px; } .igm-ministry-body p { min-height:0; } }
</style>
@endpush

@section('content')
<section class="igm-ministry-page">
    <div class="igm-ministry-wrap">
        <header class="igm-ministry-heading">
            <span>Find your community</span>
            <h1>There is a place for you here.</h1>
            <p>Discover ministries and groups where you can build friendships, grow in faith, and serve alongside others.</p>
        </header>

        @if($ministries->isNotEmpty())
            <div class="igm-ministry-grid">
                @foreach($ministries as $ministry)
                    <article class="igm-ministry-card">
                        @if($ministry->cover_image)
                            <img src="{{ $ministry->CoverImagePath }}" alt="{{ $ministry->name }}" loading="lazy">
                        @else
                            <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=900&q=80" alt="People in community" loading="lazy">
                        @endif
                        <div class="igm-ministry-body">
                            <span class="igm-ministry-type">{{ optional($ministry->groupCategory)->name ?? 'Community' }}</span>
                            <h2>{{ $ministry->name }}</h2>
                            <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags($ministry->description ?? 'Connect, grow, and serve with this church community.')), 125) }}</p>
                            <a href="{{ route('web.contact') }}">Ask about this ministry <span aria-hidden="true">→</span></a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div style="margin-top:28px">{{ $ministries->links() }}</div>
        @else
            <div class="igm-ministry-empty">
                <p>Our ministry directory is being refreshed. Contact us and we’ll help you find a place to connect.</p>
                <a href="{{ route('web.contact') }}">Contact the church</a>
            </div>
        @endif
    </div>
</section>
@endsection
