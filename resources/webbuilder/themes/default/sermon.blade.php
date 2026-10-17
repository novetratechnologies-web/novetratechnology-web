@extends('theme::layout')

@section('title', $sermon->title . ' | IGM Church')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($sermon->description ?? ''), 160))

@push('styles')
<style>
    .sermon-detail{--navy:#082846;--deep:#061d34;--gold:#d8ad5d;--ink:#142b43;--muted:#637487;--paper:#f8f7f3;background:var(--paper);color:var(--ink);padding:35px 0 75px;min-height:70vh}.sermon-detail *{box-sizing:border-box}.sermon-detail a{text-decoration:none;color:inherit}.sermon-detail-wrap{width:min(1120px,calc(100% - 40px));margin:auto}.sermon-detail-crumb{display:flex;gap:9px;align-items:center;color:#778697;font-size:12px;margin-bottom:18px}.sermon-detail-crumb a:hover{color:#986f26}.sermon-detail-hero{position:relative;overflow:hidden;border-radius:18px;padding:clamp(28px,6vw,60px);background:radial-gradient(ellipse at 86% 10%,rgba(216,173,93,.2),transparent 32%),linear-gradient(120deg,var(--deep),var(--navy));color:#fff}.sermon-detail-hero:after{content:"";position:absolute;right:-100px;bottom:-250px;width:400px;height:400px;border-radius:50%;border:1px solid rgba(240,208,138,.17);box-shadow:0 0 0 34px rgba(240,208,138,.035),0 0 0 70px rgba(240,208,138,.025)}.sermon-detail-hero>*{position:relative;z-index:1}.sermon-detail-label{color:#f0d08a;font-size:10px;font-weight:900;letter-spacing:.16em;text-transform:uppercase}.sermon-detail-hero h1{color:white;font-size:clamp(32px,5vw,56px);line-height:1.08;letter-spacing:-.045em;margin:13px 0;max-width:850px}.sermon-detail-excerpt{color:rgba(255,255,255,.76);font-size:15px;line-height:1.7;max-width:760px;margin:0}.sermon-detail-meta{display:flex;align-items:center;gap:10px;margin-top:24px;color:rgba(255,255,255,.75);font-size:12px}.sermon-detail-avatar{width:34px;height:34px;border-radius:50%;display:grid;place-items:center;background:rgba(240,208,138,.19);color:#f0d08a;font-weight:900}.sermon-detail-columns{display:grid;grid-template-columns:minmax(0,1fr) 290px;gap:22px;align-items:start;margin-top:22px}.sermon-detail-main{min-width:0}.sermon-panel{background:#fff;border:1px solid #e8e6df;border-radius:15px;box-shadow:0 8px 24px rgba(8,40,70,.045);padding:clamp(20px,4vw,32px);margin-bottom:18px}.sermon-panel h2{font-size:21px;letter-spacing:-.025em;color:var(--navy);margin:0 0 15px}.sermon-notes{color:#465d72;font-size:15px;line-height:1.9;white-space:pre-wrap;overflow-wrap:anywhere}.sermon-notes-empty{font-size:13px;color:var(--muted);margin:0}.sermon-resource{margin-bottom:16px;overflow:hidden;border:1px solid #e8e6df;border-radius:14px;background:white;box-shadow:0 8px 24px rgba(8,40,70,.04)}.sermon-resource:last-child{margin-bottom:0}.sermon-resource-head{display:flex;justify-content:space-between;align-items:center;gap:14px;padding:15px 18px}.sermon-resource-head h3{margin:0;color:var(--navy);font-size:15px}.sermon-resource-head p{margin:5px 0 0;color:var(--muted);font-size:11px}.sermon-resource-badge{color:#956e2d;background:#f5f0e4;border-radius:99px;padding:6px 10px;font-size:10px;text-transform:uppercase;font-weight:900;white-space:nowrap}.sermon-player{aspect-ratio:16/9;background:#061827}.sermon-player iframe,.sermon-player video{width:100%;height:100%;display:block;border:0}.sermon-link-out{display:inline-flex;align-items:center;gap:8px;border-radius:8px;background:var(--navy);color:white!important;padding:10px 13px;font-size:11px;font-weight:800}.sermon-audio{padding:0 18px 18px}.sermon-audio audio{display:block;width:100%;height:42px}.sermon-file-link{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:15px 18px;border-top:1px solid #f0efe9}.sermon-file-link strong{font-size:12px;color:#3c5369}.sermon-detail-sidebar{position:sticky;top:18px}.sermon-speaker-card{background:#fff;border:1px solid #e8e6df;border-radius:15px;box-shadow:0 8px 24px rgba(8,40,70,.045);overflow:hidden}.sermon-speaker-cover{height:150px;background:radial-gradient(circle at 80% 10%,rgba(216,173,93,.25),transparent 35%),linear-gradient(130deg,#092846,#1b5279)}.sermon-speaker-content{padding:18px}.sermon-speaker-content h2{font-size:15px;color:var(--navy);margin:0 0 13px}.sermon-speaker{display:flex;align-items:center;gap:11px}.sermon-speaker img{width:43px;height:43px;border-radius:50%;object-fit:cover}.sermon-speaker-name{font-size:13px;font-weight:800;color:#304a62}.sermon-speaker-role{font-size:11px;color:var(--muted);margin-top:3px}.sermon-sidebar-date{margin-top:17px;padding-top:14px;border-top:1px solid #efeee9;color:#6a7c8c;font-size:11px}.sermon-share{display:flex;gap:8px;margin-top:15px}.sermon-share a,.sermon-share button{flex:1;display:grid;place-items:center;border:1px solid #e6e5de;border-radius:8px;background:white;color:#506579;font-size:11px;font-weight:800;padding:10px;cursor:pointer}.sermon-share a:hover,.sermon-share button:hover{border-color:var(--gold);color:#8b6726}.sermon-back{display:flex;justify-content:center;margin-top:12px;padding:12px;border-radius:9px;background:var(--navy);color:white!important;font-size:11px;font-weight:800}.sermon-recommend{margin-top:38px}.sermon-recommend-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:15px}.sermon-recommend-head h2{margin:0;color:var(--navy);font-size:23px}.sermon-recommend-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:13px}.sermon-recommend-card{background:white;border:1px solid #e9e7df;border-radius:12px;padding:16px}.sermon-recommend-card h3{font-size:13px;color:var(--navy);margin:0 0 8px}.sermon-recommend-card p{font-size:10px;color:var(--muted);margin:0}
    @media(max-width:850px){.sermon-detail-columns{grid-template-columns:1fr}.sermon-detail-sidebar{position:static}.sermon-speaker-cover{height:100px}.sermon-recommend-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(max-width:560px){.sermon-detail{padding:24px 0 50px}.sermon-detail-wrap{width:min(100% - 28px,1120px)}.sermon-detail-hero{border-radius:14px;padding:25px 20px}.sermon-detail-columns{gap:12px;margin-top:13px}.sermon-panel{padding:19px}.sermon-resource-head{align-items:flex-start}.sermon-recommend-grid{grid-template-columns:1fr}.sermon-recommend-head{align-items:start;gap:10px;flex-direction:column}}
</style>
<style>
.sermon-detail-live{display:grid;grid-template-columns:minmax(210px,.65fr) minmax(0,1.35fr);gap:19px;align-items:center;margin-top:18px;padding:16px;border:1px solid #dce7ec;border-radius:15px;background:#fff;box-shadow:0 8px 24px rgba(8,40,70,.05)}.sermon-detail-live-copy{padding:7px 9px}.sermon-detail-live-label{display:inline-flex;align-items:center;gap:7px;color:#c83f3f;font-size:10px;font-weight:900;letter-spacing:.12em;text-transform:uppercase}.sermon-detail-live-label:before{content:"";width:8px;height:8px;border-radius:50%;background:#e34d4d;box-shadow:0 0 0 4px #e34d4d20}.sermon-detail-live h2{margin:8px 0 6px;color:#082846;font-size:21px;line-height:1.2}.sermon-detail-live p{margin:0;color:#687b8d;font-size:12px;line-height:1.6}.sermon-detail-live-frame{position:relative;overflow:hidden;aspect-ratio:16/9;border-radius:10px;background:#061827}.sermon-detail-live-frame video{display:block;width:100%;height:100%;object-fit:contain}.sermon-detail-live-error{position:absolute;inset:auto 10px 10px;margin:0!important;padding:8px 10px;border-radius:7px;background:#111d;color:white!important;font-size:10px!important}.sermon-detail-live-link{display:inline-flex;margin-top:13px;padding:9px 12px;border-radius:8px;background:#082846;color:white!important;font-size:10px;font-weight:800}@media(max-width:700px){.sermon-detail-live{grid-template-columns:1fr;gap:12px}.sermon-detail-live-copy{padding:3px}}
</style>
@endpush

@section('content')
<article class="sermon-detail">
    <div class="sermon-detail-wrap">
        <nav class="sermon-detail-crumb" aria-label="Breadcrumb"><a href="{{ route('web.home') }}">Home</a><span>/</span><a href="{{ route('web.sermons') }}">Sermons</a><span>/</span><span>{{ \Illuminate\Support\Str::limit($sermon->title, 48) }}</span></nav>
        <header class="sermon-detail-hero"><span class="sermon-detail-label">Sermon message · IGM Church</span><h1>{{ $sermon->title }}</h1>@if($sermon->description)<p class="sermon-detail-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($sermon->description), 220) }}</p>@endif
            <div class="sermon-detail-meta"><span class="sermon-detail-avatar">{{ strtoupper(substr($sermon->user?->name ?? 'P', 0, 1)) }}</span><span>{{ $sermon->user?->name ?? 'IGM Church' }}</span><span>·</span><span>{{ $sermon->created_at?->format('F j, Y') }}</span></div>
        </header>

        @if($liveStream && $liveStream->playback_url)
            <section class="sermon-detail-live" aria-label="Church live stream">
                <div class="sermon-detail-live-copy"><span class="sermon-detail-live-label">Live now</span><h2>{{ $liveStream->title ?: 'Join us live' }}</h2><p>{{ $liveStream->message ?: 'Worship, prayer, and the Word are streaming now.' }}</p><a class="sermon-detail-live-link" href="{{ route('web.live') }}">Open live page <span aria-hidden="true">→</span></a></div>
                <div class="sermon-detail-live-frame"><video data-live-hls data-stream-url="{{ $liveStream->playback_url }}" controls playsinline autoplay muted preload="none" aria-label="{{ $liveStream->title ?: 'IGM Church live stream' }}"></video><p class="sermon-detail-live-error live-error" hidden>Live playback is reconnecting. Please refresh or open the live page.</p></div>
            </section>
        @endif

        <div class="sermon-detail-columns">
            <main class="sermon-detail-main">
                @if($sermon->description)
                    <section class="sermon-panel"><h2>Sermon notes</h2><div class="sermon-notes">{{ strip_tags($sermon->description) }}</div></section>
                @endif

                @forelse($sermon->sermonlinks as $i => $link)
                    <section class="sermon-resource">
                        <div class="sermon-resource-head"><div><h3>{{ $link->title ?: 'Message recording' }}</h3><p>@if($link->date){{ \Carbon\Carbon::parse($link->date)->format('F j, Y') }}@else Sermon resource {{ $i + 1 }}@endif</p></div><span class="sermon-resource-badge">{{ $link->video_link ? 'Watch / listen' : ($link->audio_link ? 'Audio message' : 'Study notes') }}</span></div>
                        @if($link->video_embed_url)
                            <div class="sermon-player"><iframe src="{{ $link->video_embed_url }}" title="{{ $link->title ?: $sermon->title }}" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe></div>
                        @elseif($link->direct_video_url)
                            <div class="sermon-player"><video controls playsinline preload="metadata" src="{{ $link->direct_video_url }}">Your browser does not support video playback.</video></div>
                        @elseif($link->video_link)
                            <div class="sermon-file-link"><strong>Watch this message</strong><a class="sermon-link-out" href="{{ $link->video_link }}" target="_blank" rel="noopener noreferrer">Open video ↗</a></div>
                        @endif
                        @if($link->audio_link)
                            @php $audioUrl = \Illuminate\Support\Str::startsWith($link->audio_link, ['http://', 'https://']) ? $link->audio_link : $link->getFilePath($link->audio_link); @endphp
                            <div class="sermon-audio"><audio controls preload="none" src="{{ $audioUrl }}">Your browser does not support audio playback.</audio></div>
                        @endif
                        @if($link->pdf_link)
                            <a class="sermon-file-link" href="{{ $link->pdf_url_path }}" target="_blank" rel="noopener noreferrer"><strong>Download sermon notes and outline</strong><span class="sermon-link-out">Open PDF ↗</span></a>
                        @endif
                    </section>
                @empty
                    @unless($sermon->description)<section class="sermon-panel"><h2>Message resources</h2><p class="sermon-notes-empty">The recording and notes for this message will be added soon.</p></section>@endunless
                @endforelse
            </main>

            <aside class="sermon-detail-sidebar">
                <section class="sermon-speaker-card"><div class="sermon-speaker-cover"></div><div class="sermon-speaker-content"><h2>Message details</h2><div class="sermon-speaker">
                    @php $speakerAvatar = $sermon->user?->userprofile?->avatar_path; @endphp
                    @if($speakerAvatar)<img src="{{ $speakerAvatar }}" alt="">@else<span class="sermon-detail-avatar">{{ strtoupper(substr($sermon->user?->name ?? 'P', 0, 1)) }}</span>@endif
                    <div><div class="sermon-speaker-name">{{ $sermon->user?->name ?? 'IGM Church' }}</div><div class="sermon-speaker-role">{{ $sermon->user?->userprofile?->profession ?? 'Speaker' }}</div></div>
                </div><div class="sermon-sidebar-date">Published {{ $sermon->created_at?->format('F j, Y') }}</div>
                @php $shareUrl = urlencode(request()->url()); $shareTitle = urlencode($sermon->title); @endphp
                <div class="sermon-share"><a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Share on WhatsApp">WhatsApp</a><button type="button" onclick="navigator.clipboard.writeText(window.location.href).then(() => { this.textContent = 'Copied'; setTimeout(() => this.textContent = 'Copy link', 1500); })">Copy link</button></div>
                <a class="sermon-back" href="{{ route('web.sermons') }}">← Browse all sermons</a></div></section>
            </aside>
        </div>

        @if($recommended->isNotEmpty())
            <section class="sermon-recommend"><div class="sermon-recommend-head"><h2>More messages</h2><a class="sermon-read-link" href="{{ route('web.sermons') }}">View all →</a></div><div class="sermon-recommend-grid">@foreach($recommended as $recommendation)<a class="sermon-recommend-card" href="{{ route('web.sermon', $recommendation->id) }}"><h3>{{ $recommendation->title }}</h3><p>{{ $recommendation->user?->name ?? 'IGM Church' }} · {{ $recommendation->created_at?->format('M j, Y') }}</p></a>@endforeach</div></section>
        @endif
    </div>
</article>
@endsection
