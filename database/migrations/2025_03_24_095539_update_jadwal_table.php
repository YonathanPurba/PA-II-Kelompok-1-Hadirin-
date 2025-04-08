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
        // First, add the new column
        Schema::table('jadwal', function (Blueprint $table) {
            $table->foreignId('id_guru_mata_pelajaran')->nullable()->after('id_guru');
        });
        
        // Then, add the foreign key constraint
        Schema::table('jadwal', function (Blueprint $table) {
            $table->foreign('id_guru_mata_pelajaran')
                  ->references('id_guru_mata_pelajaran')
                  ->on('guru_mata_pelajaran')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropForeign(['id_guru_mata_pelajaran']);
            $table->dropColumn('id_guru_mata_pelajaran');
        });
    }
};
