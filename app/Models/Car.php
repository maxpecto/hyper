<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'owner_name',
        'owner_username',
        'owner_avatar',
        'brand',
        'model',
        'year',
        'color',
        'modification',
        'horsepower',
        'acceleration',
        'category',
        'tags',
        'main_image',
        'gallery_images',
        'rating',
        'rating_count',
        'is_featured',
        'is_manual', // true = manuel eklenen, false = başvuru kaynağı
        'registration_id', // başvuru kaynağı ise registration ID'si
        'is_active',
    ];

    protected $casts = [
        'tags' => 'array',
        'gallery_images' => 'array',
        'is_featured' => 'boolean',
        'is_manual' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'float',
        'rating_count' => 'integer',
    ];

    /**
     * Başvuru ile ilişki
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    /**
     * Kategori seçenekleri
     */
    public static function getCategoryOptions()
    {
        return [
            'bmw' => 'BMW',
            'mercedes' => 'Mercedes',
            'audi' => 'Audi',
            'japanese' => 'Japon',
            'other' => 'Diğer',
        ];
    }

    /**
     * Etiket seçenekleri
     */
    public static function getTagOptions()
    {
        return [
            'Drift' => 'Drift',
            'Track' => 'Track',
            'Show' => 'Show',
            'Racing' => 'Racing',
            'Tuned' => 'Tuned',
            'AWD' => 'AWD',
            'Luxury' => 'Luxury',
            'Performance' => 'Performance',
            '4-Door' => '4-Door',
            'JDM' => 'JDM',
            'Iconic' => 'Iconic',
            'Wagon' => 'Wagon',
            'Daily' => 'Daily',
            'Fast' => 'Fast',
            'Turbo' => 'Turbo',
        ];
    }

    /**
     * Aktif arabaları getir
     */
    public static function getActiveCars()
    {
        return static::where('is_active', true)->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
    }

    /**
     * Başvuru kaynaklı arabaları getir
     */
    public static function getRegistrationCars()
    {
        return static::where('is_manual', false)->where('is_active', true);
    }

    /**
     * Manuel eklenen arabaları getir
     */
    public static function getManualCars()
    {
        return static::where('is_manual', true)->where('is_active', true);
    }

    /**
     * Kategoriye göre filtrele
     */
    public function scopeByCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Arama yap
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('owner_username', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('color', 'like', "%{$search}%")
                  ->orWhere('modification', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Başvurudan araba oluştur
     */
    public static function createFromRegistration(Registration $registration)
    {
        // Başvuru zaten araba olarak eklenmiş mi kontrol et
        $existingCar = static::where('registration_id', $registration->id)->first();
        if ($existingCar) {
            return $existingCar;
        }

        // Başvuru fotoğraflarından ana görsel seç
        $photos = $registration->photo_urls;
        $mainImage = !empty($photos) ? $photos[0] : null;

        // Marka ve model bilgisini çıkar
        $carInfo = self::extractCarInfo($registration->car_brand, $registration->car_model);

        return static::create([
            'title' => $registration->car_full_name,
            'owner_name' => $registration->full_name,
            'owner_username' => '@' . strtolower(str_replace(' ', '', $registration->full_name)),
            'owner_avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face',
            'brand' => $carInfo['brand'],
            'model' => $carInfo['model'],
            'year' => $registration->car_year ?? '2024',
            'color' => $registration->car_color ?? 'Bilinmiyor',
            'modification' => 'Stock',
            'horsepower' => 'Bilinmiyor',
            'acceleration' => 'Bilinmiyor',
            'category' => self::determineCategory($carInfo['brand']),
            'tags' => ['Show', 'Daily'],
            'main_image' => $mainImage,
            'gallery_images' => $photos,
            'rating' => 5.0,
            'rating_count' => rand(10, 50),
            'is_featured' => false,
            'is_manual' => false,
            'registration_id' => $registration->id,
            'is_active' => true,
        ]);
    }

    /**
     * Araba bilgilerini çıkar
     */
    private static function extractCarInfo($brand, $model)
    {
        $brand = trim($brand ?? '');
        $model = trim($model ?? '');

        // Basit marka çıkarma
        $commonBrands = ['BMW', 'Mercedes', 'Audi', 'Toyota', 'Honda', 'Nissan', 'Mazda', 'Subaru', 'Mitsubishi', 'Lexus'];
        
        foreach ($commonBrands as $commonBrand) {
            if (stripos($brand, $commonBrand) !== false) {
                return [
                    'brand' => $commonBrand,
                    'model' => trim(str_ireplace($commonBrand, '', $brand . ' ' . $model))
                ];
            }
        }

        return [
            'brand' => $brand ?: 'Diğer',
            'model' => $model ?: 'Bilinmiyor'
        ];
    }

    /**
     * Markaya göre kategori belirle
     */
    private static function determineCategory($brand)
    {
        $japaneseBrands = ['Toyota', 'Honda', 'Nissan', 'Mazda', 'Subaru', 'Mitsubishi', 'Lexus'];
        
        if (in_array($brand, $japaneseBrands)) {
            return 'japanese';
        }
        
        $brandMap = [
            'BMW' => 'bmw',
            'Mercedes' => 'mercedes',
            'Audi' => 'audi',
        ];
        
        return $brandMap[$brand] ?? 'other';
    }
} 