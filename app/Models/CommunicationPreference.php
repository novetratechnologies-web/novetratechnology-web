<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'email_opt_in',
        'email_consent_at',
        'email_consent_source',
        'sms_opt_in',
        'sms_consent_at',
        'sms_consent_source',
        'in_app_enabled',
    ];

    protected $casts = [
        'email_opt_in' => 'boolean',
        'email_consent_at' => 'datetime',
        'sms_opt_in' => 'boolean',
        'sms_consent_at' => 'datetime',
        'in_app_enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
