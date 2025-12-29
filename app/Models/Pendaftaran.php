<?php

namespace App\Models;

use App\Models\Santri;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    //
    // Primary key bukan integer & bukan auto increment
    protected $primaryKey = 'id_bayar';
    public $incrementing = false;
    protected $keyType = 'string';

    // Kolom yang boleh diisi mass assignment
    protected $fillable = [
        'id_bayar',
        'id_santri',
        'nis',
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
