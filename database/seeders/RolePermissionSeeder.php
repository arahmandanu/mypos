<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'access pos',
            'manage products',
            'view reports',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);

        $cashierRole = Role::firstOrCreate(['name' => 'cashier']);
        $cashierRole->syncPermissions(['access pos']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@mypos.test'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );
        $admin->assignRole('admin');

        $cashier = User::firstOrCreate(
            ['email' => 'cashier@mypos.test'],
            ['name' => 'Cashier', 'password' => bcrypt('password')]
        );
        $cashier->assignRole('cashier');
    }
}
