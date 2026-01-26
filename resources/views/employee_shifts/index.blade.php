@extends('layouts.app')
@section('title', 'Shift Pegawai')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Jadwal Shift Pegawai</h2>
            <button onclick="openModal('addShiftModal')"
                class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors shadow-sm">
                <i data-lucide="calendar-plus" class="h-4 w-4"></i>
                Atur Jadwal
            </button>
        </div>

        <!-- Content -->
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50/50 text-zinc-500 border-b border-zinc-200">
                        <tr>
                            <th class="px-6 py-4 font-medium">Pegawai</th>
                            <th class="px-6 py-4 font-medium">Shift</th>
                            <th class="px-6 py-4 font-medium">Jam Kerja</th>
                            <th class="px-6 py-4 font-medium">Periode</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse ($employeeShifts as $es)
                            <tr class="group hover:bg-zinc-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-zinc-900">{{ $es->employee->nama_lengkap }}</div>
                                    <div class="text-xs text-zinc-500">{{ $es->employee->nip }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                        {{ $es->shift->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-zinc-600">
                                    {{ \Carbon\Carbon::parse($es->shift->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($es->shift->end_time)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 text-zinc-600">
                                    {{ \Carbon\Carbon::parse($es->start_date)->format('d M Y') }}
                                    @if ($es->end_date)
                                        s/d {{ \Carbon\Carbon::parse($es->end_date)->format('d M Y') }}
                                    @else
                                        <span class="text-xs text-green-600 font-medium">(Aktif Seterusnya)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('employee-shifts.destroy', $es->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus jadwal shift ini?');">
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
                                <td colspan="5" class="px-6 py-16 text-center text-zinc-500">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <i data-lucide="calendar-off" class="h-8 w-8 text-zinc-300"></i>
                                        <div class="text-center">
                                            <p class="font-medium text-zinc-900">Belum ada jadwal shift</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($employeeShifts->hasPages())
                <div class="p-4 border-t border-zinc-200 bg-zinc-50/50">
                    {{ $employeeShifts->links() }}
                </div>
            @endif
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
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-zinc-100">
                    <form action="{{ route('employee-shifts.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold leading-6 text-zinc-900">Atur Jadwal Shift Pegawai</h3>
                                <button type="button" onclick="closeModal('addShiftModal')"
                                    class="text-zinc-400 hover:text-zinc-600">
                                    <i data-lucide="x" class="h-5 w-5"></i>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="employee_id" class="block text-sm font-medium text-zinc-900">Pegawai</label>
                                    <select name="employee_id" id="employee_id" required
                                        class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                        <option value="">Pilih Pegawai</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="shift_id" class="block text-sm font-medium text-zinc-900">Shift</label>
                                    <select name="shift_id" id="shift_id" required
                                        class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                        <option value="">Pilih Shift</option>
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}">{{ $shift->name }}
                                                ({{ $shift->start_time }} - {{ $shift->end_time }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="start_date" class="block text-sm font-medium text-zinc-900">Mulai
                                            Tanggal</label>
                                        <input type="date" name="start_date" id="start_date" required
                                            class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>
                                    <div>
                                        <label for="end_date" class="block text-sm font-medium text-zinc-900">Sampai
                                            (Opsional)</label>
                                        <input type="date" name="end_date" id="end_date"
                                            class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-zinc-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-zinc-100">
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 sm:ml-3 sm:w-auto">Simpan
                                Jadwal</button>
                            <button type="button" onclick="closeModal('addShiftModal')"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 sm:mt-0 sm:w-auto">Batal</button>
                        </div>
                    </form>
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
    </script>
@endsection
