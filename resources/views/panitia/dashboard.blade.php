{{--
    File: resources/views/panitia/dashboard.blade.php
    Dashboard panitia (WT-11). Data masih dummy.

    Route di routes/web.php:
        Route::view('/panitia', 'panitia.dashboard');

    Catatan: di langkah 03, route ini harus dilindungi middleware
    (login + role panitia), supaya peserta tidak bisa membukanya.
--}}
@extends('layouts.app')

@php
    $workshops = [
        ['id' => 1, 'judul' => 'Dasar Laravel untuk Pemula', 'tanggal' => '22 Nov 2026', 'harga' => 25000, 'kuota' => 30, 'terdaftar' => 18],
        ['id' => 2, 'judul' => 'Desain UI dengan Figma',     'tanggal' => '23 Nov 2026', 'harga' => 20000, 'kuota' => 25, 'terdaftar' => 20],
        ['id' => 3, 'judul' => 'Git dan GitHub untuk Tim',   'tanggal' => '29 Nov 2026', 'harga' => 15000, 'kuota' => 30, 'terdaftar' => 10],
        ['id' => 4, 'judul' => 'Pengenalan API dan Postman', 'tanggal' => '30 Nov 2026', 'harga' => 30000, 'kuota' => 40, 'terdaftar' => 40],
    ];

    $pendaftar = [
        ['nama' => 'Andi Saputra',   'kelas' => 'XI RPL',  'workshop' => 'Dasar Laravel untuk Pemula', 'status' => 'lunas'],
        ['nama' => 'Bunga Lestari',  'kelas' => 'X RPL',   'workshop' => 'Desain UI dengan Figma',     'status' => 'pending'],
        ['nama' => 'Citra Ayu',      'kelas' => 'XII RPL', 'workshop' => 'Git dan GitHub untuk Tim',   'status' => 'lunas'],
        ['nama' => 'Dewa Pratama',   'kelas' => 'XI RPL',  'workshop' => 'Pengenalan API dan Postman', 'status' => 'gagal'],
        ['nama' => 'Eka Putri',      'kelas' => 'XI RPL',  'workshop' => 'Dasar Laravel untuk Pemula', 'status' => 'lunas'],
    ];

    $totalPendaftar = array_sum(array_column($workshops, 'terdaftar'));
    $lunas = count(array_filter($pendaftar, fn ($p) => $p['status'] === 'lunas'));
    $pending = count(array_filter($pendaftar, fn ($p) => $p['status'] === 'pending'));

    $label = ['lunas' => 'Lunas', 'pending' => 'Pending', 'gagal' => 'Gagal'];
@endphp

@section('title', 'Dashboard Panitia')

@push('styles')
<style>
    .dash { padding-block: 2.5rem 4rem; }
    .dash h1 { font-size: clamp(1.8rem, 4vw, 2.4rem); color: var(--navy); letter-spacing: -0.02em; }
    .dash .sub { color: var(--muted); margin-top: .4rem; }

    /* Ringkasan angka */
    .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-top: 1.8rem; }
    .stat { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 1.1rem 1.3rem; }
    .stat small { color: var(--muted); display: block; }
    .stat strong { font-family: 'Bricolage Grotesque', sans-serif; font-size: 1.9rem; color: var(--navy); }
    .stat.sorot { background: var(--navy); border-color: var(--navy); }
    .stat.sorot small { color: #c3d0f2; }
    .stat.sorot strong { color: #fff; }

    /* Tabel */
    .panel { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); margin-top: 2rem; }
    .panel-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; padding: 1.2rem 1.4rem; border-bottom: 1px solid var(--line); }
    .panel-head h2 { font-size: 1.3rem; color: var(--navy); }
    .panel-head .btn { padding: .45rem 1rem; font-size: .95rem; }

    .tabel-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 620px; }
    th, td { text-align: left; padding: .8rem 1.4rem; border-bottom: 1px solid var(--line); }
    th { font-size: .88rem; color: var(--muted); font-weight: 600; background: #f6f8fc; }
    tr:last-child td { border-bottom: 0; }
    td.angka, th.angka { text-align: right; }

    .tombol-kecil { display: inline-block; padding: .25rem .7rem; border-radius: 8px; font-size: .88rem; font-weight: 600; border: 1.5px solid var(--navy); color: var(--navy); background: #fff; cursor: pointer; font-family: inherit; }
    .tombol-kecil:hover { background: var(--navy); color: #fff; }
    .tombol-kecil.hapus { border-color: #a3262f; color: #a3262f; }
    .tombol-kecil.hapus:hover { background: #a3262f; color: #fff; }

    .badge { display: inline-block; padding: .15rem .65rem; border-radius: 999px; font-size: .85rem; font-weight: 600; }
    .badge.lunas { background: var(--mint); color: #17613a; }
    .badge.pending { background: #fff3e2; color: var(--orange-dark); }
    .badge.gagal { background: #fde8ea; color: #a3262f; }

    .penuh { color: #a3262f; font-weight: 600; }

    @media (max-width: 820px) {
        .stats { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')
<section class="dash">
    <div class="wrap">
        <h1>Dashboard panitia</h1>
        <p class="sub">Kelola workshop dan pantau pendaftar.</p>

        {{-- Ringkasan --}}
        <div class="stats">
            <div class="stat sorot"><small>Total workshop</small><strong>{{ count($workshops) }}</strong></div>
            <div class="stat"><small>Total pendaftar</small><strong>{{ $totalPendaftar }}</strong></div>
            <div class="stat"><small>Pembayaran lunas</small><strong>{{ $lunas }}</strong></div>
            <div class="stat"><small>Menunggu bayar</small><strong>{{ $pending }}</strong></div>
        </div>

        {{-- Kelola workshop (CRUD) --}}
        <div class="panel">
            <div class="panel-head">
                <h2>Kelola workshop</h2>
                <a href="#" class="btn btn-orange">Tambah workshop</a>
            </div>
            <div class="tabel-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Workshop</th>
                            <th>Tanggal</th>
                            <th class="angka">Harga</th>
                            <th class="angka">Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workshops as $w)
                            <tr>
                                <td><strong>{{ $w['judul'] }}</strong></td>
                                <td>{{ $w['tanggal'] }}</td>
                                <td class="angka">Rp {{ number_format($w['harga'], 0, ',', '.') }}</td>
                                <td class="angka {{ $w['terdaftar'] >= $w['kuota'] ? 'penuh' : '' }}">
                                    {{ $w['terdaftar'] }} / {{ $w['kuota'] }}
                                </td>
                                <td>
                                    <a href="#" class="tombol-kecil">Ubah</a>
                                    {{-- Nanti jadi form method DELETE dengan @csrf dan @method('DELETE') --}}
                                    <button type="button" class="tombol-kecil hapus">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Daftar pendaftar --}}
        <div class="panel">
            <div class="panel-head">
                <h2>Pendaftar terbaru</h2>
            </div>
            <div class="tabel-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama peserta</th>
                            <th>Kelas</th>
                            <th>Workshop</th>
                            <th>Status bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendaftar as $p)
                            <tr>
                                <td><strong>{{ $p['nama'] }}</strong></td>
                                <td>{{ $p['kelas'] }}</td>
                                <td>{{ $p['workshop'] }}</td>
                                <td><span class="badge {{ $p['status'] }}">{{ $label[$p['status']] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection