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
        Schema::table('test_packages', function (Blueprint $table) {
            $table->json('question_order')->nullable()->after('is_screening_test')->comment('JSON array of question IDs in order');
            $table->boolean('enable_time_per_question')->default(false)->after('question_order')->comment('Enable time per question instead of total time');
            $table->integer('time_per_question_seconds')->nullable()->after('enable_time_per_question')->comment('Time per question in seconds');
            $table->boolean('randomize_questions')->default(false)->after('time_per_question_seconds')->comment('Randomize question order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_packages', function (Blueprint $table) {
            $table->dropColumn([
                'question_order',
                'enable_time_per_question',
                'time_per_question_seconds',
                'randomize_questions'
            ]);
        });
    }
};