<?php
namespace App\Enums\VerificationRequest;

use App\Enums\Enum;

enum VerificationRequestTargetEnum: string
{
    use Enum;
    case REGISTER = 'register';
    case LOGIN = 'login';
}
