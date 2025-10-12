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
        Schema::table('test_package_question', function (Blueprint $table) {
            $table->integer('time_per_question_seconds')->nullable()->after('order')->comment('Time per question in seconds, null means use package default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_package_question', function (Blueprint $table) {
            $table->dropColumn('time_per_question_seconds');
        });
    }
};