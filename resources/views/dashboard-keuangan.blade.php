<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiData</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        ::-webkit-scrollbar {
            width: 0px;
            height: 0px;
        }

        html {
            scrollbar-width: none;
        }

        .navbar-custom {
            background-color: #ffffff;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .navbar-custom .nav-link,
        .navbar-custom .navbar-brand {
            color: #1a3e7a !important;
        }

        .footer {
            background: #1a3e7a;
            color: white;
            padding: 30px 0;
            margin-top: 80px;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
        }

        .scroll-body::-webkit-scrollbar {
            display: none;
        }

        .scroll-body {
            scrollbar-width: none;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">SiData</a>
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('beranda') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard-utama') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('kontak-alamat') }}">Kontak & Alamat</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex flex-wrap gap-4" style="margin-top: 80px; margin-left: 20px; ">

        <a href="{{ url('dashboard-akademik') }}" class="text-decoration-none">
            <div class="card shadow-sm"
                style="width: 350px; cursor: pointer; transition: 0.2s;">
                <div class="card-body d-flex flex-column align-items-center py-4">
                    <div class="rounded-circle d-flex justify-content-center align-items-center mb-3"
                        style="width: 70px; height: 70px; background-color: #0d6efd20;">
                        <i class="bi bi-mortarboard-fill" style="font-size: 2rem; color: #0d6efd;"></i>
                    </div>
                    <h5 class="text-dark">Dashboard Akademik</h5>
                </div>
            </div>
        </a>

        <a href="{{ url('dashboard-keuangan') }}" class="text-decoration-none">
            <div class="card shadow-sm"
                style="width: 350px; cursor: pointer; transition: 0.2s;">
                <div class="card-body d-flex flex-column align-items-center py-4">

                    <div class="rounded-circle d-flex justify-content-center align-items-center mb-3"
                        style="width: 70px; height: 70px; background-color: #19875420;">
                        <i class="bi bi-cash-stack" style="font-size: 2rem; color: #198754;"></i>
                    </div>

                    <h5 class="text-dark">Dashboard Keuangan</h5>
                </div>
            </div>
        </a>

        <a href="{{ url('dashboard-kehadiran') }}" class="text-decoration-none">
            <div class="card shadow-sm"
                style="width: 350px; cursor: pointer; transition: 0.2s;">
                <div class="card-body d-flex flex-column align-items-center py-4">

                    <div class="rounded-circle d-flex justify-content-center align-items-center mb-3"
                        style="width: 70px; height: 70px; background-color: #ffc10720;">
                        <i class="bi bi-clipboard-check-fill" style="font-size: 2rem; color: #ffc107;"></i>
                    </div>

                    <h5 class="text-dark">Dashboard Kehadiran</h5>
                </div>
            </div>
        </a>

        <a href="{{ url('dashboard-sarpras') }}" class="text-decoration-none">
            <div class="card shadow-sm"
                style="width: 350px; cursor: pointer; transition: 0.2s;">
                <div class="card-body d-flex flex-column align-items-center py-4">

                    <div class="rounded-circle d-flex justify-content-center align-items-center mb-3"
                        style="width: 70px; height: 70px; background-color: #28a74520;">
                        <i class="bi bi-tools" style="font-size: 2rem; color: #28a745;"></i>
                    </div>

                    <h5 class="text-dark">Dashboard Sarpras</h5>
                </div>
            </div>
        </a>

    </div>

    <div class="d-flex flex-wrap gap-4" style="margin-left: 20px; margin-top: 30px;">
        <div class="card" style="width: 450px; margin-left: 20px; margin-top: 30px;">
            <div class="card-header">
                <h4>Realisasi Anggaran</h4>
            </div>

            <div class="card-body">
                <table class="table table-bordered" style="margin: 0; width: 100%;">
                    <thead class="table-primary"
                        style="display: block; position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th style="width: 300px;">Kegiatan / Program</th>
                            <th style="width: 180px;">Anggaran (Rp)</th>
                            <th style="width: 180px;">Realisasi (Rp)</th>
                            <th style="width: 150px;">Persentase</th>
                        </tr>
                    </thead>

                    <tbody class="scroll-body"
                        style="display: block; max-height: 220px; overflow-y: auto;">

                        <tr>
                            <td style="width: 300px;">Pengadaan ATK</td>
                            <td style="width: 180px;">10.000.000</td>
                            <td style="width: 180px;">8.500.000</td>
                            <td style="width: 150px;">85%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Perawatan Gedung</td>
                            <td style="width: 180px;">25.000.000</td>
                            <td style="width: 180px;">20.000.000</td>
                            <td style="width: 150px;">80%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Pelatihan Guru</td>
                            <td style="width: 180px;">15.000.000</td>
                            <td style="width: 180px;">13.500.000</td>
                            <td style="width: 150px;">90%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Pemeliharaan Komputer</td>
                            <td style="width: 180px;">12.000.000</td>
                            <td style="width: 180px;">9.000.000</td>
                            <td style="width: 150px;">75%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Kegiatan MPLS</td>
                            <td style="width: 180px;">8.000.000</td>
                            <td style="width: 180px;">7.200.000</td>
                            <td style="width: 150px;">90%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Program Kesiswaan</td>
                            <td style="width: 180px;">20.000.000</td>
                            <td style="width: 180px;">18.000.000</td>
                            <td style="width: 150px;">90%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Perawatan Listrik</td>
                            <td style="width: 180px;">5.000.000</td>
                            <td style="width: 180px;">3.500.000</td>
                            <td style="width: 150px;">70%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Buku Perpustakaan</td>
                            <td style="width: 180px;">30.000.000</td>
                            <td style="width: 180px;">25.000.000</td>
                            <td style="width: 150px;">83%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Ekstrakurikuler</td>
                            <td style="width: 180px;">18.000.000</td>
                            <td style="width: 180px;">14.000.000</td>
                            <td style="width: 150px;">78%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Pengembangan Sistem IT</td>
                            <td style="width: 180px;">40.000.000</td>
                            <td style="width: 180px;">35.000.000</td>
                            <td style="width: 150px;">88%</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" style="width: 450px; margin-left: 20px; margin-top: 30px;">
            <div class="card-header">
                <h4>Serapan Dana BOS</h4>
            </div>

            <div class="card-body">
                <table class="table table-bordered" style="margin: 0; width: 100%;">
                    <thead class="table-primary"
                        style="display: block; position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th style="width: 300px;">Kegiatan / Program</th>
                            <th style="width: 180px;">Anggaran (Rp)</th>
                            <th style="width: 180px;">Realisasi (Rp)</th>
                            <th style="width: 150px;">Serapan</th>
                        </tr>
                    </thead>

                    <tbody class="scroll-body"
                        style="display: block; max-height: 220px; overflow-y: auto;">

                        <tr>
                            <td style="width: 300px;">Administrasi Sekolah</td>
                            <td style="width: 180px;">12.000.000</td>
                            <td style="width: 180px;">10.000.000</td>
                            <td style="width: 150px;">83%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Kegiatan Pembelajaran</td>
                            <td style="width: 180px;">20.000.000</td>
                            <td style="width: 180px;">17.000.000</td>
                            <td style="width: 150px;">85%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Pengembangan Perpustakaan</td>
                            <td style="width: 180px;">18.000.000</td>
                            <td style="width: 180px;">14.000.000</td>
                            <td style="width: 150px;">78%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Kegiatan Ekstrakurikuler</td>
                            <td style="width: 180px;">10.000.000</td>
                            <td style="width: 180px;">8.000.000</td>
                            <td style="width: 150px;">80%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Pembelian Alat Penunjang</td>
                            <td style="width: 180px;">25.000.000</td>
                            <td style="width: 180px;">20.000.000</td>
                            <td style="width: 150px;">80%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Pengelolaan Sarpras</td>
                            <td style="width: 180px;">15.000.000</td>
                            <td style="width: 180px;">12.500.000</td>
                            <td style="width: 150px;">83%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Kegiatan Evaluasi & Assessment</td>
                            <td style="width: 180px;">8.000.000</td>
                            <td style="width: 180px;">7.000.000</td>
                            <td style="width: 150px;">87%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Pelatihan Guru</td>
                            <td style="width: 180px;">14.000.000</td>
                            <td style="width: 180px;">11.000.000</td>
                            <td style="width: 150px;">78%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Pengadaan Media Pembelajaran</td>
                            <td style="width: 180px;">22.000.000</td>
                            <td style="width: 180px;">18.000.000</td>
                            <td style="width: 150px;">82%</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Operasional Sekolah</td>
                            <td style="width: 180px;">30.000.000</td>
                            <td style="width: 180px;">25.000.000</td>
                            <td style="width: 150px;">83%</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" style="width: 450px; margin-left: 20px; margin-top: 30px;">
            <div class="card-header">
                <h4>Pengeluaran per Program</h4>
            </div>

            <div class="card-body">
                <table class="table table-bordered" style="margin: 0; width: 100%;">
                    <thead class="table-primary"
                        style="display: block; position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th style="width: 300px;">Kegiatan / Program</th>
                            <th style="width: 200px;">Kategori</th>
                            <th style="width: 200px;">Pengeluaran (Rp)</th>
                        </tr>
                    </thead>

                    <tbody class="scroll-body"
                        style="display: block; max-height: 220px; overflow-y: auto;">

                        <tr>
                            <td style="width: 300px;">Administrasi Sekolah</td>
                            <td style="width: 200px;">Operasional</td>
                            <td style="width: 200px;">7.500.000</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Pengembangan Perpustakaan</td>
                            <td style="width: 200px;">Sarana Prasarana</td>
                            <td style="width: 200px;">12.000.000</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Ekstrakurikuler</td>
                            <td style="width: 200px;">Kegiatan Siswa</td>
                            <td style="width: 200px;">6.000.000</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Pelatihan Guru</td>
                            <td style="width: 200px;">SDM</td>
                            <td style="width: 200px;">9.000.000</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Pembelian Media Pembelajaran</td>
                            <td style="width: 200px;">Pembelajaran</td>
                            <td style="width: 200px;">15.000.000</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Pemeliharaan Komputer</td>
                            <td style="width: 200px;">IT & Teknologi</td>
                            <td style="width: 200px;">8.000.000</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Operasional Kebersihan</td>
                            <td style="width: 200px;">Operasional</td>
                            <td style="width: 200px;">4.500.000</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Pengadaan ATK</td>
                            <td style="width: 200px;">Operasional</td>
                            <td style="width: 200px;">5.000.000</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Sosialisasi & Publikasi Sekolah</td>
                            <td style="width: 200px;">Humas</td>
                            <td style="width: 200px;">3.500.000</td>
                        </tr>

                        <tr>
                            <td style="width: 300px;">Pemeliharaan Gedung</td>
                            <td style="width: 200px;">Sarana Prasarana</td>
                            <td style="width: 200px;">11.000.000</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

<footer class="footer text-center">
    <div class="container">
        <p>&copy; 2025 SiData — Sistem Informasi Data SMK Wikrama Bogor</p>
    </div>
</footer>

</html>
