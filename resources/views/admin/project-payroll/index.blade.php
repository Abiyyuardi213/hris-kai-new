@extends('layouts.app')
@section('title', 'Payroll Project')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900 text-shadow-sm">Payroll Project</h2>
                <p class="text-zinc-500 text-sm mt-1">Kelola gaji tambahan untuk project IT atau project khusus lainnya.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.project-payroll.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-bold text-white hover:bg-zinc-800 transition-all shadow-md hover:shadow-lg active:scale-95">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Input Payroll Project
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white/80 backdrop-blur-xl p-6 rounded-2xl shadow-sm border border-zinc-200/50">
            <form action="{{ route('admin.project-payroll.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest ml-1">Cari Pegawai</label>
                        <div class="relative group">
                            <i data-lucide="search"
                                class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400 group-focus-within:text-zinc-900 transition-colors"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nama atau NIP..."
                                class="h-11 w-full rounded-xl border border-zinc-200 bg-white/50 pl-10 pr-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all focus:bg-white">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest ml-1">Bulan</label>
                        <select name="month"
                            class="h-11 w-full rounded-xl border border-zinc-200 bg-white/50 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all focus:bg-white">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}"
                                    {{ request('month', date('n')) == $i ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest ml-1">Tahun</label>
                        <select name="year"
                            class="h-11 w-full rounded-xl border border-zinc-200 bg-white/50 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all focus:bg-white">
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
                            class="flex-1 h-11 items-center justify-center rounded-xl bg-zinc-900 px-4 text-sm font-bold text-white hover:bg-zinc-800 transition-all shadow-sm hover:shadow active:scale-95">
                            Filter
                        </button>
                        @if (request()->anyFilled(['search', 'month', 'year']))
                            <a href="{{ route('admin.project-payroll.index') }}"
                                class="h-11 px-4 flex items-center justify-center rounded-xl border border-zinc-200 bg-white text-sm font-bold text-zinc-700 hover:bg-zinc-50 transition-all active:scale-95">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="rounded-2xl border border-zinc-200/50 bg-white shadow-xl overflow-hidden">
            <div class="w-full overflow-x-auto text-shadow-none">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-zinc-50/80 text-zinc-500 border-b border-zinc-200/50">
                        <tr>
                            <th class="px-6 py-4 font-bold text-[10px] uppercase tracking-wider">Pegawai</th>
                            <th class="px-6 py-4 font-bold text-[10px] uppercase tracking-wider">Project</th>
                            <th class="px-6 py-4 font-bold text-[10px] uppercase tracking-wider text-right">Total Pay</th>
                            <th class="px-6 py-4 font-bold text-[10px] uppercase tracking-wider">Keterangan / Kinerja</th>
                            <th class="px-6 py-4 font-bold text-[10px] uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-4 font-bold text-[10px] uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 font-bold text-[10px] uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse ($projectPayrolls as $payroll)
                            <tr class="group hover:bg-zinc-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-600 font-bold text-xs ring-1 ring-zinc-200">
                                            {{ substr($payroll->pegawai->nama_lengkap, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-zinc-900">{{ $payroll->pegawai->nama_lengkap }}</div>
                                            <div class="text-[10px] text-zinc-500 font-medium">NIP: {{ $payroll->pegawai->nip }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-bold ring-1 ring-indigo-600/10">
                                        <i data-lucide="briefcase" class="h-3 w-3"></i>
                                        {{ $payroll->project_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="font-bold text-emerald-600 text-base">
                                        Rp {{ number_format($payroll->total_pay, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-zinc-600 line-clamp-2 max-w-[200px]" title="{{ $payroll->keterangan }}">
                                        {{ $payroll->keterangan ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-medium text-zinc-700">
                                        {{ Carbon\Carbon::create()->month($payroll->month)->translatedFormat('F') }} {{ $payroll->year }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'paid' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider ring-1 ring-inset {{ $statusColors[$payroll->status] }}">
                                        {{ $payroll->status }}
                                    </span>
                                    @if ($payroll->paid_at)
                                        <div class="text-[9px] text-zinc-400 mt-1 font-medium">
                                            <i data-lucide="calendar" class="h-2.5 w-2.5 inline mr-0.5"></i>
                                            {{ \Carbon\Carbon::parse($payroll->paid_at)->format('d/m/y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-1.5">
                                        @if ($payroll->status === 'pending')
                                            <form action="{{ route('admin.project-payroll.update-status', $payroll->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="paid">
                                                <button type="submit"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-zinc-200 bg-white text-zinc-400 hover:text-emerald-600 hover:border-emerald-200 transition-all shadow-sm hover:shadow active:scale-90"
                                                    title="Mark as Paid">
                                                    <i data-lucide="check-circle" class="h-4.5 w-4.5"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.project-payroll.edit', $payroll->id) }}"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-zinc-200 bg-white text-zinc-400 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm hover:shadow active:scale-90"
                                                title="Edit">
                                                <i data-lucide="pencil" class="h-4.5 w-4.5"></i>
                                            </a>
                                            <button onclick="confirmDelete('{{ $payroll->id }}')"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-zinc-200 bg-white text-zinc-400 hover:text-red-600 hover:border-red-200 transition-all shadow-sm hover:shadow active:scale-90"
                                                title="Hapus">
                                                <i data-lucide="trash-2" class="h-4.5 w-4.5"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('admin.project-payroll.update-status', $payroll->id) }}"
                                                method="POST" onsubmit="return confirm('Batalkan status pembayaran?')">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-zinc-200 bg-white text-zinc-400 hover:text-amber-600 hover:border-amber-200 transition-all shadow-sm hover:shadow active:scale-90"
                                                    title="Cancel Paid">
                                                    <i data-lucide="rotate-ccw" class="h-4.5 w-4.5"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-20 text-center text-zinc-500">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div class="p-5 rounded-full bg-zinc-50 border border-zinc-100 shadow-inner">
                                            <i data-lucide="piggy-bank" class="h-10 w-10 text-zinc-200"></i>
                                        </div>
                                        <div class="text-center">
                                            <p class="font-bold text-zinc-900 text-lg">Belum ada data payroll project</p>
                                            <p class="text-zinc-400 text-sm">Silakan input payroll project baru menggunakan tombol di atas.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($projectPayrolls->hasPages())
                <div class="p-6 border-t border-zinc-100 bg-zinc-50/30">
                    {{ $projectPayrolls->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-zinc-950/40 transition-opacity backdrop-blur-sm" onclick="closeModal('deleteModal')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto font-sans">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-zinc-200">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-red-100 sm:mx-0 sm:h-10 sm:w-10 ring-4 ring-red-50">
                                <i data-lucide="alert-circle" class="h-6 w-6 text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-bold text-zinc-900" id="modal-title">Hapus Payroll Project</h3>
                                <p class="mt-2 text-sm text-zinc-500">Apakah Anda yakin ingin menghapus data payroll project ini? Data yang sudah dihapus tidak dapat dikembalikan.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-zinc-50/50 px-6 py-4 sm:flex sm:flex-row-reverse gap-2">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-xl bg-red-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-red-200 hover:bg-red-700 sm:w-auto transition-all active:scale-95">
                                Hapus Permanen
                            </button>
                        </form>
                        <button type="button" onclick="closeModal('deleteModal')"
                            class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-2.5 text-sm font-bold text-zinc-700 shadow-sm ring-1 ring-inset ring-zinc-200 hover:bg-zinc-50 sm:mt-0 sm:w-auto transition-all active:scale-95">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            setTimeout(() => { modal.classList.add('opacity-100'); }, 10);
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('hidden');
        }

        function confirmDelete(id) {
            document.getElementById('deleteForm').action = "{{ url('admin/project-payroll') }}/" + id;
            openModal('deleteModal');
        }
    </script>
@endsection
