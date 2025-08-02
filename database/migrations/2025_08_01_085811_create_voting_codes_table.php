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
        Schema::create('voting_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // 10 karakterlik benzersiz kod
            $table->boolean('is_used')->default(false); // Kullanıldı mı?
            $table->timestamp('used_at')->nullable(); // Kullanıldığı tarih
            $table->string('used_ip')->nullable(); // Kullanan IP adresi
            $table->timestamps();
            
            // Kod üzerinde index
            $table->index('code');
            $table->index('is_used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voting_codes');
    }
};
