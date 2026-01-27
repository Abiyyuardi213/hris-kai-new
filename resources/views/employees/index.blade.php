@extends('layouts.app')
@section('title', 'Pegawai')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Manajemen Pegawai</h2>
                <p class="text-zinc-500 mt-1">Kelola data kepegawaian dan informasi personal.</p>
            </div>
            <div>
                <a href="{{ route('employees.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors shadow-sm">
                    <i data-lucide="user-plus" class="h-4 w-4"></i>
                    Tambah Pegawai
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="space-y-4">
            <!-- Search and Filter -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-zinc-200">
                <form action="{{ route('employees.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                    <!-- Search -->
                    <div class="relative flex-1 min-w-[240px]">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, NIP, atau NIK..."
                            class="flex h-10 w-full rounded-lg border border-zinc-300 pl-10 pr-3 py-2 text-sm placeholder:text-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                    </div>

                    <!-- Filter Divisi -->
                    <div class="w-full sm:w-48">
                        <select name="divisi_id" onchange="this.form.submit()"
                            class="flex h-10 w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                            <option value="">Semua Divisi</option>
                            @foreach ($divisions as $div)
                                <option value="{{ $div->id }}"
                                    {{ request('divisi_id') == $div->id ? 'selected' : '' }}>
                                    {{ $div->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Kantor -->
                    <div class="w-full sm:w-48">
                        <select name="kantor_id" onchange="this.form.submit()"
                            class="flex h-10 w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                            <option value="">Semua Kantor</option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->id }}"
                                    {{ request('kantor_id') == $office->id ? 'selected' : '' }}>
                                    {{ $office->office_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Status -->
                    <div class="w-full sm:w-48">
                        <select name="status_id" onchange="this.form.submit()"
                            class="flex h-10 w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                            <option value="">Semua Status</option>
                            @foreach ($statuses as $stat)
                                <option value="{{ $stat->id }}"
                                    {{ request('status_id') == $stat->id ? 'selected' : '' }}>
                                    {{ $stat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="w-full sm:w-48">
                        <select name="sort" onchange="this.form.submit()"
                            class="flex h-10 w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A-Z)
                            </option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama (Z-A)
                            </option>
                            <option value="joined_desc" {{ request('sort') == 'joined_desc' ? 'selected' : '' }}>Masuk
                                (Terbaru)</option>
                            <option value="joined_asc" {{ request('sort') == 'joined_asc' ? 'selected' : '' }}>Masuk
                                (Terlama)</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="inline-flex h-10 items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors">
                            Filter
                        </button>
                        @if (request()->anyFilled(['search', 'divisi_id', 'kantor_id', 'status_id', 'sort']) && request('sort') != 'latest')
                            <a href="{{ route('employees.index') }}"
                                class="inline-flex h-10 items-center justify-center rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 transition-colors">
                                Reset
                            </a>
                        @elseif(request('search') || request('divisi_id') || request('kantor_id') || request('status_id'))
                            <a href="{{ route('employees.index') }}"
                                class="inline-flex h-10 items-center justify-center rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table Info -->
            <div class="flex items-center justify-between pb-1">
                <p class="text-sm text-zinc-500">
                    Menampilkan <span class="font-medium text-zinc-900">{{ $employees->firstItem() ?? 0 }}</span> -
                    <span class="font-medium text-zinc-900">{{ $employees->lastItem() ?? 0 }}</span> dari
                    <span class="font-medium text-zinc-900">{{ $employees->total() }}</span> pegawai
                </p>
            </div>

            <!-- Table -->
            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-zinc-50/50 text-zinc-500 border-b border-zinc-200">
                            <tr>
                                <th class="px-6 py-4 font-medium">Pegawai</th>
                                <th class="px-6 py-4 font-medium">Jabatan & Divisi</th>
                                <th class="px-6 py-4 font-medium">Status & Shift</th>
                                <th class="px-6 py-4 font-medium">Kontak</th>
                                <th class="px-6 py-4 font-medium text-right w-[120px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse ($employees as $employee)
                                <tr class="group hover:bg-zinc-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($employee->foto)
                                                <img src="{{ asset('storage/' . $employee->foto) }}" alt=""
                                                    class="h-10 w-10 rounded-full object-cover ring-1 ring-zinc-100">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-500 ring-1 ring-zinc-100">
                                                    <span
                                                        class="text-xs font-semibold">{{ substr($employee->nama_lengkap, 0, 2) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-medium text-zinc-900">{{ $employee->nama_lengkap }}</div>
                                                <div class="text-xs text-zinc-500">{{ $employee->nip }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-zinc-900">{{ $employee->jabatan->name ?? '-' }}</div>
                                        <div class="text-xs text-zinc-500">{{ $employee->divisi->name ?? '-' }}</div>
                                        <div class="text-xs text-zinc-400 mt-0.5">
                                            {{ $employee->kantor->office_name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                            {{ $employee->statusPegawai->name ?? '-' }}
                                        </span>
                                        <div class="text-xs text-zinc-500 mt-1">
                                            Shift: {{ $employee->shift->name ?? 'Default' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-zinc-500">
                                        <div class="flex items-center gap-1.5 text-xs">
                                            <i data-lucide="phone" class="h-3 w-3"></i>
                                            {{ $employee->no_hp ?? '-' }}
                                        </div>
                                        @if ($employee->user && $employee->user->email)
                                            <div class="flex items-center gap-1.5 text-xs mt-1">
                                                <i data-lucide="mail" class="h-3 w-3"></i>
                                                {{ $employee->user->email }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div
                                            class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="showDetails({{ json_encode($employee) }})"
                                                class="p-2 text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 rounded-lg transition-colors"
                                                title="Detail">
                                                <i data-lucide="eye" class="h-4 w-4"></i>
                                            </button>
                                            <a href="{{ route('employees.edit', $employee->id) }}"
                                                class="p-2 text-zinc-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="Edit">
                                                <i data-lucide="edit-2" class="h-4 w-4"></i>
                                            </a>
                                            <button
                                                onclick="confirmDelete('{{ $employee->id }}', '{{ $employee->nama_lengkap }}')"
                                                class="p-2 text-zinc-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-zinc-500">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <div class="p-4 rounded-full bg-zinc-50 border border-zinc-100">
                                                <i data-lucide="users-2" class="h-8 w-8 text-zinc-300"></i>
                                            </div>
                                            <div class="text-center">
                                                <p class="font-medium text-zinc-900">Belum ada data pegawai</p>
                                                <p class="text-sm mt-1">Mulai dengan menambahkan pegawai baru.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($employees->hasPages())
                    <div class="p-4 border-t border-zinc-200 bg-zinc-50/50">
                        {{ $employees->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm"
            onclick="closeModal('detailsModal')">
        </div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-zinc-200">
                    <div class="bg-white px-6 py-4 border-b border-zinc-100 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-zinc-900">Detail Pegawai</h3>
                        <button onclick="closeModal('detailsModal')"
                            class="text-zinc-400 hover:text-zinc-600 transition-colors">
                            <i data-lucide="x" class="h-5 w-5"></i>
                        </button>
                    </div>
                    <div class="bg-white px-6 py-6 overflow-y-auto max-h-[80vh]">
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-48 flex-shrink-0">
                                <div class="aspect-square rounded-xl bg-zinc-100 border border-zinc-200 overflow-hidden"
                                    id="detailFotoContainer">
                                    <img id="detailFoto" src="" alt=""
                                        class="w-full h-full object-cover hidden">
                                    <div id="detailFotoPlaceholder"
                                        class="w-full h-full flex flex-col items-center justify-center text-zinc-400">
                                        <i data-lucide="user" class="h-12 w-12"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-1 space-y-6">
                                <div>
                                    <h4 id="detailNama" class="text-xl font-bold text-zinc-900"></h4>
                                    <p id="detailNip" class="text-sm text-zinc-500"></p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                                    <div>
                                        <label
                                            class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Jabatan</label>
                                        <p id="detailJabatan" class="text-sm font-medium text-zinc-900 mt-0.5"></p>
                                    </div>
                                    <div>
                                        <label
                                            class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Divisi</label>
                                        <p id="detailDivisi" class="text-sm font-medium text-zinc-900 mt-0.5"></p>
                                    </div>
                                    <div>
                                        <label
                                            class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Kantor</label>
                                        <p id="detailKantor" class="text-sm font-medium text-zinc-900 mt-0.5"></p>
                                    </div>
                                    <div>
                                        <label
                                            class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Status</label>
                                        <p id="detailStatus" class="mt-0.5"><span
                                                class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-800 ring-1 ring-inset ring-zinc-500/10"
                                                id="detailStatusBadge"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 border-t border-zinc-100 pt-8 grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                            <div class="space-y-4">
                                <h5 class="text-sm font-bold text-zinc-900 flex items-center gap-2">
                                    <i data-lucide="info" class="h-4 w-4 text-zinc-400"></i>
                                    Informasi Personal
                                </h5>
                                <div class="grid grid-cols-1 gap-3">
                                    <div>
                                        <label
                                            class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">NIK</label>
                                        <p id="detailNik" class="text-sm text-zinc-700"></p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Tempat,
                                            Tgl Lahir</label>
                                        <p id="detailTTL" class="text-sm text-zinc-700"></p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Jenis
                                            Kelamin</label>
                                        <p id="detailGender" class="text-sm text-zinc-700"></p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Alamat
                                            Domisili</label>
                                        <p id="detailAlamat" class="text-sm text-zinc-700 leading-relaxed"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h5 class="text-sm font-bold text-zinc-900 flex items-center gap-2">
                                    <i data-lucide="briefcase" class="h-4 w-4 text-zinc-400"></i>
                                    Informasi Pekerjaan
                                </h5>
                                <div class="grid grid-cols-1 gap-3">
                                    <div>
                                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Tanggal
                                            Masuk</label>
                                        <p id="detailTglMasuk" class="text-sm text-zinc-700"></p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Shift
                                            Kerja</label>
                                        <p id="detailShift" class="text-sm text-zinc-700"></p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Sisa
                                            Cuti</label>
                                        <p id="detailCuti" class="text-sm font-bold text-zinc-900"></p>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Kontak
                                            & Email</label>
                                        <p id="detailKontak" class="text-sm text-zinc-700"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-zinc-50/50 px-6 py-4 flex justify-end gap-3 border-t border-zinc-100">
                        <button type="button" onclick="closeModal('detailsModal')"
                            class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm" onclick="closeModal('deleteModal')">
        </div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-zinc-100">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="alert-triangle" class="h-5 w-5 text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-zinc-900">Hapus Data Pegawai</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-zinc-500">
                                        Apakah Anda yakin ingin menghapus data pegawai <span id="deleteName"
                                            class="font-medium text-zinc-900"></span>?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-zinc-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-zinc-100">
                        <form id="deleteForm" method="POST" class="inline-block w-full sm:w-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Hapus</button>
                        </form>
                        <button type="button" onclick="closeModal('deleteModal')"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 sm:mt-0 sm:w-auto">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function confirmDelete(id, name) {
            document.getElementById('deleteName').textContent = name;
            document.getElementById('deleteForm').action = "{{ url('admin/employees') }}/" + id;
            openModal('deleteModal');
        }

        function showDetails(employee) {
            document.getElementById('detailNama').textContent = employee.nama_lengkap;
            document.getElementById('detailNip').textContent = employee.nip;
            document.getElementById('detailNik').textContent = employee.nik;
            document.getElementById('detailJabatan').textContent = employee.jabatan ? employee.jabatan.name : '-';
            document.getElementById('detailDivisi').textContent = employee.divisi ? employee.divisi.name : '-';
            document.getElementById('detailKantor').textContent = employee.kantor ? employee.kantor.office_name : '-';
            document.getElementById('detailStatusBadge').textContent = employee.status_pegawai ? employee.status_pegawai
                .name : '-';
            document.getElementById('detailTTL').textContent = (employee.tempat_lahir || '-') + ', ' + (employee
                .tanggal_lahir || '-');
            document.getElementById('detailGender').textContent = employee.jenis_kelamin === 'L' ? 'Laki-laki' :
                'Perempuan';
            document.getElementById('detailAlamat').textContent = employee.alamat_domisili || '-';
            document.getElementById('detailTglMasuk').textContent = employee.tanggal_masuk || '-';
            document.getElementById('detailShift').textContent = employee.shift ? employee.shift.name : '-';
            document.getElementById('detailCuti').textContent = employee.sisa_cuti + ' Hari';
            document.getElementById('detailKontak').textContent = (employee.no_hp || '-') + ' / ' + (employee.user ?
                employee.user.email : '-');

            const fotoImg = document.getElementById('detailFoto');
            const placeholder = document.getElementById('detailFotoPlaceholder');

            if (employee.foto) {
                fotoImg.src = "{{ asset('storage') }}/" + employee.foto;
                fotoImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                fotoImg.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            openModal('detailsModal');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal('deleteModal');
                closeModal('detailsModal');
            }
        });
    </script>
@endsection
