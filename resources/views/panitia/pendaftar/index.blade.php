{{--
    File: resources/views/panitia/pendaftar/index.blade.php
    Daftar pendaftar untuk panitia (WT-18).
    Variabel: $pendaftar (paginator Registration), $workshops (untuk filter)
--}}
@extends('layouts.app')

@section('title', 'Daftar Pendaftar')

@php
    $label = ['lunas' => 'Lunas', 'pending' => 'Pending', 'gagal' => 'Gagal'];
@endphp

@push('styles')
<style>
    .dash { padding-block: 2.5rem 4rem; }
    .back { display: inline-block; color: var(--muted); font-weight: 500; margin-bottom: 1.2rem; }
    .back:hover { color: var(--orange-dark); }
    .dash h1 { font-size: clamp(1.8rem, 4vw, 2.4rem); color: var(--navy); letter-spacing: -0.02em; }
    .dash .sub { color: var(--muted); margin-top: .4rem; }

    .panel { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); margin-top: 1.8rem; }

    .filter { display: flex; gap: .8rem; flex-wrap: wrap; align-items: end; padding: 1.2rem 1.4rem; border-bottom: 1px solid var(--line); }
    .filter label { display: block; font-size: .88rem; color: var(--muted); margin-bottom: .25rem; }
    .filter select { padding: .55rem .8rem; font: inherit; border: 1.5px solid var(--line); border-radius: 10px; background: #fff; min-width: 190px; }
    .filter .btn { padding: .5rem 1.1rem; font-size: .95rem; }
    .filter .total { margin-left: auto; color: var(--muted); align-self: center; }

    .tabel-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 820px; }
    th, td { text-align: left; padding: .75rem 1.2rem; border-bottom: 1px solid var(--line); }
    th { font-size: .88rem; color: var(--muted); font-weight: 600; background: #f6f8fc; }
    tr:last-child td { border-bottom: 0; }
    td.kosong { text-align: center; color: var(--muted); padding: 2rem; }
    .kecil { color: var(--muted); font-size: .88rem; }

    .badge { display: inline-block; padding: .15rem .65rem; border-radius: 999px; font-size: .85rem; font-weight: 600; }
    .badge.lunas { background: var(--mint); color: #17613a; }
    .badge.pending { background: #fff3e2; color: var(--orange-dark); }
    .badge.gagal { background: #fde8ea; color: #a3262f; }

    .halaman { display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: 1rem 1.4rem; border-top: 1px solid var(--line); flex-wrap: wrap; }
    .halaman span { color: var(--muted); }
    .halaman .btn { padding: .4rem 1rem; font-size: .92rem; }
</style>
@endpush

@section('content')
<section class="dash">
    <div class="wrap">
        <a href="{{ route('panitia.dashboard') }}" class="back">&larr; Kembali ke dashboard</a>
        <h1>Daftar pendaftar</h1>
        <p class="sub">Semua peserta yang mendaftar workshop.</p>

        <div class="panel">
            {{-- Filter --}}
            <form method="GET" action="{{ route('panitia.pendaftar.index') }}" class="filter">
                <div>
                    <label for="workshop">Workshop</label>
                    <select name="workshop" id="workshop">
                        <option value="">Semua workshop</option>
                        @foreach ($workshops as $w)
                            <option value="{{ $w->id }}" @selected((string) request('workshop') === (string) $w->id)>{{ $w->judul }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status">Status bayar</label>
                    <select name="status" id="status">
                        <option value="">Semua status</option>
                        @foreach ($label as $nilai => $teks)
                            <option value="{{ $nilai }}" @selected(request('status') === $nilai)>{{ $teks }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-orange">Terapkan</button>
                @if (request()->hasAny(['workshop', 'status']))
                    <a href="{{ route('panitia.pendaftar.index') }}" class="btn btn-outline">Reset</a>
                @endif
                <span class="total">{{ $pendaftar->total() }} pendaftar</span>
            </form>

            {{-- Tabel --}}
            <div class="tabel-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama peserta</th>
                            <th>Kelas</th>
                            <th>Workshop</th>
                            <th>WhatsApp</th>
                            <th>Status bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendaftar as $r)
                            <tr>
                                <td>{{ $pendaftar->firstItem() + $loop->index }}</td>
                                <td>
                                    <strong>{{ $r->user->name }}</strong><br>
                                    <span class="kecil">{{ $r->user->email }}</span>
                                </td>
                                <td>{{ $r->kelas }}</td>
                                <td>{{ $r->workshop->judul }}</td>
                                <td>{{ $r->whatsapp }}</td>
                                <td>
                                    <span class="badge {{ $r->status_bayar }}">{{ $label[$r->status_bayar] }}</span><br>
                                    <span class="kecil">{{ $r->order_id }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="kosong">Belum ada pendaftar yang cocok dengan penyaringan ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Halaman --}}
            @if ($pendaftar->hasPages())
                <div class="halaman">
                    @if ($pendaftar->previousPageUrl())
                        <a href="{{ $pendaftar->previousPageUrl() }}" class="btn btn-outline">&larr; Sebelumnya</a>
                    @else
                        <span></span>
                    @endif

                    <span>Halaman {{ $pendaftar->currentPage() }} dari {{ $pendaftar->lastPage() }}</span>

                    @if ($pendaftar->nextPageUrl())
                        <a href="{{ $pendaftar->nextPageUrl() }}" class="btn btn-outline">Berikutnya &rarr;</a>
                    @else
                        <span></span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
@endsection