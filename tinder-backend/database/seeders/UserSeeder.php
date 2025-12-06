<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding demo users...');

        // Demo User 1 - John
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'age' => 28,
            'pictures' => [
                'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=600&fit=crop',
            ],
            'location' => 'Jakarta',
            'bio' => 'Software Developer 💻 | Coffee addict ☕',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ]);

        // Demo User 2 - Jane
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'age' => 25,
            'pictures' => [
                'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&h=600&fit=crop',
            ],
            'location' => 'Bandung',
            'bio' => 'Designer & Coffee Lover ☕ | Travel enthusiast 🌍',
            'latitude' => -6.9175,
            'longitude' => 107.6191,
        ]);

        // Demo User 3 - Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'age' => 30,
            'pictures' => [
                'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=600&fit=crop',
            ],
            'location' => 'Jakarta',
            'bio' => 'System Administrator',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ]);

        $this->command->info('✅ Created 3 demo users:');
        $this->command->line('  📧 john@example.com / password123');
        $this->command->line('  📧 jane@example.com / password123');
        $this->command->line('  📧 admin@example.com / admin123');
    }
}
