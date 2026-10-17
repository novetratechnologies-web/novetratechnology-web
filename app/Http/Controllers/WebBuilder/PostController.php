<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostComment;
use App\Models\Widget;
use App\Support\BranchFeed;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $church = request()->attributes->get('_church');
        $branchId = app(BranchFeed::class)->currentId($church?->id);
        $search = trim((string) $request->query('q', ''));

        $categories = PostCategory::withCount(['posts' => function ($q) use ($church, $branchId) {
            $q->where('is_posted', 1)->where('status', 'posted');
            if ($church) $q->where('church_id', $church->id);
            $q->where(function ($branchQuery) use ($branchId) { $branchQuery->whereNull('branch_id')->when($branchId, fn ($query) => $query->orWhere('branch_id', $branchId)); });
        }])
            ->where('status', 1)
            ->when($church, fn($q) => $q->where('church_id', $church->id))
            ->having('posts_count', '>', 0)
            ->orderBy('name')
            ->get();

        $activeCategoryId = request('category');
        $activeTag         = request('tag');

        $postsQuery = Post::with(['category', 'tags'])
            ->withCount(['postComment as approved_comments_count' => function ($query) {
                $query->where('status', 1)->whereNull('deleted_at');
            }])
            ->where('is_posted', 1)
            ->where('status', 'posted')
            ->when($church, fn($q) => $q->where('church_id', $church->id))
            ->where(function ($q) use ($branchId) { $q->whereNull('branch_id')->when($branchId, fn ($query) => $query->orWhere('branch_id', $branchId)); })
            ->when($activeCategoryId, fn($q) => $q->where('category_id', $activeCategoryId))
            ->when($activeTag, fn($q) => $q->whereHas('tags', fn($tq) => $tq->where('tag_name', $activeTag)))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            });

        $posts = (clone $postsQuery)
            ->orderBy('post_created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $recentPosts = Post::with('category')
            ->where('is_posted', 1)
            ->where('status', 'posted')
            ->when($church, fn($q) => $q->where('church_id', $church->id))
            ->where(function ($q) use ($branchId) { $q->whereNull('branch_id')->when($branchId, fn ($query) => $query->orWhere('branch_id', $branchId)); })
            ->orderBy('post_created_at', 'desc')
            ->limit(5)
            ->get();

        $publishedPostsCount = Post::where('is_posted', 1)
            ->where('status', 'posted')
            ->when($church, fn($q) => $q->where('church_id', $church->id))
            ->where(function ($q) use ($branchId) { $q->whereNull('branch_id')->when($branchId, fn ($query) => $query->orWhere('branch_id', $branchId)); })
            ->count();

        $widgets = Widget::where('page', 'post')
            ->orderBy('display_order')
            ->get();

        $topwidget = $widgets->where('position', 'top');
        $bottomwidget = $widgets->where('position', 'bottom');

        return view('theme::post_index', compact('posts', 'categories', 'activeCategoryId', 'activeTag', 'search', 'recentPosts', 'publishedPostsCount', 'topwidget', 'bottomwidget'));
    }

    public function show(Request $request, $id)
    {
        $church = $request->attributes->get('_church');
        $branchId = app(BranchFeed::class)->currentId($church?->id);
        $post = Post::with(['category', 'tags'])->where('id', $id)->where('is_posted', 1)->where('status', 'posted')->when($church, fn ($query) => $query->where('church_id', $church->id))->where(function ($query) use ($branchId) { $query->whereNull('branch_id')->when($branchId, fn ($q) => $q->orWhere('branch_id', $branchId)); })->firstOrFail();

        $comments = PostComment::with('user')
            ->where('entity_id', $post->id)
            ->where('entity_name', 'App\\Models\\Post')
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'comments_page');

        $likedPosts    = $request->session()->get('liked_posts', []);
        $likedComments = $request->session()->get('liked_comments', []);
        $postLiked     = in_array($post->id, $likedPosts);

        return view('theme::post', compact('post', 'comments', 'postLiked', 'likedComments'));
    }

    public function storeComment(Request $request, $id)
    {
        $church = $request->attributes->get('_church');
        $branchId = app(BranchFeed::class)->currentId($church?->id);
        $post = Post::where('id', $id)->where('is_posted', 1)->where('status', 'posted')->when($church, fn ($query) => $query->where('church_id', $church->id))->where(function ($query) use ($branchId) { $query->whereNull('branch_id')->when($branchId, fn ($q) => $q->orWhere('branch_id', $branchId)); })->firstOrFail();

        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $user    = auth()->user();
        $profile = optional($user->userprofile);

        PostComment::create([
            'user_id'     => $user->id,
            'guest_name'  => trim(($profile->firstname ?? '') . ' ' . ($profile->lastname ?? '')) ?: $user->name,
            'guest_email' => $user->email,
            'entity_id'   => $post->id,
            'entity_name' => 'App\\Models\\Post',
            'comments'    => $request->input('comment'),
            'status'      => 0,
        ]);

        return redirect()->route('web.post', $id)
            ->with('comment_success', 'Your comment has been submitted and is awaiting moderation. Thank you!');
    }

    public function toggleLike(Request $request, $id)
    {
        $church = $request->attributes->get('_church');
        $branchId = app(BranchFeed::class)->currentId($church?->id);
        $post = Post::where('id', $id)->where('is_posted', 1)->where('status', 'posted')->when($church, fn ($query) => $query->where('church_id', $church->id))->where(function ($query) use ($branchId) { $query->whereNull('branch_id')->when($branchId, fn ($q) => $q->orWhere('branch_id', $branchId)); })->firstOrFail();

        $likedPosts = $request->session()->get('liked_posts', []);

        if (in_array($id, $likedPosts)) {
            // Unlike
            $post->decrement('public_like_count');
            $request->session()->put('liked_posts', array_values(array_diff($likedPosts, [$id])));
            $liked = false;
        } else {
            // Like
            $post->increment('public_like_count');
            $likedPosts[] = $id;
            $request->session()->put('liked_posts', $likedPosts);
            $liked = true;
        }

        return response()->json(['liked' => $liked, 'count' => $post->fresh()->public_like_count]);
    }

    public function toggleCommentLike(Request $request, $id)
    {
        $comment = PostComment::where('id', $id)->where('status', 1)->firstOrFail();

        $likedComments = $request->session()->get('liked_comments', []);

        if (in_array($id, $likedComments)) {
            $comment->decrement('public_like_count');
            $request->session()->put('liked_comments', array_values(array_diff($likedComments, [$id])));
            $liked = false;
        } else {
            $comment->increment('public_like_count');
            $likedComments[] = $id;
            $request->session()->put('liked_comments', $likedComments);
            $liked = true;
        }

        return response()->json(['liked' => $liked, 'count' => $comment->fresh()->public_like_count]);
    }
}
