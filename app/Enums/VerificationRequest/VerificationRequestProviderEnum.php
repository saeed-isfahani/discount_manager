<?php
namespace app\Enums\VerificationRequest;
namespace app\Enums;

enum VerificationRequestProviderEnum: string
{
    use Enum;
    case SMSIR = 'smsir';
    case KAVEHNEGAR = 'kavehnegar';
}
