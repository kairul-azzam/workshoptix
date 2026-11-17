{{--
    File: resources/views/panitia/dashboard.blade.php
    Dashboard panitia, sekarang memakai data dari database.
    Variabel dari controller: $workshops (dengan registrations_count) dan $stat.
--}}
@extends('layouts.app')

@section('title', 'Dashboard Panitia')

@push('styles')
<style>
    .dash { padding-block: 2.5rem 4rem; }
    .dash h1 { font-size: clamp(1.8rem, 4vw, 2.4rem); color: var(--navy); letter-spacing: -0.02em; }
    .dash .sub { color: var(--muted); margin-top: .4rem; }

    .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-top: 1.8rem; }
    .stat { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 1.1rem 1.3rem; }
    .stat small { color: var(--muted); display: block; }
    .stat strong { font-family: 'Bricolage Grotesque', sans-serif; font-size: 1.9rem; color: var(--navy); }
    .stat.sorot { background: var(--navy); border-color: var(--navy); }
    .stat.sorot small { color: #c3d0f2; }
    .stat.sorot strong { color: #fff; }

    .notif { border-radius: 10px; padding: .8rem 1.1rem; margin-top: 1.5rem; }
    .notif.sukses { background: var(--mint); color: #17613a; }
    .notif.galat { background: #fde8ea; color: #a3262f; }

    .panel { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); margin-top: 2rem; }
    .panel-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; padding: 1.2rem 1.4rem; border-bottom: 1px solid var(--line); }
    .panel-head h2 { font-size: 1.3rem; color: var(--navy); }
    .panel-head .btn { padding: .45rem 1rem; font-size: .95rem; }

    .tabel-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 640px; }
    th, td { text-align: left; padding: .8rem 1.4rem; border-bottom: 1px solid var(--line); }
    th { font-size: .88rem; color: var(--muted); font-weight: 600; background: #f6f8fc; }
    tr:last-child td { border-bottom: 0; }
    td.angka, th.angka { text-align: right; }
    td.kosong { text-align: center; color: var(--muted); padding: 2rem; }

    .aksi-baris { display: flex; gap: .4rem; }
    .aksi-baris form { display: inline; }
    .tombol-kecil { display: inline-block; padding: .25rem .7rem; border-radius: 8px; font-size: .88rem; font-weight: 600; border: 1.5px solid var(--navy); color: var(--navy); background: #fff; cursor: pointer; font-family: inherit; }
    .tombol-kecil:hover { background: var(--navy); color: #fff; }
    .tombol-kecil.hapus { border-color: #a3262f; color: #a3262f; }
    .tombol-kecil.hapus:hover { background: #a3262f; color: #fff; }

    .penuh { color: #a3262f; font-weight: 600; }

    @media (max-width: 820px) { .stats { grid-template-columns: 1fr 1fr; } }
</style>
@endpush

@section('content')
<section class="dash">
    <div class="wrap">
        <h1>Dashboard panitia</h1>
        <p class="sub">Kelola workshop dan pantau pendaftar.</p>

        @if (session('sukses'))
            <div class="notif sukses">{{ session('sukses') }}</div>
        @endif
        @if (session('galat'))
            <div class="notif galat">{{ session('galat') }}</div>
        @endif

        {{-- Ringkasan --}}
        <div class="stats">
            <div class="stat sorot"><small>Total workshop</small><strong>{{ $stat['workshop'] }}</strong></div>
            <div class="stat"><small>Total pendaftar</small><strong>{{ $stat['pendaftar'] }}</strong></div>
            <div class="stat"><small>Pembayaran lunas</small><strong>{{ $stat['lunas'] }}</strong></div>
            <div class="stat"><small>Menunggu bayar</small><strong>{{ $stat['pending'] }}</strong></div>
        </div>

        {{-- Kelola workshop --}}
        <div class="panel">
            <div class="panel-head">
                <h2>Kelola workshop</h2>
                <a href="{{ route('panitia.workshops.create') }}" class="btn btn-orange">Tambah workshop</a>
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
                        @forelse ($workshops as $w)
                            <tr>
                                <td><strong>{{ $w->judul }}</strong></td>
                                <td>{{ $w->tanggal->translatedFormat('d M Y') }}</td>
                                <td class="angka">Rp {{ number_format($w->harga, 0, ',', '.') }}</td>
                                <td class="angka {{ $w->registrations_count >= $w->kuota ? 'penuh' : '' }}">
                                    {{ $w->registrations_count }} / {{ $w->kuota }}
                                </td>
                                <td>
                                    <div class="aksi-baris">
                                        <a href="{{ route('panitia.workshops.edit', $w) }}" class="tombol-kecil">Ubah</a>
                                        <form method="POST" action="{{ route('panitia.workshops.destroy', $w) }}"
                                              onsubmit="return confirm('Hapus workshop {{ $w->judul }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="tombol-kecil hapus">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="kosong">Belum ada workshop. Klik "Tambah workshop" untuk membuat yang pertama.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection