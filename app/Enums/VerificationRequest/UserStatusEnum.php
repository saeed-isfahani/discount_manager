<?php

namespace App\Enums\VerificationRequest;

use App\Enums\Enum;

enum UserStatusEnum: string
{
    use Enum;
    case ACTIVE = 'active';
    case DEACTIVE = 'deactive';
}
