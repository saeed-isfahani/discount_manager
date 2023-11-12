<?php

namespace App\Contracts\Controller\Api\V1\Auth;

use App\Facades\Response;
use App\Http\Requests\Auth\RegisterCheckVerifyRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RegisterSendVerifyRequest;

interface RegisterControllerInterface
{
    public function sendVerify(RegisterSendVerifyRequest $request);
    public function checkVerify(RegisterCheckVerifyRequest $request);
    public function register(RegisterRequest $request);
}
