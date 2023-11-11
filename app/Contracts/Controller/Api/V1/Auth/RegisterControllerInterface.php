<?php

namespace App\Contracts\Controller\Api\V1\Auth;

use App\Facades\Response;
use App\Http\Requests\Auth\CheckVerifyRequest;
use App\Http\Requests\Auth\SendVerifyRequest;

interface RegisterControllerInterface
{
    public function sendVerify(SendVerifyRequest $request);
    public function checkVerify(CheckVerifyRequest $request);
}
