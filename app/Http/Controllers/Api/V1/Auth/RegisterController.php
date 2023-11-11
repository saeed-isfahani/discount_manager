<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Contracts\Controller\Api\V1\Auth\RegisterControllerInterface;
use App\Enums\VerificationRequest\VerificationRequestProviderEnum;
use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckVerifyRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\SendVerifyRequest;
use App\Models\User;
use App\Models\VerificationRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Kavenegar;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;

class RegisterController extends Controller implements RegisterControllerInterface
{
    public function sendVerify(SendVerifyRequest $request)
    {
        if (User::where('mobile', $request->mobile)->exists()) {
            throw new BadRequestException(__('auth.errors.user_exists'));
        }

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

    public function checkVerify(CheckVerifyRequest $request)
    {
        $verificationCodeIsValid = VerificationRequest::where('receiver', $request->mobile)
            ->where('expire_at', '>', Carbon::now())
            ->where('code', $request->code)
            ->where('veriffication_at', null)
            ->first();

        if ($verificationCodeIsValid) {
            $verificationCodeIsValid->veriffication_at = Carbon::now();
            $verificationCodeIsValid->save();
            return Response::status(200)->message(__('auth.messages.mobile_is_just_verified'))->send();
        }

        throw new BadRequestException(__('auth.errors.mobile_or_code_wrong_or_code_expired'));
    }

    public function register(RegisterRequest $request)
    {
        $mobileIsVerified = VerificationRequest::where('receiver', $request->mobile)
            ->where('expire_at', '>', Carbon::now())
            ->where('veriffication_at', 'IS NOT', null)
            ->first();

        if ($mobileIsVerified) {
            User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'full_name' => $request->first_name . ' ' . $request->last_name,
                'mobile' => $request->mobile
            ]);

            return Response::status(200)->message(__('auth.messages.user_registered_successfully'))->send();
        }

        throw new BadRequestException(__('auth.errors.mobile_or_code_wrong_or_code_expired'));
    }
}
