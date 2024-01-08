<?php

namespace App\Http\Controllers\Api\V1;

use App\Facades\Response;
use App\Http\Requests\GetRoleUsersRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\assignPermissionRequest;
use App\Http\Resources\UserWithRoleCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        /*--------- fix policies problem on route resources --------*/
        $this->authorizeResource(Role::class, 'role');
    }

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

        if ($request->permissions and is_array($request->permissions)) {
            $role->givePermissionTo($request->permissions);
        }

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

        if ($request->permissions and is_array($request->permissions)) {
            $role->syncPermissions($request->permissions);
        }

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
        $usersQuery = new User();

        if ($request->validated('role')) {
            $usersQuery = $usersQuery->role($request->validated('role'));
        }
        if ($request->validated('q')) {
            $usersQuery = $usersQuery->where(function ($query, $request) {
                return $query->where('full_name', 'LIKE', '%' . $request->validated('q') . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->validated('q') . '%');
            });
        }

        $users = $usersQuery->paginate($request->per_page ?? 5);

        return Response::message('User with roles founded successfully')->data(new UserWithRoleCollection($users))->send();
    }

    public function assignPermission(Role $role, assignPermissionRequest $request)
    {
        $role->givePermissionTo($request->permissions);

        return Response::message('Permissions assigns to role successfully')->data(new RoleResource($role))->send();
    }
}
