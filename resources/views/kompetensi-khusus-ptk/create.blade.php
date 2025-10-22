@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Kompetensi Khusus PTK</h1>

    <form action="{{ route('kompetensi-khusus-ptk.store') }}" method="POST">
        @csrf
        <div class="row">
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
                    <label>Punya Lisensi Kepala Sekolah</label>
                    <select name="punya_lisensi_kepala_sekolah" class="form-control" required>
                        <option value="">Pilih</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Nomor Unik Kepala Sekolah</label>
                    <input type="text" name="nomor_unik_kepala_sekolah" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Keahlian Lab/Oratorium</label>
                    <input type="text" name="keahlian_lab_oratorium" class="form-control">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label>Mampu Menangani Peserta Didik Berkebutuhan Khusus</label>
                    <select name="mampu_menangani" id="mampu_menangani_select" class="form-control">
                        <option value="">Pilih Kebutuhan Khusus</option>
                        <option value="Tidak">Tidak</option>
                        <option value="Netra (A)">Netra (A)</option>
                        <option value="Rungu (B)">Rungu (B)</option>
                        <option value="Grahita Sedang (C1)">Grahita Sedang (C1)</option>
                        <option value="Grahita Ringan (D)">Grahita Ringan (D)</option>
                        <option value="Daksa Sedang (D1)">Daksa Sedang (D1)</option>
                        <option value="Laras">Laras</option>
                        <option value="Daksa Ringan">Daksa Ringan</option>
                        <option value="Wicara">Wicara</option>
                        <option value="Tuna Ganda">Tuna Ganda</option>
                        <option value="Hiper Aktif (H)">Hiper Aktif (H)</option>
                        <option value="Cerdas Istimewa (I)">Cerdas Istimewa (I)</option>
                        <option value="Bakat Istimewa (J)">Bakat Istimewa (J)</option>
                        <option value="Kesulitan Belajar (K)">Kesulitan Belajar (K)</option>
                        <option value="Narkoba (N)">Narkoba (N)</option>
                        <option value="Indigo (O)">Indigo (O)</option>
                        <option value="Down Sindrome (P)">Down Sindrome (P)</option>
                        <option value="Autis (Q)">Autis (Q)</option>
                        <option value="Lainnya">Lainnya...</option>
                    </select>
                    <input
                        type="text"
                        name="mampu_menangani_lainnya"
                        id="mampu_menangani_lainnya"
                        class="form-control mt-2"
                        placeholder="Tuliskan kebutuhan khusus lainnya..."
                        style="display:none;">
                </div>

                <div class="mb-3">
                    <label>Keahlian Braile</label>
                    <select name="keahlian_braile" class="form-control" required>
                        <option value="">Pilih</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Keahlian Bahasa Isyarat</label>
                    <select name="keahlian_bahasa_isyarat" class="form-control" required>
                        <option value="">Pilih</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <a href="{{ route('kompetensi-khusus-ptk.index') }}" class="btn btn-secondary me-2">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>
@endsection

<script>
    const selectEl = document.getElementById('mampu_menangani_select');
    const inputEl = document.getElementById('mampu_menangani_lainnya');

    selectEl.addEventListener('change', function() {
        if (this.value === 'Lainnya') {
            inputEl.style.display = 'block';
            inputEl.required = true;
        } else {
            inputEl.style.display = 'none';
            inputEl.required = false;
            inputEl.value = '';
        }
    });
</script>
