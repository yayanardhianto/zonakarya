<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Applicant;
use App\Models\User;

class SyncApplicantEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'applicants:sync-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync email from user to applicant if applicant email is null';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting email sync for applicants...');
        
        // Find applicants with null email but have user_id
        $applicants = Applicant::whereNull('email')
            ->whereNotNull('user_id')
            ->with('user')
            ->get();
        
        $synced = 0;
        $skipped = 0;
        
        foreach ($applicants as $applicant) {
            if ($applicant->user && $applicant->user->email) {
                $applicant->update(['email' => $applicant->user->email]);
                $synced++;
                $this->info("Synced email for applicant ID {$applicant->id}: {$applicant->user->email}");
            } else {
                $skipped++;
                $this->warn("Skipped applicant ID {$applicant->id}: User email not available");
            }
        }
        
        $this->info("\nSync completed!");
        $this->info("Synced: {$synced}");
        $this->info("Skipped: {$skipped}");
        
        return 0;
    }
}

