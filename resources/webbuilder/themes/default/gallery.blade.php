@extends('theme::layout')

@section('title', $gallery->name . ' | IGM Church Gallery')

@push('styles')
<style>
    .igm-album-page{--navy:#082846;--deep:#061d34;--gold:#d8ad5d;--gold-dark:#a97e32;--ivory:#f8f6f0;--ink:#102d4d;--muted:#66758a;background:#fff;color:var(--ink);padding:35px 0 76px}
    .igm-album-wrap{width:min(1120px,calc(100% - 40px));margin:0 auto}
    .igm-album-breadcrumb{display:flex;align-items:center;gap:9px;margin-bottom:22px;color:#8491a0;font-size:11px}.igm-album-breadcrumb a{color:#80602a;text-decoration:none;font-weight:700}.igm-album-breadcrumb a:hover{color:var(--navy)}.igm-album-breadcrumb svg{width:13px;height:13px}
    .igm-album-heading{display:flex;align-items:end;justify-content:space-between;gap:24px;margin-bottom:30px;padding-bottom:24px;border-bottom:1px solid #ebe8df}
    .igm-album-kicker{display:inline-flex;align-items:center;gap:9px;color:var(--gold-dark);font-size:10px;font-weight:900;letter-spacing:.16em;text-transform:uppercase}.igm-album-kicker:before{content:"";width:22px;height:2px;background:var(--gold)}
    .igm-album-heading h1{margin:9px 0 8px;color:var(--navy);font-size:clamp(31px,4.5vw,48px);font-weight:800;letter-spacing:-.045em;line-height:1.08}.igm-album-description{max-width:690px;margin:0;color:var(--muted);font-size:14px;line-height:1.75;white-space:pre-line}
    .igm-album-count{flex:none;display:flex;align-items:center;gap:11px;border:1px solid #e9e3d6;border-radius:14px;background:var(--ivory);padding:12px 16px;color:var(--muted);font-size:10px;font-weight:800;letter-spacing:.07em;text-transform:uppercase}.igm-album-count svg{width:21px;height:21px;color:var(--gold-dark)}.igm-album-count strong{display:block;color:var(--navy);font-size:19px;letter-spacing:0}
    .igm-album-toolbar{display:flex;align-items:center;justify-content:space-between;gap:16px;margin:0 0 16px}.igm-album-toolbar h2{margin:0;color:var(--navy);font-size:17px;font-weight:800;letter-spacing:-.02em}.igm-album-toolbar p{margin:0;color:var(--muted);font-size:11px}
    .igm-album-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));grid-auto-rows:clamp(140px,17vw,218px);gap:13px}
    .igm-album-photo{position:relative;display:block;overflow:hidden;min-width:0;padding:0;border:0;border-radius:13px;background:linear-gradient(135deg,#e9e5dc,#f8f6f0);cursor:zoom-in;box-shadow:0 5px 16px rgba(8,40,70,.08);isolation:isolate}.igm-album-photo:first-child{grid-column:span 2;grid-row:span 2;border-radius:17px}
    .igm-album-photo img{display:block;width:100%;height:100%;object-fit:cover;transition:transform .5s cubic-bezier(.2,.65,.3,1),filter .3s}.igm-album-photo:hover img{transform:scale(1.045);filter:saturate(1.04)}
    .igm-album-photo:after{position:absolute;inset:40% 0 0;z-index:1;background:linear-gradient(transparent,rgba(4,22,39,.68));content:"";opacity:.82;transition:opacity .25s}.igm-album-photo:hover:after{opacity:1}
    .igm-photo-fallback{position:absolute;inset:0;display:grid;place-items:center;color:#a98443}.igm-photo-fallback svg{width:38px;height:38px;opacity:.7}
    .igm-photo-label{position:absolute;right:13px;bottom:12px;left:13px;z-index:2;display:flex;align-items:end;justify-content:space-between;gap:10px;color:#fff;text-align:left;pointer-events:none}.igm-photo-label span{overflow:hidden;font-size:11px;font-weight:700;text-overflow:ellipsis;white-space:nowrap;text-shadow:0 1px 8px rgba(0,0,0,.35)}.igm-photo-label b{flex:none;display:grid;width:30px;height:30px;place-items:center;border:1px solid rgba(255,255,255,.35);border-radius:50%;background:rgba(6,29,52,.48);font-size:16px;font-weight:500;backdrop-filter:blur(5px)}
    .igm-album-empty{border:1px dashed #d9d2c4;border-radius:17px;background:var(--ivory);padding:60px 22px;text-align:center}.igm-album-empty svg{width:44px;height:44px;margin:0 auto 13px;color:var(--gold-dark)}.igm-album-empty h2{margin:0 0 7px;color:var(--navy);font-size:20px}.igm-album-empty p{margin:0;color:var(--muted);font-size:13px}
    .igm-album-lightbox{position:fixed;z-index:9999;inset:0;display:grid;grid-template-rows:auto minmax(0,1fr) auto;gap:16px;background:rgba(3,17,30,.97);padding:20px clamp(14px,4vw,54px) 22px;color:#fff}.igm-lightbox-head{display:flex;align-items:center;justify-content:space-between;gap:16px}.igm-lightbox-title{overflow:hidden;color:#e9edf0;font-size:12px;font-weight:700;text-overflow:ellipsis;white-space:nowrap}.igm-lightbox-counter{flex:none;color:#c5d0da;font-size:11px;font-variant-numeric:tabular-nums}.igm-lightbox-stage{position:relative;display:grid;min-height:0;place-items:center}.igm-lightbox-stage img{display:block;max-width:100%;max-height:100%;object-fit:contain}.igm-lightbox-close,.igm-lightbox-arrow{display:grid;width:44px;height:44px;place-items:center;border:1px solid rgba(255,255,255,.25);border-radius:50%;background:rgba(255,255,255,.08);color:#fff;cursor:pointer;transition:background .18s,transform .18s}.igm-lightbox-close:hover,.igm-lightbox-arrow:hover{background:rgba(255,255,255,.18)}.igm-lightbox-close svg,.igm-lightbox-arrow svg{width:19px;height:19px}.igm-lightbox-arrow{position:absolute;top:50%;transform:translateY(-50%);background:rgba(4,20,34,.62)}.igm-lightbox-arrow:hover{transform:translateY(-50%) scale(1.04)}.igm-lightbox-prev{left:0}.igm-lightbox-next{right:0}.igm-lightbox-footer{display:flex;align-items:center;justify-content:center;gap:8px;min-height:54px;overflow-x:auto}.igm-lightbox-thumb{flex:none;width:58px;height:44px;overflow:hidden;padding:0;border:2px solid transparent;border-radius:7px;background:#182d40;opacity:.62;cursor:pointer;transition:opacity .18s,border-color .18s}.igm-lightbox-thumb img{width:100%;height:100%;object-fit:cover}.igm-lightbox-thumb:hover{opacity:.9}.igm-lightbox-thumb.is-active{border-color:var(--gold);opacity:1}.igm-album-photo:focus-visible,.igm-lightbox button:focus-visible{outline:3px solid var(--gold);outline-offset:3px}
    [x-cloak]{display:none!important}
    @media(max-width:900px){.igm-album-grid{grid-template-columns:repeat(3,minmax(0,1fr));grid-auto-rows:clamp(150px,23vw,210px);gap:11px}}
    @media(max-width:600px){.igm-album-page{padding:25px 0 50px}.igm-album-wrap{width:min(100% - 30px,1120px)}.igm-album-breadcrumb{margin-bottom:17px}.igm-album-heading{align-items:start;margin-bottom:22px;padding-bottom:19px}.igm-album-heading h1{font-size:32px}.igm-album-description{font-size:13px}.igm-album-count{gap:8px;padding:9px 10px}.igm-album-count svg{width:18px;height:18px}.igm-album-count strong{font-size:16px}.igm-album-grid{grid-template-columns:repeat(2,minmax(0,1fr));grid-auto-rows:clamp(132px,39vw,210px);gap:8px}.igm-album-photo:first-child{border-radius:13px}.igm-album-photo{border-radius:10px}.igm-photo-label{right:8px;bottom:8px;left:8px}.igm-photo-label span{font-size:10px}.igm-photo-label b{width:26px;height:26px}.igm-album-toolbar p{max-width:46%;text-align:right}.igm-album-lightbox{gap:10px;padding:12px 10px}.igm-lightbox-close,.igm-lightbox-arrow{width:40px;height:40px}.igm-lightbox-thumb{width:49px;height:38px}.igm-lightbox-footer{justify-content:flex-start}}
    @media(max-width:390px){.igm-album-heading{gap:10px}.igm-album-count{flex-direction:column;align-items:center;gap:3px}.igm-album-heading h1{font-size:28px}.igm-album-grid{grid-auto-rows:34vw}}
    @media(prefers-reduced-motion:reduce){.igm-album-page *, .igm-album-lightbox *{scroll-behavior:auto!important;transition:none!important}}
</style>
@endpush

@section('content')
@php
    $albumPhotos = $gallery->photos->map(function ($photo, $index) use ($gallery) {
        $caption = $photo->description ?: ($photo->photo_name ?: 'Photo ' . ($index + 1));
        return [
            'src' => $photo->FullPath,
            'caption' => $caption,
            'alt' => $photo->description ?: ($gallery->name . ' photo ' . ($index + 1)),
        ];
    })->values();
@endphp

<main class="igm-album-page" x-data="igmAlbumViewer(@js($albumPhotos))" @keydown.left.window="prev()" @keydown.right.window="next()" @keydown.escape.window="lightbox = false">
    <div class="igm-album-wrap">
        <nav class="igm-album-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('web.home') }}">Home</a><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="m7 4 6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <a href="{{ route('web.gallery') }}">Gallery</a><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="m7 4 6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span aria-current="page">{{ $gallery->name }}</span>
        </nav>

        <header class="igm-album-heading">
            <div>
                <span class="igm-album-kicker">Church life in pictures</span>
                <h1>{{ $gallery->name }}</h1>
                @if($gallery->description)
                    <p class="igm-album-description">{{ $gallery->description }}</p>
                @else
                    <p class="igm-album-description">A collection of moments, memories and stories from our church family.</p>
                @endif
            </div>
            <div class="igm-album-count" aria-label="{{ $gallery->photos->count() }} photos">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.6"/><circle cx="8.5" cy="9" r="1.5" stroke="currentColor" stroke-width="1.6"/><path d="m4 17 5-5 3 3 3-4 5 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span><strong>{{ $gallery->photos->count() }}</strong>{{ \Illuminate\Support\Str::plural('photo', $gallery->photos->count()) }}</span>
            </div>
        </header>

        @if($gallery->photos->isNotEmpty())
            <section aria-labelledby="album-photos-title">
                <div class="igm-album-toolbar">
                    <h2 id="album-photos-title">Album moments</h2>
                    <p>Select a photo to view it full size</p>
                </div>
                <div class="igm-album-grid">
                    @foreach($gallery->photos as $i => $photo)
                        <button type="button" class="igm-album-photo" @click="open({{ $i }})" aria-label="View {{ $albumPhotos[$i]['alt'] }} full size">
                            @if($photo->FullPath)
                                <img src="{{ $photo->FullPath }}" alt="{{ $albumPhotos[$i]['alt'] }}" loading="lazy" x-on:error="$el.style.display='none'">
                            @else
                                <span class="igm-photo-fallback" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="m5 17 5-5 3 3 3-4 3 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                            @endif
                            <span class="igm-photo-label"><span>{{ $albumPhotos[$i]['caption'] }}</span><b aria-hidden="true">+</b></span>
                        </button>
                    @endforeach
                </div>
            </section>

            <div class="igm-album-lightbox" x-show="lightbox" x-transition.opacity x-cloak @click.self="lightbox = false" role="dialog" aria-modal="true" aria-label="Photo viewer">
                <div class="igm-lightbox-head">
                    <span class="igm-lightbox-title" x-text="currentPhoto ? currentPhoto.caption : ''"></span>
                    <span class="igm-lightbox-counter"><span x-text="active + 1"></span> / {{ $albumPhotos->count() }}</span>
                    <button type="button" class="igm-lightbox-close" @click="lightbox = false" aria-label="Close photo viewer"><svg viewBox="0 0 24 24" fill="none"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></button>
                </div>
                <div class="igm-lightbox-stage">
                    <button type="button" class="igm-lightbox-arrow igm-lightbox-prev" @click="prev()" aria-label="Previous photo"><svg viewBox="0 0 24 24" fill="none"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                    <template x-if="currentPhoto && !imageFailed"><img :src="currentPhoto.src" :alt="currentPhoto.alt" x-on:error="imageFailed = true"></template>
                    <template x-if="imageFailed"><p role="status">This photo could not be loaded.</p></template>
                    <button type="button" class="igm-lightbox-arrow igm-lightbox-next" @click="next()" aria-label="Next photo"><svg viewBox="0 0 24 24" fill="none"><path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                </div>
                <div class="igm-lightbox-footer" aria-label="Choose a photo">
                    @foreach($gallery->photos as $i => $photo)
                        <button type="button" class="igm-lightbox-thumb" :class="active === {{ $i }} ? 'is-active' : ''" @click="select({{ $i }})" aria-label="Open photo {{ $i + 1 }}" :aria-pressed="active === {{ $i }}">
                            @if($photo->FullPath)<img src="{{ $photo->FullPath }}" alt="" loading="lazy" x-on:error="$el.style.visibility='hidden'">@endif
                        </button>
                    @endforeach
                </div>
            </div>
        @else
            <div class="igm-album-empty">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/><circle cx="8.5" cy="9" r="1.5" stroke="currentColor" stroke-width="1.5"/><path d="m4 17 5-5 3 3 3-4 5 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <h2>Photos are coming soon</h2><p>This album does not have any photos yet. Please check back later.</p>
            </div>
        @endif
    </div>
</main>

<script>
    function igmAlbumViewer(photos) {
        return {
            photos: Array.isArray(photos) ? photos : [],
            active: 0,
            lightbox: false,
            imageFailed: false,
            get currentPhoto() { return this.photos[this.active] || null; },
            select(index) { this.active = index; this.imageFailed = false; },
            open(index) { this.select(index); this.lightbox = true; },
            prev() { if (this.lightbox && this.photos.length) this.select((this.active - 1 + this.photos.length) % this.photos.length); },
            next() { if (this.lightbox && this.photos.length) this.select((this.active + 1) % this.photos.length); }
        };
    }
</script>
@endsection
