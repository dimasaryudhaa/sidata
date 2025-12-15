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


                                <div class="mt-2 d-none validate-box" id="box-{{ $s->id }}">
                                    <button class="btn btn-sm btn-primary" onclick="toggleStatus({{ $s->id }})">Validasi</button>
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
                    document.querySelectorAll(".status-label").forEach(el => {
                        let id = el.dataset.id;
                        let saved = localStorage.getItem("validated-" + id);

                        if (saved === "valid") {
                            setValidated(id, true);
                        } else {
                            let filled = el.dataset.filled === "yes";
                            setInitialStatus(id, filled);
                        }
                    });

                    document.querySelectorAll(".status-label").forEach(el => {
                        el.addEventListener("click", function () {
                            let id = this.dataset.id;

                            if (this.dataset.filled === "no" && localStorage.getItem("validated-" + id) !== "valid") {
                                return;
                            }

                            let box = document.getElementById("box-" + id);
                            if (box) box.classList.toggle("d-none");
                        });
                    });
                });

                function setInitialStatus(id, filled) {
                    let badge = document.querySelector(`[data-id="${id}"]`);
                    badge.classList.remove("bg-success", "bg-danger", "bg-primary");

                    if (filled) {
                        badge.innerText = "Sudah Mengisi";
                        badge.classList.add("bg-success");
                        badge.style.cursor = "pointer";
                    } else {
                        badge.innerText = "Belum Mengisi";
                        badge.classList.add("bg-danger");
                        badge.style.cursor = "default";
                    }
                }

                function setValidated(id, isValid) {
                    let badge = document.querySelector(`[data-id="${id}"]`);
                    let box = document.getElementById("box-" + id);

                    badge.classList.remove("bg-danger", "bg-success", "bg-primary");

                    if (isValid) {
                        badge.innerText = "Di Validasi";
                        badge.classList.add("bg-primary");
                        badge.style.cursor = "pointer";
                        box.innerHTML = `<button class="btn btn-sm btn-warning" onclick="toggleStatus(${id})">Batalkan Validasi</button>`;
                    } else {
                        let filled = badge.dataset.filled === "yes";
                        setInitialStatus(id, filled);
                        box.innerHTML = `<button class="btn btn-sm btn-primary" onclick="toggleStatus(${id})">Validasi</button>`;
                    }
                }

                function toggleStatus(id) {
                    let current = localStorage.getItem("validated-" + id);

                    if (current === "valid") {
                        localStorage.removeItem("validated-" + id);
                        setValidated(id, false);
                    } else {
                        localStorage.setItem("validated-" + id, "valid");
                        setValidated(id, true);
                    }

                    document.getElementById("box-" + id).classList.add("d-none");
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

        @php $detail = $siswa->first(); @endphp


        <div class="d-flex mb-3">
            <a href="{{ route('siswa.siswa.edit', $detail->id) }}"
                class="btn btn-primary px-4"
                style="background: linear-gradient(180deg,#0770d3,#007efd,#55a6f8); border-radius:6px;">
                <i class="bi bi-pencil-square me-2"></i> Edit
            </a>
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


                                <div class="mt-2 d-none validate-box" id="box-{{ $s->id }}">
                                    <button class="btn btn-sm btn-primary" onclick="toggleStatus({{ $s->id }})">Validasi</button>
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
                    document.querySelectorAll(".status-label").forEach(el => {
                        let id = el.dataset.id;
                        let saved = localStorage.getItem("validated-" + id);

                        if (saved === "valid") {
                            setValidated(id, true);
                        } else {
                            let filled = el.dataset.filled === "yes";
                            setInitialStatus(id, filled);
                        }
                    });

                    document.querySelectorAll(".status-label").forEach(el => {
                        el.addEventListener("click", function () {
                            let id = this.dataset.id;

                            if (this.dataset.filled === "no" && localStorage.getItem("validated-" + id) !== "valid") {
                                return;
                            }

                            let box = document.getElementById("box-" + id);
                            if (box) box.classList.toggle("d-none");
                        });
                    });
                });

                function setInitialStatus(id, filled) {
                    let badge = document.querySelector(`[data-id="${id}"]`);
                    badge.classList.remove("bg-success", "bg-danger", "bg-primary");

                    if (filled) {
                        badge.innerText = "Sudah Mengisi";
                        badge.classList.add("bg-success");
                        badge.style.cursor = "pointer";
                    } else {
                        badge.innerText = "Belum Mengisi";
                        badge.classList.add("bg-danger");
                        badge.style.cursor = "default";
                    }
                }

                function setValidated(id, isValid) {
                    let badge = document.querySelector(`[data-id="${id}"]`);
                    let box = document.getElementById("box-" + id);

                    badge.classList.remove("bg-danger", "bg-success", "bg-primary");

                    if (isValid) {
                        badge.innerText = "Di Validasi";
                        badge.classList.add("bg-primary");
                        badge.style.cursor = "pointer";
                        box.innerHTML = `<button class="btn btn-sm btn-warning" onclick="toggleStatus(${id})">Batalkan Validasi</button>`;
                    } else {
                        let filled = badge.dataset.filled === "yes";
                        setInitialStatus(id, filled);
                        box.innerHTML = `<button class="btn btn-sm btn-primary" onclick="toggleStatus(${id})">Validasi</button>`;
                    }
                }

                function toggleStatus(id) {
                    let current = localStorage.getItem("validated-" + id);

                    if (current === "valid") {
                        localStorage.removeItem("validated-" + id);
                        setValidated(id, false);
                    } else {
                        localStorage.setItem("validated-" + id, "valid");
                        setValidated(id, true);
                    }

                    document.getElementById("box-" + id).classList.add("d-none");
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
