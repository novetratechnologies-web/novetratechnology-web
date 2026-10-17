<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Resources\API\Guest\ShowSermonLink as ShowSermonLinkResource;
use App\Http\Resources\API\Guest\Sermon as SermonResource;
use App\Http\Controllers\Controller;
use App\Models\SermonLink;
use App\Models\Sermon;
use App\Support\BranchFeed;

class SermonsController extends Controller
{
    public function index($church_id)
    {
        $query = Sermon::where('church_id', $church_id)->latest();
        app(BranchFeed::class)->apply($query, app(BranchFeed::class)->currentId((int) $church_id));
        $sermon = $query->paginate(10);
        
        $sermon = SermonResource::collection($sermon);
        
        return $sermon;
    }

	public function show($church_id,$sermons_id)
    {
        $branchId = app(BranchFeed::class)->currentId((int) $church_id);
        $sermonQuery = Sermon::where([['id', $sermons_id], ['church_id', $church_id]]);
        app(BranchFeed::class)->apply($sermonQuery, $branchId);
        $sermon = $sermonQuery->firstOrFail();
        $links = SermonLink::with('sermons')->where([['sermons_id',$sermon->id],['church_id',$church_id]])->paginate(10);
        
        $links = ShowSermonLinkResource::collection($links);
        
        return $links;
    }
}
