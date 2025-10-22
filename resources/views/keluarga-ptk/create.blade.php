@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Keluarga PTK</h1>

    <form action="{{ route('keluarga-ptk.store') }}" method="POST">
        @csrf
        <div class="row">

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <select name="ptk_id" class="form-control" required>
                        <option value="">Pilih PTK</option>
                        @foreach($ptks as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_lengkap ?? $p->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>No KK</label>
                    <input type="text" name="no_kk" class="form-control" maxlength="16">
                </div>

                <div class="mb-3">
                    <label>Status Perkawinan</label>
                    <select name="status_perkawinan" class="form-control">
                        <option value="">Pilih Status</option>
                        <option value="Kawin">Kawin</option>
                        <option value="Belum Kawin">Belum Kawin</option>
                        <option value="Janda/Duda">Janda/Duda</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Suami/Istri</label>
                    <input type="text" name="nama_suami_istri" class="form-control">
                </div>

                <div class="mb-3">
                    <label>NIP Suami/Istri</label>
                    <input type="text" name="nip_suami_istri" class="form-control" maxlength="25">
                </div>
                <div class="mb-3">
                    <label>Pekerjaan Suami/Istri</label>
                    <select name="pekerjaan_suami_istri" class="form-control">
                        <option value="">Pilih Pekerjaan</option>
                        <option value="Tidak Bekerja">Tidak Bekerja</option>
                        <option value="Nelayan">Nelayan</option>
                        <option value="Petani">Petani</option>
                        <option value="Peternak">Peternak</option>
                        <option value="PNS">PNS</option>
                        <option value="Swasta">Swasta</option>
                        <option value="Wiraswasta">Wiraswasta</option>
                        <option value="Pedagang">Pedagang</option>
                        <option value="Buruh">Buruh</option>
                        <option value="Pensiunan">Pensiunan</option>
                        <option value="Sudah Meninggal">Sudah Meninggal</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('keluarga-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
