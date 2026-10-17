<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Photos;
use App\Models\Gallery;
use App\Traits\Common;
use Carbon;
use File;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\API\ShowPhotos as ShowPhotosResource;
use App\Traits\LogActivity;
use App\Http\Requests\EventGalleryRequest;
use Exception;
use Log;

/**
 * PhotosController
 *
 * Manages photos and image uploads for galleries and galleries.
 * Handles photo upload, deletion, and association with galleries.
 * Supports image validation and storage with file system management.\n *\n * @package App\Http\Controllers\Admin
 * @uses LogActivity Trait for audit logging
 * @uses Common Trait for file utilities
 */
class PhotosController extends Controller
{

    use LogActivity;
    use Common;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPhoto($gallery_id)
    {
        return Photos::where([['gallery_id',$gallery_id],['church_id',Auth::user()->church_id]])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$gallery_id)
    {
        $validated = $request->validate([
            'data' => ['required', 'array', 'min:1', 'max:30'],
            'data.*.path' => ['required', 'string', 'regex:/^data:image\/(jpeg|png|webp);base64,/'],
        ]);

        try
        {
            $gallery = Gallery::where('church_id', Auth::user()->church_id)->findOrFail($gallery_id);

            $savedPhotos = [];
            foreach ($validated['data'] as $item)
            {
                [$header, $encoded] = explode(',', $item['path'], 2);
                $image = base64_decode($encoded, true);
                if ($image === false || strlen($image) > 5 * 1024 * 1024) {
                    return response()->json(['message' => 'Each image must be a valid image under 5 MB.'], 422);
                }

                $mime = str_contains($header, 'image/jpeg') ? 'image/jpeg' : (str_contains($header, 'image/webp') ? 'image/webp' : 'image/png');
                $imageInfo = @getimagesizefromstring($image);
                if (!$imageInfo || $imageInfo['mime'] !== $mime) {
                    return response()->json(['message' => 'One of the uploaded files is not a valid image.'], 422);
                }

                $extension = $mime === 'image/jpeg' ? 'jpg' : ($mime === 'image/webp' ? 'webp' : 'png');
                $dbPath = Auth::user()->church_id . '/gallery/covers/' . $gallery->id . '/' . Str::uuid() . '.' . $extension;
                if (!Storage::disk('public')->put($dbPath, $image)) {
                    throw new Exception('Unable to write gallery image to public storage.');
                }

                $create = [
                    'gallery_id' => $gallery->id,
                    'church_id' => Auth::user()->church_id,
                    'path' => $dbPath,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ];
                $savedPhotos[] = Photos::create($create);
            }

            $message=('Photos Added Successfully');

            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $gallery,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_ADD_PHOTO,
                $message
            );
            return response()->json(['message' => 'Uploaded Successfully', 'count' => count($savedPhotos)]);
       }
       catch (Exception $e)
        {
            Log::error('Gallery photo upload failed', ['exception' => $e]);
            return response()->json(['message' => 'Photo upload failed. Please try again.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($gallery_id)
    {
        $photos = Photos::where('gallery_id',$gallery_id)->first();
        if(Gate::allows('photo',$photos))
        {
            return $photos;
        }
          else
          {
            abort(403);
          }
    }

     public function showdetails($gallery_id)
    {

        $photos = Photos::with('gallery')->where([['gallery_id',$gallery_id],['church_id',Auth::user()->church_id]])->get();
        $photos = ShowPhotosResource::collection($photos);
        return $photos;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try
        {
            $photo = Photos::where('church_id', Auth::user()->church_id)->findOrFail($id);

            if ($photo->path && !str_starts_with($photo->path, 'http')) {
                Storage::disk('public')->delete($photo->path);
            }

            $photo->delete();

            $message = 'Photo Deleted Successfully';


            $ip= $this->getRequestIP();
            $this->doActivityLog(
                $photo,
                Auth::user(),
                ['ip' => $ip, 'details' => $_SERVER['HTTP_USER_AGENT'] ],
                LOGNAME_DELETE_PHOTO,
                $message
            );
            $res['success'] = $message;
            return $res;
        }
        catch(Exception $e)
        {
            Log::info($e->getMessage());

        }
    }
}
