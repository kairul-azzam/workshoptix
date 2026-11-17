<?php

namespace Database\Seeders;

use App\Models\Workshop;
use Illuminate\Database\Seeder;

class WorkshopSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'judul' => 'Dasar Laravel untuk Pemula', 'pemateri' => 'Kak Dimas',
                'tanggal' => '2026-11-22', 'jam' => '09:00', 'lokasi' => 'Lab RPL 1',
                'harga' => 25000, 'kuota' => 30,
                'deskripsi' => 'Kenalan dengan Laravel dari nol. Kamu akan membuat proyek pertama, memahami route, controller, dan view Blade, lalu menampilkan data sederhana di halaman web.',
            ],
            [
                'judul' => 'Desain UI dengan Figma', 'pemateri' => 'Kak Salsa',
                'tanggal' => '2026-11-23', 'jam' => '13:00', 'lokasi' => 'Lab Multimedia',
                'harga' => 20000, 'kuota' => 25,
                'deskripsi' => 'Belajar membuat rancangan tampilan aplikasi di Figma, mulai dari warna, huruf, sampai prototipe yang bisa diklik.',
            ],
            [
                'judul' => 'Git dan GitHub untuk Tim', 'pemateri' => 'Pak Rangga',
                'tanggal' => '2026-11-29', 'jam' => '09:00', 'lokasi' => 'Lab RPL 2',
                'harga' => 15000, 'kuota' => 30,
                'deskripsi' => 'Latihan alur kerja tim dengan Git: branch, commit, pull request, dan cara menyelesaikan konflik.',
            ],
            [
                'judul' => 'Pengenalan API dan Postman', 'pemateri' => 'Kak Nabila',
                'tanggal' => '2026-11-30', 'jam' => '10:00', 'lokasi' => 'Aula Sekolah',
                'harga' => 30000, 'kuota' => 40,
                'deskripsi' => 'Memahami cara aplikasi saling bertukar data lewat API, lalu mencobanya langsung dengan Postman.',
            ],
        ];

        foreach ($data as $row) {
            Workshop::create($row);
        }
    }
}
