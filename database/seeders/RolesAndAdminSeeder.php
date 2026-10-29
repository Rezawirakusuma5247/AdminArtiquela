<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $adminRole = Role::create(['name' => 'Admin']);


        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@tester.com',
            'password' => Hash::make('123456'),
        ]);

        $user->assignRole($superAdminRole);

    }
}
