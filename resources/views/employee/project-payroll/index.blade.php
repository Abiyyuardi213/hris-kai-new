@extends('layouts.employee')
@section('title', 'Payroll Project Saya')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Data Payroll Project</h2>
                <p class="text-sm text-zinc-500">Lihat riwayat upah tambahan dari pengerjaan project IT atau project khusus.</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-zinc-900 text-white flex items-center justify-center shadow-lg">
                <i data-lucide="piggy-bank" class="h-6 w-6"></i>
            </div>
        </div>

        <!-- History Table -->
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-zinc-50 flex items-center justify-between">
                <h3 class="font-bold text-zinc-900">Riwayat Pembayaran Project</h3>
                <i data-lucide="history" class="h-4 w-4 text-zinc-400"></i>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50/50 text-zinc-500">
                        <tr>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest">Project</th>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest text-right">Nominal</th>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest">Periode</th>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest">Status</th>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @forelse ($projectPayrolls as $payroll)
                            <tr class="group hover:bg-zinc-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-zinc-900">{{ $payroll->project_name }}</span>
                                        <span class="text-[11px] text-zinc-500 truncate max-w-[200px]" title="{{ $payroll->keterangan }}">
                                            {{ $payroll->keterangan ?? 'Upah Project' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-zinc-900">
                                    Rp {{ number_format($payroll->total_pay, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-zinc-900">
                                            {{ \Carbon\Carbon::create()->month($payroll->month)->translatedFormat('F') }}
                                        </span>
                                        <span class="text-[11px] text-zinc-500 tracking-wider">TAHUN {{ $payroll->year }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusStyles = [
                                            'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'paid' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase ring-1 ring-inset {{ $statusStyles[$payroll->status] }}">
                                        {{ $payroll->status === 'paid' ? 'Dibayarkan' : 'Menunggu' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                                    <a href="{{ route('employee.project-payroll.print', $payroll->id) }}" target="_blank"
                                        class="inline-flex items-center gap-2 rounded-xl border border-zinc-200 bg-white px-3 py-2 text-xs font-bold text-orange-600 hover:bg-orange-600 hover:text-white hover:border-orange-600 transition-all shadow-sm active:scale-95">
                                        <i data-lucide="file-text" class="h-3.5 w-3.5"></i>
                                    </a>
                                    <a href="{{ route('employee.project-payroll.show', $payroll->id) }}"
                                        class="inline-flex items-center gap-2 rounded-xl border border-zinc-200 bg-white px-3 py-2 text-xs font-bold text-zinc-700 hover:bg-zinc-900 hover:text-white hover:border-zinc-900 transition-all shadow-sm active:scale-95">
                                        <i data-lucide="eye" class="h-3.5 w-3.5"></i>
                                        Detail Slip
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-40">
                                        <i data-lucide="piggy-bank" class="h-10 w-10 mb-2"></i>
                                        <p class="text-sm font-medium">Belum ada data slip project</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($projectPayrolls->hasPages())
                <div class="p-4 bg-zinc-50/30 border-t border-zinc-50">
                    {{ $projectPayrolls->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
