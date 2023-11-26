<?php
namespace App\Enums\ProvincesCities;

use App\Enums\Enum;

enum ProvincesCitiesTypeEnum: string
{
    use Enum;
    case PROVINCE = 'province';
    case CITY = 'city';
}
