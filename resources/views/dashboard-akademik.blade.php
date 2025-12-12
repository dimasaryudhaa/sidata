<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiData</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
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


    <div style="display: flex; gap: 20px; margin-top: 30px; align-items: flex-start;">

        <div style="display: flex; flex-direction: column; gap: 20px; width: 300px; margin-left: 30px;">

            <div class="card">
                <div class="card-header"><h5 class="m-0">Tingkat Prestasi</h5></div>
                <div class="card-body">
                    <canvas id="chartTingkatPrestasi" height="223"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="m-0">Jenis Prestasi</h5></div>
                <div class="card-body">
                    <canvas id="chartJenisPrestasi" height="223"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="m-0">Jenis Beasiswa</h5></div>
                <div class="card-body">
                    <canvas id="chartBeasiswa" height="223"></canvas>
                </div>
            </div>

        </div>

        <div style="display: flex; flex-direction: column; gap: 20px; width: 100%; margin-right: 30px;">

            <div class="card" style="width: 100%;">
                <div class="card-header">
                    <h4>Jumlah Siswa Berprestasi per Rombel</h4>
                </div>
                <div class="card-body">
                    <canvas id="chartPrestasiRombel" height="100"></canvas>
                </div>
            </div>

            <div class="card" style="width: 100%;">
                <div class="card-header">
                    <h4>Jumlah Siswa Berprestasi per Rayon</h4>
                </div>
                <div class="card-body">
                    <canvas id="chartPrestasiRayon" height="100"></canvas>
                </div>
            </div>

        </div>

    </div>

    <div class="d-flex gap-4 mt-4">

        <div class="card" style="width: 700px; margin-left: 30px; ">
            <div class="card-header">
                <h4>Rata-Rata Nilai per Rayon per Bulan</h4>
            </div>

            <div class="card-body">
                <table class="table table-bordered" style="margin: 0; width: 100%;">
                    <thead class="table-primary"
                        style="display: block; position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th style="width: 400px;">Nama Rombel</th>
                            <th style="width: 320px;">Bulan</th>
                            <th style="width: 350px;">Rata-Rata Nilai</th>
                        </tr>
                    </thead>

                    <tbody class="scroll-body"
                        style="display: block; max-height: 250px; overflow-y: auto;">

                        @foreach($dataRataRata as $item)
                        <tr>
                            <td style="width: 400px;">{{ $item->rombel }}</td>
                            <td style="width: 320px;">{{ $item->bulan }}</td>
                            <td style="width: 350px;"></td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" style="width: 700px; margin-left: 30px;">
            <div class="card-header">
                <h4>Ketuntasan Belajar per 9 Minggu</h4>
            </div>

            <div class="card-body">
                <table class="table table-bordered" style="margin: 0; width: 100%;">
                    <thead class="table-primary"
                        style="display: block; position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th style="width: 250px;">Jurusan</th>
                            <th style="width: 80px;">Tingkat</th>
                            <th style="width: 90px;">Minggu</th>
                            <th style="width: 150px;">Nilai</th>
                            <th style="width: 150px;">Ketuntasan</th>
                        </tr>
                    </thead>

                    <tbody class="scroll-body"
                        style="display: block; max-height: 250px; overflow-y: auto;">

                        @foreach($dataGrafik as $item)
                        <tr>
                            <td style="width: 250px;">{{ $item->jurusan }}</td>
                            <td style="width: 80px;">{{ $item->tingkat }}</td>
                            <td style="width: 90px;">M{{ $item->minggu }}</td>
                            <td style="width: 150px;">{{ $item->nilai ?? '-' }}</td>
                            <td style="width: 150px;">{{ $item->ketuntasan ?? '-' }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="d-flex gap-4 mt-4">
        <div class="card" style="width: 700px; margin-left: 30px;">
            <div class="card-header">
                <h5 class="m-0">Nilai Semester per Rombel</h5>
            </div>

            <div class="card-body">
                <table class="table table-bordered" style="margin: 0; width: 100%;">
                    <thead class="table-primary"
                        style="display: block; position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th style="width: 400px;">Rombel</th>

                            @foreach ($chartLabels as $label)
                                <th style="width: 320px;">{{ $label }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="scroll-body"
                        style="display: block; max-height: 250px; overflow-y: auto;">

                        @foreach ($datasets as $ds)
                            <tr>
                                <td style="width: 400px;">{{ $ds['label'] }}</td>

                                @foreach ($ds['data'] as $nilai)
                                    <td style="width: 320px;">{{ $nilai ?? '-' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" style="width: 720px; margin-left: 30px;">
            <div class="card-header">
                <h5 class="m-0">Masalah Siswa per Rombel</h5>
            </div>

            <div class="card-body">
                <table class="table table-bordered" style="margin: 0; width: 100%;">
                    <thead class="table-primary"
                        style="display: block; position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th style="width: 400px;">Rombel</th>
                            <th style="width: 300px;">Jumlah Masalah</th>
                        </tr>
                    </thead>

                    <tbody class="scroll-body"
                        style="display: block; max-height: 250px; overflow-y: auto;">

                        @foreach ($labelsMasalah as $i => $rombel)
                        <tr>
                            <td style="width: 400px;">{{ $rombel }}</td>
                            <td style="width: 300px;">{{ $dataMasalah[$i] ?? 0 }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>

<footer class="footer text-center">
    <div class="container">
        <p>&copy; 2025 SiData — Sistem Informasi Data SMK Wikrama Bogor</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctxRombel = document.getElementById('chartPrestasiRombel');

    new Chart(ctxRombel, {
        type: 'bar',
        data: {
            labels: @json($rombelLabels),
            datasets: [{
                label: 'Jumlah Prestasi',
                data: @json($rombelPrestasi),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    const rayonLabels = @json($prestasiPerRayon->pluck('nama_rayon'));
    const rayonData = @json($prestasiPerRayon->pluck('jumlah_prestasi'));

    const ctxRayon = document.getElementById('chartPrestasiRayon').getContext('2d');

    new Chart(ctxRayon, {
        type: 'bar',
        data: {
            labels: rayonLabels,
            datasets: [{
                label: 'Jumlah Prestasi',
                data: rayonData,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    const ctxJenis = document.getElementById('chartJenisPrestasi').getContext('2d');

    new Chart(ctxJenis, {
        type: 'pie',
        data: {
            labels: @json($pieLabels),
            datasets: [{
                data: @json($pieValues),
                backgroundColor: [
                    '#36A2EB',
                    '#FF6384',
                    '#4BC0C0',
                    '#FFCD56'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    const ctxTingkat = document.getElementById('chartTingkatPrestasi').getContext('2d');

    new Chart(ctxTingkat, {
        type: 'pie',
        data: {
            labels: @json($tingkatLabels),
            datasets: [{
                data: @json($tingkatValues),
                backgroundColor: [
                    '#36A2EB',
                    '#FF6384',
                    '#4BC0C0',
                    '#FFCD56',
                    '#9966FF',
                    '#FF9F40'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    const ctxBeasiswa = document.getElementById('chartBeasiswa').getContext('2d');

    new Chart(ctxBeasiswa, {
        type: 'pie',
        data: {
            labels: @json($beasiswaLabels),
            datasets: [{
                data: @json($beasiswaValues),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    const labelsMasalah = @json($labelsMasalah);
    const dataMasalah = @json($dataMasalah);

    new Chart(document.getElementById('chartMasalah'), {
        type: 'bar',
        data: {
            labels: labelsMasalah,
            datasets: [{
                label: 'Jumlah Peserta Didik Bermasalah',
                data: dataMasalah,
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 50,
                    ticks: {
                        stepSize: 5
                    }
                }
            }
        }
    });


</script>

</html>
