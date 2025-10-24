<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FixUserBcryptPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:fix-bcrypt {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix user bcrypt password format from $2a$ to $2y$';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }
        
        $this->info("Found user: {$user->email}");
        $this->info("Current password: " . substr($user->password, 0, 15) . "...");
        
        // Check if it's $2a$ format
        if (str_starts_with($user->password, '$2a$')) {
            $this->warn("Password is in old bcrypt format (\$2a\$). Converting to new format (\$2y\$)...");
            
            // Convert $2a$ to $2y$ by replacing the prefix
            $newPassword = str_replace('$2a$', '$2y$', $user->password);
            
            $this->info("Converted password: " . substr($newPassword, 0, 15) . "...");
            
            // Test if the converted password works
            try {
                if (Hash::check($password, $newPassword)) {
                    $this->info("✅ Converted password verification successful!");
                    
                    // Update the password
                    $user->password = $newPassword;
                    $user->save();
                    
                    $this->info("✅ Password updated successfully!");
                    
                    // Final verification
                    $testUser = User::where('email', $email)->first();
                    if (Hash::check($password, $testUser->password)) {
                        $this->info("✅ Final verification successful!");
                        $this->info("User can now login with the same password.");
                    } else {
                        $this->error("❌ Final verification failed!");
                        return 1;
                    }
                    
                } else {
                    $this->error("❌ Password verification failed with converted hash!");
                    $this->info("The password you provided doesn't match the stored password.");
                    $this->info("Please provide the correct password for this user.");
                    return 1;
                }
                
            } catch (\Exception $e) {
                $this->error("Error during verification: " . $e->getMessage());
                return 1;
            }
            
        } else if (str_starts_with($user->password, '$2y$')) {
            $this->info("Password is already in correct format (\$2y\$).");
            
            // Test current password
            try {
                if (Hash::check($password, $user->password)) {
                    $this->info("✅ Password verification successful!");
                    $this->info("No changes needed.");
                } else {
                    $this->error("❌ Password verification failed!");
                    $this->info("The password you provided doesn't match the stored password.");
                    return 1;
                }
            } catch (\Exception $e) {
                $this->error("Error during verification: " . $e->getMessage());
                return 1;
            }
            
        } else {
            $this->error("Password is not in bcrypt format!");
            $this->info("Current format: " . substr($user->password, 0, 10) . "...");
            return 1;
        }
        
        return 0;
    }
}
