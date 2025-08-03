<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'car_brand',
        'car_model',
        'car_year',
        'car_color',
        'interests',
        'newsletter_subscription',
        'photo_urls',
        'status',
    ];

    protected $casts = [
        'interests' => 'array',
        'newsletter_subscription' => 'boolean',
        'photo_urls' => 'array',
    ];

    /**
     * Oy ilişkisi
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Araba ilişkisi
     */
    public function car()
    {
        return $this->hasOne(Car::class);
    }

    /**
     * Oy sayısı accessor
     */
    public function getVoteCountAttribute()
    {
        return $this->votes()->count();
    }

    /**
     * Oy yüzdesi accessor
     */
    public function getVotePercentageAttribute()
    {
        $totalVotes = Vote::count();
        if ($totalVotes === 0) {
            return 0;
        }
        return round(($this->vote_count / $totalVotes) * 100, 1);
    }

    /**
     * Durum rengi accessor
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'warning',
        };
    }

    /**
     * Durum metni accessor
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'approved' => 'Onaylandı',
            'rejected' => 'Reddedildi',
            default => 'Beklemede',
        };
    }

    /**
     * Araba tam adı accessor
     */
    public function getCarFullNameAttribute()
    {
        return trim($this->car_brand . ' ' . $this->car_model);
    }

    /**
     * Fotoğraf URL'leri accessor
     */
    public function getPhotoUrlsAttribute()
    {
        if (empty($this->photo_urls)) {
            return [];
        }
        return array_map(function($photo) {
            return asset('storage/' . $photo);
        }, $this->photo_urls);
    }

    /**
     * Onaylanmış başvurular scope
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Bekleyen başvurular scope
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Reddedilmiş başvurular scope
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * En çok oy alan başvurular scope
     */
    public function scopeTopVoted($query)
    {
        return $query->withCount('votes')->orderBy('votes_count', 'desc');
    }

    /**
     * Başvuru onaylandığında araba oluştur
     */
    public function approve()
    {
        $this->update(['status' => 'approved']);
        
        // Araba oluştur
        Car::createFromRegistration($this);
        
        return $this;
    }

    /**
     * Başvuru reddedildiğinde
     */
    public function reject()
    {
        $this->update(['status' => 'rejected']);
        return $this;
    }
}
