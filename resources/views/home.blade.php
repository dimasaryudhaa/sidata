@extends('layouts.app')

@section('content')

@php
    $user = auth()->user();
    $isAdmin = $user->role === 'admin';
@endphp

<style>
    .disabled-card {
        pointer-events: none;
        opacity: 1 !important;
        cursor: default !important;
    }

    .hoverable:hover {
        transform: translateY(-3px);
        transition: 0.2s;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="container dashboard-containerr">
    <div class="row">

        <div class="col-md-3">
            @if ($isAdmin)
                <a href="{{ route('siswa.index') }}" style="text-decoration: none; color: inherit;">
            @endif
                <div class="card mb-3 shadow {{ $isAdmin ? 'hoverable' : 'disabled-card' }}"
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
            @if ($isAdmin)
                </a>
            @endif
        </div>

        <div class="col-md-3">
            @if ($isAdmin)
                <a href="{{ route('jurusan.index') }}" style="text-decoration: none; color: inherit;">
            @endif
                <div class="card mb-3 shadow {{ $isAdmin ? 'hoverable' : 'disabled-card' }}"
                     style="height: 100px; border-radius: 12px; overflow: hidden;">
                    <div class="d-flex h-100">
                        <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                             style="background-color: white;">
                            <p class="mb-0">Jumlah Jurusan</p>
                            <h3 class="mb-0">{{ $jumlahJurusan }}</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center w-25"
                             style="background-color: #0770d3; color: white; font-size: 2rem;">
                            <i class="bi bi-server"></i>
                        </div>
                    </div>
                </div>
            @if ($isAdmin)
                </a>
            @endif
        </div>

        <div class="col-md-3">
            @if ($isAdmin)
                <a href="{{ route('rombel.index') }}" style="text-decoration: none; color: inherit;">
            @endif
                <div class="card mb-3 shadow {{ $isAdmin ? 'hoverable' : 'disabled-card' }}"
                     style="height: 100px; border-radius: 12px; overflow: hidden;">
                    <div class="d-flex h-100">
                        <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                             style="background-color: white;">
                            <p class="mb-0">Jumlah Rombel</p>
                            <h3 class="mb-0">{{ $jumlahRombel }}</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center w-25"
                             style="background-color: #0770d3; color: white; font-size: 2rem;">
                            <i class="bi bi-journal"></i>
                        </div>
                    </div>
                </div>
            @if ($isAdmin)
                </a>
            @endif
        </div>

        <div class="col-md-3">
            @if ($isAdmin)
                <a href="{{ route('rayon.index') }}" style="text-decoration: none; color: inherit;">
            @endif
                <div class="card mb-3 shadow {{ $isAdmin ? 'hoverable' : 'disabled-card' }}"
                     style="height: 100px; border-radius: 12px; overflow: hidden;">
                    <div class="d-flex h-100">
                        <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                             style="background-color: white;">
                            <p class="mb-0">Jumlah Rayon</p>
                            <h3 class="mb-0">{{ $jumlahRayon }}</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center w-25"
                             style="background-color: #0770d3; color: white; font-size: 2rem;">
                            <i class="bi bi-door-open"></i>
                        </div>
                    </div>
                </div>
            @if ($isAdmin)
                </a>
            @endif
        </div>

        <div class="col-md-3">
            @if ($isAdmin)
                <a href="{{ route('ptk.index') }}" style="text-decoration: none; color: inherit;">
            @endif
                <div class="card mb-3 shadow {{ $isAdmin ? 'hoverable' : 'disabled-card' }}"
                     style="height: 100px; border-radius: 12px; overflow: hidden;">
                    <div class="d-flex h-100">
                        <div class="d-flex flex-column justify-content-center align-items-start w-75 p-3"
                             style="background-color: white;">
                            <p class="mb-0">Jumlah PTK</p>
                            <h3 class="mb-0">{{ $jumlahPtk }}</h3>
                        </div>
                        <div class="d-flex justify-content-center align-items-center w-25"
                             style="background-color: #0770d3; color: white; font-size: 2rem;">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            @if ($isAdmin)
                </a>
            @endif
        </div>

    </div>
</div>

@endsection
