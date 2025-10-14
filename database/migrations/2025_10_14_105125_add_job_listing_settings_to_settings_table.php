<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert job listing settings into settings table
        $jobListingSettings = [
            'job_listing_title' => 'Rise Together',
            'job_listing_description' => 'Mulai perjalanan Anda dengan perusahaan kami, mari bergabung bersama kami.',
        ];

        foreach ($jobListingSettings as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove job listing settings from settings table
        $jobListingKeys = ['job_listing_title', 'job_listing_description'];
        
        DB::table('settings')->whereIn('key', $jobListingKeys)->delete();
    }
};
