<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\SetPasswordRequest;
use App\Models\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function set(SetPasswordRequest $request)
    {
        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return Response::message('auth.messages.you_have_successfully_set_password')->send();
    }

    public function remove(Request $request, Password $password)
    {
        auth()->user()->update([
            'password' => null,
        ]);

        return Response::message('auth.messages.you_have_successfully_remove_password')->send();
    }
}
