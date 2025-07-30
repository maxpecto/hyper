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
        Schema::create('whats_app_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('whatsapp_enabled')->default(true);
            $table->text('whatsapp_approved_message');
            $table->text('whatsapp_rejected_message');
            $table->text('whatsapp_pending_message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whats_app_settings');
    }
};
