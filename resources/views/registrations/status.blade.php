{{--
    File: resources/views/registrations/status.blade.php
    Status pembayaran dari database. Variabel: $registrasi (dengan workshop dan user).
    Akses dijaga RegistrationPolicy di controller.
--}}
@extends('layouts.app')

@php
    $status = $registrasi->status_bayar;

    $info = [
        'pending' => ['label' => 'Menunggu pembayaran', 'kelas' => 'pending', 'teks' => 'Selesaikan pembayaranmu agar tiket bisa dibuka. Setelah membayar, klik "Cek status pembayaran".'],
        'lunas'   => ['label' => 'Lunas',               'kelas' => 'lunas',   'teks' => 'Pembayaranmu sudah terverifikasi. Tiket digitalmu siap dibuka.'],
        'gagal'   => ['label' => 'Pembayaran gagal',    'kelas' => 'gagal',   'teks' => 'Pembayaran tidak berhasil atau sudah kedaluwarsa. Kamu bisa mencoba membayar lagi.'],
    ][$status];
@endphp

@section('title', 'Status Pembayaran')

@push('styles')
<style>
    .status-page { padding-block: 2.5rem 4rem; }
    .status-box { max-width: 640px; margin-inline: auto; }
    .status-page h1 { font-size: clamp(1.8rem, 4vw, 2.4rem); color: var(--navy); letter-spacing: -0.02em; }
    .status-page .sub { color: var(--muted); margin-top: .4rem; }

    .status-card { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); margin-top: 1.8rem; overflow: hidden; }
    .status-top { padding: 1.5rem 1.8rem; display: flex; align-items: center; gap: 1rem; }
    .status-top p { margin-top: .2rem; font-size: .95rem; }
    .badge { display: inline-block; padding: .3rem .8rem; border-radius: 999px; font-weight: 600; font-size: .95rem; }

    .status-top.pending { background: #fff3e2; }
    .status-top.pending .badge { background: var(--orange); color: #fff; }
    .status-top.lunas { background: var(--mint); }
    .status-top.lunas .badge { background: #17613a; color: #fff; }
    .status-top.gagal { background: #fde8ea; }
    .status-top.gagal .badge { background: #a3262f; color: #fff; }

    .status-body { padding: 1.5rem 1.8rem; }
    .status-body dl { display: grid; gap: .9rem; }
    .status-body .row { display: flex; justify-content: space-between; gap: 1rem; border-bottom: 1px solid var(--line); padding-bottom: .8rem; }
    .status-body .row:last-child { border-bottom: 0; padding-bottom: 0; }
    .status-body dt { color: var(--muted); }
    .status-body dd { font-weight: 600; text-align: right; }

    .aksi { padding: 0 1.8rem 1.8rem; display: flex; gap: .8rem; flex-wrap: wrap; }
    .aksi .btn { flex: 1 1 200px; text-align: center; }

</style>
@endpush

@section('content')
<section class="status-page">
    <div class="wrap">
        <div class="status-box">
            <h1>Status pembayaran</h1>
            <p class="sub">Nomor pesanan {{ $registrasi->order_id }}</p>

            @include('partials.flash')

            <div class="status-card">
                <div class="status-top {{ $info['kelas'] }}">
                    <div>
                        <span class="badge">{{ $info['label'] }}</span>
                        <p>{{ $info['teks'] }}</p>
                    </div>
                </div>

                <div class="status-body">
                    <dl>
                        <div class="row"><dt>Peserta</dt><dd>{{ $registrasi->user->name }}</dd></div>
                        <div class="row"><dt>Workshop</dt><dd>{{ $registrasi->workshop->judul }}</dd></div>
                        <div class="row"><dt>Jadwal</dt><dd>{{ $registrasi->workshop->tanggal_tampil }}, {{ $registrasi->workshop->jam_tampil }}</dd></div>
                        <div class="row"><dt>Total bayar</dt><dd>Rp {{ number_format($registrasi->workshop->harga, 0, ',', '.') }}</dd></div>
                    </dl>
                </div>

                <div class="aksi">
                    @if ($status === 'pending')
                        {{-- Tombol bayar dihubungkan ke Midtrans Snap di Langkah 04 --}}
                        <a href="#" class="btn btn-orange">Bayar sekarang</a>
                        <a href="{{ route('registrations.status', $registrasi->id) }}" class="btn btn-outline">Cek status pembayaran</a>
                    @elseif ($status === 'lunas')
                        <a href="{{ route('registrations.ticket', $registrasi->id) }}" class="btn btn-orange">Buka tiket</a>
                    @else
                        <a href="#" class="btn btn-orange">Coba bayar lagi</a>
                        <a href="/#workshop" class="btn btn-outline">Pilih workshop lain</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection