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
        Schema::create('surat_izin', function (Blueprint $table) {
            $table->id('id_surat_izin');
            $table->foreignId('id_siswa')->constrained('siswa', 'id_siswa')->onDelete('cascade');
            $table->foreignId('id_orangtua')->constrained('orangtua', 'id_orangtua')->onDelete('cascade');
            $table->enum('jenis', ['sakit', 'izin']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('alasan');
            $table->string('file_lampiran')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
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
        Schema::dropIfExists('surat_izin');
    }
};

