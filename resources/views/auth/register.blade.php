{{--
    File: resources/views/auth/register.blade.php
--}}
@extends('layouts.app')

@section('title', 'Buat Akun')

@push('styles')
<style>
    .auth-page { padding-block: 3rem 5rem; }
    .auth-card { max-width: 440px; margin-inline: auto; background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 2rem; }
    .auth-card h1 { font-size: 1.9rem; color: var(--navy); letter-spacing: -0.02em; }
    .auth-card .sub { color: var(--muted); margin: .4rem 0 1.6rem; }

    .field { margin-bottom: 1.2rem; }
    .field label { display: block; font-weight: 600; margin-bottom: .35rem; }
    .field input[type="email"], .field input[type="password"], .field input[type="text"] {
        width: 100%; padding: .7rem .9rem; font: inherit; color: var(--ink);
        border: 1.5px solid var(--line); border-radius: 10px; background: #fff;
    }
    .field input:focus { outline: none; border-color: var(--navy); box-shadow: 0 0 0 3px rgba(18, 48, 122, .15); }
    .field.has-error input { border-color: #a3262f; }
    .field .hint { font-size: .88rem; color: var(--muted); margin-top: .3rem; }
    .field .error { font-size: .9rem; color: #a3262f; margin-top: .3rem; }

    .auth-card .btn { width: 100%; text-align: center; margin-top: .4rem; }
    .alih { margin-top: 1.4rem; text-align: center; color: var(--muted); }
    .alih a { color: var(--navy); font-weight: 600; text-decoration: underline; }
</style>
@endpush

@section('content')
<section class="auth-page">
    <div class="wrap">
        <div class="auth-card">
            <h1>Buat akun</h1>
            <p class="sub">Akun peserta dipakai untuk mendaftar dan membuka tiket.</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="field @error('name') has-error @enderror">
                    <label for="name">Nama lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field @error('email') has-error @enderror">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field @error('password') has-error @enderror">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <p class="hint">Minimal 8 karakter.</p>
                    @error('password') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Ulangi password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-orange">Buat akun</button>
            </form>

            <p class="alih">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
        </div>
    </div>
</section>
@endsection