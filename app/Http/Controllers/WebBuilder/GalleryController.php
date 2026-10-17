<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Church;
use App\Support\BranchFeed;

class GalleryController extends Controller
{
    public function index()
    {
        $church = Church::first();
        $branchId = app(BranchFeed::class)->currentId($church?->id);
        $query = Gallery::query()->where('church_id', $church?->id)->withCount('photos')->orderByDesc('created_at');
        app(BranchFeed::class)->apply($query, $branchId);
        $galleries = $query->paginate(12);

        return view('theme::gallery_index', compact('galleries'));
    }

    public function show($id)
    {
        $church = Church::first();
        $branchId = app(BranchFeed::class)->currentId($church?->id);
        $query = Gallery::query()->where('church_id', $church?->id)->with('photos');
        app(BranchFeed::class)->apply($query, $branchId);
        $gallery = $query->findOrFail($id);

        return view('theme::gallery', compact('gallery'));
    }
}
