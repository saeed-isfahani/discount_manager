<?php

namespace App\Providers;

// use App\Contracts\Controller\Api\V1\Services\SmsServiceInterface;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Services\Sms\KavenegarService;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('sms', function () {
            $defaultSmsService = config('sms.default');
            $serviceConfig = config("sms.drivers.$defaultSmsService");
            $serviceClass = config("sms.drivers.$defaultSmsService.service");

            if (!class_exists($serviceClass)) {
                throw new BadRequestException("There is no $defaultSmsService service", 500);
            }

            return new $serviceClass(collect($serviceConfig));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
