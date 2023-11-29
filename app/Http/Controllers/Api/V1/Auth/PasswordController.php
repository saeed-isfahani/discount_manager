<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetPasswordRequest;
use App\Models\Password;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function set(SetPasswordRequest $request)
    {
        auth()->user()->update([
            'password' => bcrypt($request->password),
        ]);
    }

    public function remove(Request $request, Password $password)
    {
        //
    }
}
