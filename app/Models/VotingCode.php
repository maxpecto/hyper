<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VotingCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'is_used',
        'used_at',
        'used_ip',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    /**
     * Bu kodla verilen oy
     */
    public function vote(): HasOne
    {
        return $this->hasOne(Vote::class);
    }

    /**
     * Kodun kullanılabilir olup olmadığını kontrol et
     */
    public function isAvailable(): bool
    {
        return !$this->is_used;
    }

    /**
     * Kodu kullanıldı olarak işaretle
     */
    public function markAsUsed(?string $ip = null): void
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
            'used_ip' => $ip,
        ]);
    }

    /**
     * Rastgele kod üret
     */
    public static function generateCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 10));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Toplu kod üret
     */
    public static function generateCodes(int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            self::create([
                'code' => self::generateCode(),
            ]);
        }
    }
}
