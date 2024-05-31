<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'avatar' => '',
            'created_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        $admin_role = Role::create(['name' => 'admin']);

        $permission = Permission::create(['name' => 'UserManagement access']);
        $permission = Permission::create(['name' => 'Practice Access']);
        $admin->assignRole($admin_role);

        $admin_role->givePermissionTo(Permission::all());
    }
}
