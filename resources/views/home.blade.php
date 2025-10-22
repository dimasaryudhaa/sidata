@extends('layouts.app')

@section('content')

<div class="containe dashboard-containerr">
    <div class="row">

        <div class="col-md-3">
            <a href="{{ route('siswa.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card mb-3 shadow" style="height: 100px; border-radius: 12px; overflow: hidden; cursor: pointer;">
                    <div class="d-flex h-100">
                        <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3" style="background-color: white;">
                            <p class="mb-0">Jumlah Siswa</p>
                            <h3 class="mb-0">{{ $jumlahSiswa }}</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center w-25" style="background-color: #0770d3; color: white; font-size: 2rem;">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('jurusan.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card mb-3 shadow" style="height: 100px; border-radius: 12px; overflow: hidden;">
                    <div class="d-flex h-100">
                        <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3" style="background-color: white;">
                            <p class="mb-0">Jumlah Jurusan</p>
                            <h3 class="mb-0">{{ $jumlahJurusan }}</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center w-25" style="background-color: #0770d3; color: white; font-size: 2rem;">
                            <i class="bi bi-server"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('rombel.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card mb-3 shadow" style="height: 100px; border-radius: 12px; overflow: hidden;">
                    <div class="d-flex h-100">
                        <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3" style="background-color: white;">
                            <p class="mb-0">Jumlah Rombel</p>
                            <h3 class="mb-0">{{ $jumlahRombel }}</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center w-25" style="background-color: #0770d3; color: white; font-size: 2rem;">
                            <i class="bi bi-journal"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('rayon.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card mb-3 shadow" style="height: 100px; border-radius: 12px; overflow: hidden;">
                    <div class="d-flex h-100">
                        <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3" style="background-color: white;">
                            <p class="mb-0">Jumlah Rayon</p>
                            <h3 class="mb-0">{{ $jumlahRayon }}</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center w-25" style="background-color: #0770d3; color: white; font-size: 2rem;">
                            <i class="bi bi-door-open"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="{{ route('ptk.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card mb-3 shadow" style="height: 100px; border-radius: 12px; overflow: hidden;">
                    <div class="d-flex h-100">
                        <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3" style="background-color: white;">
                            <p class="mb-0">Jumlah Ptk</p>
                            <h3 class="mb-0">{{ $jumlahPtk }}</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center w-25" style="background-color: #0770d3; color: white; font-size: 2rem;">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
