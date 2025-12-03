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
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.ptk.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Ptk</p>
                                <h3 class="mb-0">{{ $jumlahPtk }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-person-badge"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.rayon.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Rayon</p>
                                <h3 class="mb-0">{{ $jumlahRayon }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-geo"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.rombel.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
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
            <div class="col-md-3">
                <a href="{{ route('admin.rayon.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Jurusan</p>
                                <h3 class="mb-0">{{ $jumlahJurusan }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-building"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    @elseif($isPtk)
        <div class="row">
            <div class="col-md-3">
                <a href="{{ route('admin.anak-ptk.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Anak</p>
                                <h3 class="mb-0">{{ $jumlahAnak }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.tunjangan.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Tunjangan</p>
                                <h3 class="mb-0">{{ $jumlahTunjangan }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-currency-exchange"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.kesejahteraan-ptk.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Kesejahteraan</p>
                                <h3 class="mb-0">{{ $jumlahKesejahteraan }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-heart"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.tugas-tambahan.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Tugas Tambahan</p>
                                <h3 class="mb-0">{{ $jumlahTugasTambahan }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-plus-square"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.riwayat-gaji.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Riwayat Gaji</p>
                                <h3 class="mb-0">{{ $jumlahRiwayatGaji }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.riwayat-karir.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Riwayat Karir</p>
                                <h3 class="mb-0">{{ $jumlahRiwayatKarir }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-briefcase"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.riwayat-jabatan.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Riwayat Jabatan</p>
                                <h3 class="mb-0">{{ $jumlahRiwayatJabatan }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.riwayat-kepangkatan.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Riwayat Kepangkatan</p>
                                <h3 class="mb-0">{{ $jumlahRiwayatKepangkatan }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-award"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.riwayat-jabatan-fungsional.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Riwayat Jabatan Fungsional</p>
                                <h3 class="mb-0">{{ $jumlahRiwayatJabatanFungsional }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-diagram-2"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.diklat.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Diklat</p>
                                <h3 class="mb-0">{{ $jumlahDiklat }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-book"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.nilai-test.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Nilai Test</p>
                                <h3 class="mb-0">{{ $jumlahNilaiTest }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-clipboard"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.pendidikan-ptk.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Pendidikan</p>
                                <h3 class="mb-0">{{ $jumlahPendidikanPtk }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-mortarboard"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.sertifikat-ptk.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Sertifikat</p>
                                <h3 class="mb-0">{{ $jumlahSertifikat }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.sertifikat-ptk.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Beasiswa</p>
                                <h3 class="mb-0">{{ $jumlahBeasiswa }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.penghargaan.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Penghargaan</p>
                                <h3 class="mb-0">{{ $jumlahPenghargaan }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-trophy"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.kompetensi-ptk.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                         style="height: 100px; border-radius: 12px; overflow: hidden;">
                        <div class="d-flex h-100">
                            <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                                 style="background-color: white;">
                                <p class="mb-0">Jumlah Kompetensi</p>
                                <h3 class="mb-0">{{ $jumlahKompetensi }}</h3>
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-25"
                                 style="background-color: #0770d3; color: white; font-size: 2rem;">
                                <i class="bi bi-gear"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            {{-- <div class="col-md-4 d-flex justify-content-center">
                <a href="{{ route('ptk.ptk.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                        style="width: 250px; height: 250px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 20px;">
                        <p class="mb-0 fw-bold text-center" style="font-size: 1.2rem;">Data PTK</p>
                        <div style="background-color: {{ $sudahMengisi ? '#28a745' : '#dc3545' }}; color: white; font-size: 3rem; width: 80px; height: 80px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                            <i class="bi {{ $sudahMengisi ? 'bi-check2-circle' : 'bi-x-circle' }}"></i>
                        </div>
                        <h6 class="mb-0 fw-bold {{ $sudahMengisi ? 'text-success' : 'text-danger' }}" style="font-size: 1.2rem;">
                            {{ $sudahMengisi ? 'Sudah Mengisi' : 'Belum Mengisi' }}
                        </h6>
                    </div>
                </a>
            </div>
            <div class="col-md-4 d-flex justify-content-center">
                <a href="{{ route('ptk.dokumen-ptk.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                        style="width: 250px; height: 250px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 20px;">
                        <p class="mb-0 fw-bold text-center" style="font-size: 1.2rem;">Data PTK</p>
                        <div style="background-color: {{ $sudahMengisi ? '#28a745' : '#dc3545' }}; color: white; font-size: 3rem; width: 80px; height: 80px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                            <i class="bi {{ $sudahMengisi ? 'bi-check2-circle' : 'bi-x-circle' }}"></i>
                        </div>
                        <h6 class="mb-0 fw-bold {{ $sudahMengisi ? 'text-success' : 'text-danger' }}" style="font-size: 1.2rem;">
                            {{ $sudahMengisi ? 'Sudah Mengisi' : 'Belum Mengisi' }}
                        </h6>
                    </div>
                </a>
            </div> --}}
            <div class="col-md-4 d-flex justify-content-center">
                <a href="{{ route('ptk.keluarga-ptk.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                        style="width: 250px; height: 250px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 20px;">
                        <p class="mb-0 fw-bold text-center" style="font-size: 1.2rem;">Data Keluarga</p>
                        <div style="background-color: {{ $sudahMengisiKeluarga ? '#28a745' : '#dc3545' }}; color: white; font-size: 3rem; width: 80px; height: 80px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                            <i class="bi {{ $sudahMengisiKeluarga ? 'bi-check2-circle' : 'bi-x-circle' }}"></i>
                        </div>
                        <h6 class="mb-0 fw-bold {{ $sudahMengisiKeluarga ? 'text-success' : 'text-danger' }}" style="font-size: 1.2rem;">
                            {{ $sudahMengisiKeluarga ? 'Sudah Mengisi' : 'Belum Mengisi' }}
                        </h6>
                    </div>
                </a>
            </div>
            <div class="col-md-4 d-flex justify-content-center">
                <a href="{{ route('ptk.penugasan-ptk.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                        style="width: 250px; height: 250px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 20px;">
                        <p class="mb-0 fw-bold text-center" style="font-size: 1.2rem;">Penugasan PTK</p>
                        <div style="background-color: {{ $sudahIsiPenugasan ? '#28a745' : '#dc3545' }}; color: white; font-size: 3rem; width: 80px; height: 80px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                            <i class="bi {{ $sudahIsiPenugasan ? 'bi-check2-circle' : 'bi-x-circle' }}"></i>
                        </div>
                        <h6 class="mb-0 fw-bold {{ $sudahIsiPenugasan ? 'text-success' : 'text-danger' }}" style="font-size: 1.2rem;">
                            {{ $sudahIsiPenugasan ? 'Sudah Mengisi' : 'Belum Mengisi' }}
                        </h6>
                    </div>
                </a>
            </div>
            <div class="col-md-4 d-flex justify-content-center">
                <a href="{{ route('ptk.kepegawaian-ptk.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="card mb-3 shadow hoverable"
                        style="width: 250px; height: 250px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 20px;">
                        <p class="mb-0 fw-bold text-center" style="font-size: 1.2rem;">Kepegawaian PTK</p>
                        <div style="background-color: {{ $sudahIsiKepegawaian ? '#28a745' : '#dc3545' }};
                                    color: white; font-size: 3rem; width: 80px; height: 80px;
                                    border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                            <i class="bi {{ $sudahIsiKepegawaian ? 'bi-check2-circle' : 'bi-x-circle' }}"></i>
                        </div>
                        <h6 class="mb-0 fw-bold {{ $sudahIsiKepegawaian ? 'text-success' : 'text-danger' }}"
                            style="font-size: 1.2rem;">
                            {{ $sudahIsiKepegawaian ? 'Sudah Mengisi' : 'Belum Mengisi' }}
                        </h6>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">

        </div>


    @elseif($isSiswa)
    <div class="row justify-content-center">
        <div class="col-md-4">
            <a href="{{ route('siswa.beasiswa.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card mb-3 shadow hoverable"
                    style="height: 100px; width: 350px; border-radius: 12px; overflow: hidden;">
                    <div class="d-flex h-100">
                        <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                            style="background-color: white;">
                            <p class="mb-0">Jumlah Beasiswa </p>
                            <h3 class="mb-0">{{ $jumlahBeasiswa }}</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center w-25"
                            style="background-color: #0770d3; color: white; font-size: 2rem;">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('siswa.prestasi.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card mb-3 shadow hoverable"
                    style="height: 100px; width: 350px; border-radius: 12px; overflow: hidden;">
                    <div class="d-flex h-100">
                        <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                            style="background-color: white;">
                            <p class="mb-0">Jumlah Prestasi </p>
                            <h3 class="mb-0">{{ $jumlahPrestasi }}</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center w-25"
                            style="background-color: #0770d3; color: white; font-size: 2rem;">
                            <i class="bi bi-award"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('siswa.kesejahteraan-siswa.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card mb-3 shadow hoverable"
                    style="height: 100px; width: 350px; border-radius: 12px; overflow: hidden;">
                    <div class="d-flex h-100">
                        <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                            style="background-color: white;">
                            <p class="mb-0">Jumlah Kesejahteraan </p>
                            <h3 class="mb-0">{{ $jumlahKesejahteraan }}</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center w-25"
                            style="background-color: #0770d3; color: white; font-size: 2rem;">
                            <i class="bi bi-heart"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 d-flex justify-content-center">
            <a href="{{ route('siswa.siswa.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card mb-3 shadow hoverable"
                    style="width: 250px; height: 250px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 20px;">
                    <p class="mb-0 fw-bold text-center" style="font-size: 1.2rem;">Data Siswa</p>
                    <div style="background-color: {{ $sudahMengisi ? '#28a745' : '#dc3545' }}; color: white; font-size: 3rem; width: 80px; height: 80px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                        <i class="bi {{ $sudahMengisi ? 'bi-check2-circle' : 'bi-x-circle' }}"></i>
                    </div>
                    <h6 class="mb-0 fw-bold {{ $sudahMengisi ? 'text-success' : 'text-danger' }}" style="font-size: 1.2rem;">
                        {{ $sudahMengisi ? 'Sudah Mengisi' : 'Belum Mengisi' }}
                    </h6>
                </div>
            </a>
        </div>
        <div class="col-md-4 d-flex justify-content-center">
            <div class="card mb-3 shadow hoverable"
                style="width: 250px; height: 250px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 20px;">
                <p class="mb-0 fw-bold text-center" style="font-size: 1.2rem;">Status Dokumen</p>

                <div class="status-card d-flex justify-content-center align-items-center"
                    data-id="{{ $dokumenSiswa?->id ?? 0 }}"
                    style="background-color: {{ $sudahMengunggahDokumen ? '#28a745' : '#dc3545' }};
                            color: white; font-size: 3rem; width: 80px; height: 80px; border-radius: 50%; cursor: pointer;">
                    <i class="bi {{ $sudahMengunggahDokumen ? 'bi-check2-circle' : 'bi-x-circle' }}"></i>
                </div>

                <h6 class="mb-0 fw-bold {{ $sudahMengunggahDokumen ? 'text-success' : 'text-danger' }}"
                    style="font-size: 1.2rem;">
                    {{ $sudahMengunggahDokumen ? 'Sudah Mengunggah' : 'Belum Mengunggah' }}
                </h6>

                @if($sudahMengunggahDokumen)
                    <div class="mt-2 d-none validate-box" id="box-{{ $dokumenSiswa?->id ?? 0 }}">
                        <button class="btn btn-sm btn-primary" onclick="validateNow({{ $dokumenSiswa?->id ?? 0 }})">
                            Validasi
                        </button>
                    </div>
                @endif
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                document.querySelectorAll(".status-card").forEach(el => {
                    let id = el.dataset.id;
                    if(!id) return;

                    let saved = localStorage.getItem("status-" + id);
                    if(saved) setStatusCard(id, saved, false);

                    el.addEventListener("click", () => {
                        let box = document.getElementById("box-" + id);
                        if(box) box.classList.toggle("d-none");
                    });
                });
            });

            function setStatusCard(id, status, save = true) {
                let card = document.querySelector(`.status-card[data-id="${id}"]`);
                let box = document.getElementById("box-" + id);
                let teks = card.nextElementSibling;

                if(status === "valid") {
                    card.style.backgroundColor = "#0d6efd";
                    card.innerHTML = `<i class="bi bi-check2-circle"></i>`;
                    if(teks) {
                        teks.innerText = "Di Validasi";
                        teks.classList.remove("text-success", "text-danger");
                        teks.classList.add("text-primary");
                    }

                    if(box) box.innerHTML = `
                        <button class="btn btn-sm btn-warning" onclick="toggleStatusCard(${id})">
                            Batalkan Validasi
                        </button>
                    `;
                } else {
                    card.style.backgroundColor = "#28a745"; 
                    card.innerHTML = `<i class="bi bi-check2-circle"></i>`;
                    if(teks) {
                        teks.innerText = "Sudah Mengunggah";
                        teks.classList.remove("text-primary", "text-danger");
                        teks.classList.add("text-success");
                    }

                    if(box) box.innerHTML = `
                        <button class="btn btn-sm btn-primary" onclick="toggleStatusCard(${id})">
                            Validasi
                        </button>
                    `;
                }

                if(save) localStorage.setItem("status-" + id, status);
            }

            function toggleStatusCard(id) {
                let current = localStorage.getItem("status-" + id);
                if(current === "valid") setStatusCard(id, "mengumpulkan");
                else setStatusCard(id, "valid");

                let box = document.getElementById("box-" + id);
                if(box) box.classList.add("d-none");
            }

            function validateNow(id) {
                setStatusCard(id, "valid");
            }
        </script>

        <div class="col-md-4 d-flex justify-content-center">
            <a href="{{ route('siswa.periodik.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card mb-3 shadow hoverable"
                    style="width: 250px; height: 250px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 20px;">
                    <p class="mb-0 fw-bold text-center" style="font-size: 1.2rem;">Data Periodik</p>
                    <div style="background-color: {{ $sudahIsiPeriodik ? '#28a745' : '#dc3545' }}; color: white; font-size: 3rem; width: 80px; height: 80px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                        <i class="bi {{ $sudahIsiPeriodik ? 'bi-check2-circle' : 'bi-x-circle' }}"></i>
                    </div>
                    <h6 class="mb-0 fw-bold {{ $sudahIsiPeriodik ? 'text-success' : 'text-danger' }}" style="font-size: 1.2rem;">
                        {{ $sudahIsiPeriodik ? 'Sudah Mengisi' : 'Belum Mengisi' }}
                    </h6>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 d-flex justify-content-center">
            <a href="{{ route('siswa.orang-tua.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card mb-3 shadow hoverable"
                    style="width: 250px; height: 250px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 20px;">
                    <p class="mb-0 fw-bold text-center" style="font-size: 1.2rem;">Data Orang Tua</p>
                    <div style="background-color: {{ $sudahIsiOrangTua ? '#28a745' : '#dc3545' }}; color: white; font-size: 3rem; width: 80px; height: 80px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                        <i class="bi {{ $sudahIsiOrangTua ? 'bi-check2-circle' : 'bi-x-circle' }}"></i>
                    </div>
                    <h6 class="mb-0 fw-bold {{ $sudahIsiOrangTua ? 'text-success' : 'text-danger' }}" style="font-size: 1.2rem;">
                        {{ $sudahIsiOrangTua ? 'Sudah Mengisi' : 'Belum Mengisi' }}
                    </h6>
                </div>
            </a>
        </div>
        <div class="col-md-4 d-flex justify-content-center">
            <a href="{{ route('siswa.registrasi-siswa.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card mb-3 shadow hoverable"
                    style="width: 250px; height: 250px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 20px;">
                    <p class="mb-0 fw-bold text-center" style="font-size: 1.2rem;">Data Registrasi</p>
                    <div style="background-color: {{ $sudahIsiRegistrasi ? '#28a745' : '#dc3545' }}; color: white; font-size: 3rem; width: 80px; height: 80px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                        <i class="bi {{ $sudahIsiRegistrasi ? 'bi-check2-circle' : 'bi-x-circle' }}"></i>
                    </div>
                    <h6 class="mb-0 fw-bold {{ $sudahIsiRegistrasi ? 'text-success' : 'text-danger' }}" style="font-size: 1.2rem;">
                        {{ $sudahIsiRegistrasi ? 'Sudah Mengisi' : 'Belum Mengisi' }}
                    </h6>

                </div>
            </a>
        </div>
        <div class="col-md-4 d-flex justify-content-center">
            <a href="{{ route('siswa.kontak-siswa.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card mb-3 shadow hoverable"
                    style="width: 250px; height: 250px; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 20px;">
                    <p class="mb-0 fw-bold text-center" style="font-size: 1.2rem;">Kontak & Alamat</p>
                    <div style="background-color: {{ $sudahIsiKontak ? '#28a745' : '#dc3545' }}; color: white; font-size: 3rem; width: 80px; height: 80px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                        <i class="bi {{ $sudahIsiKontak ? 'bi-check2-circle' : 'bi-x-circle' }}"></i>
                    </div>
                    <h6 class="mb-0 fw-bold {{ $sudahIsiKontak ? 'text-success' : 'text-danger' }}" style="font-size: 1.2rem;">
                        {{ $sudahIsiKontak ? 'Sudah Mengisi' : 'Belum Mengisi' }}
                    </h6>
                </div>
            </a>
        </div>
    </div>
    @endif
</div>

@endsection
