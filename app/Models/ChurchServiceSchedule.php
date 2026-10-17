<?php

namespace App\Models;

use App\Traits\AssignContentBranch;
use Illuminate\Database\Eloquent\Model;

class ChurchServiceSchedule extends Model
{
    use AssignContentBranch;

    protected $fillable = [
        'church_id',
        'day_of_week',
        'service_time',
        'timezone',
        'reminder_minutes_before',
        'is_active',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'reminder_minutes_before' => 'integer',
        'is_active' => 'boolean',
    ];

    public function church()
    {
        return $this->belongsTo(Church::class, 'church_id');
    }
}
