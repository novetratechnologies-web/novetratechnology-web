@extends('layouts.admin.layout')

@section('content')
<div class="sermon-library-admin">
    @include('partials.message')
    <header class="sermon-library-head">
        <div>
            <p class="sermon-library-eyebrow">MEDIA &amp; TEACHING</p>
            <h1>Sermon library</h1>
            <p>Organize teaching, recordings, audio, and study resources for your church.</p>
        </div>
        <a class="sermon-primary-action" href="{{ url('/admin/sermon/create') }}"><span aria-hidden="true">＋</span> Add a sermon</a>
    </header>

    <section class="sermon-library-tools" aria-label="Sermon search">
        <div class="sermon-library-count"><strong>{{ $sermons->total() }}</strong><span>{{ \Illuminate\Support\Str::plural('message', $sermons->total()) }} in your library</span></div>
        <form method="GET" action="{{ url('/admin/sermons') }}" class="sermon-library-search" role="search">
            <label class="sr-only" for="sermon-search">Search sermons</label>
            <span aria-hidden="true">⌕</span>
            <input id="sermon-search" type="search" name="q" value="{{ request('q') }}" placeholder="Search by title, notes, or speaker">
            @if(request('q'))<a class="sermon-clear-search" href="{{ url('/admin/sermons') }}">Clear</a>@endif
            <button type="submit">Search</button>
        </form>
    </section>

    @if($sermons->isNotEmpty())
        <div class="sermon-admin-grid">
            @foreach($sermons as $sermon)
                @php
                    $resources = $sermon->sermonlinks;
                    $speaker = trim(($sermon->user?->userprofile?->firstname ?? '') . ' ' . ($sermon->user?->userprofile?->lastname ?? '')) ?: ($sermon->user?->name ?? 'IGM Church');
                    $sermonArtwork = $sermon->CoverImagePath ?: $resources->first(fn ($resource) => $resource->youtube_thumbnail_url)?->youtube_thumbnail_url;
                @endphp
                <article class="sermon-admin-card">
                    <a class="sermon-admin-art" href="{{ url('/admin/sermon/show/' . $sermon->id) }}" aria-label="Manage {{ $sermon->title }}">
                        <div class="sermon-admin-art-placeholder" aria-hidden="true">✦</div>
                        @if($sermonArtwork)<img src="{{ $sermonArtwork }}" alt="" loading="lazy" onerror="this.style.display='none'">@endif
                        <span class="sermon-admin-date">{{ $sermon->created_at?->format('M j, Y') }}</span>
                        <span class="sermon-admin-play" aria-hidden="true">▶</span>
                    </a>
                    <div class="sermon-admin-card-body">
                        <div class="sermon-admin-meta"><span>{{ $resources->count() }} {{ \Illuminate\Support\Str::plural('resource', $resources->count()) }}</span><span>Published</span></div>
                        <h2><a href="{{ url('/admin/sermon/show/' . $sermon->id) }}">{{ $sermon->title }}</a></h2>
                        <p class="sermon-admin-description">{{ \Illuminate\Support\Str::limit(strip_tags($sermon->description ?? 'No sermon notes added yet.'), 112) }}</p>
                        <div class="sermon-admin-byline"><span class="sermon-admin-avatar">{{ strtoupper(substr($speaker, 0, 1)) }}</span><span>{{ $speaker }}</span><span class="sermon-admin-engagement">♥ {{ $sermon->sermonlikevote }}</span></div>
                        <div class="sermon-admin-card-actions">
                            <a class="sermon-manage-action" href="{{ url('/admin/sermon/show/' . $sermon->id) }}">Manage resources <span aria-hidden="true">→</span></a>
                            <div class="sermon-quick-actions"><a href="{{ url('/admin/sermon/edit/' . $sermon->id) }}" aria-label="Edit {{ $sermon->title }}">Edit</a>
                                <form action="{{ url('/admin/sermon/delete/' . $sermon->id) }}" method="POST" onsubmit="return confirm('Delete this sermon and all its chapters?')">@csrf @method('DELETE')<button type="submit">Delete</button></form>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        @if($sermons->hasPages())<div class="sermon-admin-pagination">{{ $sermons->links() }}</div>@endif
    @else
        <section class="sermon-library-empty"><span aria-hidden="true">✦</span><h2>{{ request('q') ? 'No sermons found' : 'Your sermon library is ready' }}</h2><p>{{ request('q') ? 'Try another title or speaker name, or clear your search.' : 'Add your first sermon, then attach its video, audio, and study materials.' }}</p>@if(request('q'))<a href="{{ url('/admin/sermons') }}">Clear search</a>@else<a class="sermon-primary-action" href="{{ url('/admin/sermon/create') }}">＋ Add your first sermon</a>@endif</section>
    @endif
</div>
@endsection

@push('styles')
<style>
.sermon-library-admin{--sl-navy:#082846;--sl-gold:#c99b45;--sl-ink:#18334d;--sl-muted:#718194;max-width:1440px;margin:0 auto;padding:10px 4px 38px;color:var(--sl-ink)}.sermon-library-head{display:flex;align-items:center;justify-content:space-between;gap:20px;margin:4px 0 25px}.sermon-library-eyebrow{margin:0 0 7px;color:#a7792a;font-size:10px;font-weight:900;letter-spacing:.16em}.sermon-library-head h1{margin:0;color:var(--sl-navy);font-size:clamp(25px,3vw,34px);font-weight:800;letter-spacing:-.04em}.sermon-library-head p:last-child{margin:7px 0 0;color:var(--sl-muted);font-size:13px}.sermon-primary-action{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:44px;padding:0 17px;border-radius:10px;background:var(--sl-navy);color:white!important;font-size:12px;font-weight:800;text-decoration:none;box-shadow:0 7px 16px #08284620;transition:transform .18s,background .18s}.sermon-primary-action:hover{background:#123d63;transform:translateY(-1px)}.sermon-library-tools{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:14px 16px;margin-bottom:18px;border:1px solid #e5eaf0;border-radius:13px;background:#fff}.sermon-library-count{display:flex;align-items:baseline;gap:7px;white-space:nowrap}.sermon-library-count strong{color:var(--sl-navy);font-size:20px}.sermon-library-count span{color:var(--sl-muted);font-size:11px}.sermon-library-search{display:flex;align-items:center;gap:9px;width:min(100%,490px);min-height:40px;padding:4px 5px 4px 12px;border:1px solid #e3e8ed;border-radius:9px;background:#fbfcfd}.sermon-library-search>span{color:#9aa6b2;font-size:21px;line-height:1}.sermon-library-search input{min-width:0;flex:1;border:0;outline:0;background:transparent;color:var(--sl-ink);font-size:12px}.sermon-library-search button{min-height:31px;padding:0 12px;border:0;border-radius:7px;background:#edf2f6;color:var(--sl-navy);font-size:11px;font-weight:800;cursor:pointer}.sermon-clear-search{color:#8b6729;font-size:11px;font-weight:700}.sermon-admin-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:17px}.sermon-admin-card{overflow:hidden;border:1px solid #e4e9ee;border-radius:14px;background:#fff;box-shadow:0 7px 22px #08284608;transition:transform .2s,box-shadow .2s}.sermon-admin-card:hover{transform:translateY(-3px);box-shadow:0 14px 28px #08284616}.sermon-admin-art{position:relative;display:block;height:170px;overflow:hidden;background:#092846}.sermon-admin-art-placeholder{position:absolute;inset:0;display:grid;place-items:center;color:#e9c576;font-size:35px;background:radial-gradient(circle at 80% 14%,#d9ad5d45,transparent 32%),linear-gradient(135deg,#082846,#205071)}.sermon-admin-art img{position:relative;width:100%;height:100%;object-fit:cover;transition:transform .3s}.sermon-admin-card:hover .sermon-admin-art img{transform:scale(1.04)}.sermon-admin-date{position:absolute;left:12px;top:12px;padding:6px 9px;border-radius:6px;background:#071c31d9;color:white;font-size:10px;font-weight:700}.sermon-admin-play{position:absolute;right:12px;bottom:12px;width:34px;height:34px;display:grid;place-items:center;border-radius:50%;background:#d8ad5d;color:#082846;font-size:11px}.sermon-admin-card-body{padding:15px 16px 13px}.sermon-admin-meta{display:flex;justify-content:space-between;align-items:center;color:#8c6b30;font-size:9px;font-weight:800;letter-spacing:.04em;text-transform:uppercase}.sermon-admin-meta span:last-child{color:#45765b}.sermon-admin-card h2{margin:9px 0 6px;font-size:17px;line-height:1.3;letter-spacing:-.02em}.sermon-admin-card h2 a{color:var(--sl-navy);text-decoration:none}.sermon-admin-description{min-height:36px;margin:0;color:var(--sl-muted);font-size:11px;line-height:1.6}.sermon-admin-byline{display:flex;align-items:center;gap:8px;margin-top:13px;color:#607387;font-size:10px}.sermon-admin-avatar{width:25px;height:25px;display:grid;place-items:center;border-radius:50%;background:#f4efe2;color:#8e692b;font-weight:900}.sermon-admin-engagement{margin-left:auto;color:#758496}.sermon-admin-card-actions{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:13px;padding-top:11px;border-top:1px solid #eef1f3}.sermon-manage-action{color:#8b6729;font-size:10px;font-weight:900;text-decoration:none}.sermon-quick-actions{display:flex;gap:10px}.sermon-quick-actions a,.sermon-quick-actions button{padding:0;border:0;background:none;color:#62758a;font-size:10px;font-weight:700;text-decoration:none;cursor:pointer}.sermon-quick-actions button{color:#a45656}.sermon-quick-actions form{margin:0}.sermon-admin-pagination{margin-top:22px}.sermon-library-empty{padding:58px 20px;border:1px dashed #d9e0e6;border-radius:14px;background:#fff;text-align:center}.sermon-library-empty>span{color:#bc9140;font-size:32px}.sermon-library-empty h2{margin:10px 0 6px;color:var(--sl-navy);font-size:19px}.sermon-library-empty p{margin:0 auto 17px;max-width:440px;color:var(--sl-muted);font-size:12px;line-height:1.6}.sermon-library-empty>a:not(.sermon-primary-action){color:#8b6729;font-size:12px;font-weight:800}
@media(max-width:1000px){.sermon-admin-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:640px){.sermon-library-admin{padding:0 0 28px}.sermon-library-head{align-items:stretch;flex-direction:column;margin-bottom:17px}.sermon-library-head .sermon-primary-action{width:100%}.sermon-library-tools{align-items:stretch;flex-direction:column;padding:13px}.sermon-library-search{width:100%}.sermon-admin-grid{grid-template-columns:1fr;gap:12px}.sermon-admin-art{height:190px}.sermon-admin-card-body{padding:14px}.sermon-library-count strong{font-size:18px}}
</style>
@endpush
