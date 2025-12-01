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
        Schema::table('short_urls', function (Blueprint $table) {
            // Drop the old foreign key if it exists
            try {
                $table->dropForeign(['created_by']);
            } catch (\Exception $e) {
                // Foreign key doesn't exist, continue
            }
            
            // Add foreign key constraint to admins table
            $table->foreign('created_by')
                ->references('id')
                ->on('admins')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('short_urls', function (Blueprint $table) {
            try {
                $table->dropForeign(['created_by']);
            } catch (\Exception $e) {
                // Already dropped or doesn't exist
            }
        });
    }
};
