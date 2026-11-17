{{--
    File: resources/views/layouts/app.blade.php
    Layout utama. Semua halaman memakai kerangka ini.
    Halaman lain cukup mengisi:
      @section('title')   -> judul tab browser
      @section('content') -> isi halaman
      @push('styles')     -> CSS khusus halaman (opsional)
--}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'WorkshopTIX') - WorkshopTIX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy: #12307a;
            --navy-deep: #0c2257;
            --orange: #f7861f;
            --orange-dark: #d96d0a;
            --mint: #dff3e6;
            --paper: #fbfcfe;
            --ink: #1c2540;
            --muted: #5b6684;
            --line: #d5dcec;
            --radius: 14px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Figtree', system-ui, sans-serif;
            color: var(--ink);
            background: var(--paper);
            line-height: 1.6;
        }

        h1,
        h2,
        h3,
        .logo {
            font-family: 'Bricolage Grotesque', 'Figtree', sans-serif;
            line-height: 1.1;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        :focus-visible {
            outline: 3px solid var(--orange);
            outline-offset: 3px;
            border-radius: 6px;
        }

        .wrap {
            width: min(1080px, 100% - 2.5rem);
            margin-inline: auto;
        }

        /* ---------- Navbar ---------- */
        .nav {
            border-bottom: 1px solid var(--line);
            background: #fff;
        }

        .nav .wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 68px;
        }

        .logo {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -0.02em;
        }

        .logo span {
            color: var(--orange);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            font-weight: 500;
        }

        .nav-links a:hover {
            color: var(--orange-dark);
        }

        /* ---------- Tombol ---------- */
        .btn {
            display: inline-block;
            border-radius: 10px;
            padding: .65rem 1.2rem;
            font-weight: 600;
            border: 2px solid transparent;
            cursor: pointer;
            font-family: inherit;
            font-size: 1rem;
            transition: background .15s, color .15s;
        }

        .btn-orange {
            background: var(--orange);
            color: #fff;
        }

        .btn-orange:hover {
            background: var(--orange-dark);
            color: #fff;
        }

        .btn-outline {
            border-color: var(--navy);
            color: var(--navy);
        }

        .btn-outline:hover {
            background: var(--navy);
            color: #fff;
        }

        .btn[aria-disabled="true"] {
            background: #e6e9f2;
            color: #8a93ab;
            cursor: not-allowed;
            border-color: transparent;
        }

        /* ---------- Bagian halaman ---------- */
        .section {
            padding-block: 4rem;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .section-head h2 {
            font-size: 2rem;
            color: var(--navy);
            letter-spacing: -0.01em;
        }

        .section-head p {
            color: var(--muted);
            max-width: 44ch;
        }

        /* ---------- Footer ---------- */
        footer {
            background: var(--navy-deep);
            color: #b9c5e6;
            padding-block: 2rem;
            font-size: .95rem;
        }

        footer .wrap {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .5rem;
        }

        @media (max-width: 640px) {
            .nav-links a:not(.btn) {
                display: none;
            }
        }

        .nav-links form { display: inline; }
    </style>

    {{-- CSS khusus halaman diletakkan di sini --}}
    @stack('styles')
</head>

<body>

    {{-- ===== Navbar ===== --}}
    <header class="nav">
        <div class="wrap">
            <a href="/" class="logo">Workshop<span>TIX</span></a>
            <nav class="nav-links" aria-label="Menu utama">
                <a href="/#workshop">Workshop</a>
                <a href="/#cara-daftar">Cara daftar</a>

                @guest
                <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-orange">Daftar</a>
                @endguest

                @auth
                @if (auth()->user()->isPanitia())
                <a href="/panitia">Dashboard</a>
                @endif
                <span>Halo, {{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline">Keluar</button>
                </form>
                @endauth
            </nav>
        </div>
    </header>

    {{-- ===== Isi halaman ===== --}}
    <main>
        @yield('content')
    </main>

    {{-- ===== Footer ===== --}}
    <footer>
        <div class="wrap">
            <span>WorkshopTIX, pendaftaran dan tiket digital workshop IT</span>
            <span>SMK Pesat, Rekayasa Perangkat Lunak</span>
        </div>
    </footer>

</body>

</html>