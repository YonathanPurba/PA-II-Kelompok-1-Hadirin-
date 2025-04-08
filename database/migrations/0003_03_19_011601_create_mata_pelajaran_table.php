<?php

// Membuat tabel mata pelajaran
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMataPelajaranTable extends Migration
{
    public function up()
    {
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id('id_mata_pelajaran');
            $table->string('nama');
            $table->string('kode')->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mata_pelajaran');
    }
}