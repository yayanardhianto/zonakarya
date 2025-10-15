<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TestSession;
use App\Models\Applicant;

class FixTestSessionUserIds extends Command
{
    protected $signature = 'test-session:fix-user-ids';
    protected $description = 'Fix test sessions that are missing user_id by linking them with applicants';

    public function handle()
    {
        $this->info('Fixing test sessions without user_id...');
        
        // Find test sessions without user_id but with applicant_id
        $sessions = TestSession::whereNull('user_id')
            ->whereNotNull('applicant_id')
            ->get();
            
        $fixed = 0;
        
        foreach ($sessions as $session) {
            $applicant = Applicant::find($session->applicant_id);
            if ($applicant && $applicant->user_id) {
                $session->update(['user_id' => $applicant->user_id]);
                $fixed++;
                $this->line("Fixed session {$session->id} with user_id {$applicant->user_id}");
            }
        }
        
        $this->info("Fixed {$fixed} test sessions.");
        
        return 0;
    }
}