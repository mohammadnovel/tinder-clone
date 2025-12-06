<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    /**
     * First names for dummy data
     */
    private array $firstNames = [
        'Sarah', 'Amanda', 'Jessica', 'Michelle', 'Diana', 'Emily', 'Sophia',
        'Olivia', 'Emma', 'Ava', 'Isabella', 'Mia', 'Charlotte', 'Amelia',
        'Harper', 'Evelyn', 'Abigail', 'Ella', 'Scarlett', 'Grace',
        'Chloe', 'Victoria', 'Riley', 'Aria', 'Lily', 'Aurora', 'Zoey',
        'Nora', 'Camila', 'Hannah', 'Eleanor', 'Hazel', 'Violet',
        'Luna', 'Stella', 'Natalie', 'Zoe', 'Leah', 'Penelope',
        'Lucy', 'Anna', 'Maya', 'Caroline', 'Genesis', 'Kennedy', 'Samantha',
        'Michael', 'James', 'William', 'Benjamin', 'Lucas', 'Henry', 'Alexander',
        'Daniel', 'Matthew', 'Joseph', 'David', 'Andrew', 'Joshua', 'Christopher',
    ];

    /**
     * Locations with coordinates
     */
    private array $locations = [
        ['city' => 'Jakarta', 'lat' => -6.2088, 'lng' => 106.8456],
        ['city' => 'Bandung', 'lat' => -6.9175, 'lng' => 107.6191],
        ['city' => 'Surabaya', 'lat' => -7.2575, 'lng' => 112.7521],
        ['city' => 'Yogyakarta', 'lat' => -7.7956, 'lng' => 110.3695],
        ['city' => 'Bali', 'lat' => -8.3405, 'lng' => 115.0920],
        ['city' => 'Semarang', 'lat' => -6.9666, 'lng' => 110.4196],
        ['city' => 'Medan', 'lat' => 3.5952, 'lng' => 98.6722],
        ['city' => 'Makassar', 'lat' => -5.1477, 'lng' => 119.4327],
        ['city' => 'Malang', 'lat' => -7.9666, 'lng' => 112.6326],
        ['city' => 'Palembang', 'lat' => -2.9761, 'lng' => 104.7754],
    ];

    /**
     * Bio templates
     */
    private array $bios = [
        'Love traveling and good coffee ☕',
        'Foodie 🍕 | Gym enthusiast 💪',
        'Music lover 🎵',
        'Adventure seeker 🌍',
        'Beach vibes only 🏖️',
        'Book worm 📚 | Tea lover 🍵',
        'Photography enthusiast 📸',
        'Yoga & meditation 🧘‍♀️',
        'Dog lover 🐕',
        'Netflix & chill kind of person 🎬',
        'Hiking on weekends ⛰️',
        'Art & design lover 🎨',
        'Passionate about cooking 👩‍🍳',
        'Dancing enthusiast 💃',
        'Nature lover 🌿',
        'Gaming enthusiast 🎮',
        'Wine connoisseur 🍷',
        'Fitness freak 🏋️‍♀️',
        'Cat person 🐱',
        'Plant parent 🌱',
    ];

    /**
     * Image patterns from Unsplash
     */
    private array $imagePatterns = [
        'https://images.unsplash.com/photo-1494790108377-be9c29b29330',
        'https://images.unsplash.com/photo-1517841905240-472988babdf9',
        'https://images.unsplash.com/photo-1524504388940-b1c1722653e1',
        'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e',
        'https://images.unsplash.com/photo-1488426862026-3ee34a7d66df',
        'https://images.unsplash.com/photo-1534528741775-53994a69daeb',
        'https://images.unsplash.com/photo-1502685104226-ee32379fefbe',
        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d',
        'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6',
        'https://images.unsplash.com/photo-1517365830460-955ce3ccd263',
        'https://images.unsplash.com/photo-1438761681033-6461ffad8d80',
        'https://images.unsplash.com/photo-1544005313-94ddf0286df2',
        'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04',
        'https://images.unsplash.com/photo-1489424731084-a5d8b219a5bb',
        'https://images.unsplash.com/photo-1485893086445-ed75865251e0',
        'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d',
        'https://images.unsplash.com/photo-1500648767791-00dcc994a43e',
        'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e',
        'https://images.unsplash.com/photo-1519345182560-3f2917c472ef',
        'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding 100 dummy people...');

        for ($i = 1; $i <= 100; $i++) {
            $location = $this->locations[array_rand($this->locations)];

            // Generate 1-3 random pictures
            $numPictures = rand(1, 3);
            $pictures = [];
            $usedIndexes = [];

            for ($j = 0; $j < $numPictures; $j++) {
                do {
                    $index = array_rand($this->imagePatterns);
                } while (in_array($index, $usedIndexes));

                $usedIndexes[] = $index;
                $pictures[] = $this->imagePatterns[$index] . '?w=400&h=600&fit=crop&q=80';
            }

            Person::create([
                'name' => $this->firstNames[array_rand($this->firstNames)],
                'age' => rand(20, 35),
                'pictures' => $pictures,
                'location' => $location['city'],
                'bio' => $this->bios[array_rand($this->bios)],
                'latitude' => $location['lat'] + (rand(-100, 100) / 1000),
                'longitude' => $location['lng'] + (rand(-100, 100) / 1000),
            ]);

            if ($i % 10 === 0) {
                $this->command->info("  Created {$i} people...");
            }
        }

        $this->command->info('✅ Successfully seeded 100 people!');
    }
}
