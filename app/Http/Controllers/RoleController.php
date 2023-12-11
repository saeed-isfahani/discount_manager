<?php

namespace App\Http\Controllers;

use App\Facades\Response;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
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
}
