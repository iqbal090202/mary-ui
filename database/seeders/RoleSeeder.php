<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'add admin']);

        $role1 = Role::create(['name' => 'superadmin']);
        $role1->givePermissionTo('add admin');

        $user1 = User::create([
            'user_name' => 'Superadmin',
            'email' => 'superadmin@mail.com',
            'password' => 'password'
        ]);
        $user1->assignRole($role1);

        $role2 = Role::create(['name' => 'admin']);
        $user2 = User::create([
            'user_name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => 'password'
        ]);
        $user2->assignRole($role2);

        $role3 = Role::create(['name' => 'user']);
        $user3 = User::create([
            'user_name' => 'User 1',
            'email' => 'user1@mail.com',
            'password' => 'password'
        ]);
        $user3->assignRole($role3);
    }
}
