<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ChurchLiveStream extends Model
{
    protected $fillable = [
        'church_id', 'is_live', 'title', 'message', 'provider', 'stream_url', 'updated_by', 'went_live_at',
    ];

    protected $casts = [
        'is_live' => 'boolean',
        'went_live_at' => 'datetime',
    ];

    protected $hidden = ['stream_key'];

    public function setStreamKeyAttribute(?string $value): void
    {
        $this->attributes['stream_key'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getStreamKeyAttribute(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $exception) {
            // Allow keys created before encryption was enabled to remain usable.
            return $value;
        }
    }

    public function getIngestUrlAttribute(): ?string
    {
        $baseUrl = config('services.streaming.ingest_url');
        $streamPath = $this->stream_path;

        return $baseUrl && $streamPath ? rtrim($baseUrl, '/') . '/' . $streamPath : null;
    }

    public function getPlaybackUrlAttribute(): ?string
    {
        $template = config('services.streaming.playback_url');
        $streamPath = $this->stream_path;
        $encodedPath = $streamPath ? implode('/', array_map('rawurlencode', explode('/', $streamPath))) : null;

        return $template && $encodedPath ? str_replace(['{path}', '{key}'], [$encodedPath, $encodedPath], $template) : null;
    }

    public function getStreamPathAttribute(): ?string
    {
        if (!$this->church_id) {
            return null;
        }

        return trim(config('services.streaming.path_prefix') . '/church-' . $this->church_id, '/');
    }

    public function getStreamUsernameAttribute(): ?string
    {
        return $this->church_id
            ? config('services.streaming.auth_username_prefix') . $this->church_id
            : null;
    }

    public function getEmbedUrlAttribute(): ?string
    {
        if (!$this->stream_url) {
            return null;
        }

        $url = trim($this->stream_url);
        if ($this->provider === 'youtube') {
            if (preg_match('~(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:watch\?v=|embed/|live/|shorts/))([A-Za-z0-9_-]{11})~', $url, $match)) {
                return 'https://www.youtube-nocookie.com/embed/' . $match[1] . '?autoplay=1&mute=1';
            }
        }

        if ($this->provider === 'vimeo' && preg_match('~vimeo\.com/(?:video/)?([0-9]+)~', $url, $match)) {
            return 'https://player.vimeo.com/video/' . $match[1] . '?autoplay=1&muted=1';
        }

        if ($this->provider === 'facebook' && preg_match('~^https://(?:www\.)?facebook\.com/~i', $url)) {
            return 'https://www.facebook.com/plugins/video.php?href=' . rawurlencode($url) . '&show_text=false&autoplay=true';
        }

        return null;
    }
}
