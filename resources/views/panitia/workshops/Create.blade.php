{{-- File: resources/views/panitia/workshops/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Workshop')

@section('content')
<section class="form-page">
    <div class="wrap">
        <div class="form-box">
            <a href="{{ route('panitia.dashboard') }}" class="back">&larr; Kembali ke dashboard</a>
            <h1>Tambah workshop</h1>
            <p class="sub">Isi data workshop baru yang akan dibuka untuk pendaftaran.</p>

            <div class="form-card">
                @include('panitia.workshops._form', [
                    'action'   => route('panitia.workshops.store'),
                    'method'   => 'POST',
                    'workshop' => null,
                    'tombol'   => 'Simpan workshop',
                ])
            </div>
        </div>
    </div>
</section>
@endsection