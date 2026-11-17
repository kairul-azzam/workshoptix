{{--
    File: resources/views/workshops/show.blade.php
    Halaman detail workshop (WT-07). Data masih dummy.

    Route di routes/web.php:
        Route::get('/workshops/{id}', function ($id) {
            return view('workshops.show', ['id' => $id]);
        });
--}}
@extends('layouts.app')

@php
    $semua = [
        1 => [
            'judul' => 'Dasar Laravel untuk Pemula', 'pemateri' => 'Kak Dimas',
            'tanggal' => '22 Nov 2026', 'jam' => '09.00 WIB', 'lokasi' => 'Lab RPL 1',
            'harga' => 25000, 'kuota' => 30, 'sisa' => 12,
            'deskripsi' => 'Kenalan dengan Laravel dari nol. Kamu akan membuat proyek pertama, memahami route, controller, dan view Blade, lalu menampilkan data sederhana di halaman web.',
            'materi' => ['Instalasi dan struktur folder Laravel', 'Route, controller, dan view Blade', 'Koneksi database dan migration', 'Menampilkan data dengan Eloquent'],
            'bawa' => ['Laptop dengan PHP, Composer, dan MySQL terpasang', 'Editor kode (VS Code)'],
        ],
        2 => [
            'judul' => 'Desain UI dengan Figma', 'pemateri' => 'Kak Salsa',
            'tanggal' => '23 Nov 2026', 'jam' => '13.00 WIB', 'lokasi' => 'Lab Multimedia',
            'harga' => 20000, 'kuota' => 25, 'sisa' => 5,
            'deskripsi' => 'Belajar membuat rancangan tampilan aplikasi di Figma, mulai dari warna, huruf, sampai prototipe yang bisa diklik.',
            'materi' => ['Dasar frame dan layout', 'Warna dan tipografi', 'Komponen yang bisa dipakai ulang', 'Prototipe interaktif'],
            'bawa' => ['Laptop dan akun Figma gratis'],
        ],
        3 => [
            'judul' => 'Git dan GitHub untuk Tim', 'pemateri' => 'Pak Rangga',
            'tanggal' => '29 Nov 2026', 'jam' => '09.00 WIB', 'lokasi' => 'Lab RPL 2',
            'harga' => 15000, 'kuota' => 30, 'sisa' => 20,
            'deskripsi' => 'Latihan alur kerja tim dengan Git: branch, commit, pull request, dan cara menyelesaikan konflik.',
            'materi' => ['Commit dan branch', 'Push dan pull dari GitHub', 'Pull request dan review', 'Menyelesaikan konflik merge'],
            'bawa' => ['Laptop dengan Git terpasang', 'Akun GitHub'],
        ],
        4 => [
            'judul' => 'Pengenalan API dan Postman', 'pemateri' => 'Kak Nabila',
            'tanggal' => '30 Nov 2026', 'jam' => '10.00 WIB', 'lokasi' => 'Aula Sekolah',
            'harga' => 30000, 'kuota' => 40, 'sisa' => 0,
            'deskripsi' => 'Memahami cara aplikasi saling bertukar data lewat API, lalu mencobanya langsung dengan Postman.',
            'materi' => ['Apa itu API dan JSON', 'Method GET, POST, PUT, DELETE', 'Mencoba endpoint di Postman', 'Membaca kode status HTTP'],
            'bawa' => ['Laptop dengan Postman terpasang'],
        ],
    ];

    $w = $semua[$id] ?? abort(404);
@endphp

@section('title', $w['judul'])

@push('styles')
<style>
    .detail { padding-block: 2.5rem 4rem; }
    .back { display: inline-block; color: var(--muted); font-weight: 500; margin-bottom: 1.2rem; }
    .back:hover { color: var(--orange-dark); }

    .detail-grid { display: grid; grid-template-columns: 1fr 340px; gap: 2.5rem; align-items: start; }

    .detail h1 { font-size: clamp(2rem, 4vw, 2.8rem); color: var(--navy); letter-spacing: -0.02em; }
    .detail .pemateri { color: var(--muted); margin-top: .5rem; font-size: 1.05rem; }

    .info-box {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;
        margin-top: 1.8rem; padding: 1.2rem 1.4rem;
        background: var(--mint); border-radius: var(--radius);
    }
    .info-box small { display: block; color: var(--muted); }
    .info-box strong { color: var(--navy); }

    .detail h2 { font-size: 1.4rem; color: var(--navy); margin: 2.2rem 0 .7rem; }
    .detail p.isi { max-width: 62ch; }
    .detail ul { margin-left: 1.2rem; display: grid; gap: .4rem; }

    /* Kartu daftar di samping */
    .daftar-card {
        background: #fff; border: 1px solid var(--line); border-radius: var(--radius);
        padding: 1.6rem; position: sticky; top: 1.5rem;
    }
    .daftar-card .harga { font-family: 'Bricolage Grotesque', sans-serif; font-size: 2rem; font-weight: 800; }
    .daftar-card .kuota { margin: .6rem 0 1.2rem; color: var(--muted); }
    .daftar-card .btn { display: block; text-align: center; width: 100%; }
    .daftar-card .catatan { margin-top: 1rem; font-size: .9rem; color: var(--muted); }
    .sisa { font-weight: 600; color: #17613a; }
    .sisa.sedikit { color: var(--orange-dark); }
    .sisa.habis { color: #a3262f; }

    @media (max-width: 820px) {
        .detail-grid { grid-template-columns: 1fr; }
        .daftar-card { position: static; }
    }
    @media (max-width: 520px) {
        .info-box { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<section class="detail">
    <div class="wrap">
        <a href="/#workshop" class="back">&larr; Kembali ke daftar workshop</a>

        <div class="detail-grid">
            {{-- Kolom kiri: informasi --}}
            <div>
                <h1>{{ $w['judul'] }}</h1>
                <p class="pemateri">Pemateri: {{ $w['pemateri'] }}</p>

                <div class="info-box">
                    <div><small>Tanggal</small><strong>{{ $w['tanggal'] }}</strong></div>
                    <div><small>Jam</small><strong>{{ $w['jam'] }}</strong></div>
                    <div><small>Tempat</small><strong>{{ $w['lokasi'] }}</strong></div>
                </div>

                <h2>Tentang workshop ini</h2>
                <p class="isi">{{ $w['deskripsi'] }}</p>

                <h2>Materi yang dipelajari</h2>
                <ul>
                    @foreach ($w['materi'] as $m)
                        <li>{{ $m }}</li>
                    @endforeach
                </ul>

                <h2>Yang perlu dibawa</h2>
                <ul>
                    @foreach ($w['bawa'] as $b)
                        <li>{{ $b }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- Kolom kanan: kartu pendaftaran --}}
            <aside class="daftar-card">
                <div class="harga">Rp {{ number_format($w['harga'], 0, ',', '.') }}</div>

                <div class="kuota">
                    @if ($w['sisa'] === 0)
                        <span class="sisa habis">Kursi habis</span>
                    @else
                        <span class="sisa {{ $w['sisa'] <= 5 ? 'sedikit' : '' }}">Tersisa {{ $w['sisa'] }} kursi</span>
                        dari {{ $w['kuota'] }}
                    @endif
                </div>

                @if ($w['sisa'] === 0)
                    <a class="btn" aria-disabled="true">Pendaftaran penuh</a>
                @else
                    <a href="/workshops/{{ $id }}/daftar" class="btn btn-orange">Daftar workshop ini</a>
                @endif

                <p class="catatan">
                    Tiket digital muncul setelah pembayaranmu terverifikasi.
                    Satu akun hanya bisa mendaftar sekali di workshop yang sama.
                </p>
            </aside>
        </div>
    </div>
</section>
@endsection