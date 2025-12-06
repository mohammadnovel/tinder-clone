<?php

namespace App\Console\Commands;

use App\Models\Person;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckPopularUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:check-popular {--threshold=50 : Minimum likes to be considered popular}';

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
        $threshold = (int) $this->option('threshold');
        
        $this->info("Checking for people with {$threshold}+ likes...");

        // Get people with likes count
        $popularPeople = Person::withCount(['swipesReceived as likes_count' => function ($query) {
            $query->where('type', 'like');
        }])
        ->having('likes_count', '>=', $threshold)
        ->get();

        if ($popularPeople->isEmpty()) {
            $this->info('No popular people found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$popularPeople->count()} popular people.");

        foreach ($popularPeople as $person) {
            $this->notifyAdmin($person);
            $this->line("  - {$person->name} (ID: {$person->id}) has {$person->likes_count} likes");
        }

        $this->info("Admin notification sent for {$popularPeople->count()} popular people.");
        
        return Command::SUCCESS;
    }

    /**
     * Send notification email to admin
     */
    private function notifyAdmin(Person $person): void
    {
        $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL', 'admin@example.com'));

        try {
            // In production, use a proper Mailable class
            Mail::raw(
                $this->buildEmailContent($person),
                function ($message) use ($adminEmail, $person) {
                    $message->to($adminEmail)
                        ->subject("🔥 Popular User Alert: {$person->name} has {$person->likes_count}+ likes!");
                }
            );

            Log::info("Admin notified about popular person: {$person->name} (ID: {$person->id})");
        } catch (\Exception $e) {
            Log::error("Failed to notify admin about person {$person->id}: " . $e->getMessage());
            $this->error("Failed to send email for {$person->name}: " . $e->getMessage());
        }
    }

    /**
     * Build email content
     */
    private function buildEmailContent(Person $person): string
    {
        return <<<EMAIL
        🔥 Popular User Alert!
        
        User Details:
        - Name: {$person->name}
        - ID: {$person->id}
        - Age: {$person->age}
        - Location: {$person->location}
        - Total Likes: {$person->likes_count}
        
        This user has received more than 50 likes and is now considered popular!
        
        ---
        Tinder Clone Admin System
        EMAIL;
    }
}
