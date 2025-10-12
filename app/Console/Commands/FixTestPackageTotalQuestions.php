<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TestPackage;

class FixTestPackageTotalQuestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-package:fix-total-questions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix total_questions field for all test packages to match actual question count';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo "Fixing total_questions for all test packages...\n";
        
        $packages = TestPackage::all();
        $fixed = 0;
        
        foreach ($packages as $package) {
            $actualCount = $package->questions()->count();
            $storedCount = $package->total_questions;
            
            if ($actualCount != $storedCount) {
                $package->update(['total_questions' => $actualCount]);
                echo "Package '{$package->name}' (ID: {$package->id}): {$storedCount} -> {$actualCount}\n";
                $fixed++;
            }
        }
        
        echo "Fixed {$fixed} packages out of {$packages->count()} total packages.\n";
        
        return Command::SUCCESS;
    }
}