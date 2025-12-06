<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view dashboard',
            'manage users',
            'view statistics',
            'view popular users',
            'send notifications',
            'view email logs',
            'block users',
            'change user roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create admin role with all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Create user role (no special permissions)
        Role::firstOrCreate(['name' => 'user']);

        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'age' => 30,
                'pictures' => ['https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=600&fit=crop'],
                'location' => 'Jakarta, Indonesia',
                'bio' => 'System Administrator',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'is_blocked' => false,
            ]
        );

        // Make sure admin has admin role
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Assign 'user' role to all existing users without roles
        $usersWithoutRoles = User::whereDoesntHave('roles')->where('id', '!=', $admin->id)->get();
        foreach ($usersWithoutRoles as $user) {
            $user->assignRole('user');
        }

        $this->command->info('✅ Roles and permissions seeded successfully!');
        $this->command->info("   Admin: admin@example.com / admin123");
        $this->command->info("   Users without roles assigned: {$usersWithoutRoles->count()}");
    }
}