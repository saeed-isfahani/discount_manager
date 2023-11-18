<?php

namespace App\Models;

use App\Enums\VerificationRequest\VerificationRequestTargetEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationRequest extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function scopeLatestValidLoginRequestByMobile($query, $mobile)
    {
        return $query
            ->where('receiver', $mobile)
            ->whereNull('veriffication_at')
            ->where('target', VerificationRequestTargetEnum::LOGIN->value)
            ->whereTime('created_at', '>=', now()->subMinute(config('settings.verification_request_timeout_in_minute')))
            ->latest();
    }
}
