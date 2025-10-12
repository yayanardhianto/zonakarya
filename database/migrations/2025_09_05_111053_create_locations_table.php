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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama branch/lokasi
            $table->string('address')->nullable(); // Alamat lengkap
            $table->string('city'); // Kota
            $table->string('province'); // Provinsi
            $table->string('postal_code')->nullable(); // Kode pos
            $table->string('phone')->nullable(); // Telepon branch
            $table->string('email')->nullable(); // Email branch
            $table->text('description')->nullable(); // Deskripsi branch
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
