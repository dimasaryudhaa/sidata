@extends('layouts.app')

@section('content')

@php
    $user = auth()->user();
    $isAdmin = $user->role === 'admin';
    $isPtk = $user->role === 'ptk';
    $isSiswa = $user->role === 'siswa';
@endphp

<div class="container dashboard-containerr">

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
            }, 5000);
        </script>
    @endif

    @if($isAdmin)
        <div class="row">
            <div class="col-md-3">
                <a href="{{ route('admin.siswa.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Siswa</p>
                                <h3 class="mb-0">{{ $jumlahSiswa }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Ptk</p>
                                <h3 class="mb-0">{{ $jumlahPtk }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Jurusan</p>
                                <h3 class="mb-0">{{ $jumlahJurusan }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Rayon</p>
                                <h3 class="mb-0">{{ $jumlahRayon }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Rombel</p>
                                <h3 class="mb-0">{{ $jumlahRombel }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

    @elseif($isPtk)
        <div class="text-center mt-5">
            <h2>Selamat Datang {{ $user->name }}!</h2>
        </div>
    @elseif($isSiswa)
        <div class="text-center mt-5">
            <h2>Selamat Datang {{ $user->name }}!</h2>
        </div>
    @endif

</div>

@endsection
