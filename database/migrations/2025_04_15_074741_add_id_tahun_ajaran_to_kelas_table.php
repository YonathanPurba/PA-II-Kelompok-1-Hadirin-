<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tahun_ajaran')->nullable()->after('id_guru');

            $table->foreign('id_tahun_ajaran')
                ->references('id_tahun_ajaran')
                ->on('tahun_ajaran')
                ->onDelete('set null');
        });
    }


    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['id_tahun_ajaran']);
            $table->dropColumn('id_tahun_ajaran');
        });
    }
};
