<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
            font-family: "Roboto", sans-serif;
        }

        .sidebar {
            width: 260px;
            background-color: #0770d3;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
            overflow-y: auto;
        }

        .brand-item {
            font-size: 1.25rem;
            font-weight: bold;
            margin-top: 10px;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 0.75rem 1rem;
            transition: 0.2s;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .submenu {
            display: none;
            background-color: #0770d3;
        }

        .submenu a {
            padding-left: 2.5rem;
        }

        .sidebar .bottom {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .main-content {
            flex-grow: 1;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
        }

        .navbar-top {
            height: 80px;
            background-color: white;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 0 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .navbar-top .profile-icon {
            font-size: 2.5rem;
            color: #0770d3;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s;
        }

        .navbar-top .profile-icon:hover {
            color: #1b4a92;
        }

        .navbar-top span {
            font-size: 0.9rem;
            color: #333;
        }

        .content-area {
            padding: 2rem;
            flex-grow: 1;
        }

        .has-submenu > a::after {
            content: '▸';
            float: right;
            transition: transform 0.3s;
        }

        .has-submenu.open > a::after {
            transform: rotate(90deg);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <ul>
                @guest
                    @if (Route::has('login'))
                        <li><a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a></li>
                    @endif
                    @if (Route::has('register'))
                        <li><a href="{{ route('register') }}"><i class="bi bi-person-plus me-2"></i>Register</a></li>
                    @endif
                @else
                    <li class="brand-item">Sidata</li>

                    <li style="margin-top: 1rem;">
                        <a href="{{ route('home') }}">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    </li>

                    @if (auth()->user()->role === 'admin')
                        <li class="has-submenu">
                            <a href="#" onclick="toggleSubmenu(event)">
                                <i class="bi bi-people me-2"></i>Data Siswa
                            </a>
                            <ul class="submenu">
                                <li><a href="{{ route('siswa.index') }}"><i class="bi bi-person-lines-fill me-2"></i>Siswa</a></li>
                                <li><a href="{{ route('akun-siswa.index') }}"><i class="bi bi-key me-2"></i>Akun</a></li>
                                <li><a href="{{ route('dokumen-siswa.index') }}"><i class="bi bi-file-earmark me-2"></i>Dokumen</a></li>
                                <li><a href="{{ route('periodik.index') }}"><i class="bi bi-calendar-check me-2"></i>Periodik</a></li>
                                <li><a href="{{ route('beasiswa.index') }}"><i class="bi bi-cash-stack me-2"></i>Beasiswa</a></li>
                                <li><a href="{{ route('prestasi.index') }}"><i class="bi bi-award me-2"></i>Prestasi</a></li>
                                <li><a href="{{ route('orang-tua.index') }}"><i class="bi bi-people-fill me-2"></i>Orang Tua Siswa</a></li>
                                <li><a href="{{ route('registrasi-siswa.index') }}"><i class="bi bi-journal-check me-2"></i>Registrasi Siswa</a></li>
                                <li><a href="{{ route('kesejahteraan-siswa.index') }}"><i class="bi bi-heart-pulse me-2"></i>Kesejahteraan Siswa</a></li>
                                <li><a href="{{ route('kontak-siswa.index') }}"><i class="bi bi-geo-alt me-2"></i>Kontak & Alamat Siswa</a></li>
                                <li><a href="{{ route('pendaftaran-keluar.index') }}"><i class="bi bi-box-arrow-right me-2"></i>Pendaftaran Keluar</a></li>
                            </ul>
                        </li>

                        <li class="has-submenu">
                            <a href="#" onclick="toggleSubmenu(event)">
                                <i class="bi bi-book me-2"></i>Akademik
                            </a>
                            <ul class="submenu">
                                <li><a href="{{ route('semester.index') }}"><i class="bi bi-building me-2"></i>Semester</a></li>
                                <li><a href="{{ route('jurusan.index') }}"><i class="bi bi-building me-2"></i>Jurusan</a></li>
                                <li><a href="{{ route('rombel.index') }}"><i class="bi bi-people me-2"></i>Rombel</a></li>
                                <li><a href="{{ route('rayon.index') }}"><i class="bi bi-geo me-2"></i>Rayon</a></li>
                            </ul>
                        </li>

                        <li class="has-submenu">
                            <a href="#" onclick="toggleSubmenu(event)">
                                <i class="bi bi-person-badge me-2"></i>Data PTK
                            </a>
                            <ul class="submenu">
                                <li><a href="{{ route('ptk.index') }}"><i class="bi bi-person-fill me-2"></i>Ptk</a></li>
                                <li><a href="{{ route('akun-ptk.index') }}"><i class="bi bi-key me-2"></i>Akun</a></li>
                                <li><a href="{{ route('dokumen-ptk.index') }}"><i class="bi bi-file-earmark me-2"></i>Dokumen</a></li>
                                <li><a href="{{ route('kontak-ptk.index') }}"><i class="bi bi-telephone me-2"></i>Kontak</a></li>
                                <li><a href="{{ route('anak-ptk.index') }}"><i class="bi bi-people-fill me-2"></i>Anak</a></li>
                                <li><a href="{{ route('keluarga-ptk.index') }}"><i class="bi bi-house me-2"></i>Keluarga</a></li>
                                <li><a href="{{ route('tunjangan.index') }}"><i class="bi bi-house me-2"></i>Tunjangan</a></li>
                                <li><a href="{{ route('kesejahteraan-ptk.index') }}"><i class="bi bi-heart me-2"></i>Kesejahteraan</a></li>
                            </ul>
                        </li>

                        <li class="has-submenu">
                            <a href="#" onclick="toggleSubmenu(event)">
                                <i class="bi bi-clock-history me-2"></i>Riwayat & Karir Ptk
                            </a>
                            <ul class="submenu">
                                <li><a href="{{ route('penugasan-ptk.index') }}"><i class="bi bi-pencil-square me-2"></i>Penugasan</a></li>
                                <li><a href="{{ route('kepegawaian-ptk.index') }}"><i class="bi bi-person-badge me-2"></i>Kepegawaian</a></li>
                                <li><a href="{{ route('tugas-tambahan.index') }}"><i class="bi bi-plus-square me-2"></i>Tugas Tambahan</a></li>
                                <li><a href="{{ route('riwayat-gaji.index') }}"><i class="bi bi-briefcase me-2"></i>Riwayat Gaji</a></li>
                                <li><a href="{{ route('riwayat-karir.index') }}"><i class="bi bi-briefcase me-2"></i>Riwayat Karir</a></li>
                                <li><a href="{{ route('riwayat-jabatan.index') }}"><i class="bi bi-diagram-3 me-2"></i>Riwayat Jabatan</a></li>
                                <li><a href="{{ route('riwayat-kepangkatan.index') }}"><i class="bi bi-diagram-3 me-2"></i>Riwayat Kepangkatan</a></li>
                                <li><a href="{{ route('riwayat-jabatan-fungsional.index') }}"><i class="bi bi-diagram-2 me-2"></i>Jabatan Fungsional</a></li>
                            </ul>
                        </li>

                        <li class="has-submenu">
                            <a href="#" onclick="toggleSubmenu(event)">
                                <i class="bi bi-mortarboard me-2"></i>Pendidikan & Kompetensi Ptk
                            </a>
                            <ul class="submenu">
                                <li><a href="{{ route('diklat.index') }}"><i class="bi bi-book me-2"></i>Diklat</a></li>
                                <li><a href="{{ route('nilai-test.index') }}"><i class="bi bi-book me-2"></i>Nilai Test</a></li>
                                <li><a href="{{ route('pendidikan-ptk.index') }}"><i class="bi bi-book me-2"></i>Pendidikan</a></li>
                                <li><a href="{{ route('sertifikat-ptk.index') }}"><i class="bi bi-award me-2"></i>Sertifikat</a></li>
                                <li><a href="{{ route('beasiswa-ptk.index') }}"><i class="bi bi-cash-stack me-2"></i>Beasiswa</a></li>
                                <li><a href="{{ route('penghargaan.index') }}"><i class="bi bi-trophy me-2"></i>Penghargaan</a></li>
                                <li><a href="{{ route('kompetensi-ptk.index') }}"><i class="bi bi-gear me-2"></i>Kompetensi</a></li>
                                <li><a href="{{ route('kompetensi-khusus-ptk.index') }}"><i class="bi bi-star me-2"></i>Kompetensi Khusus</a></li>
                            </ul>
                        </li>
                    @endif

                    @if (auth()->user()->role === 'ptk')
                        <li class="has-submenu">
                            <a href="#" onclick="toggleSubmenu(event)">
                                <i class="bi bi-person-badge me-2"></i>Data PTK
                            </a>
                            <ul class="submenu">
                                <li><a href="{{ route('dokumen-ptk.index') }}"><i class="bi bi-file-earmark me-2"></i>Dokumen PTK</a></li>
                            </ul>
                        </li>
                    @endif

                    @if (auth()->user()->role === 'siswa')
                        <li class="has-submenu">
                            <a href="#" onclick="toggleSubmenu(event)">
                                <i class="bi bi-person-badge me-2"></i>Data Siswa
                            </a>
                            <ul class="submenu">
                                <li><a href="{{ route('dokumen-siswa.index') }}"><i class="bi bi-file-earmark me-2"></i>Dokumen Siswa</a></li>
                            </ul>
                        </li>
                    @endif
                @endguest
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="navbar-top position-relative">
            <a href="#" class="profile-icon" id="profileToggle">
                <i class="bi bi-person-circle"></i>
            </a>

            <div id="profileDropdown"
                class="position-absolute bg-white border rounded shadow"
                style="display:none; min-width:150px; top:80%; right:5; z-index:1050;">
                <a href="{{ route('profile.index') }}" class="d-block px-3 py-2 text-decoration-none text-dark">Profile</a>
                 <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       style="display: block; padding: 0.75rem 1rem; text-decoration: none; transition: 0.2s;">
                        Logout
                    </a>
                </form>
            </div>
        </div>

        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <script>
        function toggleSubmenu(event) {
            event.preventDefault();
            const parent = event.target.closest('.has-submenu');
            parent.classList.toggle('open');
            const submenu = parent.querySelector('.submenu');
            submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
        }

        const profileToggle = document.getElementById('profileToggle');
        const profileDropdown = document.getElementById('profileDropdown');

        profileToggle.addEventListener('click', function(e) {
            e.preventDefault();
            profileDropdown.style.display = profileDropdown.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', function(e) {
            if (!profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.style.display = 'none';
            }
        });
    </script>
</body>
</html>
