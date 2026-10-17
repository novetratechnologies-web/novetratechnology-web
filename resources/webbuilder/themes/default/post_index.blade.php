@extends('theme::layout')

@section('title', 'Blog | IGM Church')
@section('meta_description', 'Read the latest published articles and updates from IGM Church.')

@push('styles')
<style>
    .blog-index{--navy:#082846;--deep:#061d34;--gold:#d8ad5d;--gold-light:#f0d08a;--ink:#142b43;--muted:#637487;--paper:#f8f7f3;background:var(--paper);color:var(--ink)}
    .blog-index *{box-sizing:border-box}.blog-index a{color:inherit;text-decoration:none}.blog-wrap{width:min(1160px,calc(100% - 40px));margin:auto}
    .blog-hero{position:relative;overflow:hidden;background:radial-gradient(ellipse at 82% 17%,rgba(216,173,93,.22),transparent 34%),linear-gradient(115deg,var(--deep),var(--navy));color:#fff;padding:62px 0 48px}.blog-hero:after{content:"";position:absolute;right:-130px;bottom:-310px;width:510px;height:510px;border:1px solid rgba(240,208,138,.14);border-radius:50%;box-shadow:0 0 0 40px rgba(240,208,138,.035),0 0 0 82px rgba(240,208,138,.023)}.blog-hero-inner{position:relative;z-index:1;max-width:760px}.blog-kicker{display:inline-flex;align-items:center;gap:9px;color:var(--gold-light);font-size:10px;font-weight:900;letter-spacing:.17em;text-transform:uppercase}.blog-kicker:before{content:"";width:24px;height:1px;background:currentColor}.blog-hero h1{margin:14px 0 12px;color:white;font-size:clamp(38px,6vw,62px);font-weight:800;line-height:1.02;letter-spacing:-.05em}.blog-hero h1 span{color:var(--gold-light)}.blog-hero p{max-width:570px;margin:0;color:rgba(255,255,255,.76);font-size:15px;line-height:1.75}.blog-crumb{display:flex;align-items:center;gap:9px;margin-top:23px;color:rgba(255,255,255,.58);font-size:11px}.blog-crumb a:hover{color:var(--gold-light)}
    .blog-content{padding:34px 0 55px}.blog-columns{display:grid;grid-template-columns:minmax(0,1fr) 265px;gap:22px;align-items:start}.blog-main{min-width:0}.blog-toolbar{display:flex;align-items:end;justify-content:space-between;gap:16px;margin-bottom:17px}.blog-toolbar-kicker{margin:0 0 5px;color:#9a722b;font-size:10px;font-weight:900;letter-spacing:.14em;text-transform:uppercase}.blog-toolbar h2{margin:0;color:var(--navy);font-size:29px;font-weight:800;letter-spacing:-.035em}.blog-result-count{margin:5px 0 0;color:var(--muted);font-size:11px}.blog-category-select{height:43px;min-width:150px;border:1px solid #e5e6e8;border-radius:10px;background:white;padding:0 12px;color:var(--ink);font:inherit;font-size:11px;outline:none}.blog-category-select:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(216,173,93,.17)}
    .blog-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.blog-card{min-width:0;overflow:hidden;border:1px solid #e7e6df;border-radius:13px;background:#fff;box-shadow:0 6px 22px rgba(8,40,70,.045);transition:transform .2s,box-shadow .2s,border-color .2s}.blog-card:hover{transform:translateY(-3px);border-color:#e0c98f;box-shadow:0 14px 28px rgba(8,40,70,.1)}.blog-card-image{position:relative;height:clamp(150px,16vw,190px);overflow:hidden;background:radial-gradient(circle at 75% 15%,rgba(216,173,93,.24),transparent 35%),linear-gradient(135deg,#082846,#164a6e)}.blog-card-image img{position:relative;z-index:1;display:block;width:100%;height:100%;object-fit:cover;transition:transform .4s}.blog-card:hover .blog-card-image img{transform:scale(1.04)}.blog-card-placeholder{position:absolute;inset:0;display:grid;place-items:center;color:var(--gold-light)}.blog-card-placeholder svg{width:42px;height:42px}.blog-card-category{position:absolute;z-index:2;top:12px;left:12px;max-width:calc(100% - 24px);overflow:hidden;border-radius:999px;background:var(--gold);padding:6px 10px;color:var(--deep);font-size:9px;font-weight:900;letter-spacing:.07em;text-overflow:ellipsis;text-transform:uppercase;white-space:nowrap}.blog-card-body{padding:13px 14px 14px}.blog-card-meta{display:flex;align-items:center;gap:12px;color:#728195;font-size:9px}.blog-card-meta span{display:inline-flex;align-items:center;gap:5px}.blog-card-meta svg{width:12px;height:12px;color:#9a722b}.blog-card h3{margin:9px 0 6px;color:var(--navy);font-size:15px;font-weight:800;line-height:1.4;letter-spacing:-.015em}.blog-card-description{display:-webkit-box;min-height:38px;overflow:hidden;margin:0;color:var(--muted);font-size:11px;line-height:1.65;-webkit-line-clamp:2;-webkit-box-orient:vertical}.blog-card-tags{display:flex;gap:5px;overflow:hidden;margin-top:8px}.blog-card-tags span{flex:none;border-radius:999px;background:#f2f3f4;padding:3px 7px;color:#617184;font-size:9px}.blog-card-footer{margin-top:11px;color:#a06f16;font-size:10px;font-weight:900}.blog-empty{grid-column:1/-1;border:1px dashed #d9d8d0;border-radius:13px;background:white;padding:48px 20px;text-align:center}.blog-empty svg{width:38px;height:38px;margin:0 auto 10px;color:#a77d33}.blog-empty strong{display:block;margin-bottom:6px;color:var(--navy);font-size:17px}.blog-empty span{color:var(--muted);font-size:12px}.blog-pagination{margin-top:23px}.blog-pagination nav{display:flex;justify-content:center}.blog-pagination nav>div:first-child{display:none}
    .blog-sidebar{display:grid;gap:16px;border-left:1px solid #e8e9e8;padding-left:16px}.blog-side-card{border:1px solid #e7e8e8;border-radius:13px;background:#fff;padding:15px;box-shadow:0 5px 18px rgba(8,40,70,.035)}.blog-search{display:flex;align-items:center;gap:6px;margin-bottom:17px;border:1px solid #e3e6e8;border-radius:999px;background:white;padding:5px 5px 5px 12px}.blog-search input{width:100%;min-width:0;border:0;outline:none;color:var(--ink);font:inherit;font-size:10px}.blog-search button{flex:none;display:grid;width:31px;height:31px;place-items:center;border:0;border-radius:50%;background:var(--navy);color:white;cursor:pointer}.blog-search button svg{width:14px;height:14px}.blog-side-title{display:flex;align-items:center;gap:8px;margin:0 0 10px;color:var(--navy);font-size:13px;font-weight:800}.blog-side-title svg{width:17px;height:17px;color:#9a722b}.blog-category-list{display:grid;gap:4px}.blog-category-link{display:flex;align-items:center;justify-content:space-between;gap:8px;min-height:34px;border-radius:8px;padding:7px 9px;color:#34495e!important;font-size:10px;transition:background .18s}.blog-category-link:hover{background:#f7f5ef}.blog-category-link.is-active{background:#f8e4b4;color:#243c53!important;font-weight:800}.blog-category-link span:last-child{min-width:23px;border-radius:999px;background:#edf0f3;padding:3px 6px;color:#526579;text-align:center;font-size:9px}.blog-category-link.is-active span:last-child{background:#d8ad5d;color:#fff}.blog-recent-list{display:grid;gap:10px}.blog-recent{display:grid;grid-template-columns:67px minmax(0,1fr);align-items:center;gap:9px}.blog-recent-image{position:relative;width:67px;height:52px;overflow:hidden;border-radius:7px;background:linear-gradient(135deg,#082846,#164a6e)}.blog-recent-image img{position:relative;z-index:1;width:100%;height:100%;object-fit:cover}.blog-recent-copy{min-width:0}.blog-recent-copy strong{display:-webkit-box;overflow:hidden;color:var(--navy);font-size:10px;line-height:1.4;-webkit-line-clamp:2;-webkit-box-orient:vertical}.blog-recent-copy small{display:block;margin-top:4px;color:#8090a0;font-size:9px}.blog-no-recent{color:var(--muted);font-size:11px;line-height:1.6}
    @media(max-width:900px){.blog-columns{grid-template-columns:minmax(0,1fr) 235px;gap:17px}.blog-sidebar{padding-left:12px}.blog-card-image{height:165px}}
    @media(max-width:700px){.blog-columns{grid-template-columns:1fr}.blog-sidebar{grid-template-columns:repeat(2,minmax(0,1fr));border:0;padding:0}.blog-sidebar .blog-side-card:first-child{grid-row:span 2}.blog-toolbar{align-items:start;flex-direction:column}.blog-category-select{width:100%}}
    @media(max-width:540px){.blog-wrap{width:min(100% - 28px,1160px)}.blog-hero{padding:50px 0 39px}.blog-hero p{font-size:13px}.blog-content{padding:27px 0 45px}.blog-grid{grid-template-columns:1fr;gap:13px}.blog-card-image{height:210px}.blog-toolbar h2{font-size:25px}.blog-sidebar{grid-template-columns:1fr}.blog-sidebar .blog-side-card:first-child{grid-row:auto}}
</style>
@endpush

@section('content')
<div class="blog-index">
    <section class="blog-hero"><div class="blog-wrap blog-hero-inner">
        <span class="blog-kicker">IGM Church blog</span>
        <h1>Stories from our<br><span>church family.</span></h1>
        <p>Read the latest articles and updates published by IGM Church.</p>
        <nav class="blog-crumb" aria-label="Breadcrumb"><a href="{{ route('web.home') }}">Home</a><span>/</span><span aria-current="page">Blog</span></nav>
    </div></section>

    @if($topwidget->isNotEmpty())<div class="blog-wrap" style="padding-top:24px">@foreach($topwidget as $widget){!! $widget->content !!}@endforeach</div>@endif

    <main class="blog-wrap blog-content">
        <div class="blog-columns">
            <section class="blog-main" aria-labelledby="blog-list-title">
                <div class="blog-toolbar">
                    <div><p class="blog-toolbar-kicker">Latest articles</p><h2 id="blog-list-title">{{ $search ? 'Search results' : 'From our blog' }}</h2>
                        <p class="blog-result-count">{{ $posts->total() }} {{ \Illuminate\Support\Str::plural('article', $posts->total()) }}{{ $search ? ' matching “' . $search . '”' : ' published' }}
                            @if($activeCategoryId && ($activeCategory = $categories->firstWhere('id', $activeCategoryId))) · {{ $activeCategory->name }} @endif
                            @if($activeTag) · #{{ $activeTag }} @endif
                        </p>
                    </div>
                    @if($categories->isNotEmpty())
                        <form method="GET" action="{{ route('web.posts') }}">
                            @if($search)<input type="hidden" name="q" value="{{ $search }}">@endif
                            @if($activeTag)<input type="hidden" name="tag" value="{{ $activeTag }}">@endif
                            <select class="blog-category-select" name="category" aria-label="Filter blog by category" onchange="this.form.submit()">
                                <option value="">All categories</option>
                                @foreach($categories as $category)<option value="{{ $category->id }}" @selected((string) $activeCategoryId === (string) $category->id)>{{ $category->name }}</option>@endforeach
                            </select>
                        </form>
                    @endif
                </div>

                @if($activeCategoryId || $activeTag || $search)
                    <p style="margin:0 0 14px;color:#637487;font-size:11px">Filtered articles · <a href="{{ route('web.posts') }}" style="color:#956e2d;font-weight:800">Clear filters</a></p>
                @endif

                <div class="blog-grid">
                    @forelse($posts as $post)
                        @php($postCover = $post->AttachmentPath[0]['path'] ?? null)
                        <a class="blog-card" href="{{ route('web.post', $post->id) }}">
                            <div class="blog-card-image">
                                <div class="blog-card-placeholder" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M5 4.5h9l5 5v10H5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M14 4.5v5h5M8 14h8M8 17h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></div>
                                @if($postCover)<img src="{{ $postCover }}" alt="{{ $post->title }}" loading="lazy" onerror="this.style.display='none'">@endif
                                @if($post->category)<span class="blog-card-category">{{ $post->category->name }}</span>@endif
                            </div>
                            <div class="blog-card-body">
                                <div class="blog-card-meta"><span><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="3" y="4.5" width="14" height="12.5" rx="2" stroke="currentColor" stroke-width="1.4"/><path d="M6.5 3v3M13.5 3v3M3 8.5h14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>{{ $post->post_created_at?->format('M j, Y') }}</span><span><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M3 4.5h14v9H9l-4 3v-3H3z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>{{ $post->approved_comments_count }} {{ \Illuminate\Support\Str::plural('Comment', $post->approved_comments_count) }}</span></div>
                                <h3>{{ $post->title }}</h3>
                                <p class="blog-card-description">{{ \Illuminate\Support\Str::limit(strip_tags($post->description ?? ''), 140) }}</p>
                                @if($post->tags->isNotEmpty())<div class="blog-card-tags">@foreach($post->tags->take(3) as $tag)<span>#{{ $tag->tag_name }}</span>@endforeach</div>@endif
                                <div class="blog-card-footer">Read article <span aria-hidden="true">→</span></div>
                            </div>
                        </a>
                    @empty
                        <div class="blog-empty"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 4.5h9l5 5v10H5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M14 4.5v5h5M8 14h8M8 17h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg><strong>{{ $search || $activeCategoryId || $activeTag ? 'No articles match these filters' : 'No published articles yet' }}</strong><span>{{ $search || $activeCategoryId || $activeTag ? 'Try another search or clear the filters.' : 'Published blog posts will appear here.' }}</span></div>
                    @endforelse
                </div>
                @if($posts->hasPages())<div class="blog-pagination">{{ $posts->links() }}</div>@endif
            </section>

            <aside class="blog-sidebar" aria-label="Blog tools and recent articles">
                <section class="blog-side-card">
                    <form class="blog-search" method="GET" action="{{ route('web.posts') }}" role="search">
                        <input type="search" name="q" value="{{ $search }}" placeholder="Search articles…" aria-label="Search blog articles">
                        @if($activeCategoryId)<input type="hidden" name="category" value="{{ $activeCategoryId }}">@endif
                        @if($activeTag)<input type="hidden" name="tag" value="{{ $activeTag }}">@endif
                        <button type="submit" aria-label="Search"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><circle cx="8.5" cy="8.5" r="5.5" stroke="currentColor" stroke-width="1.7"/><path d="m13 13 4 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></button>
                    </form>
                    <h2 class="blog-side-title"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="3" y="3.5" width="14" height="13" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M6 7h8M6 10h8M6 13h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>Categories</h2>
                    <div class="blog-category-list">
                        <a class="blog-category-link {{ !$activeCategoryId && !$activeTag ? 'is-active' : '' }}" href="{{ route('web.posts', array_filter(['q' => $search ?: null])) }}"><span>All posts</span><span>{{ $publishedPostsCount }}</span></a>
                        @foreach($categories as $category)
                            <a class="blog-category-link {{ (string) $activeCategoryId === (string) $category->id ? 'is-active' : '' }}" href="{{ route('web.posts', array_filter(['category' => $category->id, 'q' => $search ?: null])) }}"><span>{{ $category->name }}</span><span>{{ $category->posts_count }}</span></a>
                        @endforeach
                    </div>
                </section>

                <section class="blog-side-card">
                    <h2 class="blog-side-title"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M4 5.5h12v11H4zM6.5 3v5M13.5 3v5M4 9h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="m8 13 1.3 1.3L12.5 11" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>Recent posts</h2>
                    @if($recentPosts->isNotEmpty())
                        <div class="blog-recent-list">
                            @foreach($recentPosts as $recentPost)
                                @php($recentCover = $recentPost->AttachmentPath[0]['path'] ?? null)
                                <a href="{{ route('web.post', $recentPost->id) }}" class="blog-recent">
                                    <span class="blog-recent-image">@if($recentCover)<img src="{{ $recentCover }}" alt="" loading="lazy" onerror="this.style.display='none'">@endif</span>
                                    <span class="blog-recent-copy"><strong>{{ $recentPost->title }}</strong><small>{{ $recentPost->post_created_at?->format('M j, Y') }}</small></span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="blog-no-recent">No published posts to show yet.</p>
                    @endif
                </section>
            </aside>
        </div>
    </main>

    @if($bottomwidget->isNotEmpty())<div class="blog-wrap" style="padding-bottom:28px">@foreach($bottomwidget as $widget){!! $widget->content !!}@endforeach</div>@endif
</div>
@endsection
