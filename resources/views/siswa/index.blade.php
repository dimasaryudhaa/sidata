@extends('layouts.app')

@section('content')

<style>
    ::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }

    html {
        scrollbar-width: none;
    }

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

    .btn-no-border:hover,
    .btn-no-border:focus,
    .btn-no-border:active {
        background: transparent !important;
        box-shadow: none !important;
    }
</style>

@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    $isSiswa = $user->role === 'siswa';
    $isPtk   = $user->role === 'ptk';

    if ($isAdmin) {
        $prefix = 'admin.';
    } elseif ($isSiswa) {
        $prefix = 'siswa.';
    } elseif ($isPtk) {
        $prefix = 'ptk.';
    } else {
        $prefix = '';
    }
@endphp


<div class="container">

    @if(auth()->user()->role === 'admin')
        <div class="mb-3 d-flex flex-wrap gap-2">
            <a href="{{ route('admin.siswa.index') }}" class="btn btn-primary">Siswa</a>
            <a href="{{ route('admin.akun-siswa.index') }}" class="btn btn-primary">Akun</a>
            <a href="{{ route('admin.dokumen-siswa.index') }}" class="btn btn-primary">Dokumen</a>
            <a href="{{ route('admin.periodik.index') }}" class="btn btn-primary">Periodik</a>
            <a href="{{ route('admin.beasiswa.index') }}" class="btn btn-primary">Beasiswa</a>
            <a href="{{ route('admin.prestasi.index') }}" class="btn btn-primary">Prestasi</a>
            <a href="{{ route('admin.orang-tua.index') }}" class="btn btn-primary">Orang Tua</a>
            <a href="{{ route('admin.registrasi-siswa.index') }}" class="btn btn-primary">Registrasi</a>
            <a href="{{ route('admin.kesejahteraan-siswa.index') }}" class="btn btn-primary">Kesejahteraan</a>
            <a href="{{ route('admin.kontak-siswa.index') }}" class="btn btn-primary">Kontak & Alamat</a>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">

            <a href="{{ route('admin.siswa.create') }}" class="btn btn-sm btn-no-border">
                <img src="{{ asset('images/tambah.png') }}" style="width:50px;height:50px;">
            </a>

            <div class="d-flex align-items-center" style="gap:0.5rem;">
                <input type="text" id="search" class="form-control form-control-sm"
                    placeholder="Cari Nama Siswa..." style="max-width:200px;">

                <select id="rombelFilter" class="form-control form-control-sm" style="max-width:200px;">
                    <option value="">Semua Rombel</option>
                    @foreach($rombels as $rombel)
                        <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered" id="siswaTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Siswa</th>
                        <th>Jenis Kelamin</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Rayon</th>
                        <th>Rombel</th>
                        <th>Status</th>
                        <th>Validasi</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($siswa as $index => $s)
                        <tr data-rombel="{{ $s->rombel_id }}">
                            <td>{{ $siswa->firstItem() + $index }}</td>
                            <td class="nama_siswa">{{ $s->nama_lengkap ?? '-' }}</td>
                            <td>{{ $s->jenis_kelamin ?? '-' }}</td>
                            <td>{{ $s->nis ?? '-' }}</td>
                            <td>{{ $s->nisn ?? '-' }}</td>
                            <td>{{ $s->nama_rayon ?? '-' }}</td>
                            <td>{{ $s->nama_rombel ?? '-' }}</td>
                            @php
                                $lengkap = $s->nama_lengkap && $s->jenis_kelamin && $s->nis && $s->nisn && $s->nik
                                && $s->tempat_lahir && $s->tanggal_lahir && $s->agama && $s->nama_rayon && $s->nama_rombel
                                && $s->kewarganegaraan;
                            @endphp
                            <td>
                                <span class="badge status-label"
                                    data-id="{{ $s->id }}"
                                    data-filled="{{ $lengkap ? 'yes' : 'no' }}">
                                    {{ $lengkap ? 'Sudah Mengisi' : 'Belum Mengisi' }}
                                </span>
                            </td>
                            <td>
                                <div class="validate-container"
                                    data-id="{{ $s->id }}"
                                    data-filled="{{ $lengkap ? 'yes' : 'no' }}">
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.siswa.edit', $s->id) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" style="width:20px;height:20px;">
                                </a>
                                <form action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-no-border"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <img src="{{ asset('images/delete.png') }}" style="width:20px;height:20px;">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

            <script>
                document.addEventListener("DOMContentLoaded", () => {

                    document.querySelectorAll(".validate-container").forEach(box => {
                        let id     = box.dataset.id;
                        let filled = box.dataset.filled === "yes";
                        let saved  = localStorage.getItem("validated-doc-" + id);

                        if (saved === "valid") {
                            setValidated(id, true);
                        } else {
                            setInitialStatus(id, filled);
                        }
                    });

                });

                function setInitialStatus(id, filled) {
                    let badge = document.querySelector(`.status-label[data-id="${id}"]`);
                    let box   = document.querySelector(`.validate-container[data-id="${id}"]`);

                    badge.classList.remove("bg-success", "bg-danger", "bg-primary");
                    box.innerHTML = "";

                    if (filled) {
                        badge.innerText = "Sudah Mengisi";
                        badge.classList.add("bg-success");

                        box.innerHTML = `
                            <button class="btn btn-sm btn-primary"
                                onclick="toggleStatus(${id})">
                                Validasi
                            </button>`;
                    } else {
                        badge.innerText = "Belum Mengisi";
                        badge.classList.add("bg-danger");
                    }
                }

                function setValidated(id, isValid) {
                    let badge = document.querySelector(`.status-label[data-id="${id}"]`);
                    let box   = document.querySelector(`.validate-container[data-id="${id}"]`);

                    badge.classList.remove("bg-success", "bg-danger", "bg-primary");
                    box.innerHTML = "";

                    if (isValid) {
                        badge.innerText = "Di Validasi";
                        badge.classList.add("bg-primary");

                        box.innerHTML = `
                            <button class="btn btn-sm btn-warning"
                                onclick="toggleStatus(${id})">
                                Batalkan
                            </button>`;
                    } else {
                        let filled = badge.dataset.filled === "yes";
                        setInitialStatus(id, filled);
                    }
                }

                function toggleStatus(id) {
                    let key = "validated-doc-" + id;

                    if (localStorage.getItem(key) === "valid") {
                        localStorage.removeItem(key);
                        setValidated(id, false);
                    } else {
                        localStorage.setItem(key, "valid");
                        setValidated(id, true);
                    }
                }
            </script>

        </div>
    @endif

    @if(auth()->user()->role === 'siswa')
        <div class="mb-3 d-flex flex-wrap gap-2">
            <a href="{{ route('siswa.siswa.index') }}" class="btn btn-primary">Siswa</a>
            <a href="{{ route('siswa.akun-siswa.index') }}" class="btn btn-primary">Akun</a>
            <a href="{{ route('siswa.dokumen-siswa.index') }}" class="btn btn-primary">Dokumen</a>
            <a href="{{ route('siswa.periodik.index') }}" class="btn btn-primary">Periodik</a>
            <a href="{{ route('siswa.beasiswa.index') }}" class="btn btn-primary">Beasiswa</a>
            <a href="{{ route('siswa.prestasi.index') }}" class="btn btn-primary">Prestasi</a>
            <a href="{{ route('siswa.orang-tua.index') }}" class="btn btn-primary">Orang Tua</a>
            <a href="{{ route('siswa.registrasi-siswa.index') }}" class="btn btn-primary">Registrasi</a>
            <a href="{{ route('siswa.kesejahteraan-siswa.index') }}" class="btn btn-primary">Kesejahteraan</a>
            <a href="{{ route('siswa.kontak-siswa.index') }}" class="btn btn-primary">Kontak & Alamat</a>
        </div>

        @php
            $detail = $siswa->first();
            $lengkap = $detail
                && $detail->nama_lengkap
                && $detail->jenis_kelamin
                && $detail->nis
                && $detail->nisn
                && $detail->nik
                && $detail->tempat_lahir
                && $detail->tanggal_lahir
                && $detail->agama
                && $detail->nama_rayon
                && $detail->nama_rombel
                && $detail->kewarganegaraan;
        @endphp

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('siswa.siswa.edit', $detail->id) }}"
                id="btn-edit-siswa"
                class="btn btn-primary px-4"
                style="background: linear-gradient(180deg,#0770d3,#007efd,#55a6f8); border-radius:6px;">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
            <span class="badge status-label"
                data-id="{{ $detail->id }}"
                data-filled="{{ $lengkap ? 'yes' : 'no' }}">
                {{ $lengkap ? 'Sudah Mengisi' : 'Belum Mengisi' }}
            </span>
        </div>

        <div class="table-responsive rounded-3 overflow-hidden">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Data Siswa</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Identitas Diri</td>
                        <td>
                            <strong>Nama:</strong> {{ $detail->nama_lengkap ?? '-' }}<br>
                            <strong>Jenis Kelamin:</strong> {{ $detail->jenis_kelamin ?? '-' }}<br>
                            <strong>NIS:</strong> {{ $detail->nis ?? '-' }}<br>
                            <strong>NISN:</strong> {{ $detail->nisn ?? '-' }}
                        </td>
                    </tr>

                    <tr>
                        <td>Tempat & Tanggal Lahir</td>
                        <td>
                            <strong>Tempat Lahir:</strong> {{ $detail->tempat_lahir ?? '-' }}<br>
                            <strong>Tanggal Lahir:</strong> {{ $detail->tanggal_lahir ?? '-' }}<br>
                            <strong>NIK</strong> {{ $detail->nik ?? '-' }}
                        </td>
                    </tr>

                    <tr>
                        <td>Data Sekolah</td>
                        <td>
                            <strong>Rayon:</strong> {{ $detail->nama_rayon ?? '-' }}<br>
                            <strong>Rombel:</strong> {{ $detail->nama_rombel ?? '-' }}<br>
                            <strong>Agama:</strong> {{ $detail->agama ?? '-' }}
                        </td>
                    </tr>

                    <tr>
                        <td>Kewarganegaraan</td>
                        <td>
                            <strong>Kewarganegaraan:</strong> {{ $detail->kewarganegaraan ?? '-' }}<br>
                            <strong>Negara Asal:</strong> {{ $detail->negara_asal ?? '-' }}
                        </td>
                    </tr>

                    <tr>
                        <td>Berkebutuhan Khusus</td>
                        <td>{{ $detail->berkebutuhan_khusus ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
            <script>
                document.addEventListener("DOMContentLoaded", () => {

                    document.querySelectorAll(".status-label").forEach(badge => {
                        let id     = badge.dataset.id;
                        let filled = badge.dataset.filled === "yes";
                        let saved  = localStorage.getItem("validated-doc-" + id);

                        let btnEdit = document.getElementById("btn-edit-siswa");

                        badge.classList.remove("bg-success", "bg-danger", "bg-primary");

                        if (saved === "valid") {
                            badge.innerText = "Di Validasi";
                            badge.classList.add("bg-primary");

                            if (btnEdit) btnEdit.classList.remove("d-none");
                        }

                        else if (filled) {
                            badge.innerText = "Sudah Mengisi";
                            badge.classList.add("bg-success");

                            if (btnEdit) btnEdit.classList.add("d-none");
                        }

                        else {
                            badge.innerText = "Belum Mengisi";
                            badge.classList.add("bg-danger");

                            if (btnEdit) btnEdit.classList.remove("d-none");
                        }
                    });
                });
            </script>
        </div>
    @endif

    @if(auth()->user()->role === 'ptk')

        <div class="mb-3 d-flex flex-wrap gap-2">
            <a href="{{ route('ptk.siswa.index') }}" class="btn btn-primary">Data Siswa</a>
            <a href="{{ route('ptk.dokumen-siswa.index') }}" class="btn btn-primary">Dokumen</a>
            <a href="{{ route('ptk.orang-tua.index') }}" class="btn btn-primary">Orang Tua</a>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div class="d-flex align-items-center" style="gap:0.5rem;">
                <input type="text" id="search" class="form-control form-control-sm"
                    placeholder="Cari Nama Siswa..." style="max-width:200px;">

                <select id="rombelFilter" class="form-control form-control-sm" style="max-width:200px;">
                    <option value="">Semua Rombel</option>
                    @foreach($rombels as $rombel)
                        <option value="{{ $rombel->id }}">{{ $rombel->nama_rombel }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive rounded-3 overflow-hidden mt-3">
            <table class="table table-bordered" id="siswaTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Siswa</th>
                        <th>Jenis Kelamin</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Rayon</th>
                        <th>Rombel</th>
                        <th>Status</th>
                        <th>Validasi</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($siswa as $index => $s)
                        <tr data-rombel="{{ $s->rombel_id }}">
                            <td>{{ $siswa->firstItem() + $index }}</td>
                            <td class="nama_siswa">{{ $s->nama_lengkap ?? '-' }}</td>
                            <td>{{ $s->jenis_kelamin ?? '-' }}</td>
                            <td>{{ $s->nis ?? '-' }}</td>
                            <td>{{ $s->nisn ?? '-' }}</td>
                            <td>{{ $s->nama_rayon ?? '-' }}</td>
                            <td>{{ $s->nama_rombel ?? '-' }}</td>
                            @php
                                $lengkap = $s->nama_lengkap && $s->jenis_kelamin && $s->nis && $s->nisn && $s->nik
                                && $s->tempat_lahir && $s->tanggal_lahir && $s->agama && $s->nama_rayon && $s->nama_rombel
                                && $s->kewarganegaraan;
                            @endphp
                            <td>
                                <span class="badge status-label"
                                    data-id="{{ $s->id }}"
                                    data-filled="{{ $lengkap ? 'yes' : 'no' }}">
                                    {{ $lengkap ? 'Sudah Mengisi' : 'Belum Mengisi' }}
                                </span>
                            </td>
                            <td>
                                <div class="validate-container"
                                    data-id="{{ $s->id }}"
                                    data-filled="{{ $lengkap ? 'yes' : 'no' }}">
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('ptk.siswa.edit', $s->id) }}" class="btn btn-sm btn-no-border">
                                    <img src="{{ asset('images/edit.png') }}" style="width:20px;height:20px;">
                                </a>
                                <form action="{{ route('ptk.siswa.destroy', $s->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-no-border"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <img src="{{ asset('images/delete.png') }}" style="width:20px;height:20px;">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

            <script>
                document.addEventListener("DOMContentLoaded", () => {

                    document.querySelectorAll(".validate-container").forEach(box => {
                        let id     = box.dataset.id;
                        let filled = box.dataset.filled === "yes";
                        let saved  = localStorage.getItem("validated-doc-" + id);

                        if (saved === "valid") {
                            setValidated(id, true);
                        } else {
                            setInitialStatus(id, filled);
                        }
                    });

                });

                function setInitialStatus(id, filled) {
                    let badge = document.querySelector(`.status-label[data-id="${id}"]`);
                    let box   = document.querySelector(`.validate-container[data-id="${id}"]`);

                    badge.classList.remove("bg-success", "bg-danger", "bg-primary");
                    box.innerHTML = "";

                    if (filled) {
                        badge.innerText = "Sudah Mengisi";
                        badge.classList.add("bg-success");

                        box.innerHTML = `
                            <button class="btn btn-sm btn-primary"
                                onclick="toggleStatus(${id})">
                                Validasi
                            </button>`;
                    } else {
                        badge.innerText = "Belum Mengisi";
                        badge.classList.add("bg-danger");
                    }
                }

                function setValidated(id, isValid) {
                    let badge = document.querySelector(`.status-label[data-id="${id}"]`);
                    let box   = document.querySelector(`.validate-container[data-id="${id}"]`);

                    badge.classList.remove("bg-success", "bg-danger", "bg-primary");
                    box.innerHTML = "";

                    if (isValid) {
                        badge.innerText = "Di Validasi";
                        badge.classList.add("bg-primary");

                        box.innerHTML = `
                            <button class="btn btn-sm btn-warning"
                                onclick="toggleStatus(${id})">
                                Batalkan
                            </button>`;
                    } else {
                        let filled = badge.dataset.filled === "yes";
                        setInitialStatus(id, filled);
                    }
                }

                function toggleStatus(id) {
                    let key = "validated-doc-" + id;

                    if (localStorage.getItem(key) === "valid") {
                        localStorage.removeItem(key);
                        setValidated(id, false);
                    } else {
                        localStorage.setItem(key, "valid");
                        setValidated(id, true);
                    }
                }
            </script>

        </div>

    @endif

    @if(session('success'))
        <div id="successAlert"
            class="position-fixed top-50 start-50 translate-middle bg-white text-center p-4 rounded shadow-lg border"
            style="z-index:1050; min-width:320px;">
            <div class="d-flex justify-content-center mb-3">
                <div class="d-flex justify-content-center align-items-center"
                    style="width:80px; height:80px; background-color:#d4edda; border-radius:50%;">
                    <i class="bi bi-check-lg text-success" style="font-size:2.5rem;"></i>
                </div>
            </div>
            <h5 class="fw-bold mb-1">Success</h5>
            <p class="text-muted mb-0">{{ session('success') }}</p>
        </div>

        <script>
            setTimeout(() => {
                const alertBox = document.getElementById('successAlert');
                if (alertBox) {
                    alertBox.style.transition = 'opacity 0.5s ease';
                    alertBox.style.opacity = '0';
                    setTimeout(() => alertBox.remove(), 500);
                }
            }, 2000);
        </script>
    @endif

    <div class="mt-3">
        {{ $siswa->links('pagination::bootstrap-5') }}
    </div>

</div>

@if(!$isSiswa)
<script>
    const searchInput = document.getElementById('search');
    const rombelSelect = document.getElementById('rombelFilter');
    const rows = document.querySelectorAll('#siswaTable tbody tr');

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const rombelValue = rombelSelect.value;

        rows.forEach(row => {
            let nama = row.querySelector('.nama_siswa').textContent.toLowerCase();
            let rombel = row.getAttribute('data-rombel');

            row.style.display =
                (nama.includes(searchValue) &&
                (rombelValue === '' || rombel === rombelValue))
                ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterTable);
    rombelSelect.addEventListener('change', filterTable);
</script>
@endif

@endsection
