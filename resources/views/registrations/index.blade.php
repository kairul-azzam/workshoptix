{{--
    File: resources/views/registrations/index.blade.php
    Daftar pendaftaran milik user yang login. Variabel: $pendaftaran (dengan workshop).
--}}
@extends('layouts.app')

@section('title', 'Pendaftaran Saya')

@php
    $label = ['lunas' => 'Lunas', 'pending' => 'Menunggu bayar', 'gagal' => 'Gagal'];
@endphp

@push('styles')
<style>
    .saya { padding-block: 2.5rem 4rem; }
    .saya h1 { font-size: clamp(1.8rem, 4vw, 2.4rem); color: var(--navy); letter-spacing: -0.02em; }
    .saya .sub { color: var(--muted); margin-top: .4rem; }
    .saya-list { display: grid; gap: 1rem; margin-top: 1.8rem; max-width: 760px; }

    .item { display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;
            background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 1.2rem 1.5rem; }
    .item h2 { font-size: 1.2rem; color: var(--navy); }
    .item .info { color: var(--muted); font-size: .95rem; margin-top: .2rem; }
    .item .kanan { display: flex; align-items: center; gap: .8rem; flex-wrap: wrap; }
    .item .btn { padding: .45rem 1rem; font-size: .95rem; }

    .badge { display: inline-block; padding: .15rem .65rem; border-radius: 999px; font-size: .85rem; font-weight: 600; }
    .badge.lunas { background: var(--mint); color: #17613a; }
    .badge.pending { background: #fff3e2; color: var(--orange-dark); }
    .badge.gagal { background: #fde8ea; color: #a3262f; }

    .kosong { margin-top: 1.8rem; background: #fff; border: 1px dashed var(--line); border-radius: var(--radius); padding: 2rem; text-align: center; max-width: 760px; }
    .kosong p { color: var(--muted); margin-bottom: 1rem; }
</style>
@endpush

@section('content')
<section class="saya">
    <div class="wrap">
        <h1>Pendaftaran saya</h1>
        <p class="sub">Workshop yang sudah kamu daftar beserta status pembayarannya.</p>

        @if ($pendaftaran->isEmpty())
            <div class="kosong">
                <p>Kamu belum mendaftar workshop apa pun.</p>
                <a href="/#workshop" class="btn btn-orange">Lihat workshop</a>
            </div>
        @else
            <div class="saya-list">
                @foreach ($pendaftaran as $r)
                    <article class="item">
                        <div>
                            <h2>{{ $r->workshop->judul }}</h2>
                            <p class="info">{{ $r->workshop->tanggal_tampil }}, {{ $r->workshop->jam_tampil }} &middot; {{ $r->workshop->lokasi }}</p>
                        </div>
                        <div class="kanan">
                            <span class="badge {{ $r->status_bayar }}">{{ $label[$r->status_bayar] }}</span>
                            @if ($r->isLunas())
                                <a href="{{ route('registrations.ticket', $r->id) }}" class="btn btn-orange">Buka tiket</a>
                            @else
                                <a href="{{ route('registrations.status', $r->id) }}" class="btn btn-outline">Lihat status</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection