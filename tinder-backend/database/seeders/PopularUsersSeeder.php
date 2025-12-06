<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Swipe;
use Illuminate\Support\Facades\Hash;

class PopularUsersSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔥 Creating popular users with 50+ likes...');

        // Buat User Popular 1 (akan dapat 60 likes)
        $popular1 = User::create([
            'name' => 'Sarah Popular',
            'email' => 'sarah.popular@example.com',
            'password' => Hash::make('password123'),
            'age' => 25,
            'pictures' => ['https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400'],
            'location' => 'Jakarta',
            'bio' => 'I am very popular!',
        ]);
        $popular1->assignRole('user');

        // Buat User Popular 2 (akan dapat 55 likes)
        $popular2 = User::create([
            'name' => 'Mike Famous',
            'email' => 'mike.famous@example.com',
            'password' => Hash::make('password123'),
            'age' => 28,
            'pictures' => ['https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400'],
            'location' => 'Bandung',
            'bio' => 'Super popular guy!',
        ]);
        $popular2->assignRole('user');

        // Buat 60 dummy users untuk memberikan likes
        $this->command->info('👥 Creating 60 dummy users to give likes...');
        
        for ($i = 1; $i <= 60; $i++) {
            $user = User::create([
                'name' => "Dummy User {$i}",
                'email' => "dummy{$i}@example.com",
                'password' => Hash::make('password123'),
                'age' => rand(18, 35),
                'pictures' => ['https://i.pravatar.cc/400?img=' . $i],
                'location' => 'Indonesia',
                'bio' => 'Just a dummy user',
            ]);
            $user->assignRole('user');

            // Each dummy user likes popular1 (60 likes total)
            Swipe::create([
                'swiper_id' => $user->id,
                'swiped_id' => $popular1->id,
                'type' => 'like',
            ]);

            // 55 dummy users like popular2 (55 likes total)
            if ($i <= 55) {
                Swipe::create([
                    'swiper_id' => $user->id,
                    'swiped_id' => $popular2->id,
                    'type' => 'like',
                ]);
            }
        }

        $this->command->info('');
        $this->command->info('✅ Popular users created successfully!');
        $this->command->info('   📧 sarah.popular@example.com - 60 likes');
        $this->command->info('   📧 mike.famous@example.com - 55 likes');
        $this->command->info('   📧 60 dummy users created');
    }
}
