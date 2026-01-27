@extends('layouts.app')
@section('title', 'Hari Libur')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Hari Libur Nasional</h2>
            <div class="flex items-center gap-3">
                <form id="syncHolidayForm" action="{{ route('holidays.sync-api') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 transition-colors shadow-sm">
                        <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                        Sinkronisasi API
                    </button>
                </form>
                <button onclick="openModal('addHolidayModal')"
                    class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors shadow-sm">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Tambah Hari Libur
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="space-y-4">
            <!-- Search -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-zinc-200">
                <form action="{{ route('holidays.index') }}" method="GET"
                    class="flex w-full md:max-w-md items-center gap-2">
                    <div class="relative flex-1">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari hari libur..."
                            class="flex h-10 w-full rounded-lg border border-zinc-300 pl-10 pr-3 py-2 text-sm placeholder:text-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                    </div>
                    <button type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors">
                        Cari
                    </button>
                    @if (request('search'))
                        <a href="{{ route('holidays.index') }}"
                            class="inline-flex h-10 items-center justify-center rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 transition-colors">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-zinc-50/50 text-zinc-500 border-b border-zinc-200">
                            <tr>
                                <th class="px-6 py-4 font-medium w-[200px]">Tanggal</th>
                                <th class="px-6 py-4 font-medium">Nama Hari Libur</th>
                                <th class="px-6 py-4 font-medium">Keterangan</th>
                                <th class="px-6 py-4 font-medium text-right w-[100px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse ($holidays as $holiday)
                                <tr class="group hover:bg-zinc-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2 font-medium text-zinc-900">
                                            <i data-lucide="calendar" class="h-4 w-4 text-zinc-400"></i>
                                            {{ \Carbon\Carbon::parse($holiday->date)->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-zinc-900">{{ $holiday->name }}</div>
                                        @if ($holiday->is_recurring)
                                            <span
                                                class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-600 ring-1 ring-inset ring-zinc-500/10 mt-1">
                                                Berulang Tiap Tahun
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-zinc-600">
                                        {{ $holiday->description ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus hari libur ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-zinc-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center text-zinc-500">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <i data-lucide="palmtree" class="h-8 w-8 text-zinc-300"></i>
                                            <div class="text-center">
                                                <p class="font-medium text-zinc-900">Belum ada data hari libur</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($holidays->hasPages())
                    <div class="p-4 border-t border-zinc-200 bg-zinc-50/50">
                        {{ $holidays->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Add Holiday -->
    <div id="addHolidayModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm"
            onclick="closeModal('addHolidayModal')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-zinc-100">
                    <form action="{{ route('holidays.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold leading-6 text-zinc-900">Tambah Hari Libur</h3>
                                <button type="button" onclick="closeModal('addHolidayModal')"
                                    class="text-zinc-400 hover:text-zinc-600">
                                    <i data-lucide="x" class="h-5 w-5"></i>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-zinc-900">Nama Hari
                                        Libur</label>
                                    <input type="text" name="name" id="name" required
                                        class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900"
                                        placeholder="Contoh: Tahun Baru Imlek">
                                </div>

                                <div>
                                    <label for="date" class="block text-sm font-medium text-zinc-900">Tanggal</label>
                                    <input type="date" name="date" id="date" required
                                        class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium text-zinc-900">Keterangan
                                        (Opsional)</label>
                                    <textarea name="description" id="description" rows="2"
                                        class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900"></textarea>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" name="is_recurring" id="is_recurring" value="1"
                                        class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900">
                                    <label for="is_recurring" class="ml-2 block text-sm text-zinc-900">Berulang setiap
                                        tahun?</label>
                                </div>
                            </div>
                        </div>
                        <div class="bg-zinc-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-zinc-100">
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 sm:ml-3 sm:w-auto">Simpan</button>
                            <button type="button" onclick="closeModal('addHolidayModal')"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 sm:mt-0 sm:w-auto">Batal</button>
                        </div>
                    </form>
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
                            <p class="mt-1 text-sm text-zinc-500">Mohon tunggu sebentar, sedang mengambil data hari libur
                                dari API...</p>
                        </div>
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

        // Sync API Loading
        document.getElementById('syncHolidayForm')?.addEventListener('submit', function() {
            document.getElementById('loadingModal').classList.remove('hidden');
        });
    </script>
@endsection
