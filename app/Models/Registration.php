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

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="badge badge-warning">Gözləyir</span>',
            'approved' => '<span class="badge badge-success">Təsdiqləndi</span>',
            'rejected' => '<span class="badge badge-danger">Rədd edildi</span>',
            default => '<span class="badge badge-secondary">Bilinmir</span>'
        };
    }
}
