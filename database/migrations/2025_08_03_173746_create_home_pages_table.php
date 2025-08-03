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
        Schema::create('home_pages', function (Blueprint $table) {
            $table->id();
            
            // Hero Section
            $table->string('hero_title')->default('HYPER DRIVE');
            $table->text('hero_subtitle')->default('Gələcək burada. Avtomobilinlə tanışlıq vaxtı.');
            $table->text('hero_video_desktop')->nullable();
            $table->text('hero_video_mobile')->nullable();
            $table->integer('hero_stats_members')->default(500);
            $table->integer('hero_stats_events')->default(50);
            $table->integer('hero_stats_cars')->default(1000);
            
            // Latest Event
            $table->string('latest_event_title')->default('Hyper Drive ParkMeet24');
            $table->string('latest_event_date')->default('22 DEC');
            $table->string('latest_event_location')->default('Bakı');
            $table->string('latest_event_spots')->default('Tezliklə');
            $table->string('latest_event_image')->default('hyperparkmeet.webp');
            
            // About Section
            $table->string('about_title')->default('Hyper Drive nədir?');
            $table->text('about_description');
            $table->json('about_features')->nullable();
            
            // Featured Events
            $table->string('featured_events_title')->default('Önə Çıxan Tədbirlər');
            $table->json('featured_events')->nullable();
            
            // Community Section
            $table->string('community_title')->default('Cəmiyyətə Qoşulun');
            $table->text('community_description');
            $table->json('community_features')->nullable();
            $table->integer('community_stats_members')->default(500);
            $table->integer('community_stats_events')->default(50);
            $table->integer('community_stats_cars')->default(1000);
            $table->integer('community_stats_brands')->default(25);
            
            // Footer
            $table->text('footer_description')->default('Gələcək burada. Avtomobilinlə tanışlıq vaxtı.');
            $table->string('footer_email')->default('info@hyperdrive.az');
            $table->string('footer_instagram')->nullable();
            $table->string('footer_tiktok')->nullable();
            $table->string('footer_youtube')->nullable();
            $table->string('footer_telegram')->nullable();
            $table->string('footer_location')->default('Bakı, Azərbaycan');
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_pages');
    }
};
