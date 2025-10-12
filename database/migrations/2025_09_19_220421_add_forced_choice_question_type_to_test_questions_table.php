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
        Schema::table('test_questions', function (Blueprint $table) {
            // Add forced_choice to the existing question_type enum
            DB::statement("ALTER TABLE test_questions MODIFY COLUMN question_type ENUM('multiple_choice', 'essay', 'scale', 'video_record', 'forced_choice') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_questions', function (Blueprint $table) {
            // Remove forced_choice from the enum
            DB::statement("ALTER TABLE test_questions MODIFY COLUMN question_type ENUM('multiple_choice', 'essay', 'scale', 'video_record') NOT NULL");
        });
    }
};