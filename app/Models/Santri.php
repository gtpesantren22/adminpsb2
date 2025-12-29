<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    // PRIMARY KEY
    protected $primaryKey = 'id_santri';
    public $incrementing = false;
    protected $keyType = 'string';

    // MASS ASSIGNMENT
    protected $fillable = [
        'id_santri',
        'nis',
        'nisn',
        'nik',
        'no_kk',
        'nama',
        'tempat',
        'tanggal',
        'jkl',
        'agama',
        'lembaga',
        'jln',
        'rt',
        'rw',
        'desa',
        'kec',
        'kab',
        'prov',
        'kd_pos',

        // AYAH
        'bapak',
        'a_nik',
        'a_tempat',
        'a_tanggal',
        'a_pkj',
        'a_pend',
        'a_hasil',
        'a_stts',

        // IBU
        'ibu',
        'i_nik',
        'i_tempat',
        'i_tanggal',
        'i_pkj',
        'i_pend',
        'i_hasil',
        'i_stts',

        // KONTAK & AKUN
        'hp',
        'username',
        'password',

        // STATUS & PENDAFTARAN
        'stts',
        'gel',
        'jalur',
        'waktu_daftar',

        // DATA TAMBAHAN
        'anak_ke',
        'jml_sdr',
        'jenis',
        'asal',
        'npsn',
        'a_asal',
        'ket',
        'tinggal',
    ];
}
