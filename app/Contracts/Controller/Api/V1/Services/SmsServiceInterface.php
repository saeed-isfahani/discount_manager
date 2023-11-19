<?php

namespace App\Contracts\Controller\Api\V1\Services;

interface SmsServiceInterface
{
    public function send(String $mobile, String $text): bool;
}
