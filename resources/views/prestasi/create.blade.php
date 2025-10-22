@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Prestasi Siswa</h1>

    <form action="{{ route('prestasi.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Siswa</label>
                    @if(isset($siswaId))
                        <input type="text" class="form-control" value="{{ $siswa->nama_lengkap }}" disabled>
                        <input type="hidden" name="peserta_didik_id" value="{{ $siswaId }}">
                    @else
                        <select name="peserta_didik_id" class="form-control" required>
                            <option value="">Pilih Siswa</option>
                            @foreach($siswa as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="mb-3">
                    <label>Jenis Prestasi</label>
                    <select name="jenis_prestasi" class="form-control" required>
                        <option value="">Pilih Jenis</option>
                        <option value="Sains">Sains</option>
                        <option value="Seni">Seni</option>
                        <option value="Olahraga">Olahraga</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tingkat Prestasi</label>
                    <select name="tingkat_prestasi" class="form-control" required>
                        <option value="">Pilih Tingkat</option>
                        @php
                            $tingkat = ['Sekolah','Kecamatan','Kabupaten','Provinsi','Nasional','Internasional'];
                        @endphp
                        @foreach($tingkat as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama Prestasi</label>
                    <input type="text" name="nama_prestasi" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Tahun Prestasi</label>
                    <input type="number" name="tahun_prestasi" class="form-control" placeholder="YYYY" required>
                </div>

                <div class="mb-3">
                    <label>Penyelenggara</label>
                    <input type="text" name="penyelenggara" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Peringkat</label>
                    <input type="number" name="peringkat" class="form-control">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('prestasi.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection
