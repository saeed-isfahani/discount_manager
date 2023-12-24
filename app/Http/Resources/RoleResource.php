<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $users = User::whereRelation('roles', 'name', $this->name);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'users_count' => $users->count(),
            'last_users' => UserResource::collection($users->get()),
            'permissions' => new PermissionCollection($this->permissions)
        ];
    }
}
