<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'username',
        'car_brand',
        'car_model',
        'car_year',
        'car_color',
        'modifications',
        'experience',
        'interests',
        'front_photo',
        'back_photo',
        'left_photo',
        'right_photo',
        'interior_photo',
        'engine_photo',
        'status',
        'admin_notes',
        'newsletter_subscription',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'interests' => 'array',
        'newsletter_subscription' => 'boolean',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
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
     * Tam ad accessor
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
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
        $photos = [];
        
        if ($this->front_photo) $photos[] = $this->front_photo;
        if ($this->back_photo) $photos[] = $this->back_photo;
        if ($this->left_photo) $photos[] = $this->left_photo;
        if ($this->right_photo) $photos[] = $this->right_photo;
        if ($this->interior_photo) $photos[] = $this->interior_photo;
        if ($this->engine_photo) $photos[] = $this->engine_photo;
        
        return array_map(function($photo) {
            return asset('storage/' . $photo);
        }, $photos);
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
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
        
        // Araba oluştur
        Car::createFromRegistration($this);
        
        return $this;
    }

    /**
     * Başvuru reddedildiğinde
     */
    public function reject()
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
        ]);
        return $this;
    }
}
