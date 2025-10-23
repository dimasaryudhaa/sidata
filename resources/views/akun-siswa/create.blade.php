@extends('layouts.app')

@section('content')

<style>
    .card-form {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 30px;
        max-width: 800px;
        margin: 40px auto;
    }

    label {
        font-weight: 600;
        color: #333;
    }

    .form-control {
        border-radius: 8px;
    }

    .btn-success {
        background: linear-gradient(180deg, #00b35a, #00c96b, #55f89b);
        border: none;
    }

    .btn-success:hover {
        background: linear-gradient(180deg, #009f4f, #00b35a, #48d88a);
    }

    .btn-secondary {
        background: #6c757d;
        border: none;
    }
</style>

<div class="container">
    <div class="card-form">
        <h4 class="text-center mb-4 fw-bold text-primary">Tambah Akun PTK</h4>

        <form action="{{ route('akun-ptk.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="ptk_id">Nama PTK</label>
                        <select name="ptk_id" id="ptk_id" class="form-control" required>
                            <option value="">-- Pilih PTK --</option>
                            @foreach($ptks as $ptk)
                                <option value="{{ $ptk->id }}">{{ $ptk->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required placeholder="contoh: guru@email.com">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required placeholder="Masukkan password akun">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('akun-ptk.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
