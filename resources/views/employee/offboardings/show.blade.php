@extends('layouts.employee')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('employee.offboardings.index') }}"
                class="w-10 h-10 flex items-center justify-center rounded-full border border-zinc-200 hover:bg-zinc-50 transition text-zinc-600">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-zinc-900">Detail & Progress Offboarding</h1>
                <p class="text-sm text-zinc-500 mt-1">Status Clearance Checklist dan Dokumen Akhir Anda</p>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="bg-white border text-center p-6 border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <span
                class="inline-flex px-4 py-1.5 rounded-full text-sm uppercase font-bold tracking-wider mx-auto
            {{ $offboarding->status == 'Completed'
                ? 'bg-green-100 text-green-700'
                : ($offboarding->status == 'Rejected'
                    ? 'bg-red-100 text-red-700'
                    : ($offboarding->status == 'In Progress'
                        ? 'bg-blue-100 text-blue-700'
                        : 'bg-amber-100 text-amber-700')) }}">
                Status Pengajuan: {{ $offboarding->status }}
            </span>
            <h2 class="font-bold text-zinc-900 text-xl">{{ $offboarding->tipe_offboarding }}</h2>
            <p class="text-sm text-zinc-500">Tanggal Berhenti Aktif (Efektif Keluar):
                <strong>{{ \Carbon\Carbon::parse($offboarding->tanggal_efektif)->translatedFormat('d F Y') }}</strong></p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Panel Clearance Checklist -->
            <div class="bg-white border py-6 border-zinc-200 rounded-2xl shadow-sm">
                <div class="px-6 border-b border-zinc-100 pb-4 mb-4 flex items-center gap-2">
                    <i data-lucide="check-square" class="h-5 w-5 text-indigo-600"></i>
                    <h3 class="font-bold text-zinc-900 text-lg">Clearance Checklist</h3>
                </div>
                <div class="space-y-4 px-6">
                    <!-- ID Card -->
                    <div class="flex items-center justify-between p-4 bg-zinc-50 rounded-xl border border-zinc-100">
                        <div class="flex items-center gap-3">
                            <i data-lucide="id-card" class="h-5 w-5 text-zinc-400"></i>
                            <div>
                                <p class="font-bold text-zinc-900 text-sm">Pengembalian ID Card / Akses KAI</p>
                                <p class="text-xs text-zinc-500 mt-0.5">Dikembalikan ke HR/GA</p>
                            </div>
                        </div>
                        <div>
                            @if ($offboarding->clearance_id_card)
                                <div class="bg-green-100 text-green-700 p-1.5 rounded-full"><i data-lucide="check"
                                        class="h-4 w-4 text-green-600"></i></div>
                            @else
                                <div class="bg-red-50 text-red-400 p-1.5 rounded-full"><i data-lucide="x"
                                        class="h-4 w-4"></i></div>
                            @endif
                        </div>
                    </div>

                    <!-- Laptop / Aset -->
                    <div class="flex items-center justify-between p-4 bg-zinc-50 rounded-xl border border-zinc-100">
                        <div class="flex items-center gap-3">
                            <i data-lucide="laptop" class="h-5 w-5 text-zinc-400"></i>
                            <div>
                                <p class="font-bold text-zinc-900 text-sm">Pengembalian Inventaris Kantor</p>
                                <p class="text-xs text-zinc-500 mt-0.5">Laptop SSD, Kendaraan, Alat Kerja</p>
                            </div>
                        </div>
                        <div>
                            @if ($offboarding->clearance_laptop)
                                <div class="bg-green-100 text-green-700 p-1.5 rounded-full"><i data-lucide="check"
                                        class="h-4 w-4 text-green-600"></i></div>
                            @else
                                <div class="bg-red-50 text-red-400 p-1.5 rounded-full"><i data-lucide="x"
                                        class="h-4 w-4"></i></div>
                            @endif
                        </div>
                    </div>

                    <!-- Dokumen -->
                    <div class="flex items-center justify-between p-4 bg-zinc-50 rounded-xl border border-zinc-100">
                        <div class="flex items-center gap-3">
                            <i data-lucide="file-check-2" class="h-5 w-5 text-zinc-400"></i>
                            <div>
                                <p class="font-bold text-zinc-900 text-sm">Clearance Dokumen & Tanggung Jawab</p>
                                <p class="text-xs text-zinc-500 mt-0.5">Berita acara Serah Terima Pekerjaan</p>
                            </div>
                        </div>
                        <div>
                            @if ($offboarding->clearance_dokumen)
                                <div class="bg-green-100 text-green-700 p-1.5 rounded-full"><i data-lucide="check"
                                        class="h-4 w-4 text-green-600"></i></div>
                            @else
                                <div class="bg-red-50 text-red-400 p-1.5 rounded-full"><i data-lucide="x"
                                        class="h-4 w-4"></i></div>
                            @endif
                        </div>
                    </div>

                    @if ($offboarding->uang_pesangon > 0)
                        <div class="mt-6 pt-6 border-t border-zinc-100">
                            <p class="text-xs uppercase font-bold text-zinc-400 mb-2 mt-4 tracking-wider">Estimasi
                                Kompensasi / Pesangon Disetujui</p>
                            <div
                                class="text-2xl font-bold text-emerald-600 bg-emerald-50 px-4 py-3 rounded-xl inline-block w-full text-center">
                                Rp {{ number_format($offboarding->uang_pesangon, 0, ',', '.') }}
                            </div>
                            <p class="text-[10px] sm:text-xs text-zinc-400 mt-2 text-center opacity-80">*Uang
                                kompensasi/pensiun akan ditransfer secara mutlak ke Rekening Payroll berdasarkan validasi
                                sistem.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Exit Interview & Notes Info -->
            <div class="bg-white border py-6 border-zinc-200 rounded-2xl shadow-sm">
                <div class="px-6 border-b border-zinc-100 pb-4 mb-4 flex items-center gap-2">
                    <i data-lucide="file-question" class="h-5 w-5 text-indigo-600"></i>
                    <h3 class="font-bold text-zinc-900 text-lg">Catatan & Masukan Anda</h3>
                </div>

                <div class="p-6 pt-0 space-y-6">
                    @if ($offboarding->catatan_admin)
                        <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl mb-4">
                            <div class="flex gap-2">
                                <i data-lucide="message-square-text" class="h-5 w-5 text-blue-600 shrink-0"></i>
                                <div>
                                    <h4 class="font-bold text-blue-800 text-sm">Pesan/Catatan Admin untuk Anda:</h4>
                                    <p class="text-sm text-blue-700 mt-1 italic">{{ $offboarding->catatan_admin }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-2">
                        <h4 class="text-zinc-400 text-xs font-bold uppercase tracking-widest">Alasan Pengunduran Diri:</h4>
                        <p class="text-zinc-800 bg-zinc-50 p-3 rounded-lg text-sm">
                            {{ $offboarding->alasan_keluar ?? 'Tidak diisi.' }}</p>
                    </div>

                    <div class="space-y-2">
                        <h4 class="text-zinc-400 text-xs font-bold uppercase tracking-widest">Saran & Kesan:</h4>
                        <p class="text-zinc-800 bg-zinc-50 p-3 rounded-lg text-sm">
                            {{ $offboarding->saran_masukan ?? 'Tidak diisi.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
