@extends('layouts.app')
@section('title', 'Kota')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Manajemen Kota</h2>
                <p class="text-zinc-500 mt-1">Kelola data kota dan provinsi untuk sistem.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button onclick="openModal('syncModal')"
                    class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:text-zinc-900 transition-colors shadow-sm">
                    <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                    Sinkronisasi Data
                </button>
                <a href="{{ route('cities.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors shadow-sm">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Tambah Kota
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="space-y-4">
            <!-- Search and Filter -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-zinc-200">
                <form action="{{ route('cities.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                    <div class="relative flex-1 min-w-[240px]">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kode atau nama kota..."
                            class="flex h-10 w-full rounded-lg border border-zinc-300 pl-10 pr-3 py-2 text-sm placeholder:text-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                    </div>

                    <div class="w-full sm:w-64">
                        <select name="province" onchange="this.form.submit()"
                            class="flex h-10 w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                            <option value="">Semua Provinsi</option>
                            @foreach ($provinces as $prov)
                                <option value="{{ $prov->province_name }}"
                                    {{ request('province') == $prov->province_name ? 'selected' : '' }}>
                                    {{ $prov->province_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="inline-flex h-10 items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors">
                            Cari
                        </button>
                        @if (request()->anyFilled(['search', 'province']))
                            <a href="{{ route('cities.index') }}"
                                class="inline-flex h-10 items-center justify-center rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-zinc-50/50 text-zinc-500 border-b border-zinc-200">
                            <tr>
                                <th class="px-6 py-4 font-medium w-[150px]">Kode</th>
                                <th class="px-6 py-4 font-medium">Nama Kota</th>
                                <th class="px-6 py-4 font-medium">Provinsi</th>
                                <th class="px-6 py-4 font-medium text-right w-[150px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse ($cities as $city)
                                <tr class="group hover:bg-zinc-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-1 text-xs font-medium text-zinc-600 ring-1 ring-inset ring-zinc-500/10">
                                            {{ $city->code ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-zinc-900">{{ $city->name }}</td>
                                    <td class="px-6 py-4 text-zinc-600">{{ $city->province_name }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div
                                            class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('cities.edit', $city->id) }}"
                                                class="p-2 text-zinc-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="Edit">
                                                <i data-lucide="edit-2" class="h-4 w-4"></i>
                                            </a>
                                            <button onclick="confirmDelete('{{ $city->id }}', '{{ $city->name }}')"
                                                class="p-2 text-zinc-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center text-zinc-500">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <div class="p-4 rounded-full bg-zinc-50 border border-zinc-100">
                                                <i data-lucide="map-pin-off" class="h-8 w-8 text-zinc-300"></i>
                                            </div>
                                            <div class="text-center">
                                                <p class="font-medium text-zinc-900">Data tidak ditemukan</p>
                                                <p class="text-sm mt-1">Coba sesuaikan kata kunci pencarian atau
                                                    sinkronisasi data.</p>
                                            </div>
                                            @if (request('search'))
                                                <a href="{{ route('cities.index') }}"
                                                    class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                                    Hapus filter pencarian
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($cities->hasPages())
                    <div class="p-4 border-t border-zinc-200 bg-zinc-50/50">
                        {{ $cities->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <!-- Sync Confirmation Modal -->
    <div id="syncModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background backdrop -->
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm" onclick="closeModal('syncModal')">
        </div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-zinc-100">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="refresh-cw" class="h-5 w-5 text-blue-600" aria-hidden="true"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-zinc-900" id="modal-title">Konfirmasi
                                    Sinkronisasi
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-zinc-500">
                                        Proses ini akan mengambil data terbaru dari API eksternal.
                                        Proses ini mungkin memakan waktu beberapa saat tergantung koneksi internet Anda.
                                        Apakah Anda ingin melanjutkan?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-zinc-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-zinc-100">
                        <form id="syncCityForm" action="{{ route('cities.sync') }}" method="POST"
                            class="inline-block w-full sm:w-auto">
                            @csrf
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto transition-colors">
                                Ya, Sinkronisasi
                            </button>
                        </form>
                        <button type="button" onclick="closeModal('syncModal')"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 sm:mt-0 sm:w-auto transition-colors">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <!-- Background backdrop -->
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
                                <i data-lucide="alert-triangle" class="h-5 w-5 text-red-600" aria-hidden="true"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-zinc-900" id="modal-title">Hapus Data
                                    Kota</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-zinc-500">
                                        Apakah Anda yakin ingin menghapus data kota <span id="deleteCityName"
                                            class="font-medium text-zinc-900"></span>?
                                        Tindakan ini tidak dapat dibatalkan.
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
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors">
                                Hapus data
                            </button>
                        </form>
                        <button type="button" onclick="closeModal('deleteModal')"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 sm:mt-0 sm:w-auto transition-colors">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/60 transition-opacity backdrop-blur-md"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white p-8 text-left shadow-2xl transition-all sm:w-full sm:max-w-xs border border-zinc-100">
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <div class="relative h-16 w-16">
                            <div class="absolute inset-0 rounded-full border-4 border-zinc-100"></div>
                            <div
                                class="absolute inset-0 rounded-full border-4 border-zinc-900 border-t-transparent animate-spin">
                            </div>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg font-bold text-zinc-900">Menyinkronkan Data</h3>
                            <p class="mt-1 text-sm text-zinc-500">Mohon tunggu sebentar, sedang mengambil data kota dari
                                wilayah Indonesia API...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function confirmDelete(id, name) {
            document.getElementById('deleteCityName').textContent = name;
            document.getElementById('deleteForm').action = "{{ url('admin/cities') }}/" + id;
            openModal('deleteModal');
        }

        // Sync API Loading
        document.getElementById('syncCityForm')?.addEventListener('submit', function() {
            closeModal('syncModal');
            document.getElementById('loadingModal').classList.remove('hidden');
        });

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal('syncModal');
                closeModal('deleteModal');
            }
        });
    </script>
@endsection
