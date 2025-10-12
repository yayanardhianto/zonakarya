<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate remaining applicants data to users
        $applicantsWithEmail = DB::table('applicants')
            ->whereNotNull('email')
            ->whereNull('user_id')
            ->get();

        foreach ($applicantsWithEmail as $applicant) {
            // Check if user already exists
            $existingUser = DB::table('users')
                ->where('email', $applicant->email)
                ->first();

            if ($existingUser) {
                // Update applicant to reference existing user
                DB::table('applicants')
                    ->where('id', $applicant->id)
                    ->update(['user_id' => $existingUser->id]);
            } else {
                // Create new user for this applicant
                $userId = DB::table('users')->insertGetId([
                    'name' => $applicant->name,
                    'email' => $applicant->email,
                    'password' => bcrypt(Str::random(10)),
                    'status' => 'active',
                    'is_banned' => 'no',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update applicant to reference new user
                DB::table('applicants')
                    ->where('id', $applicant->id)
                    ->update(['user_id' => $userId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not easily reversible
        // Data would need to be manually restored
    }
};