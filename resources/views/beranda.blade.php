<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiData</title>
    <link rel="icon" type="" href="images/database.png">

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

        .hero {
            background: #244f9c;
            color: white;
            padding: 200px 0;
        }
        .info-card {
            border-radius: 12px;
            padding: 50px;
            margin-top: -130px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            min-height: 300px;
        }
        .info-card-2 {
            padding: 80px;
            margin-top: 10px;
            background: white;
            box-shadow: 0 20px 25px rgba(0,0,0,0.1);
            min-height: 400px;
            width: 400px;
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

    <div class="hero">
        <div class="container">
            <h1 class="fw-bold">Selamat Datang di SiData</h1>
            <p class="fs-5 mt-3">
                Solusi terbaik untuk mengelola dan mengakses data di SMK Wikrama.
            </p>
        </div>
    </div>

    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="info-card text-center">
                    <div class="mb-3">
                        <i class="bi bi-file-earmark-text fs-1 text-primary"></i>
                    </div>
                    <h4 class="fw-bold">Akses Mudah</h4>
                    <p>Memberikan kesempatan untuk mendapatkan data dengan cepat dan tanpa hambatan</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card text-center">
                    <div class="mb-3">
                        <i class="bi bi-people fs-1 text-primary"></i>
                    </div>
                    <h4 class="fw-bold">Kolaborasi</h4>
                    <p>Memberikan fasilitas kolaborasi yang lancar antara berbagai siswa, guru, dan administrasi</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card text-center">
                    <div class="mb-3">
                        <i class="bi bi-clock-history fs-1 text-primary"></i>
                    </div>
                    <h4 class="fw-bold">Efesien</h4>
                    <p>Menawarkan solusi yang dapat menghemat waktu dan upaya dengan pengelolaan informasi yang praktis</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="text-center mb-5" style="margin-top: 150px;">
            <h2 class="fw-bold">Tentang Kami</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="info-card-2 text-center">
                    <div class="mb-3">
                        <i class="bi bi-journal-check fs-1 text-primary"></i>
                    </div>
                    <h4 class="fw-bold">Pengisian Data</h4>
                    <p>Melalui SiData, proses pengisian data menjadi lebih efisien dan mudah.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card-2 text-center">
                    <div class="mb-3">
                        <i class="bi bi-collection fs-1 text-primary"></i>
                    </div>
                    <h4 class="fw-bold">Kelola Data</h4>
                    <p>Dengan tujuan utama memfasilitasi kelola data yang optimal</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card-2 text-center">
                    <div class="mb-3">
                        <i class="bi bi-lightbulb fs-1 text-primary"></i>
                    </div>
                    <h4 class="fw-bold">Solusi</h4>
                    <p>Menjadi solusi yang menjembatani kebutuhan seluruh komunitas pendidikan di SMK Wikrama Bogor</p>
                </div>
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
