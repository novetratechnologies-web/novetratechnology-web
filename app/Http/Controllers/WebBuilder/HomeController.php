<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Events;
use App\Models\Gallery;
use App\Models\Group;
use App\Models\Sermon;
use App\Models\Church;
use App\Models\ChurchServiceSchedule;
use App\Models\HomeHeroBanner;
use App\Models\ChurchLiveStream;
use App\Support\BranchFeed;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $church = Church::first();
        $churchId = $church ? $church->id : null;
        $branchId = app(BranchFeed::class)->currentId($churchId);

        $publishedPosts = Post::query()
            ->where('church_id', $churchId)
            ->where('is_posted', 1)
            ->where('status', 'posted')
            ->orderBy('post_created_at', 'desc');
        app(BranchFeed::class)->apply($publishedPosts, $branchId);

        $heroPost = (clone $publishedPosts)->first();

        $heroBanners = collect();
        $liveStream = null;
        $streamAutoStatusEnabled = true;
        if ($churchId && Schema::hasTable('home_hero_banners')) {
            $heroBannersQuery = HomeHeroBanner::where('church_id', $churchId)
                ->where('is_active', true)
                ->where(function ($query) { $query->whereNull('starts_at')->orWhere('starts_at', '<=', now()); })
                ->where(function ($query) { $query->whereNull('ends_at')->orWhere('ends_at', '>=', now()); })
                ->orderBy('display_order')->orderBy('id');
            app(BranchFeed::class)->apply($heroBannersQuery, $branchId);
            $heroBanners = $heroBannersQuery->get();
        }
        if ($churchId && Schema::hasTable('church_live_streams')) {
            $liveStream = ChurchLiveStream::where('church_id', $churchId)->where('is_live', true)->first();
        }

        // Only omit the newest post when it is actually being used as the fallback hero.
        $heroUsesPost = !$liveStream && $heroBanners->isEmpty();
        $recentPosts = (clone $publishedPosts)
            ->when($heroUsesPost, fn ($query) => $query->skip(1))
            ->take(3)
            ->get();

        $upcomingEventsQuery = Events::query()
            ->where('church_id', $churchId)
            ->where('publish_to_web', true)
            ->whereNotNull('start_date')
            ->where('start_date', '>=', Carbon::now())
            ->orderBy('start_date')
            ->take(3);
        app(BranchFeed::class)->apply($upcomingEventsQuery, $branchId);
        $upcomingEvents = $upcomingEventsQuery->get();
        $showingPastEvents = false;
        if ($upcomingEvents->isEmpty()) {
            $pastEventsQuery = Events::query()
                ->where('church_id', $churchId)
                ->where('publish_to_web', true)
                ->whereNotNull('start_date')
                ->where('start_date', '<', Carbon::now())
                ->orderByDesc('start_date')
                ->take(3);
            app(BranchFeed::class)->apply($pastEventsQuery, $branchId);
            $upcomingEvents = $pastEventsQuery->get();
            $showingPastEvents = $upcomingEvents->isNotEmpty();
        }

        $galleriesQuery = Gallery::query()
            ->where('church_id', $churchId)
            ->with(['photos' => function ($query) {
                $query->latest()->take(1);
            }])
            ->latest()
            ->take(3);
        app(BranchFeed::class)->apply($galleriesQuery, $branchId);
        $galleries = $galleriesQuery->get();

        $communitiesQuery = Group::query()
            ->where('church_id', $churchId)
            ->latest()
            ->take(3);
        app(BranchFeed::class)->apply($communitiesQuery, $branchId);
        $communities = $communitiesQuery->get();

        $latestSermonQuery = Sermon::query()
            ->where('church_id', $churchId)
            ->with('sermonlinks')
            ->latest();
        app(BranchFeed::class)->apply($latestSermonQuery, $branchId);
        $latestSermon = $latestSermonQuery->first();

        $serviceSchedule = collect();
        if ($churchId && Schema::hasTable('church_service_schedules')) {
            $serviceScheduleQuery = ChurchServiceSchedule::where('church_id', $churchId)
                ->where('is_active', true)
                ->orderBy('day_of_week')
                ->orderBy('service_time')
                ->take(2);
            app(BranchFeed::class)->apply($serviceScheduleQuery, $branchId);
            $serviceSchedule = $serviceScheduleQuery->get();
        }

        return view('theme::home', compact(
            'church', 'heroPost', 'heroBanners', 'liveStream', 'streamAutoStatusEnabled', 'recentPosts',
            'upcomingEvents', 'showingPastEvents', 'galleries', 'communities', 'latestSermon', 'serviceSchedule'
        ));
    }
}
