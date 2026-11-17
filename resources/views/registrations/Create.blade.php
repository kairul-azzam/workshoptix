{{--
    File: resources/views/registrations/create.blade.php
    Form pendaftaran. Variabel: $workshop. Nama dan email dari akun yang login.
--}}
@extends('layouts.app')

@section('title', 'Daftar ' . $workshop->judul)

@push('styles')
<style>
    .daftar { padding-block: 2.5rem 4rem; }
    .back { display: inline-block; color: var(--muted); font-weight: 500; margin-bottom: 1.2rem; }
    .back:hover { color: var(--orange-dark); }
    .daftar h1 { font-size: clamp(1.8rem, 4vw, 2.4rem); color: var(--navy); letter-spacing: -0.02em; }
    .daftar .sub { color: var(--muted); margin-top: .4rem; }

    .daftar-grid { display: grid; grid-template-columns: 1fr 340px; gap: 2.5rem; align-items: start; margin-top: 2rem; }

    /* Form */
    .form-card { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 1.8rem; }
    .field { margin-bottom: 1.2rem; }
    .field label { display: block; font-weight: 600; margin-bottom: .35rem; }
    .field input, .field select {
        width: 100%; padding: .7rem .9rem; font: inherit; color: var(--ink);
        border: 1.5px solid var(--line); border-radius: 10px; background: #fff;
    }
    .field input:focus, .field select:focus { outline: none; border-color: var(--navy); box-shadow: 0 0 0 3px rgba(18, 48, 122, .15); }
    .field input[readonly] { background: #f1f4fa; color: var(--muted); }
    .field .hint { font-size: .88rem; color: var(--muted); margin-top: .3rem; }
    .field .error { font-size: .9rem; color: #a3262f; margin-top: .3rem; }
    .field.has-error input, .field.has-error select { border-color: #a3262f; }

    .cek { display: flex; gap: .6rem; align-items: start; margin: 1.4rem 0; }
    .cek input { margin-top: .35rem; width: 18px; height: 18px; accent-color: var(--navy); }
    .form-card .btn { width: 100%; text-align: center; }

    /* Ringkasan */
    .ringkasan { background: var(--mint); border-radius: var(--radius); padding: 1.6rem; position: sticky; top: 1.5rem; }
    .ringkasan h2 { font-size: 1.2rem; color: var(--navy); margin-bottom: .8rem; }
    .ringkasan dl { display: grid; gap: .6rem; }
    .ringkasan dt { color: var(--muted); font-size: .9rem; }
    .ringkasan dd { font-weight: 600; }
    .ringkasan .total { border-top: 2px dashed #9fcdb0; margin-top: 1.2rem; padding-top: 1rem; display: flex; justify-content: space-between; align-items: baseline; }
    .ringkasan .total strong { font-family: 'Bricolage Grotesque', sans-serif; font-size: 1.6rem; }
    .ringkasan .catatan { font-size: .88rem; color: var(--muted); margin-top: .8rem; }

    @media (max-width: 820px) {
        .daftar-grid { grid-template-columns: 1fr; }
        .ringkasan { position: static; order: -1; }
    }
</style>
@endpush

@section('content')
<section class="daftar">
    <div class="wrap">
        <a href="{{ route('workshops.show', $workshop->id) }}" class="back">&larr; Kembali ke detail workshop</a>

        <h1>Daftar workshop</h1>
        <p class="sub">Isi data di bawah, lalu lanjut ke pembayaran.</p>

        <div class="daftar-grid">
            {{-- Form --}}
            <div class="form-card">
                <form method="POST" action="{{ route('registrations.store', $workshop->id) }}">
                    @csrf

                    <div class="field">
                        <label for="nama">Nama lengkap</label>
                        <input type="text" id="nama" value="{{ auth()->user()->name }}" readonly>
                        <p class="hint">Nama dan email diambil dari akunmu.</p>
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" id="email" value="{{ auth()->user()->email }}" readonly>
                    </div>

                    <div class="field @error('kelas') has-error @enderror">
                        <label for="kelas">Kelas</label>
                        <select id="kelas" name="kelas" required>
                            <option value="">Pilih kelas</option>
                            @foreach (['X RPL', 'XI RPL', 'XII RPL', 'Lainnya'] as $k)
                                <option value="{{ $k }}" @selected(old('kelas') === $k)>{{ $k }}</option>
                            @endforeach
                        </select>
                        @error('kelas') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div class="field @error('whatsapp') has-error @enderror">
                        <label for="whatsapp">Nomor WhatsApp</label>
                        <input type="tel" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="08xxxxxxxxxx" required>
                        <p class="hint">Dipakai panitia jika ada perubahan jadwal.</p>
                        @error('whatsapp') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="cek">
                            <input type="checkbox" name="setuju" value="1" required @checked(old('setuju'))>
                            <span>Data yang saya isi sudah benar dan saya setuju mengikuti aturan workshop.</span>
                        </label>
                        @error('setuju') <p style="font-size:.9rem;color:#a3262f;margin:-.8rem 0 1rem">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn btn-orange">Daftar dan lanjut bayar</button>
                </form>
            </div>

            {{-- Ringkasan --}}
            <aside class="ringkasan">
                <h2>Ringkasan pendaftaran</h2>
                <dl>
                    <div><dt>Workshop</dt><dd>{{ $workshop->judul }}</dd></div>
                    <div><dt>Jadwal</dt><dd>{{ $workshop->tanggal_tampil }}, {{ $workshop->jam_tampil }}</dd></div>
                    <div><dt>Tempat</dt><dd>{{ $workshop->lokasi }}</dd></div>
                </dl>
                <div class="total">
                    <span>Total bayar</span>
                    <strong>Rp {{ number_format($workshop->harga, 0, ',', '.') }}</strong>
                </div>
                <p class="catatan">Nominal ditentukan oleh server dan tidak bisa diubah dari form.</p>
            </aside>
        </div>
    </div>
</section>
@endsection