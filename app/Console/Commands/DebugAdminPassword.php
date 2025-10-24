<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class DebugAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:debug-password {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug admin password issues on server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("=== DEBUGGING ADMIN PASSWORD ISSUE ===");
        $this->info("Email: {$email}");
        
        // Check PHP environment
        $this->info("\n=== PHP ENVIRONMENT ===");
        $this->info("PHP Version: " . PHP_VERSION);
        $this->info("Bcrypt available: " . (function_exists('password_hash') ? 'Yes' : 'No'));
        $this->info("Hash driver: " . config('hashing.driver'));
        $this->info("Bcrypt rounds: " . config('hashing.bcrypt.rounds'));
        
        // Check admin record
        $admin = Admin::where('email', $email)->first();
        
        if (!$admin) {
            $this->error("Admin with email {$email} not found!");
            return 1;
        }
        
        $this->info("\n=== ADMIN RECORD ===");
        $this->info("ID: {$admin->id}");
        $this->info("Email: {$admin->email}");
        $this->info("Status: {$admin->status}");
        $this->info("Password length: " . strlen($admin->password));
        $this->info("Password starts with: " . substr($admin->password, 0, 15) . "...");
        
        // Check password format
        $this->info("\n=== PASSWORD ANALYSIS ===");
        $this->info("Is bcrypt format: " . (str_starts_with($admin->password, '$2y$') ? 'Yes' : 'No'));
        $this->info("Is argon format: " . (str_starts_with($admin->password, '$argon2') ? 'Yes' : 'No'));
        $this->info("Is md5 format: " . (strlen($admin->password) === 32 && ctype_xdigit($admin->password) ? 'Yes' : 'No'));
        $this->info("Is sha1 format: " . (strlen($admin->password) === 40 && ctype_xdigit($admin->password) ? 'Yes' : 'No'));
        
        // Test bcrypt functionality
        $this->info("\n=== BCRYPT TEST ===");
        try {
            $testPassword = 'test123';
            $hashed = Hash::make($testPassword);
            $this->info("Test hash created: " . substr($hashed, 0, 20) . "...");
            
            $verifyResult = Hash::check($testPassword, $hashed);
            $this->info("Test verify: " . ($verifyResult ? 'Success' : 'Failed'));
            
            // Test with admin's password
            $this->info("\n=== ADMIN PASSWORD TEST ===");
            $this->info("Attempting to verify admin password...");
            
            // This might throw the error we're seeing
            try {
                $adminVerifyResult = Hash::check('dummy_password', $admin->password);
                $this->info("Admin password verification (dummy): " . ($adminVerifyResult ? 'Success' : 'Failed'));
            } catch (\Exception $e) {
                $this->error("ERROR during admin password verification: " . $e->getMessage());
                $this->error("This is the same error you're seeing on the server!");
            }
            
        } catch (\Exception $e) {
            $this->error("Bcrypt test failed: " . $e->getMessage());
        }
        
        // Check database connection and charset
        $this->info("\n=== DATABASE INFO ===");
        $connection = \DB::connection();
        $this->info("Database driver: " . $connection->getDriverName());
        $this->info("Database charset: " . $connection->getConfig('charset'));
        
        return 0;
    }
}
