<?php

namespace App\Services\Sms;

use App\Contracts\Controller\Api\V1\Services\SmsServiceInterface;

class ExampleSmsService implements SmsServiceInterface
{
    private function init(){

    }
    
    public function send(String $mobile, String $text): bool{
        
    }
}
