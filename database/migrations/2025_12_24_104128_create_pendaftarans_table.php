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
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->string('id_bayar', 255)->primary();
            $table->string('id_santri', 255)->foreign();
            $table->string('nis', 10);
            $table->decimal('nominal', 20, 0);
            $table->date('tgl_bayar');
            $table->string('via', 20); // enum diganti string (PG best practice)
            $table->string('kasir', 50);
            $table->timestamps();
        });

        DB::statement("
            ALTER TABLE pendaftarans
            ADD CONSTRAINT pembayaran_via_check
            CHECK (via IN ('Transfer', 'Cash'))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
