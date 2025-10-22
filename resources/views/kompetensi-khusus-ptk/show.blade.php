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

.btn-no-border {
    border: none !important;
    box-shadow: none !important;
    background: transparent !important;
    padding: 0;
}

.btn-no-border:focus,
.btn-no-border:active,
.btn-no-border:hover {
    border: none !important;
    box-shadow: none !important;
    background: transparent !important;
}
</style>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>{{ $ptk->nama_lengkap }}</h4>
    </div>

    <div class="table-responsive rounded-3 overflow-hidden mt-3">
        <table class="table table-bordered" id="kompetensiKhususPtkTable">
            <thead class="text-white">
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Lisensi Kepala Sekolah</th>
                    <th>NUKS</th>
                    <th>Lab/Oratorium</th>
                    <th>Mampu Menangani</th>
                    <th>Braille</th>
                    <th>Bahasa Isyarat</th>
                    <th style="width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kompetensiKhusus as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->punya_lisensi_kepala_sekolah ? 'Ya' : 'Tidak' }}</td>
                    <td>{{ $item->nomor_unik_kepala_sekolah ?? '-' }}</td>
                    <td>{{ $item->keahlian_lab_oratorium ?? '-' }}</td>
                    <td>{{ $item->mampu_menangani ?? '-' }}</td>
                    <td>{{ $item->keahlian_braile ? 'Ya' : 'Tidak' }}</td>
                    <td>{{ $item->keahlian_bahasa_isyarat ? 'Ya' : 'Tidak' }}</td>
                    <td>
                        <a href="{{ route('kompetensi-khusus-ptk.edit', $item->id) }}" class="btn btn-sm btn-no-border">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit Kompetensi Khusus" style="width:20px; height:20px;">
                        </a>
                        <form action="{{ route('kompetensi-khusus-ptk.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-no-border">
                                <img src="{{ asset('images/delete.png') }}" alt="Hapus Kompetensi Khusus" style="width:20px; height:20px;">
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada data kompetensi khusus untuk PTK ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <a href="{{ route('kompetensi-khusus-ptk.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
</div>
@endsection
