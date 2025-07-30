<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppSetting extends Model
{
    protected $fillable = [
        'whatsapp_enabled',
        'whatsapp_approved_message',
        'whatsapp_rejected_message',
        'whatsapp_pending_message',
    ];

    protected $casts = [
        'whatsapp_enabled' => 'boolean',
    ];
} 