<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrasi extends Model
{
    //

    protected $fillable = [
        'id_santri',
        'nominal',
        'tgl_bayar',
        'via',
        'kasir',
    ];

    // Cast otomatis
    protected $casts = [
        'tgl_bayar' => 'date',
        'nominal'   => 'decimal:0',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'id_santri', 'id_santri');
    }
}
