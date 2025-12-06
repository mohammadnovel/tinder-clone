<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\PopularNotification;
use App\Mail\PopularUsersAlert;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CheckPopularUsers extends Command
{
    protected $signature = 'users:check-popular {--min-likes=50 : Minimum likes threshold}';
    protected $description = 'Check for users with 50+ likes and notify admin';

    public function handle(): int
    {
        $minLikes = $this->option('min-likes');
        $this->info("🔍 Checking for popular users (≥{$minLikes} likes)...");

        $adminEmail = config('app.admin_email', 'admin@example.com');

        // Find popular users not notified in last 24 hours
        $popularUsers = User::withCount(['receivedSwipes as likes_count' => function ($q) {
            $q->where('type', 'like');
        }])
        ->having('likes_count', '>=', $minLikes)
        ->whereDoesntHave('popularNotifications', function ($q) {
            $q->where('notified_at', '>=', Carbon::now()->subDay());
        })
        ->get();

        if ($popularUsers->isEmpty()) {
            $this->info('✨ No new popular users to notify.');
            return Command::SUCCESS;
        }

        $this->info("🔥 Found {$popularUsers->count()} popular user(s)!");

        // Display users
        $this->table(
            ['ID', 'Name', 'Email', 'Likes'],
            $popularUsers->map(fn($u) => [$u->id, $u->name, $u->email, $u->likes_count])
        );

        // Send email
        $this->info("📧 Sending notification to {$adminEmail}...");

        try {
            Mail::to($adminEmail)->send(new PopularUsersAlert($popularUsers));

            // Log notifications
            foreach ($popularUsers as $user) {
                PopularNotification::create([
                    'user_id' => $user->id,
                    'likes_count' => $user->likes_count,
                    'admin_email' => $adminEmail,
                    'notified_at' => Carbon::now(),
                ]);
            }

            $this->info('✅ Notification sent successfully!');
        } catch (\Exception $e) {
            $this->error('❌ Failed: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
