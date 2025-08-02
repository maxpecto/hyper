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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voting_code_id')->constrained('voting_codes')->onDelete('cascade');
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->string('ip_address'); // Oy veren IP adresi
            $table->text('user_agent')->nullable(); // Browser bilgisi
            $table->timestamps();
            
            // Bir kod sadece bir oy verebilir
            $table->unique('voting_code_id');
            
            // Index'ler
            $table->index('registration_id');
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
