<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SermonRequest;
use App\Http\Requests\SermonUpdateRequest;
use App\Events\SermonEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Traits\LogActivity;
use App\Models\SermonLink;
use App\Models\Sermon;
use App\Traits\Common;
use Exception;
use Log;
use App\Events\PushEvent;
use App\Events\Notification\PushNotificationEvent;

/**
 * SermonsController
 *
 * Manages sermon/message content within the church.
 * Handles sermon creation, updates, deletion, and sermon link management (audio/video files).
 * Supports searching by title and pastor name, voting, and sermon categorization.
 * Integrates with voting system and sermon media links.
 *
 * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for file path utilities
 */
class SermonsController extends Controller
{
    use LogActivity;
    use Common;
    //

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $sermons = Sermon::query()
            ->where('church_id', Auth::user()->church_id)
            ->with(['vote', 'user.userprofile', 'sermonlinks' => fn ($query) => $query->where('church_id', Auth::user()->church_id)])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query->where('title', 'like', '%' . $q . '%')
                        ->orWhere('description', 'like', '%' . $q . '%')
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', '%' . $q . '%'));
                });
            })
            ->orderByDesc('id')
            ->paginate(8)
            ->withQueryString();

        return view('admin/sermon/index', ['sermons' => $sermons])->withQuery($q);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sermon = Sermon::where('id', $id)->where('church_id', Auth::user()->church_id)->firstOrFail();
        $sermonlinks = SermonLink::where('sermons_id', $id)->where('church_id', Auth::user()->church_id)->orderBy('date', 'asc')->paginate(5);
        if (Gate::allows('sermon', $sermon)) {
            return view('/admin/sermon/show', ['sermon' => $sermon, 'sermonlinks' => $sermonlinks]);
        } else {
            abort(403);
        }
    }

    public function create()
    {
        return view('admin/sermon/create');
    }

    public function store(SermonRequest $request)
    {
        try {
            $church_id = Auth::user()->church_id;
            $user_id   = Auth::id();

            // if ($request->hasFile('cover_image')) {
            //     $path = $this->uploadFile('/uploads/sermons/covers/' . $church_id, $request->file('cover_image'));
            // } else {
            //     $path = $request->input('cover_image_path') ?: null;
            // }

            $path = null;
            if ($request->cover_image_id && str_starts_with($request->cover_image_id, 'media_')) {
                $mediaId    = str_replace('media_', '', $request->cover_image_id);
                $mediaImage = \App\Models\MediaFile::where([
                    ['id', $mediaId],
                    ['church_id', Auth::user()->church_id],
                    ['media_type', 'image'],
                ])->first();
                if (!$mediaImage) {
                    return back()->withErrors(['cover_image_id' => 'Choose an image from your church media library.'])->withInput();
                }
                $path = $mediaImage->url;
            } elseif ($request->cover_image_path) {
                $path = $request->cover_image_path;
            }

            $sermon              = new Sermon;
            $sermon->church_id   = $church_id;
            $sermon->user_id     = $user_id;
            $sermon->title       = $request->title;
            $sermon->description = $request->description;
            $sermon->cover_image = $path;
            $sermon->save();

            if ($request->filled('youtube_url')) {
                $youtubeResource = new SermonLink;
                $youtubeResource->church_id = $church_id;
                $youtubeResource->user_id = $user_id;
                $youtubeResource->sermons_id = $sermon->id;
                $youtubeResource->title = $request->title;
                $youtubeResource->date = now();
                $youtubeResource->video_link = trim($request->input('youtube_url'));
                $youtubeResource->save();

                if (!$sermon->cover_image && $youtubeResource->youtube_thumbnail_url) {
                    $sermon->cover_image = $youtubeResource->youtube_thumbnail_url;
                    $sermon->save();
                }
            }

            if (env('MAIL_STATUS') === 'on') {
                event(new SermonEvent($sermon));
            }
            $data = [];

            $data['church_id'] =  $church_id;
            $data['message'] = 'New Sermon Created';
            $data['type'] = 'sermon';

            event(new PushEvent($data));

            $array = [
                'church_id' => $church_id,
                'details'   => 'New Sermon Created',
                'message_type' => 'sermon',
                'message_id' => $sermon->id
            ];

            event(new PushNotificationEvent($array));

            return redirect('/admin/sermons')->with('successmessage', 'Sermon Created!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('errormessage', 'Could not create sermon.');
        }
    }

    public function edit($id)
    {
        $sermon = Sermon::where('id', $id)
            ->where('church_id', Auth::user()->church_id)
            ->firstOrFail();

        $youtubeUrl = SermonLink::where('sermons_id', $sermon->id)
            ->where('church_id', Auth::user()->church_id)
            ->get()
            ->first(fn ($resource) => $resource->youtube_video_id)
            ?->video_link;

        return view('admin/sermon/edit', ['sermon' => $sermon, 'youtubeUrl' => $youtubeUrl]);
    }

    public function update(SermonUpdateRequest $request, $id)
    {
        try {
            $sermon = Sermon::where('id', $id)
                ->where('church_id', Auth::user()->church_id)
                ->firstOrFail();

            $sermon->title       = $request->title;
            $sermon->description = $request->description;
            // $sermon->cover_image = $request->input('cover_image_path') ?: null;
            $path = $sermon->cover_image;
            if ($request->cover_image_id && str_starts_with($request->cover_image_id, 'media_')) {
                $mediaId    = str_replace('media_', '', $request->cover_image_id);
                $mediaImage = \App\Models\MediaFile::where([
                    ['id', $mediaId],
                    ['church_id', Auth::user()->church_id],
                    ['media_type', 'image'],
                ])->first();
                if (!$mediaImage) {
                    return back()->withErrors(['cover_image_id' => 'Choose an image from your church media library.'])->withInput();
                }
                $path = $mediaImage->url;
            } elseif ($request->cover_image_path) {
                $path = $request->cover_image_path;
            }

            //dd($path);

            $sermon->cover_image = $path;

            if ($request->filled('youtube_url')) {
                $youtubeResource = SermonLink::where('sermons_id', $sermon->id)
                    ->where('church_id', Auth::user()->church_id)
                    ->get()
                    ->first(fn ($resource) => $resource->youtube_video_id);

                if (!$youtubeResource) {
                    $youtubeResource = new SermonLink;
                    $youtubeResource->church_id = Auth::user()->church_id;
                    $youtubeResource->user_id = Auth::id();
                    $youtubeResource->sermons_id = $sermon->id;
                    $youtubeResource->title = $sermon->title;
                    $youtubeResource->date = now();
                }

                $previousYoutubeThumbnail = $youtubeResource->youtube_thumbnail_url;
                $youtubeResource->video_link = trim($request->input('youtube_url'));
                $youtubeResource->save();

                if ((!$path || ($previousYoutubeThumbnail && $path === $previousYoutubeThumbnail)) && $youtubeResource->youtube_thumbnail_url) {
                    $path = $youtubeResource->youtube_thumbnail_url;
                    $sermon->cover_image = $path;
                }
            }

            $sermon->save();

            return redirect('/admin/sermons')->with('successmessage', 'Sermon Updated!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('errormessage', 'Could not update sermon.');
        }
    }

    public function destroy($id)
    {
        try {
            $sermon = Sermon::where('id', $id)
                ->where('church_id', Auth::user()->church_id)
                ->firstOrFail();

            SermonLink::where('sermons_id', $id)->delete();
            $sermon->delete();

            return redirect('/admin/sermons')->with('successmessage', 'Sermon deleted.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('errormessage', 'Could not delete sermon.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, $id)
    {
        //PDF file is stored under project/public/download/info.pdf
        $sermon = SermonLink::where('id', $id)->first();
        if (Gate::allows('sermon', $sermon)) {
            $path = $this->getFilePath($sermon->url);


            $file = pathinfo($path);
            $extension = $file['extension'];
            $headers = [
                'Content-Type: application/' . $extension,
            ];

            return \Response::download($path, 'filename.' . $extension, $headers);
        } else {
            abort(403);
        }
    }
}
