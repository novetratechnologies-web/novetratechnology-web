<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChurchBranch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'church_id', 'name', 'slug', 'description', 'address', 'phone', 'email',
        'timezone', 'is_headquarters', 'is_active',
    ];

    protected $casts = [
        'is_headquarters' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function church()
    {
        return $this->belongsTo(Church::class, 'church_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'branch_id');
    }

    public function scopeForChurch($query, $churchId)
    {
        return $query->where('church_id', $churchId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
