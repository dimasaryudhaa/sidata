<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiData</title>

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

        .stat-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dashboard
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="dataDropdown">
                        <li><a class="dropdown-item" href="{{ route('dashboard-utama') }}">Dashboard Utama</a></li>
                        <li><a class="dropdown-item" href="{{ route('dashboard-akademik') }}">Dashboard Akademik</a></li>
                    </ul>
                </li>
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
                <div class="stat-cards px-3">
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
                                <h6 class="text-muted font-semibold"> Guru</h6>
                                <h4 class="font-extrabold mb-0">{{ $jumlahGuru }}</h4>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="row mx-3" style="margin-top: 20px;">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><h5 class="m-0">Prestasi Siswa</h5></div>
                        <div class="card-body" style="height: 300px;">
                            <canvas id="chartPrestasi"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><h5 class="m-0">Penghargaan PTK</h5></div>
                        <div class="card-body" style="height: 300px;">
                            <canvas id="chartPenghargaan"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12 col-lg-3">

            <div class="stat-cards px-3 d-flex gap-3" style="margin-left: -40px;">
                <div class="card flex-grow-1" style="min-width: 180px;">
                    <div class="card-body d-flex align-items-center px-4 py-4">
                        <div class="me-3" style="font-size: 2.5rem;">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h6 class="text-muted font-semibold"> Staf</h6>
                            <h4 class="font-extrabold mb-0">{{ $jumlahStaf }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card flex-grow-1" style="min-width: 180px;">
                    <div class="card-body d-flex align-items-center px-4 py-4">
                        <div class="me-3" style="font-size: 2.5rem;">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h6 class="text-muted font-semibold"> Laboran</h6>
                            <h4 class="font-extrabold mb-0">{{ $jumlahLaboran }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="width: 350px; margin-top: 20px; margin-left: -20px;">
                <div class="card-header"><h4>Jumlah User</h4></div>
                <div class="card-body d-flex justify-content-center">
                    <canvas id="chartUserRole" width="250" height="250"></canvas>
                </div>
            </div>

        </div>

        <div class="row mx-3" style="margin-top: 40px; width: 1500px;">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h5 class="m-0">Beasiswa Siswa</h5></div>
                    <div class="card-body" style="height: 350px; margin-left: 10px;">
                        <canvas id="chartBeasiswa"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h5 class="m-0">Beasiswa Ptk</h5></div>
                    <div class="card-body" style="height: 350px; margin-left: 10px;">
                        <canvas id="chartBeasiswaPtk"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 20px; margin-left: 20px; margin-top: 10px;">

            <div class="card mt-5" style="width: 480px;">
                <div class="card-header">
                    <h4>Jumlah Siswa per Rayon</h4>
                </div>

                <div class="card-body" style="max-height: 250px; overflow-y: auto; padding-right: 10px;">
                    <table class="table table-bordered" style="background: white;">
                        <thead class="table-primary" style="position: sticky; top: 0; z-index: 5; background: #cfe2ff;">
                            <tr>
                                <th style="width: 70%">Nama Rayon</th>
                                <th style="width: 30%">Jumlah Siswa</th>
                            </tr>
                        </thead>

                        <tbody style="background: white;">
                            @foreach($namaRayon as $index => $rayon)
                            <tr>
                                <td>{{ $rayon }}</td>
                                <td>{{ $jumlahSiswaRayon[$index] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-5" style="width: 480px;">
                <div class="card-header">
                    <h4>Jumlah Siswa per Rombel</h4>
                </div>

                <div class="card-body" style="max-height: 250px; overflow-y: auto; padding-right: 10px;">
                    <table class="table table-bordered">
                        <thead class="table-primary" style="position: sticky; top: 0; z-index: 5;">
                            <tr>
                                <th style="width: 70%">Nama Rombel</th>
                                <th style="width: 30%">Jumlah Siswa</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($namaRombel as $index => $rombel)
                            <tr>
                                <td>{{ $rombel }}</td>
                                <td>{{ $jumlahSiswaRombel[$index] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-5" style="width: 480px;">
                <div class="card-header">
                    <h4>Jumlah Siswa per Jurusan</h4>
                </div>

                <div class="card-body" style="max-height: 250px; overflow-y: auto; padding-right: 10px;">
                    <table class="table table-bordered">
                        <thead class="table-primary" style="position: sticky; top: 0; z-index: 5;">
                            <tr>
                                <th style="width: 70%">Nama Jurusan</th>
                                <th style="width: 30%">Jumlah Siswa</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($dataJurusan as $index => $jurusan)
                            <tr>
                                <td>{{ $jurusan }}</td>
                                <td>{{ $jumlahSiswaJurusan[$index] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
    const namaBulan = [
        "", "Januari","Februari","Maret","April","Mei",
        "Juni","Juli","Agustus","September","Oktober","November","Desember"
    ];

    const bulanPrestasi = @json(array_keys($statistikPrestasi));
    const totalPrestasi = @json(array_values($statistikPrestasi));

    new Chart(document.getElementById('chartPrestasi'), {
        type: 'bar',
        data: {
            labels: bulanPrestasi.map(b => namaBulan[b]),
            datasets: [{
                label: 'Jumlah Prestasi',
                data: totalPrestasi,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: Math.max(...totalPrestasi) + 50
                }
            }
        }
    });

    const bulanPenghargaan = @json(array_keys($statistikPenghargaan));
    const totalPenghargaan = @json(array_values($statistikPenghargaan));

    new Chart(document.getElementById('chartPenghargaan'), {
        type: 'bar',
        data: {
            labels: bulanPenghargaan.map(b => namaBulan[b]),
            datasets: [{
                label: 'Jumlah Penghargaan',
                data: totalPenghargaan,
                backgroundColor: 'rgba(255, 159, 64, 0.5)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: Math.max(...totalPenghargaan) + 50
                }
            }
        }
    });

    const bulanBeasiswa = @json(array_keys($statistikBeasiswa));
    const totalBeasiswa = @json(array_values($statistikBeasiswa));

    new Chart(document.getElementById('chartBeasiswa'), {
        type: 'bar',
        data: {
            labels: bulanBeasiswa.map(b => namaBulan[b]),
            datasets: [{
                label: 'Jumlah Beasiswa Siswa',
                data: totalBeasiswa,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: Math.max(...totalBeasiswa) + 50
                }
            }
        }
    });

    const bulanBeasiswaPtk = @json(array_keys($statistikBeasiswaPtk));
    const totalBeasiswaPtk = @json(array_values($statistikBeasiswaPtk));

    new Chart(document.getElementById('chartBeasiswaPtk'), {
        type: 'bar',
        data: {
            labels: bulanBeasiswaPtk.map(b => namaBulan[b]),
            datasets: [{
                label: 'Jumlah Beasiswa PTK',
                data: totalBeasiswaPtk,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: Math.max(...totalBeasiswaPtk) + 50
                }
            }
        }
    });

    const admin = @json($jumlahAdmin);
    const siswa = @json($jumlahSiswa);
    const ptk   = @json($jumlahPtk);

    new Chart(document.getElementById('chartUserRole'), {
        type: 'pie',
        data: {
            labels: ['Admin', 'Siswa', 'PTK'],
            datasets: [{
                data: [admin, siswa, ptk],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(75, 192, 75, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // const jurusanLabels = @json($dataJurusan);
    // const jurusanJumlah = @json($jumlahSiswaJurusan);

    // new Chart(document.getElementById('chartJurusan'), {
    //     type: 'doughnut',
    //     data: {
    //         labels: jurusanLabels,
    //         datasets: [{
    //             data: jurusanJumlah,
    //             backgroundColor: [
    //                 'rgba(75, 192, 75, 0.7)',
    //                 'rgba(54, 162, 235, 0.7)',
    //                 'rgba(255, 159, 64, 0.7)',
    //                 'rgba(153, 102, 255, 0.7)',
    //                 'rgba(255, 205, 86, 0.7)',
    //                 'rgba(201, 203, 207, 0.7)',
    //                 'rgba(255, 99, 132, 0.7)',
    //             ],
    //             borderWidth: 1
    //         }]
    //     },
    //     options: {
    //         cutout: '70%',
    //         responsive: true,
    //         maintainAspectRatio: false,
    //         plugins: {
    //             legend: {
    //                 position: 'bottom'
    //             }
    //         }
    //     }
    // });

    // new Chart(document.getElementById('chartRayon'), {
    //     type: 'bar',
    //     data: {
    //         labels: @json($namaRayon),
    //         datasets: [{
    //             label: 'Jumlah Siswa',
    //             data: @json($jumlahSiswaRayon),
    //             backgroundColor: 'rgba(54, 162, 235, 0.6)',
    //             borderColor: 'rgba(54, 162, 235, 1)',
    //             borderWidth: 1
    //         }]
    //     },
    //     options: {
    //         responsive: true,
    //         scales: {
    //             y: {
    //                 beginAtZero: true,
    //                 suggestedMax: Math.max(...totalBeasiswaPtk) + 50
    //             }
    //         }
    //     }
    // });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
