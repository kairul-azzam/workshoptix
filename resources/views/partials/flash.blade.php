{{-- File: resources/views/partials/flash.blade.php
     Pesan singkat setelah suatu aksi (sukses atau galat). --}}
@if (session('sukses'))
    <div style="background:var(--mint);color:#17613a;border-radius:10px;padding:.8rem 1.1rem;margin-bottom:1.2rem">{{ session('sukses') }}</div>
@endif
@if (session('galat'))
    <div style="background:#fde8ea;color:#a3262f;border-radius:10px;padding:.8rem 1.1rem;margin-bottom:1.2rem">{{ session('galat') }}</div>
@endif