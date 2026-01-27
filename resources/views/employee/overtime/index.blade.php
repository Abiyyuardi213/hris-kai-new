@extends('layouts.employee')
@section('title', 'Riwayat Lembur')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Data Lembur</h2>
                <p class="text-sm text-zinc-500">Lihat riwayat pengajuan dan penugasan lembur Anda.</p>
            </div>
            <a href="{{ route('employee.overtime.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-bold text-white hover:bg-zinc-800 transition-all active:scale-95 shadow-sm">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Ajukan Lembur
            </a>
        </div>

        <!-- History Table -->
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-zinc-50 flex items-center justify-between">
                <h3 class="font-bold text-zinc-900">Riwayat & Penugasan</h3>
                <i data-lucide="history" class="h-4 w-4 text-zinc-400"></i>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50/50 text-zinc-500">
                        <tr>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest">Tanggal & Waktu</th>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest">Jenis</th>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest">Alasan / Tugas</th>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest">Status</th>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest text-right">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @forelse ($overtimes as $overtime)
                            <tr class="group hover:bg-zinc-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-zinc-900">{{ \Carbon\Carbon::parse($overtime->date)->format('d M Y') }}</span>
                                        <span class="text-[11px] text-zinc-500">
                                            {{ \Carbon\Carbon::parse($overtime->start_time)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($overtime->end_time)->format('H:i') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($overtime->type === 'assignment')
                                        <span
                                            class="inline-flex items-center rounded-lg bg-blue-50 px-2 py-0.5 text-[10px] font-bold uppercase text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                            Penugasan
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-lg bg-zinc-100 px-2 py-0.5 text-[10px] font-bold uppercase text-zinc-600">
                                            Pengajuan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-zinc-600">
                                    <p class="truncate max-w-[150px] md:max-w-[300px]">{{ $overtime->reason }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusStyles = [
                                            'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'approved' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                            'rejected' => 'bg-red-50 text-red-700 ring-red-600/20',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase ring-1 ring-inset {{ $statusStyles[$overtime->status] }}">
                                        {{ $overtime->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button onclick="showDetail('{{ json_encode($overtime) }}')"
                                        class="p-2 hover:bg-zinc-100 rounded-xl transition-all text-zinc-400 hover:text-zinc-900">
                                        <i data-lucide="chevron-right" class="h-5 w-5"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-40">
                                        <i data-lucide="clock" class="h-10 w-10 mb-2"></i>
                                        <p class="text-sm font-medium">Belum ada riwayat lembur</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($overtimes->hasPages())
                <div class="p-4 bg-zinc-50/30 border-t border-zinc-50">
                    {{ $overtimes->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/60 transition-opacity backdrop-blur-md" onclick="closeModal('detailModal')">
        </div>
        <div class="fixed inset-0 z-[60] w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-3xl bg-white p-8 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-zinc-100">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-bold text-zinc-900">Detail Lembur</h3>
                        <button type="button" onclick="closeModal('detailModal')"
                            class="h-10 w-10 flex items-center justify-center rounded-full bg-zinc-50 text-zinc-400 hover:text-zinc-900 transition-all">
                            <i data-lucide="x" class="h-5 w-5"></i>
                        </button>
                    </div>

                    <div id="detailContent" class="space-y-6">
                        <!-- Content will be populated via JS -->
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

        function showDetail(data) {
            const overtime = JSON.parse(data);
            const content = document.getElementById('detailContent');

            const formatDate = (dateStr) => {
                const options = {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                };
                return new Date(dateStr).toLocaleDateString('id-ID', options);
            };

            const statusColors = {
                'pending': 'bg-amber-50 text-amber-700 ring-amber-600/20',
                'approved': 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                'rejected': 'bg-red-50 text-red-700 ring-red-600/20',
            };

            content.innerHTML = `
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-zinc-50/50 p-4 rounded-2xl">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest block mb-1">Jenis</label>
                        <span class="font-bold text-zinc-900 capitalize">${overtime.type === 'assignment' ? 'Penugasan' : 'Pengajuan'}</span>
                    </div>
                    <div class="bg-zinc-50/50 p-4 rounded-2xl">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest block mb-1">Status</label>
                        <span class="inline-flex items-center rounded-lg px-2 py-0.5 text-[10px] font-bold uppercase ring-1 ring-inset ${statusColors[overtime.status]}">${overtime.status}</span>
                    </div>
                </div>

                <div class="bg-zinc-50/50 p-4 rounded-2xl">
                    <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest block mb-1">Tanggal & Waktu</label>
                    <div class="font-bold text-zinc-900">${formatDate(overtime.date)}</div>
                    <div class="text-xs text-zinc-500 font-medium">${overtime.start_time.substring(0,5)} - ${overtime.end_time.substring(0,5)}</div>
                </div>

                <div class="bg-zinc-50/50 p-4 rounded-2xl">
                    <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest block mb-1">Keterangan</label>
                    <p class="text-sm font-medium text-zinc-700 leading-relaxed">${overtime.reason}</p>
                </div>

                ${overtime.admin_note ? `
                    <div class="bg-zinc-900 p-4 rounded-2xl shadow-xl shadow-zinc-200">
                        <label class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest block mb-1">Catatan Admin</label>
                        <p class="text-sm font-medium text-white leading-relaxed">${overtime.admin_note}</p>
                    </div>
                    ` : ''}
            `;

            openModal('detailModal');
            lucide.createIcons();
        }
    </script>
@endsection
