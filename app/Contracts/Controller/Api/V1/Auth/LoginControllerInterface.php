<?php

namespace App\Contracts\Controller\Api\V1\Auth;

use App\Http\Requests\Auth\LoginCheckVerifyRequest;
use App\Http\Requests\Auth\LoginSendVerifyRequest;

interface LoginControllerInterface
{
    public function checkVerify(LoginCheckVerifyRequest $request);

    public function sendVerify(LoginSendVerifyRequest $request);
}
