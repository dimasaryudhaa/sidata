@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Riwayat Karir PTK</h1>

    <form action="{{ route('riwayat-karir.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>Nama PTK</label>
                    @if(isset($ptkId))
                        <input type="text" class="form-control" value="{{ $ptk->nama_lengkap }}" readonly>
                        <input type="hidden" name="ptk_id" value="{{ $ptkId }}">
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
                    <input type="text" name="jenjang_pendidikan" class="form-control" placeholder="Contoh: S1, S2, D3" required>
                </div>

                <div class="mb-3">
                    <label>Jenis Lembaga</label>
                    <input type="text" name="jenis_lembaga" class="form-control" placeholder="Contoh: Sekolah Negeri, Swasta" required>
                </div>

                <div class="mb-3">
                    <label>Status Kepegawaian</label>
                    <select name="status_kepegawaian" class="form-control" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="PNS">PNS</option>
                        <option value="Honorer">Honorer</option>
                        <option value="GTT/PTT">GTT/PTT</option>
                        <option value="Lainnya">Lainnya...</option>
                    </select>
                    <input
                        type="text"
                        name="status_kepegawaian_lainnya"
                        id="status_kepegawaian_lainnya"
                        class="form-control mt-2"
                        placeholder="Tuliskan status kepegawaian lainnya..."
                        style="display:none;">
                </div>

                <div class="mb-3">
                    <label>Jenis PTK</label>
                    <input type="text" name="jenis_ptk" class="form-control" placeholder="Contoh: Guru Mapel, Kepala Sekolah" required>
                </div>

                <div class="mb-3">
                    <label>Lembaga Pengangkat</label>
                    <input type="text" name="lembaga_pengangkat" class="form-control" placeholder="Contoh: Dinas Pendidikan, Yayasan" required>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label>No SK Kerja</label>
                    <input type="text" name="no_sk_kerja" class="form-control" placeholder="Nomor SK Kerja" required>
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
                    <input type="text" name="tempat_kerja" class="form-control" placeholder="Contoh: SDN 1 Sukamaju" required>
                </div>

                <div class="mb-3">
                    <label>TTD SK Kerja</label>
                    <input type="text" name="ttd_sk_kerja" class="form-control" placeholder="Nama penandatangan SK" required>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('riwayat-karir.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection


<script>
    const statusSelect = document.getElementById('status_kepegawaian_lainnya')
        ? document.querySelector('select[name="status_kepegawaian"]')
        : null;
    const statusInput = document.getElementById('status_kepegawaian_lainnya');

    if (statusSelect && statusInput) {
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
    }
</script>
