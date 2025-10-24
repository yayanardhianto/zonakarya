<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class FixAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:fix-password {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix admin password by rehashing it with bcrypt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        
        $admin = Admin::where('email', $email)->first();
        
        if (!$admin) {
            $this->error("Admin with email {$email} not found!");
            return 1;
        }
        
        $this->info("Found admin: {$admin->email}");
        $this->info("Current password length: " . strlen($admin->password));
        $this->info("Current password starts with: " . substr($admin->password, 0, 10) . "...");
        
        // Check if password is already bcrypt
        if (str_starts_with($admin->password, '$2y$')) {
            $this->warn("Password is already in bcrypt format. Testing if it works...");
            
            if (Hash::check($password, $admin->password)) {
                $this->info("Password verification works correctly!");
                return 0;
            } else {
                $this->warn("Password verification failed. Rehashing...");
            }
        } else {
            $this->warn("Password is not in bcrypt format. Rehashing...");
        }
        
        // Rehash the password
        $hashedPassword = Hash::make($password);
        $admin->password = $hashedPassword;
        $admin->save();
        
        $this->info("Password updated successfully!");
        $this->info("New password length: " . strlen($hashedPassword));
        $this->info("New password starts with: " . substr($hashedPassword, 0, 10) . "...");
        
        // Test the new password
        if (Hash::check($password, $hashedPassword)) {
            $this->info("Password verification test passed!");
        } else {
            $this->error("Password verification test failed!");
            return 1;
        }
        
        return 0;
    }
}
