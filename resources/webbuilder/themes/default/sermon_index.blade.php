@extends('theme::layout')

@section('title', 'Sermons | IGM Church')
@section('meta_description', 'Watch, listen to, and read sermons and teaching from IGM Church.')

@push('styles')
<style>
    .sermon-index{--navy:#082846;--deep:#061d34;--gold:#d8ad5d;--gold-light:#f0d08a;--ink:#142b43;--muted:#637487;--paper:#f8f7f3;background:var(--paper);color:var(--ink)}.sermon-index *{box-sizing:border-box}.sermon-index a{text-decoration:none;color:inherit}.sermon-wrap{width:min(1160px,calc(100% - 40px));margin:auto}
    .sermon-hero{background:radial-gradient(ellipse at 86% 24%,rgba(216,173,93,.23),transparent 32%),linear-gradient(120deg,var(--deep),var(--navy));color:#fff;padding:68px 0 58px;overflow:hidden}.sermon-hero .sermon-wrap{position:relative}.sermon-hero:after{content:"";position:absolute;pointer-events:none}.sermon-kicker{display:flex;align-items:center;gap:9px;color:var(--gold-light);font-size:10px;font-weight:900;letter-spacing:.17em;text-transform:uppercase}.sermon-kicker:before{content:"";height:1px;width:24px;background:currentColor}.sermon-hero h1{color:#fff;font-size:clamp(39px,6vw,66px);line-height:1.02;letter-spacing:-.05em;margin:16px 0 13px}.sermon-hero p{max-width:560px;color:rgba(255,255,255,.75);font-size:15px;line-height:1.75;margin:0}.sermon-crumb{display:flex;gap:9px;align-items:center;margin-top:25px;color:rgba(255,255,255,.55);font-size:12px}.sermon-crumb a:hover{color:var(--gold-light)}
    .sermon-live-banner{margin:28px 0 36px;display:flex;align-items:center;justify-content:space-between;gap:20px;padding:22px 25px;border-radius:16px;background:linear-gradient(100deg,#092845,#12456c);color:white;box-shadow:0 12px 30px rgba(8,40,70,.12)}.sermon-live-banner.has-player{display:grid;grid-template-columns:minmax(250px,.8fr) minmax(300px,1.2fr)}.sermon-live-copy{display:flex;align-items:center;gap:16px}.sermon-live-banner.has-player .sermon-live-copy{align-items:flex-start}.sermon-live-dot{width:47px;height:47px;display:grid;place-items:center;border-radius:50%;background:#e74d4d;color:white;box-shadow:0 0 0 7px rgba(231,77,77,.12);flex:none}.sermon-live-label{color:#f0c66b;text-transform:uppercase;letter-spacing:.13em;font-size:9px;font-weight:900}.sermon-live-banner h2{color:white;font-size:20px;margin:4px 0}.sermon-live-banner p{color:rgba(255,255,255,.68);font-size:12px;margin:0}.sermon-live-cta{display:inline-flex;align-items:center;gap:10px;background:var(--gold);color:var(--deep)!important;border-radius:9px;padding:12px 17px;font-size:12px;font-weight:900;white-space:nowrap}.sermon-live-cta:hover{background:var(--gold-light)}.sermon-live-player{width:100%;aspect-ratio:16/9;overflow:hidden;border-radius:12px;background:#020a12}.sermon-live-player video{width:100%;height:100%;display:block;object-fit:contain}
    .sermon-body{padding:40px 0 80px}.sermon-toolbar{display:flex;justify-content:space-between;align-items:end;gap:20px;margin-bottom:22px}.sermon-section-label{font-size:10px;color:#9a722b;text-transform:uppercase;letter-spacing:.15em;font-weight:900;margin:0 0 6px}.sermon-toolbar h2{font-size:30px;letter-spacing:-.035em;color:var(--navy);margin:0}.sermon-result-count{margin:6px 0 0;color:var(--muted);font-size:11px}.sermon-search{display:flex;align-items:center;gap:8px;padding:7px;border:1px solid #e6e4dd;background:white;border-radius:12px;min-width:min(380px,100%)}.sermon-search input{min-width:0;flex:1;border:0;outline:none;padding:0 10px;color:var(--ink);font-size:13px;background:transparent}.sermon-search button{border:0;border-radius:8px;background:var(--navy);color:white;font-size:12px;font-weight:800;padding:11px 16px;cursor:pointer}.sermon-clear{color:#8b6729;font-size:11px;font-weight:800;text-decoration:none;white-space:nowrap}.sermon-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:19px}.sermon-card{background:#fff;border:1px solid #e7e6df;border-radius:15px;overflow:hidden;box-shadow:0 7px 25px rgba(8,40,70,.045);transition:transform .2s,box-shadow .2s}.sermon-card:hover{transform:translateY(-4px);box-shadow:0 15px 32px rgba(8,40,70,.11)}.sermon-card-cover{height:194px;position:relative;overflow:hidden;background:linear-gradient(135deg,#163c5c,#819098)}.sermon-card-cover img{position:relative;z-index:1;width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s}.sermon-card:hover .sermon-card-cover img{transform:scale(1.04)}.sermon-card-placeholder{position:absolute;inset:0;display:grid;place-items:center;color:var(--gold-light);font-size:36px;background:radial-gradient(circle at 75% 15%,rgba(216,173,93,.26),transparent 35%),linear-gradient(135deg,#082846,#164a6e)}.sermon-play{position:absolute;z-index:2;left:15px;bottom:15px;width:39px;height:39px;border-radius:50%;display:grid;place-items:center;color:var(--deep);background:var(--gold);font-size:14px;box-shadow:0 5px 14px #071d3150}.sermon-card-content{padding:17px 18px 18px}.sermon-card-meta{display:flex;align-items:center;justify-content:space-between;gap:8px;color:#a77d33;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.04em}.sermon-media-count{color:#738293;font-size:10px;text-transform:none;letter-spacing:0;font-weight:600}.sermon-card h3{font-size:18px;line-height:1.32;letter-spacing:-.02em;color:var(--navy);margin:11px 0 8px}.sermon-card-description{margin:0;color:var(--muted);font-size:12px;line-height:1.65;min-height:40px}.sermon-card-footer{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:16px;padding-top:13px;border-top:1px solid #f0efe9}.sermon-preacher{display:flex;align-items:center;gap:8px;min-width:0;color:#576b7f;font-size:11px}.sermon-avatar{width:27px;height:27px;border-radius:50%;display:grid;place-items:center;background:#f5f0e4;color:#8f6825;font-weight:900}.sermon-preacher span:last-child{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.sermon-read-link{white-space:nowrap;font-size:11px;color:#956e2d;font-weight:900}.sermon-empty{background:white;border:1px dashed #d9d8d0;border-radius:15px;text-align:center;padding:55px 20px}.sermon-empty strong{display:block;color:var(--navy);font-size:18px;margin-bottom:7px}.sermon-empty span{color:var(--muted);font-size:13px}.sermon-pagination{margin-top:25px}.sermon-pagination nav{display:flex;justify-content:center}.sermon-pagination nav>div:first-child{display:none}
    @media(max-width:850px){.sermon-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.sermon-toolbar{align-items:start;flex-direction:column}.sermon-search{min-width:0;width:100%}}
    @media(max-width:700px){.sermon-live-banner.has-player{grid-template-columns:1fr}}@media(max-width:560px){.sermon-wrap{width:min(100% - 28px,1160px)}.sermon-hero{padding:52px 0 42px}.sermon-body{padding:28px 0 55px}.sermon-grid{grid-template-columns:1fr}.sermon-card-cover{height:210px}.sermon-live-banner{align-items:flex-start;flex-direction:column;padding:19px}.sermon-live-banner.has-player{display:grid}.sermon-live-copy{align-items:flex-start}.sermon-live-cta{width:100%;justify-content:center}.sermon-toolbar h2{font-size:26px}}
</style>
@endpush

@section('content')
<div class="sermon-index">
    <section class="sermon-hero"><div class="sermon-wrap">
        <span class="sermon-kicker">Teaching for everyday life</span>
        <h1>Messages to help<br>you grow in faith.</h1>
        <p>Watch, listen, and explore practical teaching from IGM Church. Find a message to return to throughout your week.</p>
        <nav class="sermon-crumb" aria-label="Breadcrumb"><a href="{{ route('web.home') }}">Home</a><span>/</span><span>Sermons</span></nav>
    </div></section>
    <div class="sermon-wrap sermon-body">
        @if($liveStream)
            <section class="sermon-live-banner {{ $liveStream->playback_url ? 'has-player' : '' }}"><div><div class="sermon-live-copy"><span class="sermon-live-dot" aria-hidden="true">▶</span><div><span class="sermon-live-label">Live now</span><h2>{{ $liveStream->title ?: 'Join us live' }}</h2><p>{{ $liveStream->message ?: 'Join our church family for worship, prayer, and the Word.' }}</p></div></div><a class="sermon-live-cta mt-5" href="{{ route('web.live') }}">Watch live <span aria-hidden="true">→</span></a></div>@if($liveStream->playback_url)<div class="sermon-live-player"><video data-live-hls data-stream-url="{{ $liveStream->playback_url }}" controls playsinline autoplay muted preload="none" aria-label="{{ $liveStream->title ?: 'IGM Church live stream' }}"></video></div>@endif</section>
        @endif

        <div class="sermon-toolbar"><div><p class="sermon-section-label">Watch again · Listen anywhere · Read along</p><h2>{{ $search ? 'Search results' : 'Recent messages' }}</h2><p class="sermon-result-count">{{ $sermons->total() }} {{ \Illuminate\Support\Str::plural('message', $sermons->total()) }}{{ $search ? ' found for “' . $search . '”' : ' to explore' }}</p></div>
            <form class="sermon-search" method="GET" action="{{ route('web.sermons') }}" role="search"><input type="search" name="q" value="{{ $search }}" placeholder="Search messages or speakers" aria-label="Search sermons">@if($search)<a class="sermon-clear" href="{{ route('web.sermons') }}">Clear</a>@endif<button type="submit">Search</button></form>
        </div>

        @if($sermons->isNotEmpty())
            <div class="sermon-grid">
                @foreach($sermons as $sermon)
                    @php
                        $mediaCount = $sermon->sermonlinks->count();
                        $youtubeThumbnail = $sermon->sermonlinks->first(fn ($link) => $link->youtube_thumbnail_url)?->youtube_thumbnail_url;
                        $sermonArtwork = $sermon->CoverImagePath ?: $youtubeThumbnail;
                    @endphp
                    <a class="sermon-card" href="{{ route('web.sermon', $sermon->id) }}">
                        <div class="sermon-card-cover">
                            <div class="sermon-card-placeholder" aria-hidden="true">✦</div>
                            @if($sermonArtwork)<img src="{{ $sermonArtwork }}" alt="{{ $sermon->title }}" loading="lazy" onerror="this.style.display='none'">@endif
                            @if($sermon->sermonlinks->contains(fn ($link) => $link->video_link))<span class="sermon-play" aria-hidden="true">▶</span>@endif
                        </div>
                        <div class="sermon-card-content">
                            <div class="sermon-card-meta"><span>{{ $sermon->created_at?->format('M j, Y') }}</span><span class="sermon-media-count">{{ $mediaCount ? $mediaCount . ' ' . \Illuminate\Support\Str::plural('resource', $mediaCount) : 'Message notes' }}</span></div>
                            <h3>{{ $sermon->title }}</h3>
                            <p class="sermon-card-description">{{ \Illuminate\Support\Str::limit(strip_tags($sermon->description ?? 'Open this message to read the sermon notes.'), 105) }}</p>
                            <div class="sermon-card-footer"><span class="sermon-preacher"><span class="sermon-avatar">{{ strtoupper(substr($sermon->user?->name ?? 'P', 0, 1)) }}</span><span>{{ $sermon->user?->name ?? 'IGM Church' }}</span></span><span class="sermon-read-link">Explore message →</span></div>
                        </div>
                    </a>
                @endforeach
            </div>
            @if($sermons->hasPages())<div class="sermon-pagination">{{ $sermons->links() }}</div>@endif
        @else
            <div class="sermon-empty"><strong>{{ $search ? 'No messages match your search' : 'Sermon messages are coming soon' }}</strong><span>{{ $search ? 'Try a different title or speaker name.' : 'Check back for teaching, recordings, and sermon notes.' }}</span></div>
        @endif
    </div>
</div>
@endsection
