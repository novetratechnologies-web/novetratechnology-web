<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\API\ShowGallery as ShowGalleryResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Photos;
use App\Traits\Common;
use App\Support\BranchFeed;
use App\Http\Resources\API\ShowPhotos as ShowPhotosResource;
use OpenApi\Attributes as OA;   // ← add this line

/**
 * GalleryController
 *
 * Delivers gallery and photo content via API.
 * Returns gallery listings with associated photos.
 *
 * @package App\Http\Controllers\Api
 * @uses Common Trait for helper functions
 */
class GalleryController extends Controller
{
    use Common;

    public function show()
    {
        $user = Auth::user();
        $query = Gallery::with('photos')->where('church_id', $user->church_id);
        app(BranchFeed::class)->apply($query, app(BranchFeed::class)->currentId((int) $user->church_id));
        $slider = $query->get();
        $gallery = ShowGalleryResource::collection($slider);
        return response()->json($gallery);
    }

    public function view($gallery_id, $church_id)
    {
        abort_unless((int) Auth::user()->church_id === (int) $church_id, 404);
        $branchId = app(BranchFeed::class)->currentId((int) $church_id);
        $galleryQuery = Gallery::where([['id', $gallery_id], ['church_id', $church_id]]);
        app(BranchFeed::class)->apply($galleryQuery, $branchId);
        $gallery = $galleryQuery->firstOrFail();
        $photos = Photos::where([['gallery_id', $gallery->id], ['church_id', $church_id]])->get();
        $photos = ShowPhotosResource::collection($photos);
        return response()->json($photos);
    }
    #[OA\Get(
        path: '/api/v1/gallery/show/{church_id}',
        tags: ['Gallery'],
        summary: 'show Gallery',
        parameters: [
            new OA\Parameter(
                name: 'church_id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                ref: '#/components/responses/EventGalleryResponse'
            )
        ]
    )]

    public function showdetails($church_id)
    {
        abort_unless((int) Auth::user()->church_id === (int) $church_id, 404);
        $query = Gallery::where('church_id', $church_id)->latest();
        app(BranchFeed::class)->apply($query, app(BranchFeed::class)->currentId((int) $church_id));
        $gallery = $query->paginate(10);
        $gallery = ShowGalleryResource::collection($gallery);

        return $gallery;
    }
}
