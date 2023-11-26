<?php

namespace App\Facades;

use App\Contracts\Controller\Api\V1\Services\SmsServiceInterface;
use Illuminate\Support\Facades\Facade;

class Sms extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SmsServiceInterface::class;
    }
}
