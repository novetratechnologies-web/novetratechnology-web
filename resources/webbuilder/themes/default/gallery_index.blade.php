@extends('theme::layout')

@section('title', 'Gallery | IGM Church')
@section('meta_description', 'Explore photos and memories from worship, events, and community life at IGM Church.')

@push('styles')
<style>
    .gallery-index{--navy:#082846;--deep:#061d34;--gold:#d8ad5d;--gold-light:#f0d08a;--ink:#142b43;--muted:#637487;--paper:#f8f7f3;background:var(--paper);color:var(--ink)}
    .gallery-index *{box-sizing:border-box}.gallery-index a{color:inherit;text-decoration:none}.gallery-wrap{width:min(1160px,calc(100% - 40px));margin:auto}
    .gallery-hero{position:relative;overflow:hidden;background:radial-gradient(ellipse at 84% 20%,rgba(216,173,93,.24),transparent 33%),linear-gradient(120deg,var(--deep),var(--navy));color:#fff;padding:70px 0 58px}
    .gallery-hero:after{content:"";position:absolute;right:-115px;bottom:-286px;width:490px;height:490px;border:1px solid rgba(240,208,138,.15);border-radius:50%;box-shadow:0 0 0 38px rgba(240,208,138,.035),0 0 0 78px rgba(240,208,138,.025)}
    .gallery-hero-inner{position:relative;z-index:1;max-width:750px}.gallery-kicker{display:inline-flex;align-items:center;gap:9px;color:var(--gold-light);font-size:11px;font-weight:800;letter-spacing:.17em;text-transform:uppercase}.gallery-kicker:before{content:"";width:24px;height:1px;background:currentColor}
    .gallery-hero h1{margin:16px 0 13px;color:#fff;font-size:clamp(38px,6vw,64px);font-weight:800;line-height:1.02;letter-spacing:-.05em}.gallery-hero p{max-width:570px;margin:0;color:rgba(255,255,255,.76);font-size:16px;line-height:1.75}
    .gallery-breadcrumb{display:flex;align-items:center;gap:9px;margin-top:26px;color:rgba(255,255,255,.57);font-size:12px}.gallery-breadcrumb a:hover{color:var(--gold-light)}
    .gallery-content{padding:43px 0 82px}.gallery-section-head{display:flex;align-items:end;justify-content:space-between;gap:18px;margin-bottom:22px}.gallery-section-kicker{margin:0 0 6px;color:#9a722b;font-size:10px;font-weight:900;letter-spacing:.15em;text-transform:uppercase}.gallery-section-head h2{margin:0;color:var(--navy);font-size:30px;font-weight:800;letter-spacing:-.035em}.gallery-summary{margin:7px 0 0;color:var(--muted);font-size:12px}.gallery-total{flex:none;display:flex;align-items:center;gap:10px;border:1px solid #e8e4da;border-radius:12px;background:#fff;padding:10px 14px;color:var(--muted);font-size:11px;font-weight:700}.gallery-total strong{color:var(--navy);font-size:17px}
    .gallery-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:19px}.gallery-card{display:block;overflow:hidden;border:1px solid #e7e6df;border-radius:15px;background:white;box-shadow:0 7px 25px rgba(8,40,70,.045);transition:transform .2s,box-shadow .2s,border-color .2s}.gallery-card:hover{transform:translateY(-4px);border-color:#dec795;box-shadow:0 15px 32px rgba(8,40,70,.11)}
    .gallery-card-cover{position:relative;height:clamp(180px,20vw,245px);overflow:hidden;background:radial-gradient(circle at 76% 20%,rgba(216,173,93,.24),transparent 35%),linear-gradient(135deg,#082846,#164a6e)}.gallery-card-cover img{position:relative;z-index:1;display:block;width:100%;height:100%;object-fit:cover;transition:transform .45s}.gallery-card:hover .gallery-card-cover img{transform:scale(1.045)}.gallery-card-placeholder{position:absolute;inset:0;display:grid;place-items:center;color:var(--gold-light)}.gallery-card-placeholder svg{width:48px;height:48px;opacity:.86}
    .gallery-photo-count{position:absolute;z-index:2;right:13px;bottom:13px;display:inline-flex;align-items:center;gap:7px;border:1px solid rgba(255,255,255,.24);border-radius:999px;background:rgba(5,26,45,.79);padding:7px 11px;color:white;font-size:10px;font-weight:800;backdrop-filter:blur(8px)}.gallery-photo-count svg{width:14px;height:14px}
    .gallery-card-body{padding:17px 18px 18px}.gallery-card-meta{display:flex;align-items:center;justify-content:space-between;gap:10px;color:#a77d33;font-size:10px;font-weight:800;letter-spacing:.04em;text-transform:uppercase}.gallery-card-date{color:#8290a0;font-size:10px;font-weight:600;text-transform:none;letter-spacing:0}.gallery-card h3{margin:10px 0 7px;color:var(--navy);font-size:18px;font-weight:800;line-height:1.35;letter-spacing:-.02em}.gallery-card-description{display:-webkit-box;min-height:40px;overflow:hidden;margin:0;color:var(--muted);font-size:12px;line-height:1.65;-webkit-line-clamp:2;-webkit-box-orient:vertical}.gallery-card-footer{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:15px;padding-top:12px;border-top:1px solid #f0efe9;color:#956e2d;font-size:11px;font-weight:900}.gallery-card-footer span:last-child{font-size:17px;transition:transform .2s}.gallery-card:hover .gallery-card-footer span:last-child{transform:translateX(4px)}
    .gallery-empty{grid-column:1/-1;border:1px dashed #d9d8d0;border-radius:15px;background:#fff;padding:55px 20px;text-align:center}.gallery-empty svg{width:43px;height:43px;margin:0 auto 13px;color:#a77d33}.gallery-empty strong{display:block;margin-bottom:7px;color:var(--navy);font-size:18px}.gallery-empty span{color:var(--muted);font-size:13px}
    .gallery-pagination{margin-top:26px}.gallery-pagination nav{display:flex;justify-content:center}.gallery-pagination nav>div:first-child{display:none}
    @media(max-width:850px){.gallery-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.gallery-section-head{align-items:start}}
    @media(max-width:560px){.gallery-wrap{width:min(100% - 28px,1160px)}.gallery-hero{padding:52px 0 42px}.gallery-hero p{font-size:14px}.gallery-content{padding:29px 0 56px}.gallery-grid{grid-template-columns:1fr;gap:14px}.gallery-card-cover{height:220px}.gallery-section-head h2{font-size:26px}.gallery-total{padding:8px 10px}.gallery-card-body{padding:16px}.gallery-section-head{gap:10px}}
</style>
@endpush

@section('content')
<div class="gallery-index">
    <section class="gallery-hero">
        <div class="gallery-wrap gallery-hero-inner">
            <span class="gallery-kicker">Life together</span>
            <h1>Moments from our<br>church family.</h1>
            <p>Explore worship, celebrations, service, and the everyday moments that bring our church community together.</p>
            <nav class="gallery-breadcrumb" aria-label="Breadcrumb"><a href="{{ route('web.home') }}">Home</a><span>/</span><span aria-current="page">Gallery</span></nav>
        </div>
    </section>

    <main class="gallery-wrap gallery-content">
        <div class="gallery-section-head">
            <div><p class="gallery-section-kicker">Stories in pictures</p><h2>Explore our albums</h2><p class="gallery-summary">Browse the moments captured across IGM Church.</p></div>
            <div class="gallery-total" aria-label="{{ $galleries->total() }} albums"><strong>{{ $galleries->total() }}</strong><span>{{ \Illuminate\Support\Str::plural('album', $galleries->total()) }}</span></div>
        </div>

        <section class="gallery-grid" aria-label="Gallery albums">
            @forelse($galleries as $album)
                @php($cover = $album->FullPath)
                <a href="{{ route('web.gallery.show', $album->id) }}" class="gallery-card" aria-label="Open {{ $album->name }} album">
                    <div class="gallery-card-cover">
                        <div class="gallery-card-placeholder" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/><circle cx="8.5" cy="9" r="1.5" stroke="currentColor" stroke-width="1.5"/><path d="m4 17 5-5 3 3 3-4 5 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                        @if($cover)<img src="{{ $cover }}" alt="{{ $album->name }}" loading="lazy" onerror="this.style.display='none'">@endif
                        <span class="gallery-photo-count"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="2.5" y="3.5" width="15" height="13" rx="2" stroke="currentColor" stroke-width="1.5"/><circle cx="7" cy="8" r="1.5" stroke="currentColor" stroke-width="1.4"/><path d="m4 14 4-4 2.5 2.5 2.5-3 3 4.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>{{ $album->photos_count }} {{ \Illuminate\Support\Str::plural('photo', $album->photos_count) }}</span>
                    </div>
                    <div class="gallery-card-body">
                        <div class="gallery-card-meta"><span>Photo album</span><span class="gallery-card-date">{{ $album->created_at?->format('M j, Y') }}</span></div>
                        <h3>{{ $album->name }}</h3>
                        <p class="gallery-card-description">{{ \Illuminate\Support\Str::limit(strip_tags($album->description ?: 'A collection of moments from our church community.'), 110) }}</p>
                        <div class="gallery-card-footer"><span>View album</span><span aria-hidden="true">→</span></div>
                    </div>
                </a>
            @empty
                <div class="gallery-empty"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/><circle cx="8.5" cy="9" r="1.5" stroke="currentColor" stroke-width="1.5"/><path d="m4 17 5-5 3 3 3-4 5 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg><strong>Gallery albums are coming soon</strong><span>Check back later to see photos from our church community.</span></div>
            @endforelse
        </section>
        @if($galleries->hasPages())<div class="gallery-pagination">{{ $galleries->onEachSide(1)->links() }}</div>@endif
    </main>
</div>
@endsection
