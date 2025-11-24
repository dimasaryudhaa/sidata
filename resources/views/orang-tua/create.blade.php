@extends('layouts.app')

@section('content')

@php
    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $isSiswa = $user->role === 'siswa';
    $prefix = $isAdmin ? 'admin.' : 'siswa.';
@endphp

<div class="container">
    <h1 class="mb-4">Tambah Data Orang Tua / Wali</h1>

    <form action="{{ route($prefix.'orang-tua.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="form-label fw-bold">Nama Siswa</label>

            @if($isAdmin)
                <select name="peserta_didik_id" class="form-control" required>
                    <option value="">Pilih Siswa</option>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}">{{ $s->nama_lengkap }}</option>
                    @endforeach
                </select>
            @elseif($isSiswa)
                <input type="hidden" name="peserta_didik_id" value="{{ $siswa->id }}">
                <input type="text" class="form-control" value="{{ $siswa->nama_lengkap }}" disabled>
            @endif
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white fw-bold">
                Data Ayah & Ibu
            </div>

            <div class="card-body row">

                <div class="col-md-6">
                    <h5 class="mb-3 text-primary">Data Ayah</h5>

                    <div class="mb-3"><label>Nama Ayah</label><input type="text" name="nama_ayah" class="form-control"></div>
                    <div class="mb-3"><label>NIK Ayah</label><input type="text" name="nik_ayah" class="form-control"></div>
                    <div class="mb-3"><label>Tahun Lahir Ayah</label><input type="number" name="tahun_lahir_ayah" class="form-control"></div>

                    <div class="mb-3">
                        <label>Pendidikan Ayah</label>
                        <select name="pendidikan_ayah" class="form-control">
                            <option value="">Pilih Pendidikan</option>
                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                            <option value="Putus SD">Putus SD</option>
                            <option value="SD Sederajat">SD Sederajat</option>
                            <option value="SMP Sederajat">SMP Sederajat</option>
                            <option value="SMA Sederajat">SMA Sederajat</option>
                            <option value="D1">D1</option>
                            <option value="D2">D2</option>
                            <option value="D3">D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Pekerjaan Ayah</label>
                        <select name="pekerjaan_ayah" class="form-control">
                            <option value="">Pilih Pekerjaan</option>
                            <option value="Tidak Bekerja">Tidak Bekerja</option>
                            <option value="Nelayan">Nelayan</option>
                            <option value="Petani">Petani</option>
                            <option value="Peternak">Peternak</option>
                            <option value="PNS/TNI/POLRI">PNS/TNI/POLRI</option>
                            <option value="Karyawan Swasta">Karyawan Swasta</option>
                            <option value="Pedagang Kecil">Pedagang Kecil</option>
                            <option value="Pedagang Besar">Pedagang Besar</option>
                            <option value="Wiraswasta">Wiraswasta</option>
                            <option value="Wirausaha">Wirausaha</option>
                            <option value="Buruh">Buruh</option>
                            <option value="Pensiunan">Pensiunan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Penghasilan Ayah</label>
                        <select name="penghasilan_ayah" class="form-control">
                            <option value="">Pilih Penghasilan</option>
                            <option value="<500rb">< 500rb</option>
                            <option value="500rb-999rb">500rb - 999rb</option>
                            <option value="1jt-1.9jt">1jt - 1.9jt</option>
                            <option value="2jt-4.9jt">2jt - 4.9jt</option>
                            <option value=">=5jt">>= 5jt</option>
                        </select>
                    </div>

                    <div class="mb-3"><label>Kebutuhan Khusus Ayah</label><input type="text" name="kebutuhan_khusus_ayah" class="form-control"></div>
                </div>

                <div class="col-md-6">
                    <h5 class="mb-3 text-primary">Data Ibu</h5>

                    <div class="mb-3"><label>Nama Ibu</label><input type="text" name="nama_ibu" class="form-control"></div>
                    <div class="mb-3"><label>NIK Ibu</label><input type="text" name="nik_ibu" class="form-control"></div>
                    <div class="mb-3"><label>Tahun Lahir Ibu</label><input type="number" name="tahun_lahir_ibu" class="form-control"></div>

                    <div class="mb-3">
                        <label>Pendidikan Ibu</label>
                        <select name="pendidikan_ibu" class="form-control">
                            <option value="">Pilih Pendidikan</option>
                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                            <option value="Putus SD">Putus SD</option>
                            <option value="SD Sederajat">SD Sederajat</option>
                            <option value="SMP Sederajat">SMP Sederajat</option>
                            <option value="SMA Sederajat">SMA Sederajat</option>
                            <option value="D1">D1</option>
                            <option value="D2">D2</option>
                            <option value="D3">D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Pekerjaan Ibu</label>
                        <select name="pekerjaan_ibu" class="form-control">
                            <option value="">Pilih Pekerjaan</option>
                            <option value="Tidak Bekerja">Tidak Bekerja</option>
                            <option value="Nelayan">Nelayan</option>
                            <option value="Petani">Petani</option>
                            <option value="Peternak">Peternak</option>
                            <option value="PNS/TNI/POLRI">PNS/TNI/POLRI</option>
                            <option value="Karyawan Swasta">Karyawan Swasta</option>
                            <option value="Pedagang Kecil">Pedagang Kecil</option>
                            <option value="Pedagang Besar">Pedagang Besar</option>
                            <option value="Wiraswasta">Wiraswasta</option>
                            <option value="Wirausaha">Wirausaha</option>
                            <option value="Buruh">Buruh</option>
                            <option value="Pensiunan">Pensiunan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Penghasilan Ibu</label>
                        <select name="penghasilan_ibu" class="form-control">
                            <option value="">Pilih Penghasilan</option>
                            <option value="<500rb">< 500rb</option>
                            <option value="500rb-999rb">500rb - 999rb</option>
                            <option value="1jt-1.9jt">1jt - 1.9jt</option>
                            <option value="2jt-4.9jt">2jt - 4.9jt</option>
                            <option value=">=5jt">>= 5jt</option>
                        </select>
                    </div>

                    <div class="mb-3"><label>Kebutuhan Khusus Ibu</label><input type="text" name="kebutuhan_khusus_ibu" class="form-control"></div>
                </div>

            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white fw-bold">
                Data Wali (Opsional)
            </div>

            <div class="card-body row">
                <div class="col-md-6">
                    <div class="mb-3"><label>Nama Wali</label><input type="text" name="nama_wali" class="form-control"></div>
                    <div class="mb-3"><label>NIK Wali</label><input type="text" name="nik_wali" class="form-control"></div>
                    <div class="mb-3"><label>Tahun Lahir Wali</label><input type="number" name="tahun_lahir_wali" class="form-control"></div>
                </div>

                <div class="col-md-6">

                    <div class="mb-3">
                        <label>Pendidikan Wali</label>
                        <select name="pendidikan_wali" class="form-control">
                            <option value="">Pilih Pendidikan</option>
                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                            <option value="Putus SD">Putus SD</option>
                            <option value="SD Sederajat">SD Sederajat</option>
                            <option value="SMP Sederajat">SMP Sederajat</option>
                            <option value="SMA Sederajat">SMA Sederajat</option>
                            <option value="D1">D1</option>
                            <option value="D2">D2</option>
                            <option value="D3">D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Pekerjaan Wali</label>
                        <select name="pekerjaan_wali" class="form-control">
                            <option value="">Pilih Pekerjaan</option>
                            <option value="Tidak Bekerja">Tidak Bekerja</option>
                            <option value="Nelayan">Nelayan</option>
                            <option value="Petani">Petani</option>
                            <option value="Peternak">Peternak</option>
                            <option value="PNS/TNI/POLRI">PNS/TNI/POLRI</option>
                            <option value="Karyawan Swasta">Karyawan Swasta</option>
                            <option value="Pedagang Kecil">Pedagang Kecil</option>
                            <option value="Pedagang Besar">Pedagang Besar</option>
                            <option value="Wiraswasta">Wiraswasta</option>
                            <option value="Wirausaha">Wirausaha</option>
                            <option value="Buruh">Buruh</option>
                            <option value="Pensiunan">Pensiunan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Penghasilan Wali</label>
                        <select name="penghasilan_wali" class="form-control">
                            <option value="">Pilih Penghasilan</option>
                            <option value="<500rb">< 500rb</option>
                            <option value="500rb-999rb">500rb - 999rb</option>
                            <option value="1jt-1.9jt">1jt - 1.9jt</option>
                            <option value="2jt-4.9jt">2jt - 4.9jt</option>
                            <option value=">=5jt">>= 5jt</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route($prefix.'orang-tua.index') }}" class="btn btn-secondary">Kembali</a>
        </div>

    </form>
</div>

@endsection
