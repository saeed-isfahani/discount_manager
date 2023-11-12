<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Contracts\Controller\Api\V1\Auth\RegisterControllerInterface;
use App\Enums\VerificationRequest\VerificationRequestProviderEnum;
use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterCheckVerifyRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RegisterSendVerifyRequest;
use App\Models\User;
use App\Models\VerificationRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Kavenegar;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller implements RegisterControllerInterface
{


    public function sendVerify(RegisterSendVerifyRequest $request)
    {
        if (User::where('mobile', $request->mobile)->exists()) {
            throw new BadRequestException(__('auth.errors.user_exists'));
        }

        $hasVerificationCode = VerificationRequest::where('receiver', $request->mobile)
            ->where('expire_at', '>', Carbon::now())
            ->first();

        if (!$hasVerificationCode) {
            $verificationCode = '123456';
            if (app()->environment(['production'])) {
                $verificationCode = rand(10000, 99999);
                try {
                    Kavenegar::send('10004346', $request->mobile, __('auth.messages.your_verification_code', ['code', $verificationCode]));
                } catch (\Kavenegar\Exceptions\ApiException $e) {
                    throw new ApiException($e->errorMessage(), 500);
                } catch (\Kavenegar\Exceptions\HttpException $e) {
                    throw new HttpException($e->errorMessage(), 500);
                } catch (\Exceptions $ex) {
                    throw new Exception($ex->getMessage(), 500);
                }
            }

            VerificationRequest::create([
                'provider' => VerificationRequestProviderEnum::KAVEHNEGAR,
                'code' => $verificationCode,
                'receiver' => $request->mobile,
                'target' => VerificationRequestTargetEnum::REGISTER->value,

                'expire_at' => Carbon::now()->addMinute(config('settings.verification_request_timeout_in_minute'))
            ]);
        }

        return Response::message('auth.messages.code_was_sent')->send();
    }


    public function checkVerify(RegisterCheckVerifyRequest $request)
    {
        $verificationCodeIsValid = VerificationRequest::where('receiver', $request->mobile)
            ->where('expire_at', '>', Carbon::now())
            ->where('veriffication_at', null)
            ->first();

        if (!$verificationCodeIsValid) {
            throw new BadRequestException(__('auth.errors.mobile_or_code_wrong_or_code_expired'));
        }

        $verificationCodeIsValid->increment('attempts');

        if ($verificationCodeIsValid and $verificationCodeIsValid->code != $request->code) {
            throw new BadRequestException(__('auth.errors.mobile_or_code_wrong_or_code_expired'));
        }

        $verificationCodeIsValid->update('veriffication_at', Carbon::now());

        return Response::message('auth.messages.mobile_is_just_verified')->send();
    }

    
    public function register(RegisterRequest $request)
    {
        $mobileIsVerified = VerificationRequest::where('receiver', $request->mobile)
            ->where('expire_at', '>', Carbon::now())
            ->where('veriffication_at', 'IS NOT', null)
            ->first();

        if (!$mobileIsVerified) {
            throw new BadRequestException(__('auth.errors.mobile_or_code_wrong_or_code_expired'));
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile' => $request->mobile,
            'email' => $request->email
        ]);

        $token = JWTAuth::fromUser($user);
        if (!$token) {
            throw new UnauthorizedException();
        }


        Registered::dispatch();

        return Response::data(['access_token' => $token])->message('auth.messages.user_registered_successfully')->send();
    }
}
