<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionArray = [
            [
                'name' => '1',
                'guard_name' => 'api',
            ],
            [
                'name' => '2',
                'guard_name' => 'api',
            ],
            [
                'name' => '3',
                'guard_name' => 'api',
            ],
            [
                'name' => '4',
                'guard_name' => 'api',
            ],
            [
                'name' => '5',
                'guard_name' => 'api',
            ],
            [
                'name' => '6',
                'guard_name' => 'api',
            ],
            [
                'name' => '7',
                'guard_name' => 'api',
            ],
            [
                'name' => '8',
                'guard_name' => 'api',
            ],
            [
                'name' => '9',
                'guard_name' => 'api',
            ],
            [
                'name' => '10',
                'guard_name' => 'api',
            ]
        ];

        $permissions = [];
        foreach ($permissionArray as $permission) {

            $insertPermission = [
                'name' => $permission['name'],
                'guard_name' => $permission['guard_name'],
            ];
            $permissions[] = Permission::firstOrCreate($insertPermission)->name;
        }

        Permission::whereNotIn('name', $permissions)->delete();
    }
}
