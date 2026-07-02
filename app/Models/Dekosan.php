<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dekosan extends Model
{
    protected $fillable = [
        'id_santri',
        'nominal',
        'tgl_bayar',
        'tempat_kos',
        'kasir',
    ];

    protected $casts = [
        'tgl_bayar' => 'date',
        'nominal'   => 'decimal:0',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'id_santri', 'id_santri');
    }
}
