<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Contracts\Controller\Api\V1\Auth\LoginControllerInterface;
use App\Enums\VerificationRequest\VerificationRequestProviderEnum;
use App\Enums\VerificationRequest\VerificationRequestTargetEnum;
use App\Exceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginSendVerifyRequest;
use App\Models\VerificationRequest;
use App\Facades\Response;
use App\Http\Requests\Auth\LoginCheckPasswordRequest;
use App\Http\Requests\Auth\LoginCheckVerifyRequest;
use App\Jobs\VerificationCodeSender;
use App\Repositories\UserRepository;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller implements LoginControllerInterface
{
    public function __construct(public UserRepository $userRepository)
    {
    }

    public function checkPassword(LoginCheckPasswordRequest $request)
    {
        $user = $this->userRepository->findWhere([['mobile', '=', $request->mobile]])->first();
        if (!$user) {
            return Response::status(404)->message('auth.messages.this_user_is_not_registered_please_use_the_registration_tab')->send();
        }

        $credentials = request(['mobile', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            throw new UnauthorizedException();
        }

        return Response::message('auth.messages.you_have_successfully_logged_into_your_account')
            ->data(['access_token' => $token])
            ->send();
    }

    public function checkVerify(LoginCheckVerifyRequest $request)
    {
        $user = $this->userRepository->findWhere([['mobile', '=', $request->mobile]])->first();
        if (!$user) {
            return Response::status(404)->message('auth.messages.this_user_is_not_registered_please_use_the_registration_tab')->send();
        }

        $lastVerificationRequest = VerificationRequest::latestValidLoginRequestByMobile($request->mobile)->first();
        if (!$lastVerificationRequest) {
            throw new BadRequestHttpException();
        }

        $lastVerificationRequest->increment('attempts');

        if ($lastVerificationRequest->code != $request->code) {
            throw new BadRequestHttpException('auth.messages.code_is_invalid');
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
        if (!$this->userRepository->exists([['mobile', '=', $request->mobile]])) {
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
        }

        VerificationCodeSender::dispatch($request->mobile, VerificationRequestProviderEnum::KAVEHNEGAR, VerificationRequestTargetEnum::LOGIN);

        return Response::status(200)->message('auth.messages.code_was_sent')->send();
    }

    public function logout()
    {
        if (!JWTAuth::parseToken()) {
            throw new BadRequestException(__('auth.errors.user_is_not_login'));
        }

        JWTAuth::parseToken()->invalidate(false);

        return Response::message('auth.messages.user_logged_out_successfully')->send();
    }
}
