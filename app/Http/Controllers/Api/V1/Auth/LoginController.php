<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Contracts\Controller\Api\V1\Auth\LoginControllerInterface;
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

class LoginController extends Controller implements LoginControllerInterface
{
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        return Response::message(__('auth.messages.your_account_information_has_been_found'))->data(auth()->user())->send();
    }

    public function checkVerify(LoginCheckVerifyRequest $request)
    {
        $user = User::where('mobile', $request->mobile)->first();
        if (!$user) {
            return Response::status(404)->message(__('auth.messages.This user is not registered Please use the registration tab'))->send();
        }

        $lastVerificationRequest = VerificationRequest::where('receiver', $request->mobile)
            ->whereCode($request->code)
            ->whereNull('veriffication_at')
            ->whereTarget('login')
            ->whereTime('created_at', '>=', now()->subMinute(2))
            ->latest()
            ->first();
        if (!$lastVerificationRequest) {
            return Response::status(500)->message(__('auth.messages.mobile_or_code_was_not_valid'))->send();
        }

        $lastVerificationRequest->update([
            'veriffication_at' => now(),
        ]);

        $token = auth()->attempt([
            'mobile' => $request->mobile,
            'password' => 'password',
        ]);
        if (!$token) {
            throw new UnauthorizedException();
        }

        return Response::message(__('auth.messages.you_have_successfully_logged_into_your_account'))->data($this->respondWithToken($token))->send();
    }

    public function sendVerify(LoginSendVerifyRequest $request)
    {
        $user = User::where('mobile', $request->mobile)->first();
        if (!$user) {
            return Response::status(404)->message(__('auth.messages.This user is not registered Please use the registration tab'))->send();
        }

        $lastVerificationRequest = VerificationRequest::where('receiver', $request->mobile)
            ->whereNull('veriffication_at')
            ->whereTarget('login')
            ->whereTime('expire_at', '>=', now())
            ->latest()
            ->first();
        if ($lastVerificationRequest) {
            $lastVerificationRequest->increment('attempts');
        } else {
            $lastVerificationRequest = VerificationRequest::create([
                'provider' => 'kavehnegar',
                'code' => rand(10000, 99999),
                'receiver' => $request->mobile,
                'attempts' => 1,
                'target' => 'login',
                'expire_at' => now()->addMinutes(2),
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

        return Response::status(200)->message(__('auth.messages.code_was_sent'))->send();
    }
}
