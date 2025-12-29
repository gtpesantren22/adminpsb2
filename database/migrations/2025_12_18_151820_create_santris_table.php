<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('santris', function (Blueprint $table) {
            // PRIMARY KEY
            $table->string('id_santri', 255)->primary();

            // IDENTITAS
            $table->string('nis', 10)->nullable();
            $table->string('nisn', 20)->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('no_kk', 30)->nullable();
            $table->string('nama', 100);
            $table->string('tempat', 100)->nullable();
            $table->string('tanggal', 50)->nullable();
            $table->string('jkl', 20);
            $table->string('agama', 20)->nullable();
            $table->string('lembaga', 50)->nullable();

            // ALAMAT
            $table->text('jln')->nullable();
            $table->string('rt', 10)->nullable();
            $table->string('rw', 10)->nullable();
            $table->string('desa', 100)->nullable();
            $table->string('kec', 100)->nullable();
            $table->string('kab', 100)->nullable();
            $table->string('prov', 100)->nullable();
            $table->integer('kd_pos')->nullable();

            // AYAH
            $table->string('bapak', 50)->nullable();
            $table->string('a_nik', 20)->nullable();
            $table->string('a_tempat', 50)->nullable();
            $table->string('a_tanggal', 20)->nullable();
            $table->string('a_pkj', 30)->nullable();
            $table->string('a_pend', 20)->nullable();
            $table->string('a_hasil', 30)->nullable();
            $table->string('a_stts', 20)->nullable();

            // IBU
            $table->string('ibu', 50)->nullable();
            $table->string('i_nik', 20)->nullable();
            $table->string('i_tempat', 50)->nullable();
            $table->string('i_tanggal', 20)->nullable();
            $table->string('i_pkj', 30)->nullable();
            $table->string('i_pend', 20)->nullable();
            $table->string('i_hasil', 30)->nullable();
            $table->string('i_stts', 20)->nullable();

            // KONTAK & AKUN
            $table->string('hp', 15)->nullable();
            $table->string('username', 100)->unique();
            $table->string('password', 100);

            // STATUS
            $table->string('stts', 50)->nullable();
            $table->string('gel', 20)->nullable();
            $table->string('jalur', 20)->nullable();
            $table->timestamp('waktu_daftar')->nullable();

            // DATA TAMBAHAN
            $table->smallInteger('anak_ke')->nullable();
            $table->smallInteger('jml_sdr')->nullable();
            $table->string('jenis', 10)->nullable();
            $table->text('asal')->nullable();
            $table->integer('npsn')->nullable();
            $table->text('a_asal')->nullable();
            $table->string('ket', 10)->nullable();
            $table->string('tinggal', 20)->nullable();

            $table->timestamps();
        });


        // CHECK CONSTRAINT (ENUM PostgreSQL)
        DB::statement("ALTER TABLE santris ADD CONSTRAINT chk_jkl CHECK (jkl IN ('Laki-laki','Perempuan'))");
        DB::statement("ALTER TABLE santris ADD CONSTRAINT chk_jalur CHECK (jalur IN ('Reguler','Prestasi'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};
