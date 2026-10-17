<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChurchLiveStream;
use App\Models\Church;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class StreamingMediaController extends Controller
{
    /** Validate MediaMTX HTTP authentication requests. */
    public function authenticate(Request $request)
    {
        $data = $request->validate([
            'action' => ['required', 'string', 'in:publish,read,playback'],
            'path' => ['required', 'string', 'max:255'],
            'protocol' => ['nullable', 'string', 'max:20'],
            'user' => ['nullable', 'string', 'max:190'],
            'password' => ['nullable', 'string', 'max:255'],
        ]);

        $stream = $this->findStreamByPath($data['path']);

        if (!$stream) {
            return response()->json(['error' => 'Unknown stream path'], 403);
        }

        if (in_array($data['action'], ['read', 'playback'], true)) {
            return $stream->is_live
                ? response()->noContent()
                : response()->json(['error' => 'Stream is offline'], 403);
        }

        $validCredentials = $stream->stream_key
            && hash_equals((string) $stream->stream_username, (string) ($data['user'] ?? ''))
            && hash_equals((string) $stream->stream_key, (string) ($data['password'] ?? ''));

        return $validCredentials
            ? response()->noContent()
            : response()->json(['error' => 'Invalid publishing credentials'], 403);
    }

    /** Receive online/offline events emitted by the media server. */
    public function status(Request $request)
    {
        abort_unless($this->hasValidWebhookToken($request), 401);

        $data = $request->validate([
            'path' => ['required', 'string', 'max:255'],
            'online' => ['required', 'boolean'],
        ]);

        $stream = $this->findStreamByPath($data['path']);

        if (!$stream) {
            return response()->json(['error' => 'Unknown stream path'], 404);
        }

        $online = (bool) $data['online'];
        $wasOnline = (bool) $stream->is_live;
        $stream->is_live = $online;
        $stream->went_live_at = $online
            ? ($wasOnline ? $stream->went_live_at : now())
            : null;
        $stream->save();
        Cache::forget('public-church-live-status:' . $stream->church_id);

        return response()->json(['accepted' => true, 'is_live' => $online]);
    }

    /** Public, secret-free state used by the home and sermon pages. */
    public function publicStatus()
    {
        $church = Church::first();
        if (!$church || !Schema::hasTable('church_live_streams')) {
            return response()->json(['is_live' => false])->header('Cache-Control', 'no-store');
        }

        $status = Cache::remember('public-church-live-status:' . $church->id, now()->addSeconds(5), function () use ($church) {
            $stream = ChurchLiveStream::where('church_id', $church->id)->first();

            return ['is_live' => (bool) $stream?->is_live];
        });

        return response()->json($status)->header('Cache-Control', 'no-store');
    }

    private function hasValidWebhookToken(Request $request): bool
    {
        $expected = (string) config('services.streaming.webhook_token');
        $provided = (string) $request->bearerToken();

        return $expected !== '' && $provided !== '' && hash_equals($expected, $provided);
    }

    private function findStreamByPath(string $path): ?ChurchLiveStream
    {
        $pathPrefix = trim((string) config('services.streaming.path_prefix'), '/');
        $lastSlash = strrpos($path, '/');
        $prefix = $lastSlash === false ? '' : substr($path, 0, $lastSlash);
        $name = $lastSlash === false ? $path : substr($path, $lastSlash + 1);

        if ($prefix !== $pathPrefix || !preg_match('/^church-(\d+)$/', $name, $matches)) {
            return null;
        }

        $stream = ChurchLiveStream::where('church_id', (int) $matches[1])
            ->where('provider', 'church_server')
            ->first();

        return $stream && hash_equals($stream->stream_path ?? '', $path) ? $stream : null;
    }
}
