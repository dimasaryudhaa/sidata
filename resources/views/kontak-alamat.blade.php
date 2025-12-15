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

<div class="container" style="margin-top: 120px;">
    @if(session('success'))
        <div id="successAlert"
            class="position-fixed top-50 start-50 translate-middle bg-white text-center p-4 rounded shadow-lg border"
            style="z-index:1050; min-width:320px;">
            <div class="d-flex justify-content-center mb-3">
                <div class="d-flex justify-content-center align-items-center"
                    style="width:80px; height:80px; background-color:#d4edda; border-radius:50%;">
                    <i class="bi bi-check-lg text-success" style="font-size:2.5rem;"></i>
                </div>
            </div>
            <h5 class="fw-bold mb-1">Success</h5>
            <p class="text-muted mb-0">{{ session('success') }}</p>
        </div>

        <script>
            setTimeout(() => {
                const alertBox = document.getElementById('successAlert');
                if (alertBox) {
                    alertBox.style.transition = 'opacity 0.5s ease';
                    alertBox.style.opacity = '0';
                    setTimeout(() => alertBox.remove(), 500);
                }
            }, 2000);
        </script>
    @endif

    <div class="row g-4">

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm overflow-hidden">

                <div class="w-100">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.0139230514087!2d106.84130407499401!3d-6.645191993349406!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c89505b4c37d%3A0x307fc4a38e65fa2b!2sSMK%20Wikrama%20Bogor!5e0!3m2!1sid!2sid!4v1765171894003!5m2!1sid!2sid"
                 width="600" height="370" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                </div>

                <div class="p-4 text-white" style="background:#060b4a;">
                    <h4 class="mb-2">Lokasi Kami</h4>
                    <div class="mt-3">
                        <a href="https://www.instagram.com/smkwikrama/" class="text-white me-3 fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="https://www.tiktok.com/@smkwikrama" class="text-white me-3 fs-5"><i class="bi bi-tiktok"></i></a>
                        <a href="https://www.youtube.com/@multimediawikrama7482" class="text-white fs-5"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm p-4" style="height: 430px;">
                <h3 class="mb-4 fw-bold">Kirim Pesan</h3>

                <form action="{{ route('kirim.pesan') }}" method="POST">
                    @csrf
                    <div class="row g-3">

                        <div class="col-md-12">
                            <input type="text" class="form-control" name="name" placeholder="Nama Anda" id="name">
                            <small class="text-danger d-none" id="error-name">Nama wajib diisi.</small>
                        </div>

                        <div class="col-md-12">
                            <input type="email" class="form-control" name="email" placeholder="Email" id="email">
                            <small class="text-danger d-none" id="error-email">Email wajib diisi.</small>
                        </div>

                        <div class="col-12">
                            <textarea class="form-control" rows="5" name="message" id="message"
                                placeholder="Pesan Anda"></textarea>
                            <small class="text-danger d-none" id="error-message">Pesan wajib diisi.</small>
                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary px-4">Kirim</button>
                        </div>

                    </div>
                </form>
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
