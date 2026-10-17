<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Common;
use App\Traits\AssignContentBranch;

class HomeHeroBanner extends Model
{
    use Common;
    use AssignContentBranch;

    protected $fillable = [
        'church_id', 'eyebrow', 'title', 'highlight', 'description', 'image_path',
        'primary_label', 'primary_url', 'secondary_label', 'secondary_url',
        'is_active', 'display_order', 'transition', 'duration_seconds', 'starts_at', 'ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function getImageUrlAttribute(): string
    {
        return $this->getFilePath($this->image_path);
    }
}
