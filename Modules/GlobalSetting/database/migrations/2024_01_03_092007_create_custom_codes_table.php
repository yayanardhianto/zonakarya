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
        Schema::create('custom_codes', function (Blueprint $table) {
            $table->id();
            $table->text('css')->nullable();
            $table->text('header_javascript')->nullable();
            $table->text('body_javascript')->nullable();
            $table->text('footer_javascript')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_codes');
    }
};
