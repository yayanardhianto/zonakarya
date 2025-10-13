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
        Schema::table('contact_sections', function (Blueprint $table) {
            // Page title fields
            $table->string('page_title')->nullable()->after('submit_button_text');
            $table->string('breadcrumb_title')->nullable()->after('page_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_sections', function (Blueprint $table) {
            $table->dropColumn([
                'page_title',
                'breadcrumb_title'
            ]);
        });
    }
};