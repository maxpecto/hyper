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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            
            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('username');
            
            // Car Information
            $table->string('car_brand');
            $table->string('car_model');
            $table->string('car_year');
            $table->string('car_color');
            $table->text('modifications')->nullable();
            
            // Experience Level
            $table->string('experience');
            $table->json('interests')->nullable();
            
            // Photo Paths
            $table->string('front_photo')->nullable();
            $table->string('back_photo')->nullable();
            $table->string('left_photo')->nullable();
            $table->string('right_photo')->nullable();
            $table->string('interior_photo')->nullable();
            $table->string('engine_photo')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            
            // Communication
            $table->boolean('newsletter_subscription')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
