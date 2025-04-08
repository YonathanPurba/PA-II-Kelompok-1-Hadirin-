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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id('id_siswa');
            $table->string('nama');
            $table->string('nis')->unique();
            $table->foreignId('id_orangtua')->constrained('orangtua', 'id_orangtua')->onDelete('cascade');
            $table->foreignId('id_kelas')->constrained('kelas', 'id_kelas')->onDelete('cascade');
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
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
        Schema::dropIfExists('siswa');
    }
};

