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
        Schema::table('jadwal', function (Blueprint $table) {
            $table->enum('semester', ['ganjil', 'genap'])->after('hari');
            $table->foreignId('id_tahun_ajaran')->nullable()->after('semester')
                  ->constrained('tahun_ajaran', 'id_tahun_ajaran')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropForeign(['id_tahun_ajaran']);
            $table->dropColumn(['semester', 'id_tahun_ajaran']);
        });
    }
};

