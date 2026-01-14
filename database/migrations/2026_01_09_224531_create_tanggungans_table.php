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
        Schema::create('tanggungans', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('gelombang', 20);
            $table->string('jkl', 20);
            $table->string('nama', 100);
            $table->decimal('nominal', 20, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanggungans');
    }
};
