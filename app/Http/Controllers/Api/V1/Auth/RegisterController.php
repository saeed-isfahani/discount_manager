<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Contracts\Controller\Api\V1\Auth\RegisterControllerInterface;
use App\Enums\VerificationRequest\VerificationRequestProviderEnum;
use App\Enums\VerificationRequest\VerificationRequestTargetEnum;
use App\Exceptions\BadRequestException;
use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterCheckVerifyRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RegisterSendVerifyRequest;
use App\Jobs\VerificationCodeSender;
use App\Repositories\UserRepository;
use App\Models\VerificationRequest;
use App\Repositories\VerificationRequestRepository;
use Carbon\Carbon;
use Illuminate\Validation\UnauthorizedException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller implements RegisterControllerInterface
{

    public function __construct(
        public UserRepository $userRepository,
        public VerificationRequestRepository $VerificationRequestRepository
    ) {
    }

    public function sendVerify(RegisterSendVerifyRequest $request)
    {
        if ($this->userRepository->exists([['mobile', '=', $request->mobile]])) {
            throw new BadRequestException(__('auth.errors.user_exists'));
        }


        if (!$this->VerificationRequestRepository->exists(
            [
                ['receiver', '=', $request->mobile],
                ['expire_at', '>', Carbon::now()]
            ]
        )) {
            VerificationCodeSender::dispatch($request->mobile, VerificationRequestProviderEnum::KAVEHNEGAR, VerificationRequestTargetEnum::REGISTER);
        }

        return Response::message('auth.messages.code_was_sent')->send();
    }

    public function checkVerify(RegisterCheckVerifyRequest $request)
    {
        $verificationCodeIsValid = $this->VerificationRequestRepository->findWhere(
            [
                ['expire_at', '>', now()],
                ['receiver', '=', $request->mobile],
                ['veriffication_at', '=', null]
            ],
            true
        );

        if (!$verificationCodeIsValid) throw new BadRequestException(__('auth.errors.mobile_or_code_wrong_or_code_expired'));

        $this->VerificationRequestRepository->increment($verificationCodeIsValid, 'attempts');
        if ($verificationCodeIsValid and $verificationCodeIsValid->code != $request->code) {
            $verificationCodeIsValid->update([
                'attempts' => $verificationCodeIsValid->attempts + 1
            ]);
            throw new BadRequestException(__('auth.errors.mobile_or_code_wrong_or_code_expired'));
        }

        $verificationCodeIsValid->update([
            'veriffication_at' => Carbon::now()
        ]);

        return Response::message('auth.messages.mobile_is_just_verified')->send();
    }


    public function register(RegisterRequest $request)
    {
        $mobileIsVerified = VerificationRequest::where('receiver', $request->mobile)
            ->where('expire_at', '>', now()->format('Y-m-d H:i:s'))
            ->where('veriffication_at', 'IS NOT', null)
            ->first();

        if (!$mobileIsVerified) {
            throw new BadRequestException(__('auth.errors.mobile_or_code_wrong_or_code_expired'));
        }

        $user = $this->userRepository->create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile' => $request->mobile,
            'email' => $request->email
        ]);

        $token = JWTAuth::fromUser($user);
        if (!$token) {
            throw new UnauthorizedException();
        }


        // Registered::dispatch();

        return Response::data(['access_token' => $token])->message('auth.messages.user_registered_successfully')->send();
    }
}
