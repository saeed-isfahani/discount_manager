<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Contracts\Controller\Api\V1\Auth\ProfileControllerInterface;
use App\Http\Controllers\Controller;
use App\Facades\Response;
use App\Http\Requests\Profile\EditProfileRequest;

class ProfileController extends Controller implements ProfileControllerInterface
{
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMe()
    {
        return Response::message('auth.messages.your_account_information_has_been_found')->data(auth()->user())->send();
    }

    public function edit(EditProfileRequest $profile)
    {
        auth()->user()->update([
            'first_name' => $profile['first_name'],
            'last_name' => $profile['last_name'],
            'email' => $profile['email'],
            'full_name' => $profile['first_name'] . ' ' . $profile['last_name']
        ]);

        return Response::message('auth.messages.profile_updated_successfully')->send();
    }
}
