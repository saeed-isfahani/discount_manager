<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use App\Enums\VerificationRequest\VerificationRequestProviderEnum;
use App\Enums\VerificationRequest\VerificationRequestTargetEnum;
use App\Models\VerificationRequest;
use Exception;

class VerificationCodeSender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $mobile , public VerificationRequestProviderEnum $provider, public VerificationRequestTargetEnum $target)
    {
        $this->onQueue('sms');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $verificationCode = config('APP_SMS') ? rand(100000, 999999) : '123456';

        VerificationRequest::create([
            'provider' => $this->provider,
            'code' => $verificationCode,
            'receiver' => $this->mobile,
            'target' => $this->target,
            'expire_at' => now()->addMinutes(config('settings.verification_request_timeout_in_minute')),
        ]);

        if (config('APP_SMS')) {
            try {
                Kavenegar::send('10004346', $this->mobile, __('auth.messages.your_verification_code', ['code', $verificationCode]));
            } catch (\Kavenegar\Exceptions\ApiException $e) {
                throw new ApiException($e->errorMessage(), 500);
            } catch (\Kavenegar\Exceptions\HttpException $e) {
                throw new HttpException($e->errorMessage(), 500);
            } catch (\Exceptions $ex) {
                throw new Exception($ex->getMessage(), 500);
            }
        }
    }
}
