<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Contracts\Controller\Api\V1\Auth\LoginControllerInterface;
use App\Enums\VerificationRequest\VerificationRequestProviderEnum;
use App\Enums\VerificationRequest\VerificationRequestTargetEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginSendVerifyRequest;
use App\Models\User;
use App\Models\VerificationRequest;
use App\Facades\Response;
use App\Http\Requests\Auth\LoginCheckVerifyRequest;
use Exception;
use Illuminate\Validation\UnauthorizedException;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller implements LoginControllerInterface
{
    public function checkVerify(LoginCheckVerifyRequest $request)
    {
        $user = User::where('mobile', $request->mobile)->first();
        if (!$user) {
            return Response::status(404)->message('auth.messages.this_user_is_not_registered_please_use_the_registration_tab')->send();
        }

        $lastVerificationRequest = VerificationRequest::where('receiver', $request->mobile)
            ->whereNull('veriffication_at')
            ->where('target', VerificationRequestTargetEnum::LOGIN->value)
            ->whereTime('created_at', '>=', now()->subMinute(config('settings.verification_request_timeout_in_minute')))
            ->latest()
            ->first();
        if (!$lastVerificationRequest) {
            return new BadRequestHttpException();
        }

        $lastVerificationRequest->increment('attempts');

        if ($lastVerificationRequest->code != $request->code) {
            return new BadRequestHttpException(__('auth.messages.code_is_invalid'));
        }

        $lastVerificationRequest->update([
            'veriffication_at' => now(),
        ]);

        $token = JWTAuth::fromUser($user);
        if (!$token) {
            throw new UnauthorizedException();
        }

        return Response::message('auth.messages.you_have_successfully_logged_into_your_account')
            ->data(['access_token' => $token])
            ->send();
    }

    public function sendVerify(LoginSendVerifyRequest $request)
    {
        $user = User::where('mobile', $request->mobile)->first();
        if (!$user) {
            return Response::status(404)->message('auth.messages.this_user_is_not_registered_please_use_the_registration_tab')->send();
        }

        $lastVerificationRequest = VerificationRequest::where('receiver', $request->mobile)
            ->whereNull('veriffication_at')
            ->where('target', VerificationRequestTargetEnum::LOGIN->value)
            ->whereTime('expire_at', '>=', now())
            ->latest()
            ->first();
        if ($lastVerificationRequest) {
            return Response::status(200)->message('auth.messages.code_was_sent')->send();
        } else {
            $lastVerificationRequest = VerificationRequest::create([
                'provider' => VerificationRequestProviderEnum::KAVEHNEGAR->value,
                'code' => rand(10000, 99999),
                'receiver' => $request->mobile,
                'attempts' => 1,
                'target' => VerificationRequestTargetEnum::LOGIN->value,
                'expire_at' => now()->addMinutes(config('settings.verification_request_timeout_in_minute')),
            ]);
        }

        try {
            Kavenegar::send('10004346', $request->mobile, __('auth.messages.your_verification_code', ['code', $lastVerificationRequest->code]));
        } catch (\Kavenegar\Exceptions\ApiException $e) {
            throw new ApiException($e->errorMessage(), 500);
        } catch (\Kavenegar\Exceptions\HttpException $e) {
            throw new HttpException($e->errorMessage(), 500);
        } catch (\Exception $ex) {
            throw new Exception($ex->getMessage(), 500);
        }

        return Response::status(200)->message('auth.messages.code_was_sent')->send();
    }
}
