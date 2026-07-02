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
        Schema::create('dekosans', function (Blueprint $table) {
            $table->id();
            $table->string('id_santri', 255);
            $table->decimal('nominal', 20, 0);
            $table->date('tgl_bayar');
            $table->string('tempat_kos', 100);
            $table->string('kasir', 50);
            $table->timestamps();

            // Index for performance
            $table->index('id_santri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dekosans');
    }
};
