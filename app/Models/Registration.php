<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Araba Kayıt Modeli
 * 
 * Bu model, kullanıcıların araba kayıt bilgilerini temsil eder.
 * Kayıt durumu, fotoğraflar ve oy ilişkilerini yönetir.
 */
class Registration extends Model
{
    use HasFactory;

    /**
     * Toplu atama için güvenli alanlar
     * 
     * @var array
     */
    protected $fillable = [
        'full_name',           // Ad ve soyad
        'phone',               // Telefon numarası
        'email',               // E-posta adresi
        'car_brand',           // Araba markası
        'car_model',           // Araba modeli
        'car_year',            // Üretim yılı
        'engine_size',         // Motor hacmi
        'modifications',       // Modifikasyonlar
        'experience_years',    // Deneyim yılı
        'interests',           // İlgi alanları (JSON)
        'photo_front',         // Ön fotoğraf yolu
        'photo_back',          // Arka fotoğraf yolu
        'photo_left',          // Sol fotoğraf yolu
        'photo_right',         // Sağ fotoğraf yolu
        'photo_interior',      // İç mekan fotoğraf yolu
        'photo_engine',        // Motor fotoğraf yolu
        'status',              // Durum (pending, approved, rejected)
        'admin_notes',         // Admin notları
        'newsletter_subscription', // Bülten aboneliği
    ];

    /**
     * Veri türü dönüşümleri
     * 
     * @var array
     */
    protected $casts = [
        'interests' => 'array',                    // JSON array olarak
        'newsletter_subscription' => 'boolean',    // Boolean olarak
        'car_year' => 'integer',                   // Integer olarak
        'experience_years' => 'integer',           // Integer olarak
        'created_at' => 'datetime',                // DateTime olarak
        'updated_at' => 'datetime',                // DateTime olarak
    ];

    /**
     * Durum seçenekleri
     * 
     * @var array
     */
    public const STATUS_PENDING = 'pending';      // Bekliyor
    public const STATUS_APPROVED = 'approved';    // Onaylandı
    public const STATUS_REJECTED = 'rejected';    // Reddedildi

    /**
     * Bu kayıta verilen oyları getirir
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Toplam oy sayısını hesaplar
     * 
     * @return int
     */
    public function getVoteCountAttribute(): int
    {
        return $this->votes()->count();
    }

    /**
     * Oy yüzdesini hesaplar
     * 
     * @return float
     */
    public function getVotePercentageAttribute(): float
    {
        $totalVotes = Vote::count();
        
        if ($totalVotes === 0) {
            return 0.0;
        }
        
        return round(($this->vote_count / $totalVotes) * 100, 1);
    }

    /**
     * Durum badge rengini döner
     * 
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Durum metnini Azerbaycan dilinde döner
     * 
     * @return string
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Gözləyir',
            self::STATUS_APPROVED => 'Təsdiqləndi',
            self::STATUS_REJECTED => 'Rədd edildi',
            default => 'Naməlum'
        };
    }

    /**
     * Araba tam adını döner
     * 
     * @return string
     */
    public function getCarFullNameAttribute(): string
    {
        return "{$this->car_brand} {$this->car_model} ({$this->car_year})";
    }

    /**
     * Fotoğraf URL'lerini döner
     * 
     * @return array
     */
    public function getPhotoUrlsAttribute(): array
    {
        $baseUrl = asset('storage/');
        
        return [
            'front' => $this->photo_front ? $baseUrl . '/' . $this->photo_front : null,
            'back' => $this->photo_back ? $baseUrl . '/' . $this->photo_back : null,
            'left' => $this->photo_left ? $baseUrl . '/' . $this->photo_left : null,
            'right' => $this->photo_right ? $baseUrl . '/' . $this->photo_right : null,
            'interior' => $this->photo_interior ? $baseUrl . '/' . $this->photo_interior : null,
            'engine' => $this->photo_engine ? $baseUrl . '/' . $this->photo_engine : null,
        ];
    }

    /**
     * Onaylanmış kayıtları getirir
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Bekleyen kayıtları getirir
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Reddedilen kayıtları getirir
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * En çok oy alan arabaları getirir
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTopVoted($query)
    {
        return $query->approved()
            ->withCount('votes')
            ->orderBy('votes_count', 'desc');
    }
}
