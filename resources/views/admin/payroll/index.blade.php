@extends('layouts.app')
@section('title', 'Manajemen Payroll')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Manajemen Payroll</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.payroll.generate') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors shadow-sm">
                    <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                    Generate Payroll
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-zinc-200">
            <form action="{{ route('admin.payroll.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Cari Pegawai</label>
                        <div class="relative">
                            <i data-lucide="search"
                                class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nama atau NIP..."
                                class="h-10 w-full rounded-lg border border-zinc-200 pl-10 pr-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Bulan</label>
                        <select name="month"
                            class="h-10 w-full rounded-lg border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}"
                                    {{ request('month', date('n')) == $i ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Tahun</label>
                        <select name="year"
                            class="h-10 w-full rounded-lg border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                            @for ($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}"
                                    {{ request('year', date('Y')) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 h-10 items-center justify-center rounded-lg bg-zinc-900 px-4 text-sm font-bold text-white hover:bg-zinc-800 transition-all">
                            Filter
                        </button>
                        @if (request()->anyFilled(['search', 'month', 'year']))
                            <a href="{{ route('admin.payroll.index') }}"
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
                            <th class="px-6 py-4 font-medium">Pegawai</th>
                            <th class="px-6 py-4 font-medium">Jabatan</th>
                            <th class="px-6 py-4 font-medium">Hadir</th>
                            <th class="px-6 py-4 font-medium text-right">Gaji/Hari</th>
                            <th class="px-6 py-4 font-medium text-right">Tunjangan</th>
                            <th class="px-6 py-4 font-bold text-right text-zinc-900">Total Gaji</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse ($payrolls as $payroll)
                            <tr class="group hover:bg-zinc-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-zinc-900">{{ $payroll->pegawai->nama_lengkap }}</div>
                                    <div class="text-[10px] text-zinc-500">NIP: {{ $payroll->pegawai->nip }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-zinc-600">{{ $payroll->pegawai->jabatan->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded bg-blue-50 text-blue-700 text-xs font-bold">
                                        {{ $payroll->jumlah_hadir }} Hari
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="text-xs text-zinc-600">Rp
                                        {{ number_format($payroll->gaji_harian, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="text-xs text-zinc-600">Rp
                                        {{ number_format($payroll->tunjangan_jabatan, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-emerald-600">
                                    Rp {{ number_format($payroll->total_gaji, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'paid' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold uppercase ring-1 ring-inset {{ $statusColors[$payroll->status] }}">
                                        {{ $payroll->status }}
                                    </span>
                                    @if ($payroll->paid_at)
                                        <div class="text-[9px] text-zinc-400 mt-0.5">
                                            {{ \Carbon\Carbon::parse($payroll->paid_at)->format('d/m/y H:i') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        @if ($payroll->status === 'pending')
                                            <form action="{{ route('admin.payroll.update-status', $payroll->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="paid">
                                                <button type="submit"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-emerald-600 hover:border-emerald-200 transition-colors"
                                                    title="Mark as Paid">
                                                    <i data-lucide="check-circle" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.payroll.update-status', $payroll->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-amber-600 hover:border-amber-200 transition-colors"
                                                    title="Cancel Paid">
                                                    <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <button onclick="confirmDelete('{{ $payroll->id }}')"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-red-600 hover:border-red-200 transition-colors"
                                            title="Hapus">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center text-zinc-500">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="p-4 rounded-full bg-zinc-50 border border-zinc-100">
                                            <i data-lucide="banknote" class="h-8 w-8 text-zinc-300"></i>
                                        </div>
                                        <div class="text-center">
                                            <p class="font-medium text-zinc-900">Belum ada data payroll di periode ini</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($payrolls->hasPages())
                <div class="p-4 border-t border-zinc-200 bg-zinc-50/50">
                    {{ $payrolls->links() }}
                </div>
            @endif
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
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="alert-triangle" class="h-5 w-5 text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-bold text-zinc-900">Hapus Data Payroll</h3>
                                <p class="mt-2 text-sm text-zinc-500">Anda yakin ingin menghapus data payroll ini? Tindakan
                                    ini tidak dapat dibatalkan.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-zinc-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-lg bg-red-600 px-6 py-2 text-sm font-bold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-all">Hapus
                                Permanen</button>
                        </form>
                        <button type="button" onclick="closeModal('deleteModal')"
                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-6 py-2 text-sm font-bold text-zinc-700 shadow-sm ring-1 ring-inset ring-zinc-200 hover:bg-zinc-50 sm:mt-0 sm:w-auto transition-all">Batalkan</button>
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

        function confirmDelete(id) {
            document.getElementById('deleteForm').action = "{{ url('admin/payroll') }}/" + id;
            openModal('deleteModal');
        }
    </script>
@endsection
