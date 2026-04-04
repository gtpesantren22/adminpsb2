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
        Schema::create('wa_messages', function (Blueprint $table) {
            $table->id();
            $table->string('message_id')->nullable()->index();

            $table->string('sender')->nullable();
            $table->string('receiver')->nullable();

            $table->text('message')->nullable();

            $table->string('type'); // message, message_ack, message_browser
            $table->string('direction')->nullable(); // inbound / outbound

            $table->string('status')->nullable(); // sent, delivered, read

            $table->json('raw')->nullable(); // simpan raw JSON (PENTING 🔥)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wa_messages');
    }
};
