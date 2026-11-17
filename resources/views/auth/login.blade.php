{{--
    File: resources/views/auth/login.blade.php
--}}
@extends('layouts.app')

@section('title', 'Masuk')

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
    .field .error { font-size: .9rem; color: #a3262f; margin-top: .3rem; }

    .ingat { display: flex; gap: .5rem; align-items: center; margin-bottom: 1.4rem; color: var(--muted); }
    .ingat input { width: 17px; height: 17px; accent-color: var(--navy); }
    .auth-card .btn { width: 100%; text-align: center; }
    .alih { margin-top: 1.4rem; text-align: center; color: var(--muted); }
    .alih a { color: var(--navy); font-weight: 600; text-decoration: underline; }

    .notif { background: var(--mint); color: #17613a; border-radius: 10px; padding: .7rem 1rem; margin-bottom: 1.2rem; }
</style>
@endpush

@section('content')
<section class="auth-page">
    <div class="wrap">
        <div class="auth-card">
            <h1>Masuk</h1>
            <p class="sub">Masuk untuk mendaftar workshop dan melihat tiketmu.</p>

            @if (session('sukses'))
                <div class="notif">{{ session('sukses') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field @error('email') has-error @enderror">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field @error('password') has-error @enderror">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    @error('password') <p class="error">{{ $message }}</p> @enderror
                </div>

                <label class="ingat">
                    <input type="checkbox" name="ingat" value="1">
                    <span>Ingat saya</span>
                </label>

                <button type="submit" class="btn btn-orange">Masuk</button>
            </form>

            <p class="alih">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
        </div>
    </div>
</section>
@endsection