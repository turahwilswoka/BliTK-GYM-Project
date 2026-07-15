<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'duration_days', 'price',
        'features', 'is_active', 'badge_color',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function subscriptions()
    {
        return $this->hasMany(MemberSubscription::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getDurationLabelAttribute(): string
    {
        if ($this->duration_days >= 365) {
            return ($this->duration_days / 365) . ' Tahun';
        } elseif ($this->duration_days >= 30) {
            return ($this->duration_days / 30) . ' Bulan';
        }
        return $this->duration_days . ' Hari';
    }
}
