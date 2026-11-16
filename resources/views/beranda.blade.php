{{--
    File: resources/views/beranda.blade.php
    Tampilan awal WorkshopTIX (data dummy, belum terhubung database).

    Cara pakai, di routes/web.php:
        Route::view('/', 'beranda');

    Nanti saat sudah ada database, ganti array $workshops di bawah
    dengan data dari controller (Workshop::all()) dan kirim lewat view().
--}}
@php
    $workshops = [
        ['id' => 1, 'judul' => 'Dasar Laravel untuk Pemula',  'pemateri' => 'Kak Dimas',  'tanggal' => '22 Nov 2026', 'jam' => '09.00 WIB', 'lokasi' => 'Lab RPL 1',   'harga' => 25000, 'sisa' => 12],
        ['id' => 2, 'judul' => 'Desain UI dengan Figma',      'pemateri' => 'Kak Salsa',  'tanggal' => '23 Nov 2026', 'jam' => '13.00 WIB', 'lokasi' => 'Lab Multimedia', 'harga' => 20000, 'sisa' => 5],
        ['id' => 3, 'judul' => 'Git dan GitHub untuk Tim',    'pemateri' => 'Pak Rangga', 'tanggal' => '29 Nov 2026', 'jam' => '09.00 WIB', 'lokasi' => 'Lab RPL 2',   'harga' => 15000, 'sisa' => 20],
        ['id' => 4, 'judul' => 'Pengenalan API dan Postman',  'pemateri' => 'Kak Nabila', 'tanggal' => '30 Nov 2026', 'jam' => '10.00 WIB', 'lokasi' => 'Aula Sekolah', 'harga' => 30000, 'sisa' => 0],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WorkshopTIX - Pendaftaran dan Tiket Digital Workshop IT</title>

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

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Figtree', system-ui, sans-serif;
            color: var(--ink);
            background: var(--paper);
            line-height: 1.6;
        }

        h1, h2, h3, .logo { font-family: 'Bricolage Grotesque', 'Figtree', sans-serif; line-height: 1.1; }

        a { color: inherit; text-decoration: none; }
        :focus-visible { outline: 3px solid var(--orange); outline-offset: 3px; border-radius: 6px; }

        .wrap { width: min(1080px, 100% - 2.5rem); margin-inline: auto; }

        /* ---------- Navbar ---------- */
        .nav { border-bottom: 1px solid var(--line); background: #fff; }
        .nav .wrap { display: flex; align-items: center; justify-content: space-between; height: 68px; }
        .logo { font-size: 1.6rem; font-weight: 800; color: var(--navy); letter-spacing: -0.02em; }
        .logo span { color: var(--orange); }
        .nav-links { display: flex; align-items: center; gap: 1.5rem; font-weight: 500; }
        .nav-links a:hover { color: var(--orange-dark); }

        .btn {
            display: inline-block; border-radius: 10px; padding: .65rem 1.2rem;
            font-weight: 600; border: 2px solid transparent; cursor: pointer;
            font-family: inherit; font-size: 1rem; transition: background .15s, color .15s;
        }
        .btn-orange { background: var(--orange); color: #fff; }
        .btn-orange:hover { background: var(--orange-dark); }
        .btn-outline { border-color: var(--navy); color: var(--navy); }
        .btn-outline:hover { background: var(--navy); color: #fff; }
        .btn[aria-disabled="true"] { background: #e6e9f2; color: #8a93ab; cursor: not-allowed; border-color: transparent; }

        /* ---------- Hero ---------- */
        .hero { background: var(--navy); color: #fff; overflow: hidden; }
        .hero .wrap {
            display: grid; grid-template-columns: 1.1fr .9fr; gap: 3rem;
            align-items: center; padding-block: 4.5rem;
        }
        .hero h1 { font-size: clamp(2.2rem, 5vw, 3.6rem); font-weight: 800; letter-spacing: -0.02em; }
        .hero p { margin-top: 1.2rem; max-width: 46ch; color: #d3dcf5; font-size: 1.1rem; }
        .hero-actions { margin-top: 2rem; display: flex; gap: .8rem; flex-wrap: wrap; }
        .hero .btn-outline { border-color: #fff; color: #fff; }
        .hero .btn-outline:hover { background: #fff; color: var(--navy); }

        /* Contoh tiket di hero: inilah hasil akhir yang akan didapat peserta */
        .sample-ticket {
            background: #fff; color: var(--ink); border-radius: var(--radius);
            transform: rotate(3deg); box-shadow: 0 24px 50px rgba(0, 0, 0, .35);
            display: grid; grid-template-columns: 1fr 112px; position: relative;
        }
        .sample-ticket .main { padding: 1.4rem 1.4rem 1.4rem 1.6rem; }
        .sample-ticket small { color: var(--muted); display: block; }
        .sample-ticket h3 { font-size: 1.25rem; color: var(--navy); margin: .3rem 0 .9rem; }
        .sample-ticket .kode {
            display: inline-block; background: var(--mint); color: #17613a;
            font-weight: 600; padding: .15rem .6rem; border-radius: 6px; font-size: .9rem;
        }
        .sample-ticket .stub {
            border-left: 2px dashed var(--line); display: grid; place-items: center; padding: 1rem;
        }
        .qr {
            width: 76px; height: 76px;
            background:
                conic-gradient(var(--ink) 25%, transparent 0 50%, var(--ink) 0 75%, transparent 0) 0 0 / 19px 19px,
                #fff;
            border: 4px solid var(--ink); border-radius: 4px;
        }
        .sample-ticket::before, .sample-ticket::after {
            content: ""; position: absolute; right: 100px; width: 20px; height: 20px;
            background: var(--navy); border-radius: 50%;
        }
        .sample-ticket::before { top: -10px; }
        .sample-ticket::after { bottom: -10px; }

        /* ---------- Daftar workshop ---------- */
        .section { padding-block: 4rem; }
        .section-head { display: flex; justify-content: space-between; align-items: end; gap: 1rem; flex-wrap: wrap; margin-bottom: 2rem; }
        .section-head h2 { font-size: 2rem; color: var(--navy); letter-spacing: -0.01em; }
        .section-head p { color: var(--muted); max-width: 44ch; }

        .list { display: grid; gap: 1.2rem; }

        .ticket {
            display: grid; grid-template-columns: 1fr 210px; background: #fff;
            border: 1px solid var(--line); border-radius: var(--radius); position: relative;
        }
        .ticket .info { padding: 1.4rem 1.6rem; }
        .ticket h3 { font-size: 1.35rem; color: var(--navy); }
        .ticket .pemateri { color: var(--muted); margin-top: .2rem; }
        .meta { display: flex; flex-wrap: wrap; gap: .4rem 1.4rem; margin-top: 1rem; font-size: .95rem; }
        .meta dt { color: var(--muted); display: inline; }
        .meta dd { display: inline; font-weight: 600; }

        .ticket .side {
            border-left: 2px dashed var(--line); padding: 1.4rem; text-align: center;
            display: flex; flex-direction: column; justify-content: center; gap: .6rem;
        }
        .harga { font-family: 'Bricolage Grotesque', sans-serif; font-size: 1.6rem; font-weight: 800; color: var(--ink); }
        .sisa { font-size: .88rem; font-weight: 600; color: #17613a; }
        .sisa.sedikit { color: var(--orange-dark); }
        .sisa.habis { color: #a3262f; }

        /* Lekukan tiket di garis putus-putus */
        .ticket::before, .ticket::after {
            content: ""; position: absolute; right: 199px; width: 20px; height: 20px;
            background: var(--paper); border: 1px solid var(--line); border-radius: 50%;
        }
        .ticket::before { top: -11px; clip-path: inset(50% 0 0 0); }
        .ticket::after { bottom: -11px; clip-path: inset(0 0 50% 0); }

        /* ---------- Langkah singkat ---------- */
        .steps { background: var(--mint); }
        .steps ol { list-style: none; display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-top: 1.5rem; counter-reset: s; }
        .steps li { counter-increment: s; }
        .steps li::before {
            content: counter(s); display: grid; place-items: center; width: 34px; height: 34px;
            background: var(--navy); color: #fff; border-radius: 50%; font-weight: 700; margin-bottom: .7rem;
        }
        .steps strong { display: block; color: var(--navy); }
        .steps span { color: var(--muted); font-size: .95rem; }

        /* ---------- Footer ---------- */
        footer { background: var(--navy-deep); color: #b9c5e6; padding-block: 2rem; font-size: .95rem; }
        footer .wrap { display: flex; justify-content: space-between; flex-wrap: wrap; gap: .5rem; }

        /* ---------- Layar kecil ---------- */
        @media (max-width: 820px) {
            .hero .wrap { grid-template-columns: 1fr; padding-block: 3rem; }
            .sample-ticket { transform: none; max-width: 420px; }
            .steps ol { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 640px) {
            .nav-links a:not(.btn) { display: none; }
            .ticket { grid-template-columns: 1fr; }
            .ticket .side { border-left: 0; border-top: 2px dashed var(--line); }
            .ticket::before, .ticket::after { display: none; }
            .steps ol { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    {{-- ===== Navbar ===== --}}
    <header class="nav">
        <div class="wrap">
            <a href="/" class="logo">Workshop<span>TIX</span></a>
            <nav class="nav-links" aria-label="Menu utama">
                <a href="#workshop">Workshop</a>
                <a href="#cara-daftar">Cara daftar</a>
                <a href="#" class="btn btn-outline">Masuk</a>
                <a href="#" class="btn btn-orange">Daftar</a>
            </nav>
        </div>
    </header>

    {{-- ===== Hero ===== --}}
    <section class="hero">
        <div class="wrap">
            <div>
                <h1>Pilih workshop, bayar, tiketmu langsung muncul.</h1>
                <p>
                    Daftar workshop IT yang diadakan panitia sekolah. Setelah pembayaranmu
                    terverifikasi, tiket digital berisi kode dan jadwal bisa kamu buka kapan saja.
                </p>
                <div class="hero-actions">
                    <a href="#workshop" class="btn btn-orange">Lihat workshop</a>
                    <a href="#" class="btn btn-outline">Masuk ke akun</a>
                </div>
            </div>

            <div class="sample-ticket" aria-label="Contoh tiket digital">
                <div class="main">
                    <small>Contoh tiket</small>
                    <h3>Dasar Laravel untuk Pemula</h3>
                    <small>22 Nov 2026, 09.00 WIB</small>
                    <small>Lab RPL 1</small>
                    <span class="kode" style="margin-top:.9rem">WTX-2026-0001</span>
                </div>
                <div class="stub"><div class="qr" role="img" aria-label="Kode QR contoh"></div></div>
            </div>
        </div>
    </section>

    {{-- ===== Daftar workshop ===== --}}
    <section class="section" id="workshop">
        <div class="wrap">
            <div class="section-head">
                <h2>Workshop yang dibuka</h2>
                <p>Pilih satu workshop lalu daftar. Kamu hanya bisa mendaftar satu kali di tiap workshop.</p>
            </div>

            <div class="list">
                @foreach ($workshops as $w)
                    <article class="ticket">
                        <div class="info">
                            <h3>{{ $w['judul'] }}</h3>
                            <p class="pemateri">Pemateri: {{ $w['pemateri'] }}</p>
                            <dl class="meta">
                                <div><dt>Tanggal:</dt> <dd>{{ $w['tanggal'] }}</dd></div>
                                <div><dt>Jam:</dt> <dd>{{ $w['jam'] }}</dd></div>
                                <div><dt>Tempat:</dt> <dd>{{ $w['lokasi'] }}</dd></div>
                            </dl>
                        </div>

                        <div class="side">
                            <div class="harga">Rp {{ number_format($w['harga'], 0, ',', '.') }}</div>

                            @if ($w['sisa'] === 0)
                                <div class="sisa habis">Kursi habis</div>
                                <a class="btn" aria-disabled="true">Penuh</a>
                            @else
                                <div class="sisa {{ $w['sisa'] <= 5 ? 'sedikit' : '' }}">
                                    Tersisa {{ $w['sisa'] }} kursi
                                </div>
                                <a href="#" class="btn btn-orange">Lihat detail</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Cara daftar ===== --}}
    <section class="section steps" id="cara-daftar">
        <div class="wrap">
            <h2 style="font-size:2rem;color:var(--navy)">Cara mendapatkan tiket</h2>
            <ol>
                <li><strong>Masuk atau daftar akun</strong><span>Buat akun peserta dengan emailmu.</span></li>
                <li><strong>Pilih workshop</strong><span>Buka detailnya, lalu klik daftar.</span></li>
                <li><strong>Bayar lewat Midtrans</strong><span>Status pendaftaran awalnya pending.</span></li>
                <li><strong>Buka tiketmu</strong><span>Tiket muncul setelah pembayaran lunas.</span></li>
            </ol>
        </div>
    </section>

    {{-- ===== Footer ===== --}}
    <footer>
        <div class="wrap">
            <span>WorkshopTIX, pendaftaran dan tiket digital workshop IT</span>
            <span>SMK Pesat, Rekayasa Perangkat Lunak</span>
        </div>
    </footer>

</body>
</html>