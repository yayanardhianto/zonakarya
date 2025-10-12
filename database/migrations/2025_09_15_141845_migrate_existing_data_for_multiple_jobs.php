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
        // Update existing applicants with user_id
        DB::statement('
            UPDATE applicants a 
            JOIN users u ON a.email = u.email 
            SET a.user_id = u.id 
            WHERE a.user_id IS NULL
        ');

        // Update existing applications with user_id
        DB::statement('
            UPDATE applications app 
            JOIN applicants a ON app.applicant_id = a.id 
            SET app.user_id = a.user_id 
            WHERE app.user_id IS NULL
        ');

        // Update existing test_sessions with user_id
        DB::statement('
            UPDATE test_sessions ts 
            JOIN applicants a ON ts.applicant_id = a.id 
            SET ts.user_id = a.user_id 
            WHERE ts.user_id IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data migration
    }
};