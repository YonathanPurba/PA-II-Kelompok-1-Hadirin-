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
        Schema::table('kelas', function (Blueprint $table) {
            $table->foreignId('id_tahun_ajaran')->nullable()->after('id_guru_wali')
                  ->constrained('tahun_ajaran', 'id_tahun_ajaran')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['id_tahun_ajaran']);
            $table->dropColumn('id_tahun_ajaran');
        });
    }
};

