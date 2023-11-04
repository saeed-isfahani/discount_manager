<?php

namespace App\Enums\Example;

use App\Enums\Enum;

enum ExampleStatusEnum: string
{
    use Enum;

    case PENDING = 'pending';
    case REJECTED = 'rejected';
    case APPROVED = 'approved';
}
