@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Kompetensi Khusus PTK</h1>

    <form action="{{ route('kompetensi-khusus-ptk.update', $kompetensiKhusus->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    <input type="text" class="form-control" value="{{ $ptk->nama_lengkap ?? $kompetensi->ptk->nama_lengkap }}" readonly>
                    <input type="hidden" name="ptk_id" value="{{ $ptk->id ?? $kompetensi->ptk_id }}">
                </div>

                <div class="mb-3">
                    <label>Punya Lisensi Kepala Sekolah</label>
                    <select name="punya_lisensi_kepala_sekolah" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="1" {{ $kompetensiKhusus->punya_lisensi_kepala_sekolah == 1 ? 'selected' : '' }}>Ya</option>
                        <option value="0" {{ $kompetensiKhusus->punya_lisensi_kepala_sekolah == 0 ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Nomor Unik Kepala Sekolah</label>
                    <input type="text" name="nomor_unik_kepala_sekolah" class="form-control"
                    value="{{ $kompetensiKhusus->nomor_unik_kepala_sekolah }}">
                </div>
                <div class="mb-3">
                    <label>Keahlian Lab/Oratorium</label>
                    <input type="text" name="keahlian_lab_oratorium" class="form-control"
                    value="{{ $kompetensiKhusus->keahlian_lab_oratorium }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Mampu Menangani Peserta Didik Berkebutuhan Khusus</label>
                    <select name="mampu_menangani" class="form-control">
                        <option value="">Pilih Kebutuhan Khusus</option>
                        @php
                            $options = [
                                'Tidak', 'Netra (A)', 'Rungu (B)', 'Grahita Sedang (C1)', 'Grahita Ringan (D)',
                                'Daksa Sedang (D1)', 'Laras', 'Daksa Ringan', 'Wicara', 'Tuna Ganda',
                                'Hiper Aktif (H)', 'Cerdas Istimewa (I)', 'Bakat Istimewa (J)',
                                'Kesulitan Belajar (K)', 'Narkoba (N)', 'Indigo (O)', 'Down Sindrome (P)', 'Autis (Q)'
                            ];
                        @endphp
                        @foreach($options as $option)
                            <option value="{{ $option }}" {{ $kompetensiKhusus->mampu_menangani == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Keahlian Braile</label>
                    <select name="keahlian_braile" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="1" {{ $kompetensiKhusus->keahlian_braile == 1 ? 'selected' : '' }}>Ya</option>
                        <option value="0" {{ $kompetensiKhusus->keahlian_braile == 0 ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Keahlian Bahasa Isyarat</label>
                    <select name="keahlian_bahasa_isyarat" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="1" {{ $kompetensiKhusus->keahlian_bahasa_isyarat == 1 ? 'selected' : '' }}>Ya</option>
                        <option value="0" {{ $kompetensiKhusus->keahlian_bahasa_isyarat == 0 ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('kompetensi-khusus-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>
@endsection
