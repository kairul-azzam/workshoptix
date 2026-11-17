{{-- File: resources/views/panitia/workshops/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Ubah Workshop')

@section('content')
<section class="form-page">
    <div class="wrap">
        <div class="form-box">
            <a href="{{ route('panitia.dashboard') }}" class="back">&larr; Kembali ke dashboard</a>
            <h1>Ubah workshop</h1>
            <p class="sub">{{ $workshop->judul }}</p>

            <div class="form-card">
                @include('panitia.workshops._form', [
                    'action'   => route('panitia.workshops.update', $workshop),
                    'method'   => 'PUT',
                    'workshop' => $workshop,
                    'tombol'   => 'Simpan perubahan',
                ])
            </div>
        </div>
    </div>
</section>
@endsection