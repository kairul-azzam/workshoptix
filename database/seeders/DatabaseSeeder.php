<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun tetap untuk login saat uji (password: password)
        User::factory()->create([
            'name' => 'Panitia WorkshopTIX',
            'email' => 'panitia@workshoptix.test',
            'role' => 'panitia',
        ]);

        User::factory()->create([
            'name' => 'Peserta Uji',
            'email' => 'peserta@workshoptix.test',
            'role' => 'peserta',
        ]);

        // Peserta tambahan agar tabel pendaftar cukup banyak untuk uji N+1
        User::factory(50)->create(['role' => 'peserta']);

        $this->call(WorkshopSeeder::class);

        // Pendaftaran acak: tiap peserta daftar 1 sampai 3 workshop berbeda
        $workshops = Workshop::all();
        $statuses = ['lunas', 'lunas', 'pending', 'gagal'];
        $kelas = ['X RPL', 'XI RPL', 'XII RPL'];
        $urut = 1;

        User::where('role', 'peserta')->get()->each(function ($user) use ($workshops, $statuses, $kelas, &$urut) {
            foreach ($workshops->shuffle()->take(rand(1, 3)) as $workshop) {
                $status = $statuses[array_rand($statuses)];
                $nomor = str_pad($urut, 5, '0', STR_PAD_LEFT);

                Registration::create([
                    'user_id' => $user->id,
                    'workshop_id' => $workshop->id,
                    'kelas' => $kelas[array_rand($kelas)],
                    'whatsapp' => '08' . rand(1000000000, 9999999999),
                    'order_id' => 'WTX-ORD-' . $nomor,
                    'status_bayar' => $status,
                    'kode_tiket' => $status === 'lunas' ? 'WTX-2026-' . $nomor : null,
                ]);

                $urut++;
            }
        });
    }
}
