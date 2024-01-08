<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolesAndUsers = [
            'Super admin' => [
                'user_data' => [
                    'first_name' => 'Super admin',
                    'last_name' => 'Sa',
                    'full_name' => 'Super admin',
                    'email' => 'superadmin@example.com',
                    'email_verified_at' => now(),
                    'password' => '123456',
                    'remember_token' => null,
                    'mobile' => fake()->numerify('989#########')
                ],
                'permissions' => Permission::all()->pluck('name')->toArray()
            ],
            'Admin' => [
                'user_data' => [
                    'first_name' => 'Admin',
                    'last_name' => 'A',
                    'full_name' => 'Admin',
                    'email' => 'admin@example.com',
                    'email_verified_at' => now(),
                    'password' => '123456',
                    'remember_token' => null,
                    'mobile' => fake()->numerify('989#########')
                ],
                'permissions' => [
                    'list.users',
                    'list.products',
                    'list.categories',
                    'dashboard',
                    'shops',
                    'users',
                    'products',
                    'categories',
                    'profile'
                ]
            ],
            'Account manager' => [
                'user_data' => [
                    'first_name' => 'Account manager',
                    'last_name' => 'Am',
                    'full_name' => 'Account manager',
                    'email' => 'accountmanager@example.com',
                    'email_verified_at' => now(),
                    'password' => '123456',
                    'remember_token' => null,
                    'mobile' => fake()->numerify('989#########')
                ],
                'permissions' => [
                    'list.users',
                    'list.products',
                    'list.categories',
                    'dashboard',
                    'shops',
                    'users',
                    'products',
                    'categories',
                ]
            ],
            'Shop manager' => [
                'user_data' => [
                    'first_name' => 'Shop manager',
                    'last_name' => 'Sm',
                    'full_name' => 'Shop manager',
                    'email' => 'shopmanager@example.com',
                    'email_verified_at' => now(),
                    'password' => '123456',
                    'remember_token' => null,
                    'mobile' => fake()->numerify('989#########')
                ],
                'permissions' => [
                    'list.products',
                    'dashboard',
                    'shops',
                    'products',
                ]
            ]
        ];

        foreach ($rolesAndUsers as $roleName => $userArray) {

            $role = Role::firstOrCreate([
                'name' => $roleName
            ]);

            $role->givePermissionTo($userArray['permissions']);

            $user = User::firstOrCreate($userArray['user_data']);

            $user->assignRole($role->name);
        }
    }
}
