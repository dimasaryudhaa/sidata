@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $role = $user->role;
    $prefix = $role === 'admin' ? 'admin.' : ($role === 'ptk' ? 'ptk.' : 'siswa.');

    $isEdit = isset($data->id);
@endphp

<div class="container">

    <div class="scroll-container p-5 rounded">
        <form
            action="{{ $isEdit ? route($prefix.'orang-tua.update', $data->id) : route($prefix.'orang-tua.store') }}"
            method="POST"
        >
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-6">
                    <h5 class="mt-3 mb-2 text-primary">Data Ayah</h5>

                    <div class="mb-3"><label>Nama Ayah</label>
                        <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $data->nama_ayah) }}">
                    </div>

                    <div class="mb-3"><label>NIK Ayah</label>
                        <input type="text" name="nik_ayah" class="form-control" value="{{ old('nik_ayah', $data->nik_ayah) }}">
                    </div>

                    <div class="mb-3"><label>Tahun Lahir Ayah</label>
                        <input type="number" name="tahun_lahir_ayah" class="form-control" value="{{ old('tahun_lahir_ayah', $data->tahun_lahir_ayah) }}">
                    </div>

                    <div class="mb-3">
                        <label>Pendidikan Ayah</label>
                        @php
                            $pendidikanList = ['Tidak Sekolah','Putus SD','SD Sederajat','SMP Sederajat','SMA Sederajat','D1','D2','D3','S1','S2','S3'];
                        @endphp
                        <select name="pendidikan_ayah" class="form-control">
                            <option value="">Pilih Pendidikan</option>
                            @foreach($pendidikanList as $p)
                                <option value="{{ $p }}" {{ old('pendidikan_ayah', $data->pendidikan_ayah) == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Pekerjaan Ayah</label>
                        @php
                            $pekerjaanList = ['Tidak Bekerja','Nelayan','Petani','Peternak','PNS/TNI/POLRI','Karyawan Swasta','Pedagang Kecil','Pedagang Besar','Wiraswasta','Wirausaha','Buruh','Pensiunan'];
                        @endphp
                        <select name="pekerjaan_ayah" class="form-control">
                            <option value="">Pilih Pekerjaan</option>
                            @foreach($pekerjaanList as $p)
                                <option value="{{ $p }}" {{ old('pekerjaan_ayah', $data->pekerjaan_ayah) == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Penghasilan Ayah</label>
                        @php
                            $penghasilanList = ['<500rb','500rb-999rb','1jt-1.9jt','2jt-4.9jt','>=5jt'];
                        @endphp
                        <select name="penghasilan_ayah" class="form-control">
                            <option value="">Pilih Penghasilan</option>
                            @foreach($penghasilanList as $p)
                                <option value="{{ $p }}" {{ old('penghasilan_ayah', $data->penghasilan_ayah) == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3"><label>Kebutuhan Khusus Ayah</label>
                        <input type="text" name="kebutuhan_khusus_ayah" class="form-control" value="{{ old('kebutuhan_khusus_ayah', $data->kebutuhan_khusus_ayah) }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="mt-3 mb-2 text-primary">Data Ibu</h5>

                    <div class="mb-3"><label>Nama Ibu</label>
                        <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $data->nama_ibu) }}">
                    </div>

                    <div class="mb-3"><label>NIK Ibu</label>
                        <input type="text" name="nik_ibu" class="form-control" value="{{ old('nik_ibu', $data->nik_ibu) }}">
                    </div>

                    <div class="mb-3"><label>Tahun Lahir Ibu</label>
                        <input type="number" name="tahun_lahir_ibu" class="form-control" value="{{ old('tahun_lahir_ibu', $data->tahun_lahir_ibu) }}">
                    </div>

                    <div class="mb-3">
                        <label>Pendidikan Ibu</label>
                        <select name="pendidikan_ibu" class="form-control">
                            <option value="">Pilih Pendidikan</option>
                            @foreach($pendidikanList as $p)
                                <option value="{{ $p }}" {{ old('pendidikan_ibu', $data->pendidikan_ibu) == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Pekerjaan Ibu</label>
                        <select name="pekerjaan_ibu" class="form-control">
                            <option value="">Pilih Pekerjaan</option>
                            @foreach($pekerjaanList as $p)
                                <option value="{{ $p }}" {{ old('pekerjaan_ibu', $data->pekerjaan_ibu) == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Penghasilan Ibu</label>
                        <select name="penghasilan_ibu" class="form-control">
                            <option value="">Pilih Penghasilan</option>
                            @foreach($penghasilanList as $p)
                                <option value="{{ $p }}" {{ old('penghasilan_ibu', $data->penghasilan_ibu) == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3"><label>Kebutuhan Khusus Ibu</label>
                        <input type="text" name="kebutuhan_khusus_ibu" class="form-control" value="{{ old('kebutuhan_khusus_ibu', $data->kebutuhan_khusus_ibu) }}">
                    </div>
                </div>

                <div class="col-md-12 mt-4">
                    <h5 class="text-primary">Data Wali</h5>
                    <div class="row">

                        <div class="col-md-4 mb-3"><label>Nama Wali</label>
                            <input type="text" name="nama_wali" class="form-control" value="{{ old('nama_wali', $data->nama_wali) }}">
                        </div>

                        <div class="col-md-4 mb-3"><label>NIK Wali</label>
                            <input type="text" name="nik_wali" class="form-control" value="{{ old('nik_wali', $data->nik_wali) }}">
                        </div>

                        <div class="col-md-4 mb-3"><label>Tahun Lahir Wali</label>
                            <input type="number" name="tahun_lahir_wali" class="form-control" value="{{ old('tahun_lahir_wali', $data->tahun_lahir_wali) }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Pendidikan Wali</label>
                            <select name="pendidikan_wali" class="form-control">
                                <option value="">Pilih Pendidikan</option>
                                @foreach($pendidikanList as $p)
                                    <option value="{{ $p }}" {{ old('pendidikan_wali', $data->pendidikan_wali) == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Pekerjaan Wali</label>
                            <select name="pekerjaan_wali" class="form-control">
                                <option value="">Pilih Pekerjaan</option>
                                @foreach($pekerjaanList as $p)
                                    <option value="{{ $p }}" {{ old('pekerjaan_wali', $data->pekerjaan_wali) == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Penghasilan Wali</label>
                            <select name="penghasilan_wali" class="form-control">
                                <option value="">Pilih Penghasilan</option>
                                @foreach($penghasilanList as $p)
                                    <option value="{{ $p }}" {{ old('penghasilan_wali', $data->penghasilan_wali) == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-start mt-3">
                <a href="{{ route($prefix.'orang-tua.index') }}" class="btn btn-secondary me-2">Kembali</a>
                <button type="submit" class="btn btn-success">
                    {{ $isEdit ? 'Perbarui' : 'Simpan' }}
                </button>
            </div>

        </form>
    </div>
</div>

@endsection
