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
        // Update the status enum to include new statuses
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM('pending','sent','check','short_call','group_interview','test_psychology','ojt','final_interview','sent_offering_letter','onboard','rejected','rejected_by_applicant') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM('pending','sent','check','short_call','rejected') NOT NULL DEFAULT 'pending'");
    }
};