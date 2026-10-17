<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChurchLiveStream;
use App\Models\HomeHeroBanner;
use App\Traits\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HeroBannerController extends Controller
{
    use Common;

    private function churchId(): int
    {
        return (int) Auth::user()->church_id;
    }

    public function index()
    {
        $churchId = $this->churchId();
        $banners = HomeHeroBanner::where('church_id', $churchId)->orderBy('display_order')->orderByDesc('id')->get();
        $liveStream = ChurchLiveStream::firstOrNew(['church_id' => $churchId], [
            'title' => 'Join us live', 'provider' => 'youtube', 'is_live' => false,
        ]);

        return view('admin.hero-banners.index', compact('banners', 'liveStream'));
    }

    public function create()
    {
        $banner = new HomeHeroBanner(['is_active' => true, 'transition' => 'fade', 'duration_seconds' => 6]);
        return view('admin.hero-banners.form', compact('banner'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedBanner($request);
        $data['church_id'] = $this->churchId();
        $data['image_path'] = $this->uploadFile($data['church_id'] . '/hero-banners', $request->file('image'));
        unset($data['image']);

        HomeHeroBanner::create($data);
        return redirect()->route('admin.hero-banners.index')->with('success', 'Hero slide created.');
    }

    public function edit(int $id)
    {
        $banner = HomeHeroBanner::where('church_id', $this->churchId())->findOrFail($id);
        return view('admin.hero-banners.form', compact('banner'));
    }

    public function update(Request $request, int $id)
    {
        $banner = HomeHeroBanner::where('church_id', $this->churchId())->findOrFail($id);
        $data = $this->validatedBanner($request, true);
        if ($request->hasFile('image')) {
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $data['image_path'] = $this->uploadFile($this->churchId() . '/hero-banners', $request->file('image'));
        }
        unset($data['image']);

        $banner->update($data);
        return redirect()->route('admin.hero-banners.index')->with('success', 'Hero slide updated.');
    }

    public function destroy(int $id)
    {
        $banner = HomeHeroBanner::where('church_id', $this->churchId())->findOrFail($id);
        Storage::disk('public')->delete($banner->image_path);
        $banner->delete();
        return back()->with('success', 'Hero slide deleted.');
    }

    public function updateLive(Request $request)
    {
        $currentStream = ChurchLiveStream::where('church_id', $this->churchId())->first();
        $requestedProvider = $request->input('provider');
        $request->merge([
            'is_live' => $requestedProvider === 'church_server'
                ? (bool) $currentStream?->is_live
                : $request->boolean('is_live'),
        ]);
        $streamUrlRules = $request->input('provider') === 'church_server' || !$request->boolean('is_live')
            ? ['nullable', 'url', 'regex:/^https:\/\//i', 'max:500']
            : ['required', 'url', 'regex:/^https:\/\//i', 'max:500'];
        $data = $request->validate([
            'is_live' => ['required', 'boolean'],
            'title' => ['required', 'string', 'max:180'],
            'message' => ['nullable', 'string', 'max:1000'],
            'provider' => ['required', 'in:youtube,vimeo,facebook,church_server'],
            'stream_url' => $streamUrlRules,
        ]);

        if ($data['provider'] === 'church_server') {
            $stream = $currentStream;
            if (!$stream?->stream_key) {
                return back()->withErrors(['stream_url' => 'Generate a stream key before enabling the church RTMP input.'])->withInput();
            }
            if (!config('services.streaming.ingest_url') || !config('services.streaming.playback_url')) {
                return back()->withErrors(['stream_url' => 'Configure STREAM_INGEST_URL and STREAM_PLAYBACK_URL in .env before connecting the media server.'])->withInput();
            }
            if ($currentStream?->provider !== 'church_server') {
                $data['is_live'] = false;
            }
        }

        if ($data['provider'] === 'church_server') {
            $data['stream_url'] = null;
        }

        ChurchLiveStream::updateOrCreate(
            ['church_id' => $this->churchId()],
            array_merge($data, [
                'updated_by' => Auth::id(),
                'went_live_at' => $data['provider'] === 'church_server'
                    ? $currentStream?->went_live_at
                    : ($data['is_live'] ? now() : null),
            ])
        );

        if ($data['provider'] === 'church_server') {
            return back()->with('success', 'Stream settings saved. The website will switch to the live feed automatically when your encoder connects.');
        }

        return back()->with('success', $data['is_live'] ? 'Live mode is on. The live service now replaces the banner slides.' : 'Live mode is off. The banner slides are visible again.');
    }

    public function generateStreamKey()
    {
        $stream = ChurchLiveStream::firstOrNew(['church_id' => $this->churchId()]);
        if (!$stream->exists) {
            $stream->title = 'Join us live';
            $stream->provider = 'church_server';
            $stream->is_live = false;
        }

        $stream->stream_key = Str::random(48);
        $stream->updated_by = Auth::id();
        $stream->save();

        return back()->with('success', 'A new private stream key was generated. Update your streaming encoder with the new key.');
    }

    private function validatedBanner(Request $request, bool $updating = false): array
    {
        $request->merge(['is_active' => $request->boolean('is_active')]);
        return $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:180'],
            'highlight' => ['nullable', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => [$updating ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'primary_label' => ['nullable', 'string', 'max:60'],
            'primary_url' => ['nullable', 'string', 'regex:/^(\/(?!\/)|https?:\/\/)/i', 'max:500'],
            'secondary_label' => ['nullable', 'string', 'max:60'],
            'secondary_url' => ['nullable', 'string', 'regex:/^(\/(?!\/)|https?:\/\/)/i', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'display_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'transition' => ['required', 'in:fade,slide'],
            'duration_seconds' => ['required', 'integer', 'min:4', 'max:15'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
        ]);
    }
}
