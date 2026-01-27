@extends('layouts.employee')
@section('title', 'Riwayat Pengajuan Izin')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Pengajuan Izin</h2>
                <p class="text-sm text-zinc-500">Ajukan izin, sakit, atau cuti Anda di sini.</p>
            </div>
            <a href="{{ route('employee.izin.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-bold text-white hover:bg-zinc-800 transition-all active:scale-95 shadow-sm">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Ajukan Izin
            </a>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-zinc-100 shadow-sm">
                <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Total Pengajuan</div>
                <div class="text-2xl font-bold text-zinc-900">{{ $izins->total() }}</div>
            </div>
            <div class="bg-emerald-50/50 p-4 rounded-2xl border border-emerald-100/50 shadow-sm">
                <div class="text-[10px] font-bold text-emerald-600/70 uppercase tracking-widest mb-1">Disetujui</div>
                <div class="text-2xl font-bold text-emerald-700">{{ $izins->where('status', 'approved')->count() }}</div>
            </div>
            <div class="bg-amber-50/50 p-4 rounded-2xl border border-amber-100/50 shadow-sm">
                <div class="text-[10px] font-bold text-amber-600/70 uppercase tracking-widest mb-1">Pending</div>
                <div class="text-2xl font-bold text-amber-700">{{ $izins->where('status', 'pending')->count() }}</div>
            </div>
            <div class="bg-red-50/50 p-4 rounded-2xl border border-red-100/50 shadow-sm">
                <div class="text-[10px] font-bold text-red-600/70 uppercase tracking-widest mb-1">Ditolak</div>
                <div class="text-2xl font-bold text-red-700">{{ $izins->where('status', 'rejected')->count() }}</div>
            </div>
        </div>

        <!-- History Table -->
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-zinc-50 flex items-center justify-between">
                <h3 class="font-bold text-zinc-900">Riwayat Pengajuan</h3>
                <i data-lucide="history" class="h-4 w-4 text-zinc-400"></i>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50/50 text-zinc-500">
                        <tr>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest">Jenis & Tanggal</th>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest">Alasan</th>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest">Status</th>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest text-right">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @forelse ($izins as $izin)
                            <tr class="group hover:bg-zinc-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-zinc-900 capitalize">{{ $izin->type }}</span>
                                        <span class="text-[11px] text-zinc-500">
                                            {{ \Carbon\Carbon::parse($izin->start_date)->format('d M') }} -
                                            {{ \Carbon\Carbon::parse($izin->end_date)->format('d M Y') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-zinc-600">
                                    <p class="truncate max-w-[150px] md:max-w-[300px]">{{ $izin->reason }}</p>
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
                                        class="inline-flex items-center rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase ring-1 ring-inset {{ $statusStyles[$izin->status] }}">
                                        {{ $izin->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button onclick="showDetail('{{ json_encode($izin) }}')"
                                        class="p-2 hover:bg-zinc-100 rounded-xl transition-all text-zinc-400 hover:text-zinc-900">
                                        <i data-lucide="chevron-right" class="h-5 w-5"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-40">
                                        <i data-lucide="file-x" class="h-10 w-10 mb-2"></i>
                                        <p class="text-sm font-medium">Belum ada riwayat pengajuan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($izins->hasPages())
                <div class="p-4 bg-zinc-50/30 border-t border-zinc-50">
                    {{ $izins->links() }}
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
                        <h3 class="text-xl font-bold text-zinc-900">Detail Pengajuan</h3>
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
            const izin = JSON.parse(data);
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
                        <span class="font-bold text-zinc-900 capitalize">${izin.type}</span>
                    </div>
                    <div class="bg-zinc-50/50 p-4 rounded-2xl">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest block mb-1">Status</label>
                        <span class="inline-flex items-center rounded-lg px-2 py-0.5 text-[10px] font-bold uppercase ring-1 ring-inset ${statusColors[izin.status]}">${izin.status}</span>
                    </div>
                </div>

                <div class="bg-zinc-50/50 p-4 rounded-2xl">
                    <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest block mb-1">Periode</label>
                    <div class="font-bold text-zinc-900">${formatDate(izin.start_date)} - ${formatDate(izin.end_date)}</div>
                </div>

                <div class="bg-zinc-50/50 p-4 rounded-2xl">
                    <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest block mb-1">Alasan</label>
                    <p class="text-sm font-medium text-zinc-700 leading-relaxed">${izin.reason}</p>
                </div>

                ${izin.attachment ? `
                    <div class="bg-zinc-50/50 p-4 rounded-2xl">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest block mb-1">Lampiran</label>
                        <a href="/storage/${izin.attachment}" target="_blank" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-700 pt-1">
                            <i data-lucide="file-text" class="h-4 w-4"></i>
                            Lihat Dokumen Pendukung
                        </a>
                    </div>
                    ` : ''}

                ${izin.admin_note ? `
                    <div class="bg-zinc-900 p-4 rounded-2xl shadow-xl shadow-zinc-200">
                        <label class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest block mb-1">Catatan Admin</label>
                        <p class="text-sm font-medium text-white leading-relaxed">${izin.admin_note}</p>
                    </div>
                    ` : ''}
            `;

            openModal('detailModal');
            lucide.createIcons();
        }
    </script>
@endsection
