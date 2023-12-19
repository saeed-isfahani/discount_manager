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
        'List'
    ];

    private array $singularActions = [
        'View', 'Create', 'Update', 'Delete', 'Excel'
    ];

    private array $menuItems = [
        'Dashboard', 'Shops', 'Users',
        'Products', 'Categories', 'Roles',
        'Profile'
    ];

    private string $defaultRole = 'Super Admin';

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
                    'name' => $action . ' ' . $plural
                ]);
            }
            foreach ($this->singularActions as $action) {
                Permission::firstOrCreate([
                    'name' => $action . ' ' . $singular
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
