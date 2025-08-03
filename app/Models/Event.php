<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_title',
        'page_subtitle',
        'filter_all_text',
        'filter_racing_text',
        'filter_meet_text',
        'filter_workshop_text',
        'filter_drift_text',
        'events',
        'is_active',
    ];

    protected $casts = [
        'events' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Singleton pattern - tek etkinlikler sayfası ayarı
     */
    public static function getSettings(): Event
    {
        $settings = static::first();
        
        if (!$settings) {
            $settings = static::createDefaultSettings();
        }
        
        return $settings;
    }

    /**
     * Varsayılan ayarları oluştur
     */
    public static function createDefaultSettings(): Event
    {
        return static::create([
            'page_title' => 'TƏDBİRLƏR',
            'page_subtitle' => 'Hyper Drive cəmiyyətinin ən həyəcanlı tədbirləri',
            'filter_all_text' => 'Hamısı',
            'filter_racing_text' => 'Yarış',
            'filter_meet_text' => 'Görüş',
            'filter_workshop_text' => 'Emalatxana',
            'filter_drift_text' => 'Drift',
            'events' => [
                [
                    'title' => 'Hyper Drive ParkMeet24',
                    'category' => 'meet',
                    'description' => 'İlin son parkda görüşü - Hyper Drive cəmiyyətinin ən böyük buluşması. Avtomobil həvəskarlarının bir araya gəldiyi tədbir.',
                    'date' => '22 DEC',
                    'location' => 'Bakı Şəhər Parkı',
                    'time' => '15:00 - 19:00',
                    'participants' => '200+ İştirakçı',
                    'price' => 'Ödənişsiz',
                    'image' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=600&h=400&fit=crop',
                    'is_featured' => true,
                    'registration_url' => '/register',
                    'details_url' => '#',
                ],
                [
                    'title' => 'Hyper Drive RoofTop24',
                    'category' => 'meet',
                    'description' => 'Şəhərin üstündə avtomobil həvəskarlarının buluşması - RoofTop təcrübəsi. Gözəl mənzərə eşliğində car meet.',
                    'date' => '05 MAY',
                    'location' => 'Bakı RoofTop Məkanı',
                    'time' => '16:00 - 20:00',
                    'participants' => '150+ İştirakçı',
                    'price' => 'Ödənişsiz',
                    'image' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=600&h=400&fit=crop',
                    'is_featured' => false,
                    'registration_url' => '/register',
                    'details_url' => '#',
                ],
                [
                    'title' => 'Movie Night by Hyper Drive',
                    'category' => 'workshop',
                    'description' => 'Car meet və "Baby Driver" filminin nümayişi - avtomobil mədəniyyəti və kino bir arada. Həvəskarlarla film gecəsi.',
                    'date' => '27 AUG',
                    'location' => 'Bakı Kino Mərkəzi',
                    'time' => '19:00 - 22:00',
                    'participants' => '100+ İştirakçı',
                    'price' => 'Ödənişsiz',
                    'image' => 'https://images.unsplash.com/photo-1489824904134-891ab64532f1?w=600&h=400&fit=crop',
                    'is_featured' => false,
                    'registration_url' => '/register',
                    'details_url' => '#',
                ],
                [
                    'title' => 'Hyper Drive X Sky Cinema',
                    'category' => 'workshop',
                    'description' => 'Sky Cinema kinoteatrında "Tokyo Drift" filminin nümayişi - avtomobil mədəniyyəti və sinema həvəskarları üçün.',
                    'date' => '06 AUG',
                    'location' => 'Hyper Drive Merkez',
                    'time' => '14:00 - 18:00',
                    'participants' => '15/20 Katılımcı',
                    'price' => '₺150 Katılım Ücreti',
                    'image' => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=600&h=400&fit=crop',
                    'is_featured' => false,
                    'registration_url' => '/register',
                    'details_url' => '#',
                ],
            ],
            'is_active' => true,
        ]);
    }
} 