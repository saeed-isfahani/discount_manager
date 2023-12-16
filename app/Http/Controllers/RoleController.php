<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Facades\Response;
use App\Http\Requests\GetRoleUsersRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();

        return Response::message('Roles list find successfully')->data(new RoleCollection($roles))->send();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create(['name' => $request->name]);

        return Response::message('Role created successfully')->data(new RoleResource($role))->send();
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return Response::message('Role founded successfully')->data(new RoleResource($role))->send();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->all());

        return Response::message('Role updated successfully')->send();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return Response::message('Role deleted successfully')->send();
    }

    public function usersWithRoles(GetRoleUsersRequest $request)
    {
        if($request->validated('role') and !Role::where('name', $request->validated('role'))->exists()){
            throw new BadRequestException('role is not exists');
        }

        $usersQuery = new User();

        if ($request->validated('role')) {
            $usersQuery = $usersQuery->role($request->validated('role'));
        }
        if ($request->validated('user')) {
            $usersQuery->where('full_name', 'LIKE', '%' . $request->validated('user') . '%');
            $usersQuery = $usersQuery->orWhere('email', 'LIKE', '%' . $request->validated('user') . '%');
        }

        $users = $usersQuery->paginate($request->per_page ?? 5);

        return Response::message('User with roles founded successfully')->data(new UserCollection($users))->send();
    }
}
