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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('owner_name');
            $table->string('owner_username');
            $table->string('owner_avatar')->nullable();
            $table->string('brand');
            $table->string('model');
            $table->string('year');
            $table->string('color');
            $table->string('modification');
            $table->string('horsepower');
            $table->string('acceleration');
            $table->string('category');
            $table->json('tags');
            $table->string('main_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->integer('rating_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_manual')->default(false); // true = manuel eklenen, false = başvuru kaynağı
            $table->unsignedBigInteger('registration_id')->nullable(); // başvuru kaynağı ise registration ID'si
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
