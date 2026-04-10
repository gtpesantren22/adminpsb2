<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seragam extends Model
{
    protected $fillable = [
        'id_santri',
        'atasan',
        'bawahan',
        'songkok',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'id_santri', 'id_santri');
    }
}
