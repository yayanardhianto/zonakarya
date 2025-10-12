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
        Schema::table('talents', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('applicant_id')->constrained()->onDelete('cascade');
        });

        // Populate user_id from applicant relationship
        \DB::statement('
            UPDATE talents t 
            JOIN applicants a ON t.applicant_id = a.id 
            SET t.user_id = a.user_id 
            WHERE a.user_id IS NOT NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};