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

        $admin_role = Role::create(['name' => 'Management']);

        $permission = Permission::create(['name' => 'UserManagement access']);
        $permission = Permission::create(['name' => 'Institution access']);
        $permission = Permission::create(['name' => 'Doctor access']);
        $permission = Permission::create(['name' => 'Patient access']);
        $permission = Permission::create(['name' => 'TestCharges access']);
        $permission = Permission::create(['name' => 'Notes access']);
        $permission = Permission::create(['name' => 'Sample create']);
        $permission = Permission::create(['name' => 'Sample edit']);
        $permission = Permission::create(['name' => 'Sample delete']);
        $permission = Permission::create(['name' => 'Manage TestReports']);
        $admin->assignRole($admin_role);

        $admin_role->givePermissionTo(Permission::all());
    }
}
