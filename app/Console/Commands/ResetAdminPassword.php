<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:reset-password {email} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset admin password with proper bcrypt hashing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $force = $this->option('force');
        
        $admin = Admin::where('email', $email)->first();
        
        if (!$admin) {
            $this->error("Admin with email {$email} not found!");
            return 1;
        }
        
        $this->info("Found admin: {$admin->email}");
        $this->info("Current password length: " . strlen($admin->password));
        $this->info("Current password format: " . substr($admin->password, 0, 10) . "...");
        
        // Check if password is already bcrypt
        if (str_starts_with($admin->password, '$2y$') && !$force) {
            $this->warn("Password appears to be in bcrypt format already.");
            $this->warn("If you're still having issues, use --force flag to rehash.");
            return 0;
        }
        
        // Generate new password
        $newPassword = $this->ask('Enter new password for admin');
        if (empty($newPassword)) {
            $this->error("Password cannot be empty!");
            return 1;
        }
        
        // Confirm password
        $confirmPassword = $this->ask('Confirm new password');
        if ($newPassword !== $confirmPassword) {
            $this->error("Passwords do not match!");
            return 1;
        }
        
        // Hash the password with bcrypt
        $this->info("Hashing password with bcrypt...");
        $hashedPassword = Hash::make($newPassword);
        
        // Verify the hash works
        if (!Hash::check($newPassword, $hashedPassword)) {
            $this->error("Password verification failed after hashing!");
            return 1;
        }
        
        // Update admin password
        $admin->password = $hashedPassword;
        $admin->save();
        
        $this->info("Password updated successfully!");
        $this->info("New password length: " . strlen($hashedPassword));
        $this->info("New password format: " . substr($hashedPassword, 0, 15) . "...");
        
        // Final verification
        $this->info("Performing final verification...");
        $testAdmin = Admin::where('email', $email)->first();
        
        if (Hash::check($newPassword, $testAdmin->password)) {
            $this->info("✅ Password verification successful!");
            $this->info("Admin can now login with the new password.");
        } else {
            $this->error("❌ Password verification failed!");
            return 1;
        }
        
        return 0;
    }
}
