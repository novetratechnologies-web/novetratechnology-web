<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Models\ChurchLiveStream;
use App\Models\Sermon;
use App\Support\BranchFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SermonController extends Controller
{
    public function index(Request $request)
    {
        $church = Church::first();
        $churchId = $church?->id;
        $branchId = app(BranchFeed::class)->currentId($churchId);
        $search = trim((string) $request->query('q', ''));

        $sermonsQuery = Sermon::query()
            ->where('church_id', $churchId)
            ->with([
                'user.userprofile',
                'sermonlinks' => fn ($query) => $query->where('church_id', $churchId),
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', '%' . $search . '%'));
                });
            })
            ->latest();
        app(BranchFeed::class)->apply($sermonsQuery, $branchId);
        $sermons = $sermonsQuery->paginate(12)
            ->withQueryString();

        $liveStream = $churchId && Schema::hasTable('church_live_streams')
            ? ChurchLiveStream::where('church_id', $churchId)->where('is_live', true)->first()
            : null;
        $streamAutoStatusEnabled = true;

        return view('theme::sermon_index', compact('sermons', 'search', 'liveStream', 'streamAutoStatusEnabled'));
    }

    public function show($id)
    {
        $church = Church::first();
        $branchId = app(BranchFeed::class)->currentId($church?->id);
        $sermon = Sermon::query()
            ->where('church_id', $church?->id)
            ->where(function ($query) use ($branchId) { $query->whereNull('branch_id')->when($branchId, fn ($q) => $q->orWhere('branch_id', $branchId)); })
            ->with([
                'user.userprofile',
                'sermonlinks' => fn ($query) => $query->where('church_id', $church?->id),
            ])
            ->findOrFail($id);

        $recommended = Sermon::query()
            ->where('church_id', $sermon->church_id)
            ->where('id', '!=', $sermon->id)
            ->where(function ($query) use ($branchId) { $query->whereNull('branch_id')->when($branchId, fn ($q) => $q->orWhere('branch_id', $branchId)); })
            ->with('user')
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        $liveStream = $church && Schema::hasTable('church_live_streams')
            ? ChurchLiveStream::where('church_id', $church->id)->where('is_live', true)->first()
            : null;
        $streamAutoStatusEnabled = true;

        return view('theme::sermon', compact('sermon', 'recommended', 'liveStream', 'streamAutoStatusEnabled'));
    }

    public function live()
    {
        $church = Church::first();
        $liveStream = $church && Schema::hasTable('church_live_streams')
            ? ChurchLiveStream::where('church_id', $church->id)->where('is_live', true)->first()
            : null;
        $streamAutoStatusEnabled = true;

        return view('theme::live', compact('liveStream', 'church', 'streamAutoStatusEnabled'));
    }
}
