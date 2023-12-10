<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Enums\VerificationRequest\UserStatusEnum;
use App\Facades\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index(PaginateRequest $request)
    {
        $users = new User();
        if ($request->q) {
            $users = $users->where('full_name', 'LIKE', '%' . $request->q . '%');
        }
        if ($request->status) {
            $users = $users->where('status', $request->status);
        }
        if ($request->date) {
            $users = $users->whereDate('created_at', $request->date);
        }

        $users = $users->orderBy('updated_at', 'DESC')->paginate($request->per_page ?? 5);

        return Response::message('user.messages.user_list_found_successfully')
            ->data(new UserCollection($users))
            ->send();
    }

    public function show(User $user)
    {
        return Response::message('user.messages.user_list_found_successfully')
            ->data(new UserResource($user))
            ->send();
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $result = $user->update($request->all());
        if ($result) {
            return Response::message('user.messages.user_successfuly_updated')
                ->data(new UserResource($user))
                ->send();
        }
    }

    public function active(User $user)
    {
        $user->update([
            'status' => UserStatusEnum::ACTIVE->value,
        ]);
        return Response::message('general.messages.successfull')
            ->data(new UserResource($user))
            ->send();
    }

    public function deactive(User $user)
    {
        $user->update([
            'status' => UserStatusEnum::DEACTIVE->value,
        ]);
        return Response::message('general.messages.successfull')
            ->data(new UserResource($user))
            ->send();
    }
}
