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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('page_title');
            $table->text('page_subtitle');
            $table->string('filter_all_text');
            $table->string('filter_racing_text');
            $table->string('filter_meet_text');
            $table->string('filter_workshop_text');
            $table->string('filter_drift_text');
            $table->json('events');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
