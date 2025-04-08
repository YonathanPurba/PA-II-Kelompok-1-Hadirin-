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
            $table->foreignId('id_kelas')->constrained('kelas', 'id_kelas')->onDelete('cascade');
            $table->foreignId('id_mata_pelajaran')->constrained('mata_pelajaran', 'id_mata_pelajaran')->onDelete('cascade');
            $table->foreignId('id_guru')->constrained('guru', 'id_guru')->onDelete('cascade');
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']);
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
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

