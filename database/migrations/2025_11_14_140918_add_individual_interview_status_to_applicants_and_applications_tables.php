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
        // Add individual_interview status to applicants table enum
        // Insert it after 'short_call' and before 'group_interview'
        DB::statement("ALTER TABLE applicants MODIFY COLUMN status ENUM('pending','sent','check','short_call','individual_interview','group_interview','test_psychology','ojt','final_interview','sent_offering_letter','onboard','rejected','rejected_by_applicant') NOT NULL DEFAULT 'pending'");
        
        // Add individual_interview status to applications table enum
        // Insert it after 'short_call' and before 'group_interview'
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM('pending','sent','check','short_call','individual_interview','group_interview','test_psychology','ojt','final_interview','sent_offering_letter','onboard','rejected','rejected_by_applicant') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove individual_interview status from applicants table enum
        DB::statement("ALTER TABLE applicants MODIFY COLUMN status ENUM('pending','sent','check','short_call','group_interview','test_psychology','ojt','final_interview','sent_offering_letter','onboard','rejected','rejected_by_applicant') NOT NULL DEFAULT 'pending'");
        
        // Remove individual_interview status from applications table enum
        DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM('pending','sent','check','short_call','group_interview','test_psychology','ojt','final_interview','sent_offering_letter','onboard','rejected','rejected_by_applicant') NOT NULL DEFAULT 'pending'");
    }
};
