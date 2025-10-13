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
            $table->boolean('fix_first_question')->default(false)->after('randomize_questions')->comment('Fix first question position when randomizing');
            $table->boolean('fix_last_question')->default(false)->after('fix_first_question')->comment('Fix last question position when randomizing');
            $table->foreignId('fixed_first_question_id')->nullable()->after('fix_last_question')->constrained('test_questions')->onDelete('set null')->comment('Question ID to fix as first');
            $table->foreignId('fixed_last_question_id')->nullable()->after('fixed_first_question_id')->constrained('test_questions')->onDelete('set null')->comment('Question ID to fix as last');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_packages', function (Blueprint $table) {
            $table->dropForeign(['fixed_first_question_id']);
            $table->dropForeign(['fixed_last_question_id']);
            $table->dropColumn([
                'fix_first_question',
                'fix_last_question',
                'fixed_first_question_id',
                'fixed_last_question_id'
            ]);
        });
    }
};