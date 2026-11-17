{{--
    File: resources/views/registrations/ticket.blade.php
    Tiket digital dari database. Variabel: $registrasi (dengan workshop dan user).
    Akses dijaga RegistrationPolicy. Tiket hanya tampil jika status lunas.
--}}
@extends('layouts.app')

@php
    // Tiket hanya tampil jika sudah lunas DAN kode tiket sudah dibuat
    $bisaTampil = $registrasi->isLunas() && $registrasi->kode_tiket;
@endphp

@section('title', 'Tiket Digital')

@push('styles')
<style>
    .tiket-page { padding-block: 2.5rem 4rem; }
    .tiket-box { max-width: 760px; margin-inline: auto; }
    .tiket-page h1 { font-size: clamp(1.8rem, 4vw, 2.4rem); color: var(--navy); letter-spacing: -0.02em; }
    .tiket-page .sub { color: var(--muted); margin-top: .4rem; }

    /* Tiket */
    .tiket {
        display: grid; grid-template-columns: 1fr 200px; margin-top: 1.8rem; position: relative;
        background: #fff; border: 1px solid var(--line); border-radius: var(--radius);
        box-shadow: 0 10px 30px rgba(18, 48, 122, .08);
    }
    .tiket-kepala { grid-column: 1 / -1; background: var(--navy); color: #fff; padding: .9rem 1.6rem; border-radius: var(--radius) var(--radius) 0 0; display: flex; justify-content: space-between; align-items: center; }
    .tiket-kepala .logo { color: #fff; font-size: 1.3rem; }
    .tiket-kepala .logo span { color: var(--orange); }
    .tiket-kepala small { color: #c3d0f2; }

    .tiket-isi { padding: 1.6rem; }
    .tiket-isi h2 { font-size: 1.6rem; color: var(--navy); letter-spacing: -0.01em; }
    .tiket-isi .pemateri { color: var(--muted); margin-top: .3rem; }
    .data { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem 1.5rem; margin-top: 1.5rem; }
    .data dt { color: var(--muted); font-size: .88rem; }
    .data dd { font-weight: 600; }

    .tiket-stub {
        border-left: 2px dashed var(--line); padding: 1.6rem 1rem; text-align: center;
        display: flex; flex-direction: column; align-items: center; justify-content: center; gap: .8rem;
    }
    .qr {
        width: 120px; height: 120px;
        background:
            conic-gradient(var(--ink) 25%, transparent 0 50%, var(--ink) 0 75%, transparent 0) 0 0 / 24px 24px,
            #fff;
        border: 5px solid var(--ink); border-radius: 6px;
    }
    .kode-label { font-size: .85rem; color: var(--muted); }
    .kode { display: inline-block; background: var(--mint); color: #17613a; font-weight: 700; padding: .25rem .7rem; border-radius: 6px; letter-spacing: .02em; }

    .tiket-kaki { grid-column: 1 / -1; padding: .9rem 1.6rem; background: var(--mint); border-radius: 0 0 var(--radius) var(--radius); font-size: .92rem; color: #17613a; }

    .aksi { display: flex; gap: .8rem; flex-wrap: wrap; margin-top: 1.5rem; }
    .aksi .btn { flex: 1 1 180px; text-align: center; }

    /* Belum lunas */
    .terkunci { margin-top: 1.8rem; background: #fff3e2; border: 1px solid #f3cf9f; border-radius: var(--radius); padding: 2rem; text-align: center; }
    .terkunci h2 { font-size: 1.4rem; color: var(--navy); }
    .terkunci p { color: var(--muted); margin: .5rem auto 1.4rem; max-width: 46ch; }

    @media (max-width: 640px) {
        .tiket { grid-template-columns: 1fr; }
        .tiket-stub { border-left: 0; border-top: 2px dashed var(--line); }
        .data { grid-template-columns: 1fr; }
    }

    /* Saat dicetak: hanya tiket yang tampil */
    @media print {
        .nav, footer, .aksi, .tiket-page h1, .tiket-page .sub { display: none !important; }
        .tiket { box-shadow: none; margin-top: 0; }
    }
</style>
@endpush

@section('content')
<section class="tiket-page">
    <div class="wrap">
        <div class="tiket-box">
            <h1>Tiket digital</h1>
            <p class="sub">Tunjukkan tiket ini kepada panitia saat hari workshop.</p>

            @if ($bisaTampil)
                <article class="tiket" aria-label="Tiket {{ $registrasi->workshop->judul }}">
                    <div class="tiket-kepala">
                        <span class="logo">Workshop<span>TIX</span></span>
                        <small>Tiket peserta</small>
                    </div>

                    <div class="tiket-isi">
                        <h2>{{ $registrasi->workshop->judul }}</h2>
                        <p class="pemateri">Pemateri: {{ $registrasi->workshop->pemateri }}</p>

                        <dl class="data">
                            <div><dt>Nama peserta</dt><dd>{{ $registrasi->user->name }}</dd></div>
                            <div><dt>Kelas</dt><dd>{{ $registrasi->kelas }}</dd></div>
                            <div><dt>Tanggal</dt><dd>{{ $registrasi->workshop->tanggal_tampil }}</dd></div>
                            <div><dt>Jam</dt><dd>{{ $registrasi->workshop->jam_tampil }}</dd></div>
                            <div><dt>Tempat</dt><dd>{{ $registrasi->workshop->lokasi }}</dd></div>
                        </dl>
                    </div>

                    <div class="tiket-stub">
                        <div class="qr" role="img" aria-label="Kode QR tiket"></div>
                        <div>
                            <div class="kode-label">Kode tiket</div>
                            <span class="kode">{{ $registrasi->kode_tiket }}</span>
                        </div>
                    </div>

                    <div class="tiket-kaki">Pembayaran lunas. Tiket ini berlaku untuk satu peserta.</div>
                </article>

                <div class="aksi">
                    <button type="button" class="btn btn-orange" onclick="window.print()">Cetak tiket</button>
                    <a href="{{ route('registrations.index') }}" class="btn btn-outline">Pendaftaran saya</a>
                </div>
            @else
                <div class="terkunci">
                    <h2>Tiket belum bisa dibuka</h2>
                    <p>Pembayaranmu belum lunas. Tiket muncul setelah pembayaran terverifikasi.</p>
                    <a href="{{ route('registrations.status', $registrasi->id) }}" class="btn btn-orange">Lihat status pembayaran</a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection