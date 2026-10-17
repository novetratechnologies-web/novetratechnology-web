<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationTemplate extends Model
{
    protected $fillable = ['church_id', 'created_by', 'name', 'category', 'title', 'body', 'channels'];

    protected $casts = ['channels' => 'array'];
}
