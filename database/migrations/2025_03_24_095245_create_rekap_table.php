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
        Schema::create('rekap_absensi', function (Blueprint $table) {
            $table->id('id_rekap');
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa')->onDelete('cascade');
            $table->foreignId('id_kelas')->constrained('kelas', 'id_kelas')->onDelete('cascade');
            $table->string('bulan', 2);
            $table->year('tahun');
            $table->integer('jumlah_hadir')->default(0);
            $table->integer('jumlah_sakit')->default(0);
            $table->integer('jumlah_izin')->default(0);
            $table->integer('jumlah_alpa')->default(0);
            $table->timestamp('dibuat_pada')->nullable();
            $table->string('dibuat_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
            $table->string('diperbarui_oleh')->nullable();
            
            // Ensure a student can only have one recap per class per month per year
            $table->unique(['id_siswa', 'id_kelas', 'bulan', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_absensi');
    }
};

