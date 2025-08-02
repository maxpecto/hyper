<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'voting_code_id',
        'registration_id',
        'ip_address',
        'user_agent',
    ];

    /**
     * Bu oyun hangi kodla verildiği
     */
    public function votingCode(): BelongsTo
    {
        return $this->belongsTo(VotingCode::class);
    }

    /**
     * Bu oyun hangi arabaya verildiği
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}
