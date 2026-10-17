@extends('theme::layout')

@section('title', $post->title . ' | IGM Church Blog')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($post->description ?? ''), 160))

@push('styles')
<style>
    .blog-detail{--navy:#082846;--deep:#061d34;--gold:#d8ad5d;--gold-light:#f0d08a;--ink:#142b43;--muted:#637487;--paper:#f8f7f3;background:var(--paper);color:var(--ink);padding:34px 0 76px}.blog-detail *{box-sizing:border-box}.blog-detail a{color:inherit;text-decoration:none}.blog-detail-wrap{width:min(1120px,calc(100% - 40px));margin:0 auto}
    .blog-detail-crumb{display:flex;align-items:center;gap:9px;margin-bottom:19px;color:#8190a0;font-size:11px}.blog-detail-crumb a{color:#946c25;font-weight:800}.blog-detail-crumb a:hover{color:var(--navy)}.blog-detail-crumb svg{width:13px;height:13px}
    .blog-detail-heading{max-width:900px;margin-bottom:23px}.blog-detail-category{display:inline-flex;align-items:center;gap:8px;border:1px solid #ead7ac;border-radius:99px;background:#fff8e9;padding:6px 11px;color:#916820;font-size:9px;font-weight:900;letter-spacing:.09em;text-transform:uppercase}.blog-detail-category:before{width:6px;height:6px;border-radius:50%;background:var(--gold-dark,#a97e32);content:""}.blog-detail-heading h1{margin:14px 0 14px;color:var(--navy);font-size:clamp(34px,5vw,56px);font-weight:800;line-height:1.08;letter-spacing:-.045em}.blog-detail-meta{display:flex;align-items:center;flex-wrap:wrap;gap:15px;color:var(--muted);font-size:11px}.blog-detail-meta span{display:inline-flex;align-items:center;gap:7px}.blog-detail-meta svg{width:15px;height:15px;color:#9a722b}
    .blog-detail-cover{position:relative;display:grid;min-height:clamp(240px,40vw,440px);place-items:center;overflow:hidden;margin-bottom:25px;border:1px solid #e4e2db;border-radius:18px;background:radial-gradient(circle at 80% 15%,rgba(216,173,93,.25),transparent 33%),linear-gradient(125deg,#061d34,#164a6e);box-shadow:0 14px 36px rgba(8,40,70,.12)}.blog-detail-cover img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}.blog-detail-cover:after{position:absolute;inset:60% 0 0;background:linear-gradient(transparent,rgba(4,22,39,.16));content:"";pointer-events:none}.blog-detail-cover-placeholder{color:var(--gold-light)}.blog-detail-cover-placeholder svg{width:58px;height:58px}
    .blog-detail-columns{display:grid;grid-template-columns:minmax(0,1fr) 275px;gap:22px;align-items:start}.blog-article,.blog-detail-aside-card,.blog-comments-card{border:1px solid #e7e6df;border-radius:15px;background:#fff;box-shadow:0 7px 25px rgba(8,40,70,.045)}.blog-article{min-width:0;padding:clamp(22px,4vw,42px)}.blog-article-content{color:#394e63;font-size:15px;line-height:1.9;overflow-wrap:anywhere}.blog-article-content>*:first-child{margin-top:0}.blog-article-content>*:last-child{margin-bottom:0}.blog-article-content h1,.blog-article-content h2,.blog-article-content h3,.blog-article-content h4{margin:1.7em 0 .65em;color:var(--navy);font-weight:800;line-height:1.28;letter-spacing:-.025em}.blog-article-content h2{font-size:27px}.blog-article-content h3{font-size:21px}.blog-article-content p{margin:0 0 1.2em}.blog-article-content a{color:#916820;text-decoration:underline;text-underline-offset:3px}.blog-article-content img{display:block;max-width:100%;height:auto;margin:24px auto;border-radius:12px}.blog-article-content blockquote{margin:24px 0;border-left:3px solid var(--gold);border-radius:0 10px 10px 0;background:#fbf8f0;padding:17px 20px;color:#526579;font-size:17px}.blog-article-content ul,.blog-article-content ol{padding-left:1.4em;margin:0 0 1.2em}.blog-article-content li{margin:.45em 0}.blog-article-content table{display:block;max-width:100%;overflow-x:auto;border-collapse:collapse}.blog-article-content td,.blog-article-content th{border:1px solid #e5e5e2;padding:9px 12px}.blog-article-content iframe{max-width:100%}
    .blog-article-tags{display:flex;align-items:center;flex-wrap:wrap;gap:8px;margin-top:27px;padding-top:20px;border-top:1px solid #efeee9}.blog-article-tags strong{margin-right:2px;color:var(--muted);font-size:10px}.blog-tag{border:1px solid #e8e7e2;border-radius:999px;background:#f8f8f6;padding:6px 10px;color:#53677a!important;font-size:10px;transition:border-color .18s,background .18s}.blog-tag:hover{border-color:#e3c987;background:#fff8e9}
    .blog-article-actions{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-top:23px;padding-top:18px;border-top:1px solid #efeee9}.blog-article-actions p{margin:0;color:var(--muted);font-size:11px}.blog-like,.blog-login-like{display:inline-flex;align-items:center;gap:8px;min-height:39px;border:1px solid #e5d7d7;border-radius:999px;background:#fff;padding:0 14px;color:#8a4a53!important;font-size:11px;font-weight:800;cursor:pointer;transition:background .18s,border-color .18s}.blog-like:hover,.blog-login-like:hover{border-color:#d78d95;background:#fff7f8}.blog-like.is-liked{border-color:#c55d6c;background:#c55d6c;color:white!important}.blog-like svg,.blog-login-like svg{width:16px;height:16px}
    .blog-detail-aside{display:grid;gap:14px;position:sticky;top:18px}.blog-detail-aside-card{padding:18px}.blog-aside-title{margin:0 0 14px;color:var(--navy);font-size:14px;font-weight:800}.blog-aside-info{display:grid;gap:12px}.blog-aside-info-row{display:flex;align-items:flex-start;gap:10px;color:var(--muted);font-size:11px;line-height:1.5}.blog-aside-icon{flex:none;display:grid;width:30px;height:30px;place-items:center;border-radius:9px;background:#f6f1e5;color:#986f26}.blog-aside-icon svg{width:15px;height:15px}.blog-aside-info-row strong{display:block;margin-bottom:2px;color:#344c63;font-size:10px}.blog-aside-back{display:flex;align-items:center;justify-content:center;gap:9px;min-height:43px;margin-top:14px;border-radius:9px;background:var(--navy);color:#fff!important;font-size:11px;font-weight:800;transition:background .18s}.blog-aside-back:hover{background:#123e64}.blog-aside-back svg{width:16px;height:16px}
    .blog-comments{margin-top:25px}.blog-comments-card{padding:clamp(20px,3vw,30px)}.blog-comments-head{display:flex;align-items:end;justify-content:space-between;gap:14px;margin-bottom:18px}.blog-comments-kicker{margin:0 0 5px;color:#9a722b;font-size:9px;font-weight:900;letter-spacing:.13em;text-transform:uppercase}.blog-comments-head h2{margin:0;color:var(--navy);font-size:24px;font-weight:800;letter-spacing:-.03em}.blog-comments-total{border-radius:999px;background:#f5f2e9;padding:7px 10px;color:#80602a;font-size:10px;font-weight:800;white-space:nowrap}.blog-comment-success,.blog-comment-errors{margin-bottom:16px;border-radius:10px;padding:12px 14px;font-size:12px}.blog-comment-success{border:1px solid #c8e6d4;background:#effaf3;color:#28734a}.blog-comment-errors{border:1px solid #f2caca;background:#fff4f4;color:#a43c3c}.blog-comment-list{display:grid;gap:11px;margin-bottom:21px}.blog-comment{display:flex;align-items:flex-start;gap:11px;border:1px solid #eeede8;border-radius:12px;background:#fcfcfa;padding:14px}.blog-comment-avatar{flex:none;display:grid;width:36px;height:36px;place-items:center;border-radius:50%;background:#f6e8c6;color:#805d23;font-size:13px;font-weight:900}.blog-comment-body{flex:1;min-width:0}.blog-comment-head{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:5px}.blog-comment-head strong{color:#314a62;font-size:11px}.blog-comment-head time{color:#8693a0;font-size:9px}.blog-comment-body p{margin:0;color:#5c6d7d;font-size:12px;line-height:1.65;white-space:pre-line;overflow-wrap:anywhere}.blog-comment-like{display:inline-flex;align-items:center;gap:5px;margin-top:8px;border:0;background:none;padding:2px 0;color:#7b8793;font-size:10px;cursor:pointer}.blog-comment-like.is-liked{color:#c55d6c}.blog-comment-like svg{width:13px;height:13px}.blog-comment-empty{margin:0 0 20px;border:1px dashed #dddcd5;border-radius:10px;background:#fbfaf7;padding:17px;color:var(--muted);font-size:12px;text-align:center}.blog-comment-form{border-top:1px solid #efeee9;padding-top:20px}.blog-comment-form h3{margin:0 0 6px;color:var(--navy);font-size:16px;font-weight:800}.blog-comment-form>p{margin:0 0 14px;color:var(--muted);font-size:11px}.blog-comment-form label{display:block;margin-bottom:6px;color:#44596e;font-size:10px;font-weight:800}.blog-comment-form textarea{display:block;width:100%;min-height:120px;resize:vertical;border:1px solid #dfe2e4;border-radius:10px;background:#fff;padding:12px;color:var(--ink);font:inherit;font-size:12px;line-height:1.6;outline:none}.blog-comment-form textarea:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(216,173,93,.17)}.blog-comment-submit{display:inline-flex;align-items:center;justify-content:center;min-height:41px;margin-top:11px;border:0;border-radius:9px;background:var(--navy);padding:0 17px;color:white;font:inherit;font-size:11px;font-weight:800;cursor:pointer}.blog-comment-submit:hover{background:#123e64}.blog-comment-auth{display:flex;flex-wrap:wrap;gap:9px;margin-top:14px}.blog-comment-auth a{display:inline-flex;align-items:center;justify-content:center;min-height:39px;border:1px solid #d7dce0;border-radius:9px;padding:0 13px;color:var(--navy);font-size:11px;font-weight:800}.blog-comment-auth a:first-child{border-color:var(--navy);background:var(--navy);color:white}.blog-detail-pagination{margin-top:15px}.blog-detail-pagination nav{display:flex;justify-content:center}.blog-detail-pagination nav>div:first-child{display:none}
    @media(max-width:850px){.blog-detail-columns{grid-template-columns:minmax(0,1fr) 235px;gap:16px}.blog-detail-aside-card{padding:15px}.blog-article{padding:25px}}
    @media(max-width:680px){.blog-detail{padding:24px 0 52px}.blog-detail-wrap{width:min(100% - 28px,1120px)}.blog-detail-columns{grid-template-columns:1fr}.blog-detail-aside{position:static;grid-row:1}.blog-detail-aside-card{padding:14px}.blog-detail-aside-card:first-child{display:none}.blog-aside-back{margin:0}.blog-detail-cover{min-height:clamp(220px,58vw,360px);border-radius:14px}.blog-detail-heading h1{font-size:clamp(31px,8vw,43px)}.blog-article{padding:22px}.blog-article-content{font-size:14px}.blog-article-content h2{font-size:23px}.blog-comments-card{padding:19px}.blog-comment-head{align-items:flex-start;flex-direction:column;gap:3px}}
</style>
@endpush

@section('content')
@php
    $postDate = $post->post_created_at ? \Carbon\Carbon::parse($post->post_created_at) : $post->created_at;
    $postCover = $post->AttachmentPath[0]['path'] ?? null;
@endphp

<main class="blog-detail">
    <div class="blog-detail-wrap">
        <nav class="blog-detail-crumb" aria-label="Breadcrumb"><a href="{{ route('web.home') }}">Home</a><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="m7 4 6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg><a href="{{ route('web.posts') }}">Blog</a><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="m7 4 6 6-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg><span aria-current="page">{{ \Illuminate\Support\Str::limit($post->title, 44) }}</span></nav>

        <header class="blog-detail-heading">
            @if($post->category)<span class="blog-detail-category">{{ $post->category->name }}</span>@endif
            <h1>{{ $post->title }}</h1>
            <div class="blog-detail-meta">
                @if($postDate)<span><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="3" y="4.5" width="14" height="12.5" rx="2" stroke="currentColor" stroke-width="1.4"/><path d="M6.5 3v3M13.5 3v3M3 8.5h14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>{{ $postDate->format('F j, Y') }}</span>@endif
                <span><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M3 4.5h14v9H9l-4 3v-3H3z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>{{ $comments->total() }} {{ \Illuminate\Support\Str::plural('comment', $comments->total()) }}</span>
            </div>
        </header>

        @if($postCover)
            <div class="blog-detail-cover"><div class="blog-detail-cover-placeholder" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M5 4.5h9l5 5v10H5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M14 4.5v5h5M8 14h8M8 17h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></div><img src="{{ $postCover }}" alt="{{ $post->title }}" onerror="this.style.display='none'"></div>
        @endif

        <div class="blog-detail-columns">
            <article class="blog-article">
                <div class="blog-article-content">{!! $post->description !!}</div>

                @if($post->tags->isNotEmpty())
                    <div class="blog-article-tags"><strong>Topics</strong>@foreach($post->tags as $tag)<a class="blog-tag" href="{{ route('web.posts', ['tag' => $tag->tag_name]) }}">#{{ $tag->tag_name }}</a>@endforeach</div>
                @endif

                <div class="blog-article-actions">
                    <p>Was this article helpful? Show your support.</p>
                    @auth
                        <button type="button" id="post-like-btn" class="blog-like {{ $postLiked ? 'is-liked' : '' }}" data-liked="{{ $postLiked ? 'true' : 'false' }}" data-url="{{ route('web.post.like', $post->id) }}" aria-pressed="{{ $postLiked ? 'true' : 'false' }}">
                            <svg viewBox="0 0 24 24" fill="{{ $postLiked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/></svg>
                            <span id="post-like-label">{{ $postLiked ? 'Liked' : 'Like' }}</span><span id="post-like-count">{{ $post->public_like_count }}</span>
                        </button>
                    @else
                        <a class="blog-login-like" href="{{ route('web.guest.login') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/></svg>Like <span id="post-like-count">{{ $post->public_like_count }}</span></a>
                    @endauth
                </div>
            </article>

            <aside class="blog-detail-aside" aria-label="Article details">
                <section class="blog-detail-aside-card">
                    <h2 class="blog-aside-title">Article details</h2>
                    <div class="blog-aside-info">
                        @if($postDate)<div class="blog-aside-info-row"><span class="blog-aside-icon"><svg viewBox="0 0 20 20" fill="none"><rect x="3" y="4.5" width="14" height="12.5" rx="2" stroke="currentColor" stroke-width="1.4"/><path d="M6.5 3v3M13.5 3v3M3 8.5h14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg></span><span><strong>Published</strong>{{ $postDate->format('M j, Y') }}</span></div>@endif
                        @if($post->category)<div class="blog-aside-info-row"><span class="blog-aside-icon"><svg viewBox="0 0 20 20" fill="none"><path d="M3 4h14v12H3zM6 7h8M6 10h8M6 13h5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg></span><span><strong>Category</strong>{{ $post->category->name }}</span></div>@endif
                        <div class="blog-aside-info-row"><span class="blog-aside-icon"><svg viewBox="0 0 20 20" fill="none"><path d="M3 4.5h14v9H9l-4 3v-3H3z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg></span><span><strong>Conversation</strong>{{ $comments->total() }} {{ \Illuminate\Support\Str::plural('comment', $comments->total()) }}</span></div>
                    </div>
                    <a class="blog-aside-back" href="{{ route('web.posts') }}"><svg viewBox="0 0 20 20" fill="none"><path d="M16.5 10h-12m5 5-5-5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>Back to all articles</a>
                </section>
                @if($post->tags->isNotEmpty())
                    <section class="blog-detail-aside-card"><h2 class="blog-aside-title">Topics in this article</h2><div class="blog-article-tags" style="margin:0;padding:0;border:0"><strong class="sr-only">Topics</strong>@foreach($post->tags as $tag)<a class="blog-tag" href="{{ route('web.posts', ['tag' => $tag->tag_name]) }}">#{{ $tag->tag_name }}</a>@endforeach</div></section>
                @endif
            </aside>
        </div>

        <section class="blog-comments" id="comments" aria-labelledby="blog-comments-title">
            <div class="blog-comments-card">
                <div class="blog-comments-head"><div><p class="blog-comments-kicker">Join the conversation</p><h2 id="blog-comments-title">Comments</h2></div><span class="blog-comments-total">{{ $comments->total() }} {{ \Illuminate\Support\Str::plural('comment', $comments->total()) }}</span></div>
                @if(session('comment_success'))<div class="blog-comment-success" role="status">{{ session('comment_success') }}</div>@endif
                @if($comments->isNotEmpty())
                    <div class="blog-comment-list">
                        @foreach($comments as $comment)
                            @php
                                $commentName = $comment->user?->name ?? $comment->guest_name ?? 'Guest';
                                $commentLiked = in_array($comment->id, $likedComments ?? []);
                            @endphp
                            <article class="blog-comment">
                                <span class="blog-comment-avatar" aria-hidden="true">{{ strtoupper(substr($commentName, 0, 1)) }}</span>
                                <div class="blog-comment-body">
                                    <div class="blog-comment-head"><strong>{{ $commentName }}</strong><time datetime="{{ $comment->created_at?->toIso8601String() }}">{{ $comment->created_at?->format('M j, Y · g:i A') }}</time></div>
                                    <p>{{ $comment->comments }}</p>
                                    <button type="button" class="blog-comment-like {{ $commentLiked ? 'is-liked' : '' }} comment-like-btn" data-liked="{{ $commentLiked ? 'true' : 'false' }}" data-url="{{ route('web.comment.like', $comment->id) }}" aria-pressed="{{ $commentLiked ? 'true' : 'false' }}"><svg viewBox="0 0 24 24" fill="{{ $commentLiked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/></svg><span class="comment-like-label">{{ $commentLiked ? 'Liked' : 'Like' }}</span><span class="comment-like-count">{{ $comment->public_like_count ?: '' }}</span></button>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    @if($comments->hasPages())<div class="blog-detail-pagination">{{ $comments->fragment('comments')->links() }}</div>@endif
                @else
                    <p class="blog-comment-empty">No comments yet. Share your thoughts on this article.</p>
                @endif

                <div class="blog-comment-form">
                    <h3>Leave a comment</h3>
                    @auth
                        @if($errors->any())<div class="blog-comment-errors" role="alert">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
                        @php
                            $authProfile = optional(auth()->user()->userprofile);
                            $authName = trim(($authProfile->firstname ?? '') . ' ' . ($authProfile->lastname ?? '')) ?: auth()->user()->name;
                        @endphp
                        <p>Commenting as <strong>{{ $authName }}</strong>. Comments are moderated before they appear.</p>
                        <form method="POST" action="{{ route('web.post.comment', $post->id) }}" novalidate>
                            @csrf
                            <label for="comment_text">Your comment</label>
                            <textarea id="comment_text" name="comment" maxlength="1000" required placeholder="Share your thoughts…">{{ old('comment') }}</textarea>
                            <button type="submit" class="blog-comment-submit">Submit comment</button>
                        </form>
                    @else
                        <p>Sign in or create an account to add a comment.</p>
                        <div class="blog-comment-auth"><a href="{{ route('web.guest.register') }}">Create account</a><a href="{{ route('web.guest.login') }}">Log in</a></div>
                    @endauth
                </div>
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
<script>
    (() => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const sendLike = async (button, onUpdate) => {
            if (button.dataset.busy === 'true') return;
            button.dataset.busy = 'true';
            try {
                const response = await fetch(button.dataset.url, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
                if (!response.ok) throw new Error('Like request failed');
                onUpdate(await response.json());
            } catch (_) {
                button.setAttribute('aria-label', 'Unable to update like. Please try again.');
            } finally {
                button.dataset.busy = 'false';
            }
        };

        const postButton = document.getElementById('post-like-btn');
        if (postButton) postButton.addEventListener('click', () => sendLike(postButton, (data) => {
            postButton.dataset.liked = data.liked ? 'true' : 'false';
            postButton.setAttribute('aria-pressed', data.liked ? 'true' : 'false');
            postButton.classList.toggle('is-liked', Boolean(data.liked));
            postButton.querySelector('svg')?.setAttribute('fill', data.liked ? 'currentColor' : 'none');
            document.getElementById('post-like-label').textContent = data.liked ? 'Liked' : 'Like';
            document.getElementById('post-like-count').textContent = data.count;
        }));

        document.querySelectorAll('.comment-like-btn').forEach((button) => button.addEventListener('click', () => sendLike(button, (data) => {
            button.dataset.liked = data.liked ? 'true' : 'false';
            button.setAttribute('aria-pressed', data.liked ? 'true' : 'false');
            button.classList.toggle('is-liked', Boolean(data.liked));
            button.querySelector('svg')?.setAttribute('fill', data.liked ? 'currentColor' : 'none');
            button.querySelector('.comment-like-label').textContent = data.liked ? 'Liked' : 'Like';
            button.querySelector('.comment-like-count').textContent = data.count > 0 ? data.count : '';
        })));
    })();
</script>
@endpush
