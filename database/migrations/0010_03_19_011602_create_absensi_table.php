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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa')->onDelete('cascade');
            $table->foreignId('id_jadwal')->constrained('jadwal', 'id_jadwal')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'alpa', 'sakit', 'izin']);
            $table->text('catatan')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->string('dibuat_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
            $table->string('diperbarui_oleh')->nullable();
            
            // Ensure a student can only have one attendance record per schedule per day
            $table->unique(['id_siswa', 'id_jadwal', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};

