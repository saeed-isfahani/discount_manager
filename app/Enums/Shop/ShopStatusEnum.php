<?php

namespace App\Enums\Shop;

use App\Enums\Enum;

enum ShopStatusEnum: string
{
    use Enum;
    case ACTIVE = 'active';
    case DEACTIVE = 'deactive';
    case PENDING = 'pending';
}
