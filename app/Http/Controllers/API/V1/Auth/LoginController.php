<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Contracts\Controller\Api\V1\Auth\LoginControllerInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginSendVerifyRequest;
use App\Models\User;
use App\Models\VerificationRequest;
use Carbon\Carbon;
use Exception;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;

class LoginController extends Controller implements LoginControllerInterface
{
    public function sendVerify(LoginSendVerifyRequest $request)
    {
        $user = User::where('mobile', $request->mobile)->findOrFail();

        $lastVerificationCode = VerificationRequest::where('receiver', $request->mobile)
            ->whereNotNull('last_seen')
            ->whereTime('last_seen', '>=', now()->subMinute(2))
            ->first();

        if (app()->environment(['production'])) {
            $verificationCode = rand(10000, 99999);
        } else {
            $verificationCode = '12345';
        }

        try {
            Kavenegar::send('10004346', $request->mobile, __('auth.messages.your_verification_code', ['code', $verificationCode]));
        } catch (\Kavenegar\Exceptions\ApiException $e) {
            throw new ApiException($e->errorMessage(), 500);
        } catch (\Kavenegar\Exceptions\HttpException $e) {
            throw new HttpException($e->errorMessage(), 500);
        } catch (\Exception $ex) {
            throw new Exception($ex->getMessage(), 500);
        }

        $hasVerificationCode = VerificationRequest::where('receiver', $request->mobile)
            ->where('expire_at', '>', Carbon::now())
            ->first();

        if ($hasVerificationCode) {
            DB::table('verification_requests')
                ->where('receiver', $hasVerificationCode->receiver)
                ->where('updated_at', $hasVerificationCode->updated_at)
                ->increment('attempts', 1);
        } else {
            VerificationRequest::create([
                'provider' => VerificationRequestProviderEnum::KAVEHNEGAR,
                'code' => $verificationCode,
                'receiver' => $request->mobile,
                'expire_at' => Carbon::now()->addMinute(2)
            ]);
        }
        return Response::status(200)->message(__('auth.messages.code_was_sent'))->send();
    }
}
