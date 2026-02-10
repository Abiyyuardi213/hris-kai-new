@extends('layouts.app')
@section('title', 'Manajemen Shift')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Manajemen Shift</h2>
                <p class="text-zinc-500 text-sm">Kelola daftar shift kerja pegawai PT KAI.</p>
            </div>
            <button onclick="openModal('addShiftModal')"
                class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors shadow-sm">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Tambah Shift
            </button>
        </div>

        @if (session('success'))
            <div
                class="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-600 border border-emerald-100 flex items-center gap-3">
                <i data-lucide="check-circle" class="h-5 w-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Content -->
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50/50 text-zinc-500 border-b border-zinc-200">
                        <tr>
                            <th class="px-6 py-4 font-medium">Nama Shift</th>
                            <th class="px-6 py-4 font-medium">Jam Masuk</th>
                            <th class="px-6 py-4 font-medium">Jam Pulang</th>
                            <th class="px-6 py-4 font-medium">Durasi</th>
                            <th class="px-6 py-4 font-medium text-center">Wajib QR</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse ($shifts as $shift)
                            @php
                                $start = \Carbon\Carbon::parse($shift->start_time);
                                $end = \Carbon\Carbon::parse($shift->end_time);
                                if ($end->lt($start)) {
                                    $end->addDay();
                                }
                                $hours = $end->diffInHours($start);
                                $minutes = $end->diffInMinutes($start) % 60;
                            @endphp
                            <tr class="group hover:bg-zinc-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-zinc-900">{{ $shift->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-emerald-600 font-medium">
                                    {{ substr($shift->start_time, 0, 5) }}
                                </td>
                                <td class="px-6 py-4 text-red-600 font-medium">
                                    {{ substr($shift->end_time, 0, 5) }}
                                </td>
                                <td class="px-6 py-4 text-zinc-600">
                                    {{ $hours }}j {{ $minutes > 0 ? $minutes . 'm' : '' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($shift->require_qr)
                                        <span
                                            class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Wajib</span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-zinc-50 px-2 py-1 text-xs font-medium text-zinc-600 ring-1 ring-inset ring-zinc-500/10">Tidak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('shifts.edit', $shift->id) }}"
                                            class="p-2 text-zinc-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Edit">
                                            <i data-lucide="edit-2" class="h-4 w-4"></i>
                                        </a>
                                        <button onclick="confirmDelete('{{ route('shifts.destroy', $shift->id) }}')"
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
                                        <i data-lucide="clock" class="h-8 w-8 text-zinc-300"></i>
                                        <div>
                                            <p class="font-medium text-zinc-900">Belum ada data shift</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Add Shift -->
    <div id="addShiftModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm" onclick="closeModal('addShiftModal')">
        </div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-zinc-100">
                    <form action="{{ route('shifts.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold leading-6 text-zinc-900">Tambah Shift Baru</h3>
                                <button type="button" onclick="closeModal('addShiftModal')"
                                    class="text-zinc-400 hover:text-zinc-600">
                                    <i data-lucide="x" class="h-5 w-5"></i>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-zinc-900">Nama Shift</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        placeholder="Pagi / Siang / Malam"
                                        class="mt-1 block w-full rounded-lg border {{ $errors->has('name') ? 'border-red-500' : 'border-zinc-300' }} px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    @error('name')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="start_time" class="block text-sm font-medium text-zinc-900">Jam
                                            Masuk</label>
                                        <input type="time" name="start_time" id="start_time"
                                            value="{{ old('start_time') }}" required
                                            class="mt-1 block w-full rounded-lg border {{ $errors->has('start_time') ? 'border-red-500' : 'border-zinc-300' }} px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                        @error('start_time')
                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="end_time" class="block text-sm font-medium text-zinc-900">Jam
                                            Pulang</label>
                                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}"
                                            required
                                            class="mt-1 block w-full rounded-lg border {{ $errors->has('end_time') ? 'border-red-500' : 'border-zinc-300' }} px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                        @error('end_time')
                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <input id="require_qr" name="require_qr" type="checkbox" value="1"
                                        class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900">
                                    <label for="require_qr" class="ml-2 block text-sm font-medium text-zinc-900">
                                        Wajib Absensi dengan QR Code
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="bg-zinc-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-zinc-100">
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 sm:ml-3 sm:w-auto transition-colors">Simpan
                                Shift</button>
                            <button type="button" onclick="closeModal('addShiftModal')"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                        </div>
                    </form>
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
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-zinc-100">
                    <div class="bg-white px-6 py-6 text-center">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600 mx-auto mb-4">
                            <i data-lucide="alert-triangle" class="h-6 w-6"></i>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900">Hapus Shift?</h3>
                        <p class="text-sm text-zinc-500 mt-2 leading-relaxed">Tindakan ini tidak dapat dibatalkan. Pastikan
                            shift ini tidak sedang digunakan oleh pegawai.</p>
                    </div>
                    <div class="bg-zinc-50 px-6 py-4 flex justify-end gap-3 border-t">
                        <button type="button" onclick="closeModal('deleteModal')"
                            class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50">Batal</button>
                        <form id="deleteForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">Hapus
                                Shift</button>
                        </form>
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

        function confirmDelete(url) {
            document.getElementById('deleteForm').action = url;
            openModal('deleteModal');
        }

        @if ($errors->any())
            openModal('addShiftModal');
        @endif

        // Close on ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal('addShiftModal');
                closeModal('deleteModal');
            }
        });
    </script>
@endsection
