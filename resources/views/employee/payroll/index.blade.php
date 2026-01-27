@extends('layouts.employee')
@section('title', 'Slip Gaji Saya')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Data Payroll</h2>
                <p class="text-sm text-zinc-500">Lihat riwayat gaji dan detail slip gaji bulanan Anda.</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-zinc-900 text-white flex items-center justify-center shadow-lg">
                <i data-lucide="banknote" class="h-6 w-6"></i>
            </div>
        </div>

        <!-- History Table -->
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-zinc-50 flex items-center justify-between">
                <h3 class="font-bold text-zinc-900">Riwayat Penggajian</h3>
                <i data-lucide="history" class="h-4 w-4 text-zinc-400"></i>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50/50 text-zinc-500">
                        <tr>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest">Periode</th>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest">Kehadiran</th>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest text-right">Total Gaji
                                Netto</th>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest">Status</th>
                            <th class="px-6 py-4 font-bold uppercase text-[10px] tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-50">
                        @forelse ($payrolls as $payroll)
                            <tr class="group hover:bg-zinc-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-zinc-900">
                                            {{ \Carbon\Carbon::create()->month($payroll->month)->translatedFormat('F') }}
                                        </span>
                                        <span class="text-[11px] text-zinc-500 tracking-wider">TAHUN
                                            {{ $payroll->year }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 text-[10px] font-bold">
                                        {{ $payroll->jumlah_hadir }} HARI KERJA
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-zinc-900">
                                    Rp {{ number_format($payroll->total_gaji, 0, ',', '.') }}
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
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('employee.payroll.show', $payroll->id) }}"
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
                                        <i data-lucide="banknote" class="h-10 w-10 mb-2"></i>
                                        <p class="text-sm font-medium">Belum ada data slip gaji</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($payrolls->hasPages())
                <div class="p-4 bg-zinc-50/30 border-t border-zinc-50">
                    {{ $payrolls->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
