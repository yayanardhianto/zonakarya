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
            $table->string('level_potential')->nullable()->after('city');
            $table->string('talent_potential')->nullable()->after('level_potential');
            $table->integer('communication')->nullable()->comment('Scale 1-5')->after('potential_position');
            $table->integer('initiative')->nullable()->comment('Scale 1-5')->after('communication');
            $table->integer('leadership')->nullable()->comment('Scale 1-5')->after('initiative');
            $table->text('notes')->nullable()->after('leadership');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            $table->dropColumn([
                'level_potential',
                'talent_potential', 
                'communication',
                'initiative',
                'leadership',
                'notes'
            ]);
        });
    }
};