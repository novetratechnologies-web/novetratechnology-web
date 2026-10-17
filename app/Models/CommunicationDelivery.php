<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationDelivery extends Model
{
    protected $fillable = [
        'church_id',
        'user_id',
        'event_type',
        'event_id',
        'occurrence_date',
        'channel',
        'recipient',
        'idempotency_key',
        'status',
        'payload',
        'provider_message_id',
        'failure_reason',
        'scheduled_at',
        'sent_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'occurrence_date' => 'date',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function church()
    {
        return $this->belongsTo(Church::class, 'church_id');
    }
}
