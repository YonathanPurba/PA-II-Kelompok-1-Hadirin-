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
        Schema::create('guru', function (Blueprint $table) {
            $table->id('id_guru');

            // Tambahkan unique constraint agar 1 user hanya bisa punya 1 akun guru
            $table->foreignId('id_user')
                ->unique()
                ->constrained('users', 'id_user')
                ->onDelete('cascade');

            $table->string('nama_lengkap');
            $table->string('nip')->unique()->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->string('bidang_studi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
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
        Schema::dropIfExists('guru');
    }
};
