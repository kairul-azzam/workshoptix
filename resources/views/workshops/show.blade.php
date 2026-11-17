{{--
    File: resources/views/workshops/show.blade.php
    Detail workshop dari database.
    Variabel: $workshop (dengan sisa kursi), $registrasi (pendaftaran milik user yang login, atau null)
--}}
@extends('layouts.app')

@section('title', $workshop->judul)

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

        @include('partials.flash')

        <div class="detail-grid">
            {{-- Kolom kiri: informasi --}}
            <div>
                <h1>{{ $workshop->judul }}</h1>
                <p class="pemateri">Pemateri: {{ $workshop->pemateri }}</p>

                <div class="info-box">
                    <div><small>Tanggal</small><strong>{{ $workshop->tanggal_tampil }}</strong></div>
                    <div><small>Jam</small><strong>{{ $workshop->jam_tampil }}</strong></div>
                    <div><small>Tempat</small><strong>{{ $workshop->lokasi }}</strong></div>
                </div>

                <h2>Tentang workshop ini</h2>
                <p class="isi">{!! nl2br(e($workshop->deskripsi)) !!}</p>
            </div>

            {{-- Kolom kanan: kartu pendaftaran --}}
            <aside class="daftar-card">
                <div class="harga">Rp {{ number_format($workshop->harga, 0, ',', '.') }}</div>

                <div class="kuota">
                    @if ($workshop->sisa === 0)
                        <span class="sisa habis">Kursi habis</span>
                    @else
                        <span class="sisa {{ $workshop->sisa <= 5 ? 'sedikit' : '' }}">Tersisa {{ $workshop->sisa }} kursi</span>
                        dari {{ $workshop->kuota }}
                    @endif
                </div>

                @if ($registrasi)
                    <a href="{{ route('registrations.status', $registrasi->id) }}" class="btn btn-outline">Kamu sudah terdaftar, lihat status</a>
                @elseif ($workshop->sisa === 0)
                    <a class="btn" aria-disabled="true">Pendaftaran penuh</a>
                @else
                    <a href="{{ route('registrations.create', $workshop->id) }}" class="btn btn-orange">Daftar workshop ini</a>
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