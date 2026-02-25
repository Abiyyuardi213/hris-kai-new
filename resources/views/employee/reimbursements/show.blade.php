@extends('layouts.employee')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('employee.reimbursements.index') }}"
                    class="inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-white border border-zinc-200 text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900">Detail Reimbursement</h1>
                    <p class="text-sm text-zinc-500 mt-1">Cek status dan informasi pengajuan reimbursement.</p>
                </div>
            </div>

            <div class="flex gap-2 items-center">
                <span
                    class="px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                    {{ $reimbursement->status == 'Approved' ? 'bg-green-100 text-green-700' : ($reimbursement->status == 'Rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                    Status: {{ $reimbursement->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Data Detail -->
                <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Tipe Reimbursement</p>
                                <p class="text-sm font-medium text-zinc-900">{{ $reimbursement->tipe_reimbursement }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Tanggal Pengajuan</p>
                                <p class="text-sm font-medium text-zinc-900">
                                    {{ \Carbon\Carbon::parse($reimbursement->tanggal_pengajuan)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Nominal</p>
                                <p class="text-lg font-bold text-zinc-900">Rp
                                    {{ number_format($reimbursement->nominal, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="space-y-1 pt-4 border-t border-zinc-100">
                            <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Keterangan Pengajuan</p>
                            <div class="bg-zinc-50 rounded-xl p-4 mt-2">
                                <p class="text-sm text-zinc-700 whitespace-pre-line">
                                    {{ $reimbursement->keterangan ?? 'Tidak ada keterangan.' }}</p>
                            </div>
                        </div>

                        @if ($reimbursement->lampiran)
                            <div class="space-y-1 pt-4 border-t border-zinc-100">
                                <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Lampiran Bukti</p>
                                <a href="{{ Storage::url($reimbursement->lampiran) }}" target="_blank"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-bold hover:bg-indigo-100 transition-colors">
                                    <i data-lucide="paperclip" class="h-4 w-4"></i>
                                    Lihat Dokumen Lampiran
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Info Appoval -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-zinc-900 mb-4 flex items-center gap-2">
                        <i data-lucide="shield-check" class="h-5 w-5 text-indigo-600"></i>
                        Informasi Persetujuan
                    </h3>

                    @if ($reimbursement->status == 'Pending')
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3 text-amber-700">
                            <i data-lucide="clock" class="h-5 w-5 shrink-0 mt-0.5"></i>
                            <div class="text-sm text-amber-800">
                                <p class="font-bold">Menunggu Persetujuan</p>
                                <p class="mt-1 opacity-90">Pengajuan Anda sedang menunggu ditinjau oleh pihak HR / Admin.
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <i data-lucide="calendar" class="h-5 w-5 text-zinc-400 mt-0.5"></i>
                                <div>
                                    <p class="text-xs font-bold text-zinc-400 uppercase">Tanggal Keputusan</p>
                                    <p class="text-sm font-semibold text-zinc-900 mt-1">
                                        {{ \Carbon\Carbon::parse($reimbursement->tanggal_approval)->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>

                            <hr class="border-zinc-100">

                            <div class="flex items-start gap-3">
                                <i class="h-5 w-5 mt-0.5 text-zinc-400" data-lucide="message-square"></i>
                                <div>
                                    <p class="text-xs font-bold text-zinc-400 uppercase">Catatan Evaluasi</p>
                                    <p class="text-sm text-zinc-700 mt-1 italic">
                                        "{{ $reimbursement->catatan_approval ?? 'Tidak ada catatan.' }}"
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
