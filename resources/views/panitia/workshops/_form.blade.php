{{--
    File: resources/views/panitia/workshops/_form.blade.php
    Form yang dipakai bersama oleh halaman tambah dan ubah workshop.
    Variabel: $action (url), $method ('POST' atau 'PUT'), $workshop (boleh null), $tombol (teks)
--}}
@push('styles')
<style>
    .form-page { padding-block: 2.5rem 4rem; }
    .form-box { max-width: 720px; margin-inline: auto; }
    .form-page h1 { font-size: clamp(1.8rem, 4vw, 2.4rem); color: var(--navy); letter-spacing: -0.02em; }
    .form-page .sub { color: var(--muted); margin-top: .4rem; }
    .back { display: inline-block; color: var(--muted); font-weight: 500; margin-bottom: 1.2rem; }
    .back:hover { color: var(--orange-dark); }

    .form-card { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 1.8rem; margin-top: 1.8rem; }
    .baris { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .field { margin-bottom: 1.2rem; }
    .field label { display: block; font-weight: 600; margin-bottom: .35rem; }
    .field input, .field textarea {
        width: 100%; padding: .7rem .9rem; font: inherit; color: var(--ink);
        border: 1.5px solid var(--line); border-radius: 10px; background: #fff;
    }
    .field textarea { min-height: 130px; resize: vertical; }
    .field input:focus, .field textarea:focus { outline: none; border-color: var(--navy); box-shadow: 0 0 0 3px rgba(18, 48, 122, .15); }
    .field.has-error input, .field.has-error textarea { border-color: #a3262f; }
    .field .hint { font-size: .88rem; color: var(--muted); margin-top: .3rem; }
    .field .error { font-size: .9rem; color: #a3262f; margin-top: .3rem; }

    .aksi-form { display: flex; gap: .8rem; flex-wrap: wrap; }
    .aksi-form .btn { flex: 1 1 160px; text-align: center; }

    @media (max-width: 560px) { .baris { grid-template-columns: 1fr; } }
</style>
@endpush

<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="field @error('judul') has-error @enderror">
        <label for="judul">Judul workshop</label>
        <input type="text" id="judul" name="judul" value="{{ old('judul', $workshop->judul ?? '') }}" required>
        @error('judul') <p class="error">{{ $message }}</p> @enderror
    </div>

    <div class="field @error('pemateri') has-error @enderror">
        <label for="pemateri">Pemateri</label>
        <input type="text" id="pemateri" name="pemateri" value="{{ old('pemateri', $workshop->pemateri ?? '') }}" required>
        @error('pemateri') <p class="error">{{ $message }}</p> @enderror
    </div>

    <div class="field @error('deskripsi') has-error @enderror">
        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" required>{{ old('deskripsi', $workshop->deskripsi ?? '') }}</textarea>
        @error('deskripsi') <p class="error">{{ $message }}</p> @enderror
    </div>

    <div class="baris">
        <div class="field @error('tanggal') has-error @enderror">
            <label for="tanggal">Tanggal</label>
            <input type="date" id="tanggal" name="tanggal"
                   value="{{ old('tanggal', isset($workshop) ? $workshop->tanggal->format('Y-m-d') : '') }}" required>
            @error('tanggal') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field @error('jam') has-error @enderror">
            <label for="jam">Jam mulai</label>
            <input type="time" id="jam" name="jam"
                   value="{{ old('jam', isset($workshop) ? substr($workshop->jam, 0, 5) : '') }}" required>
            @error('jam') <p class="error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="field @error('lokasi') has-error @enderror">
        <label for="lokasi">Tempat</label>
        <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $workshop->lokasi ?? '') }}" required>
        @error('lokasi') <p class="error">{{ $message }}</p> @enderror
    </div>

    <div class="baris">
        <div class="field @error('harga') has-error @enderror">
            <label for="harga">Harga (Rp)</label>
            <input type="number" id="harga" name="harga" min="0" step="1000" value="{{ old('harga', $workshop->harga ?? '') }}" required>
            <p class="hint">Isi 0 jika gratis.</p>
            @error('harga') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field @error('kuota') has-error @enderror">
            <label for="kuota">Kuota kursi</label>
            <input type="number" id="kuota" name="kuota" min="1" value="{{ old('kuota', $workshop->kuota ?? '') }}" required>
            @error('kuota') <p class="error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="aksi-form">
        <button type="submit" class="btn btn-orange">{{ $tombol }}</button>
        <a href="{{ route('panitia.dashboard') }}" class="btn btn-outline">Batal</a>
    </div>
</form>