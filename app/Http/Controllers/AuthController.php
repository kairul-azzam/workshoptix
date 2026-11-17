<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ---------- Register ----------

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak benar.',
            'email.unique'       => 'Email ini sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        // Role TIDAK diambil dari input form. Akun baru selalu peserta.
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'peserta',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/')->with('sukses', 'Akun berhasil dibuat. Selamat datang!');
    }

    // ---------- Login ----------

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $kredensial = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak benar.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (! Auth::attempt($kredensial, $request->boolean('ingat'))) {
            // Pesan sengaja umum, tidak memberi tahu apakah email atau password yang salah
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // Panitia langsung ke dashboard, peserta kembali ke halaman yang tadi dituju
        if (Auth::user()->isPanitia()) {
            return redirect('/panitia');
        }

        return redirect()->intended('/');
    }

    // ---------- Logout ----------

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}