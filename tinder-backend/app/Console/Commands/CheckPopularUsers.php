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
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:check-popular 
                            {--min-likes=50 : Minimum likes threshold}
                            {--force : Force send notification even if already notified today}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for users with 50+ likes and notify admin via email';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $minLikes = $this->option('min-likes');
        $force = $this->option('force');

        $this->info("🔍 Checking for popular users (≥{$minLikes} likes)...");

        // Get admin email from config
        $adminEmail = config('app.admin_email', 'admin@example.com');

        // Build query for popular users
        $query = User::withCount(['receivedSwipes as likes_count' => function ($query) {
            $query->where('type', 'like');
        }])
            ->having('likes_count', '>=', $minLikes);

        // Unless force, exclude users who were notified in the last 24 hours
        if (!$force) {
            $query->whereDoesntHave('popularNotifications', function ($q) {
                $q->where('notified_at', '>=', Carbon::now()->subDay());
            });
        }

        $popularUsers = $query->get();

        if ($popularUsers->isEmpty()) {
            $this->info('✨ No new popular users to notify.');
            return Command::SUCCESS;
        }

        $this->info("🔥 Found {$popularUsers->count()} popular user(s):");

        // Display table of popular users
        $tableData = $popularUsers->map(function ($user) {
            return [
                'ID' => $user->id,
                'Name' => $user->name,
                'Email' => $user->email,
                'Likes' => $user->likes_count,
            ];
        })->toArray();

        $this->table(['ID', 'Name', 'Email', 'Likes'], $tableData);

        // Send email notification
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
            $this->error('❌ Failed to send notification: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}