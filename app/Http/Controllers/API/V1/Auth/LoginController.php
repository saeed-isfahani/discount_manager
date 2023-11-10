<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Contracts\Controller\Api\V1\Auth\LoginControllerInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginSendVerifyRequest;
use App\Models\User;
use App\Models\VerificationRequest;
use App\Facades\Response;
use Exception;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;

class LoginController extends Controller implements LoginControllerInterface
{
    public function sendVerify(LoginSendVerifyRequest $request)
    {
        $user = User::where('mobile', $request->mobile)->firstOrFail();

        $lastVerificationCode = VerificationRequest::where('receiver', $user->mobile)
            ->whereNull('veriffication_at')
            ->whereTarget('login')
            ->whereTime('created_at', '>=', now()->subMinute(2))
            ->first();
        if ($lastVerificationCode) {
            $lastVerificationCode->increment('attempts');
        } else {
            $lastVerificationCode = VerificationRequest::create([
                'provider' => 'kavehnegar',
                'code' => rand(10000, 99999),
                'receiver' => $user->mobile,
                'attempts' => 1,
                'target' => 'login',
                'expire_at'=>now()->addMinutes(2),
            ]);
        }

        try {
            Kavenegar::send('10004346', $request->mobile, __('auth.messages.your_verification_code', ['code', $lastVerificationCode->code]));
        } catch (\Kavenegar\Exceptions\ApiException $e) {
            throw new ApiException($e->errorMessage(), 500);
        } catch (\Kavenegar\Exceptions\HttpException $e) {
            throw new HttpException($e->errorMessage(), 500);
        } catch (\Exception $ex) {
            throw new Exception($ex->getMessage(), 500);
        }

        return Response::status(200)->message(__('auth.messages.code_was_sent'))->send();
    }
}
