<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationCampaign extends Model
{
    protected $fillable = [
        'church_id', 'created_by', 'title', 'body', 'event_name', 'event_at', 'audience', 'channels',
        'timezone', 'scheduled_at', 'recurrence', 'repeat_interval', 'repeat_until', 'run_count',
        'status', 'queued_deliveries', 'processed_at', 'failure_reason',
    ];

    protected $casts = [
        'channels' => 'array',
        'scheduled_at' => 'datetime',
        'event_at' => 'datetime',
        'processed_at' => 'datetime',
        'queued_deliveries' => 'integer',
        'repeat_interval' => 'integer',
        'repeat_until' => 'date',
        'run_count' => 'integer',
    ];
}
