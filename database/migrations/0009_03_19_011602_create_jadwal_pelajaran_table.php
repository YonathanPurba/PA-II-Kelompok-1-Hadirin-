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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id('id_jadwal');

            // Foreign key ke tabel kelas
            $table->foreignId('id_kelas')
                ->constrained('kelas', 'id_kelas')
                ->onDelete('cascade');

            // Foreign key ke tabel mata_pelajaran
            $table->foreignId('id_mata_pelajaran')
                ->constrained('mata_pelajaran', 'id_mata_pelajaran')
                ->onDelete('cascade');

            // Foreign key ke tabel guru
            $table->foreignId('id_guru')
                ->constrained('guru', 'id_guru')
                ->onDelete('cascade');

            // Foreign key ke tabel tahun_ajaran
            $table->foreignId('id_tahun_ajaran')
                ->constrained('tahun_ajaran', 'id_tahun_ajaran')
                ->onDelete('cascade');

            // Hari dan waktu
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']);
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');

            // Status aktif/nonaktif
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            // Informasi audit
            $table->timestamp('dibuat_pada')->nullable();
            $table->string('dibuat_oleh')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
            $table->string('diperbarui_oleh')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
