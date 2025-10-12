<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('test_sessions', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['applicant_id']);
            
            // Add the correct foreign key constraint to applicants table
            $table->foreign('applicant_id')->references('id')->on('applicants')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_sessions', function (Blueprint $table) {
            // Drop the correct foreign key constraint
            $table->dropForeign(['applicant_id']);
            
            // Restore the original foreign key constraint to users table
            $table->foreign('applicant_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};