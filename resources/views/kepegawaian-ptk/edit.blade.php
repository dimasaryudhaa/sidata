@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $data->id ? 'Edit Kepegawaian PTK' : 'Tambah Kepegawaian PTK' }}</h1>

    @php
        use Illuminate\Support\Facades\Auth;

        $user = Auth::user();
        $isPtk = $user->role === 'ptk';
        $prefix = $isPtk ? 'ptk.' : 'admin.';
    @endphp

    <form action="{{ $data->id
            ? route($prefix.'kepegawaian-ptk.update', $data->id)
            : route($prefix.'kepegawaian-ptk.store')
        }}" method="POST">

        @csrf
        @if($data->id)
            @method('PUT')
        @endif

        <div class="row">

            <div class="col-md-6">

                <div class="mb-3">
                    <label>Nama PTK</label>

                    @if(!$isPtk)
                        <select name="ptk_id" class="form-control" required>
                            <option value="">Pilih PTK</option>
                            @foreach($ptks as $p)
                                <option value="{{ $p->id }}" {{ $data->ptk_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>

                    @else
                        <input type="text" class="form-control"
                               value="{{ $data->ptk->nama_lengkap ?? $user->name }}" readonly>
                        <input type="hidden" name="ptk_id" value="{{ $data->ptk_id }}">
                    @endif
                </div>

                <div class="mb-3">
                    <label>Status Kepegawaian</label>
                    <select name="status_kepegawaian" class="form-control" required>
                        <option value="">Pilih Status</option>
                        @foreach(['PNS','PNS Diperbantukan','PNS Depag','GTY/PTY','GTT/PTT Propinsi','GTT/PTT Kab/Kota','Guru Bantu Pusat','Guru Honor Sekolah','Tenaga Honor','CPNS','PPPK','PPNPN','Kontrak Kerja WNA'] as $status)
                            <option value="{{ $status }}" {{ $data->status_kepegawaian == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>NIP</label>
                    <input type="text" name="nip" class="form-control" maxlength="18"
                           value="{{ old('nip', $data->nip) }}">
                </div>

                <div class="mb-3">
                    <label>NIY/NIGK</label>
                    <input type="text" name="niy_nigk" class="form-control"
                           value="{{ old('niy_nigk', $data->niy_nigk) }}">
                </div>

                <div class="mb-3">
                    <label>NUPTK</label>
                    <input type="text" name="nuptk" class="form-control"
                           value="{{ old('nuptk', $data->nuptk) }}">
                </div>

                <div class="mb-3">
                    <label>Jenis PTK</label>
                    <select name="jenis_ptk" class="form-control" required>
                        @foreach(['Kepala Sekolah','Guru','Tenaga Kependidikan'] as $jenis)
                            <option value="{{ $jenis }}" {{ $data->jenis_ptk == $jenis ? 'selected' : '' }}>
                                {{ $jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>SK Pengangkatan</label>
                    <input type="text" name="sk_pengangkatan" class="form-control"
                           value="{{ old('sk_pengangkatan', $data->sk_pengangkatan) }}">
                </div>

            </div>

            <div class="col-md-6">

                <div class="mb-3">
                    <label>TMT Pengangkatan</label>
                    <input type="date" name="tmt_pengangkatan" class="form-control"
                           value="{{ old('tmt_pengangkatan', $data->tmt_pengangkatan) }}">
                </div>

                <div class="mb-3">
                    <label>Lembaga Pengangkat</label>
                    <select name="lembaga_pengangkat" class="form-control" required>
                        @foreach(['Pemerintah Pusat','Pemerintah Provinsi','Pemerintah Kab/Kota','Ketua Yayasan','Kepala Sekolah','Komite Sekolah','Lainnya'] as $lembaga)
                            <option value="{{ $lembaga }}" {{ $data->lembaga_pengangkat == $lembaga ? 'selected' : '' }}>
                                {{ $lembaga }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>SK CPNS</label>
                    <input type="text" name="sk_cpns" class="form-control"
                           value="{{ old('sk_cpns', $data->sk_cpns) }}">
                </div>

                <div class="mb-3">
                    <label>TMT PNS</label>
                    <input type="date" name="tmt_pns" class="form-control"
                           value="{{ old('tmt_pns', $data->tmt_pns) }}">
                </div>

                <div class="mb-3">
                    <label>Pangkat/Golongan</label>
                    <input type="text" name="pangkat_golongan" class="form-control"
                           value="{{ old('pangkat_golongan', $data->pangkat_golongan) }}">
                </div>

                <div class="mb-3">
                    <label>Sumber Gaji</label>
                    <select name="sumber_gaji" class="form-control" required>
                        @foreach(['APBN','APBD Provinsi','APBD Kab/Kota','Yayasan','Sekolah','Lembaga Donor','Lainnya'] as $sumber)
                            <option value="{{ $sumber }}" {{ $data->sumber_gaji == $sumber ? 'selected' : '' }}>
                                {{ $sumber }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex align-items-end gap-2">
                    <div class="flex-grow-1">
                        <label>Kartu Pegawai</label>
                        <input type="text" name="kartu_pegawai" class="form-control"
                               value="{{ old('kartu_pegawai', $data->kartu_pegawai) }}">
                    </div>

                    <div class="flex-grow-1">
                        <label>Kartu Keluarga</label>
                        <input type="text" name="kartu_keluarga" class="form-control"
                               value="{{ old('kartu_keluarga', $data->kartu_keluarga) }}">
                    </div>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'kepegawaian-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">
                {{ $data->id ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>

    </form>
</div>
@endsection
