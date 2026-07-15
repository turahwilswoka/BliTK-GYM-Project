<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MemberSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'membership_id', 'start_date', 'end_date',
        'status', 'payment_proof', 'amount_paid', 'qr_token',
        'confirmed_at', 'confirmed_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'confirmed_at' => 'datetime',
        'amount_paid' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($subscription) {
            if (empty($subscription->qr_token)) {
                $subscription->qr_token = Str::random(40) . '_' . time();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class, 'subscription_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->end_date
            && $this->end_date->greaterThanOrEqualTo(now());
    }

    public function isExpired(): bool
    {
        return $this->end_date && $this->end_date->lessThan(now());
    }

    public function getDaysRemainingAttribute(): int
    {
        if (!$this->end_date || $this->isExpired()) return 0;
        return (int) now()->diffInDays($this->end_date);
    }

    public function getQrDataAttribute(): string
    {
        return route('qrcode.validate', ['token' => $this->qr_token]);
    }
}
