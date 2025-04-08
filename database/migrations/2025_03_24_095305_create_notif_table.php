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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('judul');
            $table->text('pesan');
            $table->string('tipe')->default('info'); // info, warning, success, error
            $table->boolean('dibaca')->default(false);
            $table->timestamp('waktu_dibaca')->nullable();
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
        Schema::dropIfExists('notifikasi');
    }
};

