{{--
    File: resources/views/registrations/status.blade.php
    Halaman status pembayaran (WT-09). Data masih dummy.

    Route di routes/web.php:
        Route::get('/pendaftaran/{id}/status', function ($id) {
            return view('registrations.status', ['id' => $id]);
        });

    Untuk menguji tiga kondisi, tambahkan ?status= di alamat:
        /pendaftaran/1/status?status=pending
        /pendaftaran/1/status?status=lunas
        /pendaftaran/1/status?status=gagal

    Catatan: di langkah 04, status TIDAK boleh diambil dari URL.
    Status harus dari database dan diverifikasi lewat backend ke Midtrans.
--}}
@extends('layouts.app')

@php
    // Data dummy pendaftaran (nanti diganti Registration::with('workshop')->findOrFail($id))
    $p = [
        'order_id' => 'WTX-ORD-' . str_pad($id, 4, '0', STR_PAD_LEFT),
        'peserta'  => 'Nama Peserta',
        'workshop' => 'Dasar Laravel untuk Pemula',
        'jadwal'   => '22 Nov 2026, 09.00 WIB',
        'harga'    => 25000,
    ];

    $status = request()->query('status', 'pending');
    if (! in_array($status, ['pending', 'lunas', 'gagal'])) {
        $status = 'pending';
    }

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

    .uji { margin-top: 1.5rem; font-size: .9rem; color: var(--muted); text-align: center; }
    .uji a { text-decoration: underline; }
</style>
@endpush

@section('content')
<section class="status-page">
    <div class="wrap">
        <div class="status-box">
            <h1>Status pembayaran</h1>
            <p class="sub">Nomor pesanan {{ $p['order_id'] }}</p>

            <div class="status-card">
                <div class="status-top {{ $info['kelas'] }}">
                    <div>
                        <span class="badge">{{ $info['label'] }}</span>
                        <p>{{ $info['teks'] }}</p>
                    </div>
                </div>

                <div class="status-body">
                    <dl>
                        <div class="row"><dt>Peserta</dt><dd>{{ $p['peserta'] }}</dd></div>
                        <div class="row"><dt>Workshop</dt><dd>{{ $p['workshop'] }}</dd></div>
                        <div class="row"><dt>Jadwal</dt><dd>{{ $p['jadwal'] }}</dd></div>
                        <div class="row"><dt>Total bayar</dt><dd>Rp {{ number_format($p['harga'], 0, ',', '.') }}</dd></div>
                    </dl>
                </div>

                <div class="aksi">
                    @if ($status === 'pending')
                        {{-- Tombol bayar akan membuka Midtrans Snap di langkah 04 --}}
                        <a href="#" class="btn btn-orange">Bayar sekarang</a>
                        <a href="/pendaftaran/{{ $id }}/status?status=pending" class="btn btn-outline">Cek status pembayaran</a>
                    @elseif ($status === 'lunas')
                        <a href="/pendaftaran/{{ $id }}/tiket" class="btn btn-orange">Buka tiket</a>
                    @else
                        <a href="#" class="btn btn-orange">Coba bayar lagi</a>
                        <a href="/#workshop" class="btn btn-outline">Pilih workshop lain</a>
                    @endif
                </div>
            </div>

            {{-- Hanya untuk uji tampilan, hapus di langkah 04 --}}
            <p class="uji">
                Uji tampilan:
                <a href="?status=pending">pending</a> |
                <a href="?status=lunas">lunas</a> |
                <a href="?status=gagal">gagal</a>
            </p>
        </div>
    </div>
</section>
@endsection