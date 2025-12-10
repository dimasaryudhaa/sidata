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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('beranda') }}">Beranda</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Data
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="dataDropdown">
                            <li><a class="dropdown-item" href="{{ route('data') }}">Data Master</a></li>
                            <li><a class="dropdown-item" href="{{ route('data-akademik') }}">Data Akademik</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('kontak-alamat') }}">Kontak & Alamat</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div style="display: flex; gap: 20px; margin-left: 30px; margin-top: 80px;">
        <div class="card" style="width: 400px; margin: 30px;">
            <div class="card-header">
                <h5 class="m-0">Tingkat Prestasi</h5>
            </div>
            <div class="card-body">
                <canvas id="chartTingkatPrestasi" height="250"></canvas>
            </div>
        </div>

        <div class="card" style="width: 400px; margin: 30px;">
            <div class="card-header">
                <h5 class="m-0">Jenis Prestasi</h5>
            </div>
            <div class="card-body">
                <canvas id="chartJenisPrestasi" height="250"></canvas>
            </div>
        </div>

        <div class="card" style="width: 400px; margin: 30px;">
            <div class="card-header">
                <h5 class="m-0">Jenis Beasiswa</h5>
            </div>
            <div class="card-body">
                <canvas id="chartBeasiswa" height="250"></canvas>
            </div>
        </div>

    </div>

    <div style="display: flex; gap: 20px; margin-left: 30px; margin-top: 50px;">

        <div class="card" style="width: 720px;">
            <div class="card-header">
                <h4>Jumlah Siswa Berprestasi per Rombel</h4>
            </div>
            <div class="card-body">
                <canvas id="chartPrestasiRombel" height="100"></canvas>
            </div>
        </div>

        <div class="card" style="width: 720px;">
            <div class="card-header">
                <h4>Jumlah Siswa Berprestasi per Rayon</h4>
            </div>
            <div class="card-body">
                <canvas id="chartPrestasiRayon" height="100"></canvas>
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
        }
    });
</script>

</html>
