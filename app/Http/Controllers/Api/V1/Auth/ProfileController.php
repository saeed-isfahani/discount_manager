<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Contracts\Controller\Api\V1\Auth\ProfileControllerInterface;
use App\Http\Controllers\Controller;
use App\Facades\Response;

class ProfileController extends Controller implements ProfileControllerInterface
{
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return Response::message(__('auth.messages.your_account_information_has_been_found'))->data(auth()->user())->send();
    }
}
