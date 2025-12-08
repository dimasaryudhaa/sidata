@extends('layouts.app')

@section('content')

<style>
    .table thead th {
        background: linear-gradient(180deg, #0770d3, #007efd, #55a6f8) !important;
        color: white !important;
        border: none !important;
        vertical-align: middle !important;
        font-weight: 600;
    }
</style>

<div class="container mt-5">
    <h2 class="fw-bold mb-4">Daftar Pesan Masuk</h2>

    <div class="table-responsive rounded-3 overflow-hidden mt-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Pesan</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($pesans as $pesan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pesan->name }}</td>
                        <td>{{ $pesan->email }}</td>
                        <td>{{ $pesan->message }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada pesan masuk.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <div class="mt-3">
        {{ $pesans->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
