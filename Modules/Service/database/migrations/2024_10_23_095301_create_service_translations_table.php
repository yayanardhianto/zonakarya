<?php

use Illuminate\Support\Facades\Schema;
use Modules\Service\app\Models\Service;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Service::class);
            $table->string('lang_code');
            $table->string('title');
            $table->string('short_description')->nullable();
            $table->longText('description');
            $table->text('seo_title')->nullable();
            $table->string('btn_text')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_translations');
    }
};
