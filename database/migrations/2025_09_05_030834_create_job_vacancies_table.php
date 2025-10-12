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
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('position'); // Posisi pekerjaan
            $table->string('location'); // Lokasi
            $table->enum('work_type', ['Full-Time', 'Part-Time', 'Contract', 'Freelance', 'Internship']); // Jenis pekerjaan
            $table->string('salary_min')->nullable(); // Gaji minimum
            $table->string('salary_max')->nullable(); // Gaji maksimum
            $table->enum('education', ['SMA', 'D3', 'S1', 'S2', 'S3', 'Tidak Ada Persyaratan']); // Pendidikan
            $table->enum('gender', ['Pria', 'Wanita', 'Semua Jenis']); // Jenis kelamin
            $table->integer('age_min')->nullable(); // Usia minimum
            $table->integer('age_max')->nullable(); // Usia maksimum
            $table->integer('experience_years')->default(0); // Pengalaman dalam tahun
            $table->json('specific_requirements')->nullable(); // Persyaratan khusus (array)
            $table->text('description'); // Deskripsi pekerjaan
            $table->text('responsibilities')->nullable(); // Tanggung jawab
            $table->text('benefits')->nullable(); // Benefit/keuntungan
            $table->string('company_name'); // Nama perusahaan
            $table->string('company_logo')->nullable(); // Logo perusahaan
            $table->string('contact_email'); // Email kontak
            $table->string('contact_phone')->nullable(); // Telepon kontak
            $table->enum('status', ['active', 'inactive', 'closed'])->default('active'); // Status lowongan
            $table->date('application_deadline')->nullable(); // Batas waktu lamaran
            $table->integer('views')->default(0); // Jumlah dilihat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
