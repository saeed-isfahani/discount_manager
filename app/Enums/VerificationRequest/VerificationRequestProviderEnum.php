<?php
namespace App\Enums\VerificationRequest;

use App\Enums\Enum;

enum VerificationRequestProviderEnum: string
{
    use Enum;
    case SMSIR = 'smsir';
    case KAVEHNEGAR = 'kavehnegar';
}
