@extends('layouts.app')

@section('content')

@php
    $user = auth()->user();
    $prefix = $user->role === 'admin' ? 'admin.' : 'ptk.';
@endphp

<div class="container">
    <h1 class="mb-4">Tambah Riwayat Karir PTK</h1>

    <form action="{{ route($prefix.'riwayat-karir.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>

                    @if(isset($ptk))
                        <input type="text" class="form-control" value="{{ $ptk->nama_lengkap }}" readonly>
                        <input type="hidden" name="ptk_id" value="{{ $ptk->id }}">
                    @else
                        <select name="ptk_id" class="form-control" required>
                            <option value="">-- Pilih PTK --</option>
                            @foreach($ptks as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="mb-3">
                    <label>Jenjang Pendidikan</label>
                    <input type="text" name="jenjang_pendidikan" class="form-control"
                           placeholder="Contoh: S1, S2, D3" required>
                </div>

                <div class="mb-3">
                    <label>Jenis Lembaga</label>
                    <input type="text" name="jenis_lembaga" class="form-control"
                           placeholder="Contoh: Sekolah Negeri, Swasta" required>
                </div>

                <div class="mb-3">
                    <label>Status Kepegawaian</label>
                    <select name="status_kepegawaian" class="form-control" id="status_kepegawaian" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="PNS">PNS</option>
                        <option value="Honorer">Honorer</option>
                        <option value="GTT/PTT">GTT/PTT</option>
                        <option value="Lainnya">Lainnya...</option>
                    </select>

                    <input type="text"
                        name="status_kepegawaian_lainnya"
                        id="status_kepegawaian_lainnya"
                        class="form-control mt-2"
                        placeholder="Tuliskan status lainnya..."
                        style="display:none;">
                </div>

                <div class="mb-3">
                    <label>Jenis PTK</label>
                    <input type="text" name="jenis_ptk" class="form-control"
                           placeholder="Contoh: Guru Mapel, Kepala Sekolah" required>
                </div>

                <div class="mb-3">
                    <label>Lembaga Pengangkat</label>
                    <input type="text" name="lembaga_pengangkat" class="form-control"
                           placeholder="Contoh: Dinas Pendidikan, Yayasan" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>No SK Kerja</label>
                    <input type="text" name="no_sk_kerja" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Tanggal SK Kerja</label>
                    <input type="date" name="tgl_sk_kerja" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>TMT Kerja</label>
                    <input type="date" name="tmt_kerja" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>TST Kerja</label>
                    <input type="date" name="tst_kerja" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Tempat Kerja</label>
                    <input type="text" name="tempat_kerja" class="form-control"
                           placeholder="Contoh: SDN 1 Sukamaju" required>
                </div>

                <div class="mb-3">
                    <label>TTD SK Kerja</label>
                    <input type="text" name="ttd_sk_kerja" class="form-control"
                           placeholder="Nama penandatangan SK" required>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route($prefix.'riwayat-karir.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>

@endsection

<script>
    const statusSelect = document.getElementById('status_kepegawaian');
    const statusInput = document.getElementById('status_kepegawaian_lainnya');

    statusSelect.addEventListener('change', function() {
        if (this.value === 'Lainnya') {
            statusInput.style.display = 'block';
            statusInput.required = true;
        } else {
            statusInput.style.display = 'none';
            statusInput.required = false;
            statusInput.value = '';
        }
    });
</script>
