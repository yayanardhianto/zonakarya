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
        // Insert footer settings into settings table
        $footerSettings = [
            'footer_title' => 'Have Any Thing in your mind?',
            'footer_description' => 'We are retail company engaged in sports equipment and gear, more commonly known by their store names, Sneakerzone and Jerseyzone',
            'footer_button_text' => 'Contact Us',
        ];

        foreach ($footerSettings as $key => $value) {
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
        // Remove footer settings from settings table
        $footerKeys = ['footer_title', 'footer_description', 'footer_button_text'];
        
        DB::table('settings')->whereIn('key', $footerKeys)->delete();
    }
};
