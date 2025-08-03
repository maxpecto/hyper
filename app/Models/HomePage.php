<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_video_desktop',
        'hero_video_mobile',
        'hero_stats_members',
        'hero_stats_events',
        'hero_stats_cars',
        'latest_event_title',
        'latest_event_date',
        'latest_event_location',
        'latest_event_spots',
        'latest_event_image',
        'about_title',
        'about_description',
        'about_features',
        'featured_events_title',
        'featured_events',
        'community_title',
        'community_description',
        'community_features',
        'community_stats_members',
        'community_stats_events',
        'community_stats_cars',
        'community_stats_brands',
        'footer_description',
        'footer_email',
        'footer_instagram',
        'footer_tiktok',
        'footer_youtube',
        'footer_telegram',
        'footer_location',
        'is_active'
    ];

    protected $casts = [
        'about_features' => 'array',
        'featured_events' => 'array',
        'community_features' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Aktif ana sayfa ayarlarını getir
     */
    public static function getActiveSettings()
    {
        return static::where('is_active', true)->first() ?? static::createDefaultSettings();
    }

    /**
     * Varsayılan ayarları oluştur
     */
    public static function createDefaultSettings()
    {
        return static::create([
            'hero_title' => 'HYPER DRIVE',
            'hero_subtitle' => 'Gələcək burada. Avtomobilinlə tanışlıq vaxtı.',
            'hero_video_desktop' => 'https://www.youtube.com/embed/vlDOjTaaEdA?autoplay=1&mute=1&loop=1&playlist=vlDOjTaaEdA&controls=0&showinfo=0&rel=0&iv_load_policy=3&modestbranding=1&disablekb=1&fs=0&cc_load_policy=0&playsinline=1',
            'hero_video_mobile' => 'https://www.youtube.com/embed/VATHDIChgwI?autoplay=1&mute=1&loop=1&playlist=VATHDIChgwI&controls=0&showinfo=0&rel=0&iv_load_policy=3&modestbranding=1&disablekb=1&fs=0&cc_load_policy=0&playsinline=1',
            'hero_stats_members' => 500,
            'hero_stats_events' => 50,
            'hero_stats_cars' => 1000,
            'latest_event_title' => 'Hyper Drive ParkMeet24',
            'latest_event_date' => '22 DEC',
            'latest_event_location' => 'Bakı',
            'latest_event_spots' => 'Tezliklə',
            'latest_event_image' => 'hyperparkmeet.webp',
            'about_title' => 'Hyper Drive nədir?',
            'about_description' => 'Hyper Drive avto həvəskarları birləşdirən əyləncəli və maarifləndirici bir cəmiyyətdir. Layihə çərçivəsində müxtəlif görüşlər, rallilər, maarifləndirici və digər tədbirlər həyata keçiriləcəkdir.',
            'about_features' => [
                [
                    'icon' => '🚗',
                    'title' => 'ParkMeet Tədbirləri',
                    'description' => 'Şəhər parklarında avtomobil buluşmaları'
                ],
                [
                    'icon' => '🎬',
                    'title' => 'Movie Night',
                    'description' => 'Avtomobil filmləri ilə kino gecələri'
                ],
                [
                    'icon' => '🌅',
                    'title' => 'Breakfast Səyahətlər',
                    'description' => 'Şəhər kənarlına səhər yeməyi səfərləri'
                ],
                [
                    'icon' => '🚗',
                    'title' => 'Mədəniyyət',
                    'description' => 'Avtomobil mədəniyyətini inkişaf'
                ]
            ],
            'featured_events_title' => 'Önə Çıxan Tədbirlər',
            'featured_events' => [
                [
                    'title' => 'Hyper Drive ParkMeet24',
                    'description' => 'İlin son parkda görüşü - Hyper Drive cəmiyyətinin ən böyük buluşması Bakıda.',
                    'date' => '22 DEC',
                    'location' => 'Bakı',
                    'participants' => '200+ İştirakçı',
                    'image' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=400&h=300&fit=crop'
                ],
                [
                    'title' => 'Hyper Drive RoofTop24',
                    'description' => 'Şəhərin üstündə avtomobil həvəskarlarının buluşması - RoofTop təcrübəsi.',
                    'date' => '05 MAY',
                    'location' => 'Bakı RoofTop',
                    'participants' => '150+ İştirakçı',
                    'image' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=400&h=300&fit=crop'
                ],
                [
                    'title' => 'Movie Night by Hyper Drive',
                    'description' => 'Car meet və "Baby Driver" filminin nümayişi - avtomobil mədəniyyəti və kino bir arada.',
                    'date' => '27 AUG',
                    'location' => 'Bakı',
                    'participants' => '100+ İştirakçı',
                    'image' => 'https://images.unsplash.com/photo-1489824904134-891ab64532f1?w=400&h=300&fit=crop'
                ]
            ],
            'community_title' => 'Cəmiyyətə Qoşulun',
            'community_description' => 'Hyper Drive, avtomobil həvəskarlarının bir araya gəldiyi gələcəkçi bir cəmiyyətdir. Burada avtomobil modifikasiyası, yarış texnikaları və texnologiya haqqında məlumat paylaşırıq.',
            'community_features' => [
                'Eksklüziv tədbirlərə giriş',
                'Peşəkar yarışçılardan təhsil',
                'Avtomobil modifikasiyası bələdçiləri',
                'Cəmiyyət yarışları və mükafatlar'
            ],
            'community_stats_members' => 500,
            'community_stats_events' => 50,
            'community_stats_cars' => 1000,
            'community_stats_brands' => 25,
            'footer_description' => 'Gələcək burada. Avtomobilinlə tanışlıq vaxtı.',
            'footer_email' => 'info@hyperdrive.az',
            'footer_instagram' => 'https://instagram.com/hyperdrive.az',
            'footer_tiktok' => 'https://tiktok.com/@hyperdrive.az',
            'footer_youtube' => 'https://youtube.com/@hyperdrive.az',
            'footer_telegram' => 'https://t.me/hyperdriveaz',
            'footer_location' => 'Bakı, Azərbaycan',
            'is_active' => true
        ]);
    }
} 