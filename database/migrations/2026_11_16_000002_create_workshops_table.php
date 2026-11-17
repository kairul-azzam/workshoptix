<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('pemateri');
            $table->date('tanggal');
            $table->time('jam');
            $table->string('lokasi');
            $table->unsignedInteger('harga');   // dalam rupiah, tanpa desimal
            $table->unsignedInteger('kuota');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
