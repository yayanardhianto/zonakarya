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
            $table->integer('applicant_flow_order')->nullable()->after('is_active');
            $table->boolean('is_applicant_flow')->default(false)->after('applicant_flow_order');
            $table->boolean('is_screening_test')->default(false)->after('is_applicant_flow');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_packages', function (Blueprint $table) {
            $table->dropColumn(['applicant_flow_order', 'is_applicant_flow', 'is_screening_test']);
        });
    }
};