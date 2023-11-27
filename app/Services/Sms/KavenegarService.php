<?php

namespace App\Services\Sms;

use App\Contracts\Controller\Api\V1\Services\SmsServiceInterface;
use Illuminate\Support\Collection;
use Kavenegar;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;

class KavenegarService implements SmsServiceInterface
{
    public function __construct(Collection $config)
    {   
    }
    
    public function send(String $mobile, String $text): bool
    {
        try {
            Kavenegar::send('10004346', $mobile, $text);
        } catch (\Kavenegar\Exceptions\ApiException $e) {
            throw new ApiException($e->errorMessage(), 500);
        } catch (\Kavenegar\Exceptions\HttpException $e) {
            throw new HttpException($e->errorMessage(), 500);
        } catch (\Exceptions $ex) {
            throw new Exception($ex->getMessage(), 500);
        }

        return true;
    }
}
