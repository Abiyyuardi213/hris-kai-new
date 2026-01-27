@extends('layouts.app')
@section('title', 'Aset')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Manajemen Aset</h2>
            <a href="{{ route('assets.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors shadow-sm">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Tambah Aset
            </a>
        </div>

        <!-- Content -->
        <div class="space-y-4">
            <!-- Filters -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-zinc-200">
                <form action="{{ route('assets.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <!-- Search -->
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Cari Aset</label>
                            <div class="relative">
                                <i data-lucide="search"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Kode, Nama, Serial..."
                                    class="h-10 w-full rounded-lg border border-zinc-200 pl-10 pr-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                            </div>
                        </div>

                        <!-- Office -->
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Unit Kerja /
                                Kantor</label>
                            <select name="office_id"
                                class="h-10 w-full rounded-lg border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                                <option value="">Semua Lokasi</option>
                                @foreach ($offices as $off)
                                    <option value="{{ $off->id }}"
                                        {{ request('office_id') == $off->id ? 'selected' : '' }}>
                                        {{ $off->office_code }} | {{ $off->office_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Division -->
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Divisi</label>
                            <select name="division_id"
                                class="h-10 w-full rounded-lg border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                                <option value="">Semua Divisi</option>
                                @foreach ($divisions as $div)
                                    <option value="{{ $div->id }}"
                                        {{ request('division_id') == $div->id ? 'selected' : '' }}>
                                        {{ $div->code }} | {{ $div->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Condition -->
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Kondisi</label>
                            <select name="condition"
                                class="h-10 w-full rounded-lg border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                                <option value="">Semua Kondisi</option>
                                <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Baik</option>
                                <option value="repair" {{ request('condition') == 'repair' ? 'selected' : '' }}>Perbaikan
                                </option>
                                <option value="broken" {{ request('condition') == 'broken' ? 'selected' : '' }}>Rusak
                                </option>
                                <option value="lost" {{ request('condition') == 'lost' ? 'selected' : '' }}>Hilang
                                </option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="flex-1 h-10 items-center justify-center rounded-lg bg-zinc-900 px-4 text-sm font-bold text-white hover:bg-zinc-800 transition-all active:scale-[0.98]">
                                Filter
                            </button>
                            @if (request()->anyFilled(['search', 'office_id', 'division_id', 'condition']))
                                <a href="{{ route('assets.index') }}"
                                    class="h-10 flex items-center justify-center rounded-lg border border-zinc-200 bg-white px-4 text-sm font-bold text-zinc-700 hover:bg-zinc-50 transition-all">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-zinc-50/50 text-zinc-500 border-b border-zinc-200">
                            <tr>
                                <th class="px-6 py-4 font-medium w-[120px]">Aset</th>
                                <th class="px-6 py-4 font-medium">Info Detail</th>
                                <th class="px-6 py-4 font-medium">Lokasi Penempatan</th>
                                <th class="px-6 py-4 font-medium">Kondisi</th>
                                <th class="px-6 py-4 font-medium text-right w-[120px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse ($assets as $asset)
                                <tr class="group hover:bg-zinc-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-1 text-[10px] font-bold text-zinc-600 ring-1 ring-inset ring-zinc-500/10 uppercase mb-1">
                                            {{ $asset->code }}
                                        </span>
                                        <div class="font-bold text-zinc-900 leading-tight">{{ $asset->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs text-zinc-500 italic">SN: {{ $asset->serial_number ?? '-' }}
                                        </div>
                                        <div class="text-xs text-zinc-500 mt-0.5">Kat: <span
                                                class="text-zinc-700 font-medium">{{ $asset->category ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-start gap-2">
                                            <i data-lucide="map-pin" class="h-3.5 w-3.5 text-zinc-400 mt-0.5"></i>
                                            <div>
                                                <div class="text-xs font-bold text-zinc-900">
                                                    {{ $asset->office->office_name ?? 'Belum diatur' }}</div>
                                                <div class="text-[10px] text-zinc-500">
                                                    {{ $asset->division->name ?? 'Semua Divisi' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $colors = [
                                                'good' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                                'repair' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
                                                'broken' => 'bg-red-50 text-red-700 ring-red-600/20',
                                                'lost' => 'bg-zinc-50 text-zinc-700 ring-zinc-600/20',
                                            ];
                                            $labels = [
                                                'good' => 'Baik',
                                                'repair' => 'Perbaikan',
                                                'broken' => 'Rusak',
                                                'lost' => 'Hilang',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold uppercase ring-1 ring-inset {{ $colors[$asset->condition] ?? 'bg-gray-50 text-gray-600' }}">
                                            {{ $labels[$asset->condition] ?? $asset->condition }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ route('assets.edit', $asset->id) }}"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-blue-600 hover:border-blue-200 transition-colors"
                                                title="Edit">
                                                <i data-lucide="edit-2" class="h-4 w-4"></i>
                                            </a>
                                            <button onclick="confirmDelete('{{ $asset->id }}', '{{ $asset->name }}')"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-red-600 hover:border-red-200 transition-colors"
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
                                                <i data-lucide="box" class="h-8 w-8 text-zinc-300"></i>
                                            </div>
                                            <div class="text-center">
                                                <p class="font-medium text-zinc-900">Belum ada data aset</p>
                                                <p class="text-sm mt-1">Mulai dengan menambahkan aset baru.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($assets->hasPages())
                    <div class="p-4 border-t border-zinc-200 bg-zinc-50/50">
                        {{ $assets->links() }}
                    </div>
                @endif
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
                                <h3 class="text-base font-semibold leading-6 text-zinc-900">Hapus Data Aset</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-zinc-500">
                                        Apakah Anda yakin ingin menghapus aset <span id="deleteName"
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
            document.getElementById('deleteForm').action = "{{ url('admin/assets') }}/" + id;
            openModal('deleteModal');
        }
    </script>
@endsection
