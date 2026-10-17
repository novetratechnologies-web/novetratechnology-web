<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Models\Events;
use App\Support\BranchFeed;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $church = Church::first();
        $branchId = app(BranchFeed::class)->currentId($church?->id);
        $today = Carbon::today();
        $search = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));
        $period = in_array($request->query('period'), ['all', 'upcoming', 'past'], true)
            ? $request->query('period')
            : 'all';

        $baseQuery = Events::query()
            ->where('church_id', $church?->id)
            ->where('publish_to_web', true)
            ->whereIn('select_type', ['public', 'online'])
            ->whereNotNull('start_date')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhere('location', 'like', '%' . $search . '%');
                });
            })
            ->when($category !== '', fn ($query) => $query->where('category', $category));
        app(BranchFeed::class)->apply($baseQuery, $branchId);

        $categories = (clone $baseQuery)->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        $upcomingQuery = (clone $baseQuery)
            ->where(function ($query) use ($today) {
                $query->where('start_date', '>=', $today)
                    ->orWhere(function ($query) use ($today) {
                        // Keep an active recurring series visible after its first occurrence.
                        $query->where('repeats', 1)->where('end_date', '>=', $today);
                    })
                    ->orWhere(function ($query) {
                        // An event that started earlier today but has not ended is still upcoming.
                        $query->where('end_date', '>=', now())->where('start_date', '<', now());
                    });
            })
            ->orderByRaw('CASE WHEN start_date >= ? THEN 0 ELSE 1 END', [$today])
            ->orderBy('start_date');

        if ($period === 'past') {
            $upcomingQuery->whereRaw('1 = 0');
        }
        $upcoming = $upcomingQuery->paginate(9, ['*'], 'upcoming_page')->withQueryString();

        $completedRaw = $period === 'upcoming' ? collect() : (clone $baseQuery)
            ->where(function ($query) use ($today) {
                $query->where(function ($query) use ($today) {
                    $query->whereNull('end_date')->where('start_date', '<', $today);
                })->orWhere('end_date', '<', $today);
            })
            ->orderByDesc('start_date')
            ->get();

        $completed = [];
        foreach ($completedRaw as $event) {
            $date = Carbon::parse($event->start_date);
            $completed[$date->format('Y')][$date->format('F')][] = $event;
        }

        return view('theme::events', compact('upcoming', 'completed', 'categories', 'search', 'category', 'period'));
    }

    public function show($id)
    {
        $church = Church::first();
        $branchId = app(BranchFeed::class)->currentId($church?->id);
        $event = Events::query()
            ->where('church_id', $church?->id)
            ->where('publish_to_web', true)
            ->whereIn('select_type', ['public', 'online'])
            ->with(['gallery' => fn ($query) => $query->where('church_id', $church?->id)])
            ->where(function ($query) use ($branchId) { $query->whereNull('branch_id')->when($branchId, fn ($q) => $q->orWhere('branch_id', $branchId)); })
            ->findOrFail($id);

        return view('theme::event', compact('event'));
    }
}
