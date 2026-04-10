<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('atasans', function (Blueprint $table) {
            $table->id();
            $table->string('jenjang', 50); // SLTP / SLTA
            $table->string('jkl', 20); // L / P or Laki-laki / Perempuan
            $table->string('ukuran', 20); // 25, 26, XL, etc
            $table->string('ld')->nullable(); // Lebar Dada
            $table->string('pj')->nullable(); // Panjang
            $table->timestamps();
        });

        Schema::create('bawahans', function (Blueprint $table) {
            $table->id();
            $table->string('jenjang', 50);
            $table->string('jkl', 20);
            $table->string('ukuran', 20);
            $table->string('lp')->nullable(); // Lingkar Pinggang
            $table->string('pj')->nullable(); // Panjang
            $table->timestamps();
        });

        Schema::create('seragams', function (Blueprint $table) {
            $table->id();
            // User states ID Santri from santris table (string). Let's use string.
            $table->string('id_santri');
            $table->string('atasan');
            $table->string('bawahan');
            $table->string('songkok')->default('0'); // 0 for female or default
            $table->timestamps();

            // Index for faster lookups
            $table->index('id_santri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seragams');
        Schema::dropIfExists('bawahans');
        Schema::dropIfExists('atasans');
    }
};
