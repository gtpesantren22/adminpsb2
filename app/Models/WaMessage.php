<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaMessage extends Model
{
    //
    protected $fillable = [
        'message_id',
        'sender',
        'receiver',
        'message',
        'type',
        'direction',
        'status',
        'raw'
    ];

    protected $casts = [
        'raw' => 'array',
    ];
}
