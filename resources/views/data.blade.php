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

<div class="page-content" style="margin-top: 100px;">
    <section class="row">

        <div class="col-12 col-lg-9">
            <div class="row px-3">
                <div class="d-flex flex-wrap px-3 gap-3">
                    <div class="card flex-grow-1" style="min-width: 180px;">
                        <div class="card-body d-flex align-items-center px-4 py-4">
                            <div class="me-3" style="font-size: 2.5rem;">
                                <i class="bi bi-people"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold"> Siswa</h6>
                                <h4 class="font-extrabold mb-0">{{ $jumlahSiswa }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="card flex-grow-1" style="min-width: 180px;">
                        <div class="card-body d-flex align-items-center px-4 py-4">
                            <div class="me-3" style="font-size: 2.5rem;">
                                <i class="bi bi-geo"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold"> Rayon</h6>
                                <h4 class="font-extrabold mb-0">{{ $jumlahRayon }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="card flex-grow-1" style="min-width: 180px;">
                        <div class="card-body d-flex align-items-center px-4 py-4">
                            <div class="me-3" style="font-size: 2.5rem;">
                                <i class="bi bi-building"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold"> Jurusan</h6>
                                <h4 class="font-extrabold mb-0">{{ $jumlahJurusan }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="card flex-grow-1" style="min-width: 180px;">
                        <div class="card-body d-flex align-items-center px-4 py-4">
                            <div class="me-3" style="font-size: 2.5rem;">
                                <i class="bi bi-people"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold"> Rombel</h6>
                                <h4 class="font-extrabold mb-0">{{ $jumlahRombel }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="card flex-grow-1" style="min-width: 180px;">
                        <div class="card-body d-flex align-items-center px-4 py-4">
                            <div class="me-3" style="font-size: 2.5rem;">
                                <i class="bi bi-people"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold"> Ptk</h6>
                                <h4 class="font-extrabold mb-0">{{ $jumlahPtk }}</h4>
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
                <div class="card-header"><h4>Role Login</h4></div>
                <div class="card-content pb-4">

                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg d-flex justify-content-center align-items-center rounded-circle"
                            style="width: 50px; height: 50px; font-size: 1.5rem; color: black;">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="ms-4 mt-3"><h5>Admin</h5></div>
                    </div>

                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg d-flex justify-content-center align-items-center rounded-circle"
                            style="width: 50px; height: 50px; font-size: 1.5rem; color: black;">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="ms-4 mt-3"><h5>Ptk</h5></div>
                    </div>

                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg d-flex justify-content-center align-items-center rounded-circle"
                            style="width: 50px; height: 50px; font-size: 1.5rem; color: black;">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="ms-4 mt-3"><h5>Siswa</h5></div>
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
    const bulan = @json(array_keys($statistikPrestasi)); // [1,2,...,12]
    const total = @json(array_values($statistikPrestasi)); // jumlah per bulan

    const namaBulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

    new Chart(document.getElementById('chartPrestasi'), {
        type: 'bar',
        data: {
            labels: bulan.map(b => namaBulan[b]),
            datasets: [{
                label: 'Jumlah Prestasi',
                data: total,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: Math.max(...total) + 10 // otomatis menyesuaikan skala
                }
            }
        }
    });
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
