<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    private array $modules = [
        'role', 'shop', 'user',
        'product', 'category'
    ];

    private array $pluralActions = [
        'list'
    ];

    private array $singularActions = [
        'view', 'create', 'update', 'delete', 'excel'
    ];

    private array $menuItems = [
        'dashboard', 'shops', 'users',
        'products', 'categories', 'roles',
        'profile', 'shop.logo', 'activate.user',
        'deactivate.user', 'activate.shop',
        'deactivate.shop', 'count.shop',
        'count_by_category.shop', 'count_by_month.shop',
        'products.shop', 'permissions', 'roles.users',
        'assign_permission.roles', 'most_visited.products',
    ];

    private string $defaultRole = 'Default Role';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->modules as $module) {
            $plural = Str::plural($module);
            $singular = $module;
            foreach ($this->pluralActions as $action) {
                Permission::firstOrCreate([
                    'name' => $action . '.' . $plural
                ]);
            }
            foreach ($this->singularActions as $action) {
                Permission::firstOrCreate([
                    'name' => $action . '.' . $singular
                ]);
            }
        }

        foreach ($this->menuItems as $menuItem) {
            Permission::firstOrCreate([
                'name' => $menuItem
            ]);
        }

        // Create default role
        $role = Role::firstOrCreate([
            'name' => $this->defaultRole
        ]);

        // Add all permissions to default role
        $role->syncPermissions(Permission::all()->pluck('name')->toArray());

        // Assign default role to first database user
        if ($user = User::first()) {
            $user->syncRoles([$this->defaultRole]);
        }
    }
}
