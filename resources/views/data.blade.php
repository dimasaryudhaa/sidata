<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiData - Dashboard</title>

    <link rel="stylesheet" href="assets/vendors/iconly/bold.css">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html, body {
            overflow-x: hidden;
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
                <li class="nav-item"><a class="nav-link" href="{{ route('data') }}">Data</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('kontak-alamat') }}">Kontak & Alamat</a></li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- SPACER -->
<div style="margin-top: 100px;"></div>

<div class="page-content">
    <section class="row">

        <!-- KOLOM KIRI -->
        <div class="col-12 col-lg-9">

            <div class="row px-3">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="font-size: 2rem;">
                                <i class="bi bi-people"></i>
                            </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Jumlah Siswa</h6>
                                    <h6 class="font-extrabold mb-0">{{ $jumlahSiswa }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="font-size: 2rem;">
                                <i class="bi bi-geo"></i>
                            </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Jumlah Rayon</h6>
                                    <h6 class="font-extrabold mb-0">{{ $jumlahRayon }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="font-size: 2rem;">
                                <i class="bi bi-building"></i>
                            </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Jumlah Jurusan</h6>
                                    <h6 class="font-extrabold mb-0">{{ $jumlahJurusan }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="font-size: 2rem;">
                                <i class="bi bi-people"></i>
                            </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Jumlah Rombel</h6>
                                    <h6 class="font-extrabold mb-0">{{ $jumlahRombel }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4 mx-3">
                <div class="card-header"><h4>Statistik Prestasi</h4></div>
                <div class="card-body">
                    <canvas id="chartPrestasi" height="120"></canvas>
                </div>
            </div>

        </div>

        <div class="col-12 col-lg-3">

            <div class="card" style="width: 350px;">
                <div class="card-header"><h4>Recent Messages</h4></div>
                <div class="card-content pb-4">

                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg"><img src="assets/images/faces/4.jpg"></div>
                        <div class="ms-4"><h5>Hank Schrader</h5><h6 class="text-muted">@johnducky</h6></div>
                    </div>

                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg"><img src="assets/images/faces/5.jpg"></div>
                        <div class="ms-4"><h5>Dean Winchester</h5><h6 class="text-muted">@imdean</h6></div>
                    </div>

                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg"><img src="assets/images/faces/1.jpg"></div>
                        <div class="ms-4"><h5>John Dodol</h5><h6 class="text-muted">@dodoljohn</h6></div>
                    </div>

                    <div class="px-4">
                        <button class="btn btn-block btn-xl btn-light-primary font-bold mt-3">Start Conversation</button>
                    </div>

                </div>
            </div>

        </div>

    </section>
</div>

<footer class="footer text-center">
    <div class="container">
        <p>&copy; 2025 SiData — Sistem Informasi Data SMK Wikrama Bogor</p>
    </div>
</footer>

<script src="assets/vendors/apexcharts/apexcharts.js"></script>
<script src="assets/js/pages/dashboard.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const bulan = @json(array_keys($statistikPrestasi));
    const total = @json(array_values($statistikPrestasi));

    const namaBulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

    new Chart(document.getElementById('chartPrestasi'), {
        type: 'bar',
        data: {
            labels: bulan.map(b => namaBulan[b]),
            datasets: [{
                label: 'Jumlah Prestasi',
                data: total,
                borderWidth: 1
            }]
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
