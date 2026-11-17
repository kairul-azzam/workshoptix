<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workshop_id')->constrained()->cascadeOnDelete();

            // Data dari form pendaftaran
            $table->string('kelas', 20);
            $table->string('whatsapp', 20);

            // Data pembayaran
            $table->string('order_id')->unique();
            $table->enum('status_bayar', ['pending', 'lunas', 'gagal'])->default('pending');
            $table->string('snap_token')->nullable();
            $table->string('kode_tiket')->nullable()->unique(); // diisi saat lunas

            $table->timestamps();

            // Satu akun hanya boleh mendaftar sekali di workshop yang sama
            $table->unique(['user_id', 'workshop_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
