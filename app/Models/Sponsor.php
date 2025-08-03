<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'category',
        'description',
        'website',
        'email',
        'phone',
        'instagram',
        'facebook',
        'linkedin',
        'partnership_start',
        'partnership_end',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'partnership_start' => 'date',
        'partnership_end' => 'date',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Kategori seçenekleri
     */
    public static function getCategoryOptions(): array
    {
        return [
            'platinum' => 'Platinum Sponsor',
            'gold' => 'Gold Sponsor', 
            'silver' => 'Silver Sponsor',
            'bronze' => 'Bronze Sponsor',
        ];
    }

    /**
     * Kategori renkleri
     */
    public static function getCategoryColors(): array
    {
        return [
            'platinum' => '#E5E4E2',
            'gold' => '#FFD700',
            'silver' => '#C0C0C0',
            'bronze' => '#CD7F32',
        ];
    }

    /**
     * Aktif sponsorları getir
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Kategoriye göre sırala
     */
    public function scopeByCategory($query, $category = null)
    {
        if ($category) {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Sıralama
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Kategori rengini getir
     */
    public function getCategoryColorAttribute(): string
    {
        return self::getCategoryColors()[$this->category] ?? '#666666';
    }

    /**
     * Partner süresi
     */
    public function getPartnershipPeriodAttribute(): string
    {
        if (!$this->partnership_start) {
            return 'Yeni Partner';
        }

        $start = $this->partnership_start->format('Y');
        $end = $this->partnership_end ? $this->partnership_end->format('Y') : 'Devam Ediyor';
        
        return "{$start} - {$end}";
    }
} 