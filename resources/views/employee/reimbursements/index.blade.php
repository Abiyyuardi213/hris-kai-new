@extends('layouts.employee')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900">Riwayat Reimbursement</h1>
                <p class="text-sm text-zinc-500 mt-1">Daftar pengajuan reimbursement Anda.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('employee.reimbursements.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-zinc-900 px-4 py-2 text-sm font-bold text-white transition-all hover:bg-zinc-800 focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Ajukan Reimbursement
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <!-- List Data -->
        @if ($reimbursements->count() > 0)
            <div class="space-y-4">
                @foreach ($reimbursements as $reimbursement)
                    <div
                        class="bg-white rounded-xl border border-zinc-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row gap-6">
                                <!-- Status Icon -->
                                <div class="flex-shrink-0">
                                    <div
                                        class="h-12 w-12 rounded-full flex items-center justify-center 
                                        {{ $reimbursement->status == 'Approved'
                                            ? 'bg-green-100 text-green-600'
                                            : ($reimbursement->status == 'Rejected'
                                                ? 'bg-red-100 text-red-600'
                                                : 'bg-amber-100 text-amber-600') }}">
                                        @if ($reimbursement->status == 'Approved')
                                            <i data-lucide="check-circle" class="h-6 w-6"></i>
                                        @elseif($reimbursement->status == 'Rejected')
                                            <i data-lucide="x-circle" class="h-6 w-6"></i>
                                        @else
                                            <i data-lucide="clock" class="h-6 w-6"></i>
                                        @endif
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-grow space-y-4">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-lg font-bold text-zinc-900 capitalize">
                                                    {{ $reimbursement->tipe_reimbursement }}</h3>
                                                <span
                                                    class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider 
                                                    {{ $reimbursement->status == 'Approved' ? 'bg-green-100 text-green-700' : ($reimbursement->status == 'Rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                                    {{ $reimbursement->status }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-zinc-500 mt-1">Diajukan:
                                                {{ \Carbon\Carbon::parse($reimbursement->tanggal_pengajuan)->translatedFormat('d F Y') }}
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('employee.reimbursements.show', $reimbursement->id) }}"
                                                class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                                                Lihat Detail
                                                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="pt-4 border-t border-zinc-100">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex gap-2 items-center text-sm font-bold">
                                                <span class="text-zinc-500">Nominal:</span>
                                                <span class="text-zinc-900">Rp
                                                    {{ number_format($reimbursement->nominal, 0, ',', '.') }}</span>
                                            </div>
                                            <p class="text-sm text-zinc-600 line-clamp-2">
                                                {{ $reimbursement->keterangan ?? 'Tidak ada keterangan.' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div
                class="flex flex-col items-center justify-center py-12 px-4 bg-white rounded-xl border border-zinc-200 border-dashed text-center">
                <div class="h-16 w-16 bg-zinc-50 rounded-full flex items-center justify-center mb-4">
                    <i data-lucide="receipt" class="h-8 w-8 text-zinc-300"></i>
                </div>
                <h3 class="text-lg font-medium text-zinc-900">Belum Ada Reimbursement</h3>
                <p class="text-zinc-500 mt-2 max-w-sm">
                    Anda belum pernah mengajukan reimbursement apapun.
                </p>
                <a href="{{ route('employee.reimbursements.create') }}"
                    class="mt-4 px-4 py-2 bg-zinc-900 text-white rounded-lg text-sm font-bold">Buat Pengajuan</a>
            </div>
        @endif
    </div>
@endsection
