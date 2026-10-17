<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Resources\API\Guest\ShowGallery as ShowGalleryResource;
use App\Http\Resources\API\Guest\ShowPhotos as ShowPhotosResource;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Photos;
use App\Support\BranchFeed;

class GalleryController extends Controller
{
    //
    
	public function index($church_id)
    {
		$query = Gallery::where('church_id', $church_id);
        app(BranchFeed::class)->apply($query, app(BranchFeed::class)->currentId((int) $church_id));
        $gallery = $query->paginate(10);

        $gallery = ShowGalleryResource::collection($gallery);
        
        return $gallery;
    }

    public function show($church_id,$gallery_id)
    {
		$branchId = app(BranchFeed::class)->currentId((int) $church_id);
        $galleryQuery = Gallery::where([['id', $gallery_id], ['church_id', $church_id]]);
        app(BranchFeed::class)->apply($galleryQuery, $branchId);
        $gallery = $galleryQuery->firstOrFail();
        $photos = Photos::with('gallery')->where([['gallery_id',$gallery->id],['church_id',$church_id]])->paginate(10);

        $photos = ShowPhotosResource::collection($photos);

        return $photos; 
    }
}
