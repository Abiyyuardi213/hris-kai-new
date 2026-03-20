@extends('layouts.employee')
@section('title', 'Detail Slip Project')

@section('content')
    <div class="flex flex-col space-y-6 max-w-3xl mx-auto pb-20">
        <!-- Header & Back -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('employee.project-payroll.index') }}"
                    class="h-10 w-10 flex items-center justify-center rounded-full bg-white border border-zinc-100 text-zinc-400 hover:text-zinc-900 transition-all shadow-sm">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Slip Project Digital</h2>
                    <p class="text-sm text-zinc-500">Periode
                        {{ \Carbon\Carbon::create()->month($projectPayroll->month)->translatedFormat('F') }} {{ $projectPayroll->year }}
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('employee.project-payroll.print', $projectPayroll->id) }}" target="_blank"
                    class="hidden md:flex items-center gap-2 rounded-xl bg-orange-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-orange-700 transition-all shadow-lg active:scale-95 shadow-orange-600/20">
                    <i data-lucide="file-text" class="h-4 w-4"></i>
                    Unduh PDF
                </a>
                <button onclick="window.print()"
                    class="hidden md:flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-bold text-white hover:bg-zinc-800 transition-all shadow-lg active:scale-95">
                    <i data-lucide="printer" class="h-4 w-4"></i>
                    Cetak Slip
                </button>
            </div>
        </div>

        <!-- Slip content -->
        <div id="payroll-slip"
            class="bg-white rounded-3xl border border-zinc-100 shadow-2xl shadow-zinc-200/50 overflow-hidden print:border-none print:shadow-none">
            <!-- Slip Header -->
            <div class="p-8 border-b border-zinc-100 flex flex-col md:flex-row justify-between gap-6 bg-zinc-50/30">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('image/logo-kai.png') }}" alt="Logo KAI" class="h-12 w-auto">
                    <div class="border-l border-zinc-200 pl-4">
                        <h3 class="font-bold text-zinc-900 text-lg uppercase tracking-tight">PT KERETA API INDONESIA</h3>
                        <p class="text-xs text-zinc-500 font-medium tracking-wide">Slip Gaji Project (Bonus Tahunan/Proyek)</p>
                    </div>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">ID Referensi</p>
                    <p class="text-sm font-mono font-bold text-zinc-900 uppercase">#PROJ-{{ substr($projectPayroll->id, 0, 8) }}</p>
                </div>
            </div>

            <!-- Employee Info -->
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-zinc-50">
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Nama Pegawai</p>
                        <p class="text-md font-bold text-zinc-900">{{ Auth::guard('employee')->user()->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">NIP / Jabatan</p>
                        <p class="text-sm font-bold text-zinc-600">{{ Auth::guard('employee')->user()->nip }} •
                            {{ Auth::guard('employee')->user()->jabatan->name ?? '-' }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Status Pembayaran</p>
                        <span
                            class="inline-flex items-center rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase {{ $projectPayroll->status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ $projectPayroll->status === 'paid' ? 'LUNAS / DIBAYARKAN' : 'PENDING' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Tanggal Cetak</p>
                        <p class="text-sm font-bold text-zinc-900">{{ date('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Calculation Details -->
            <div class="p-8 space-y-6">
                <h4 class="font-bold text-zinc-900 flex items-center gap-2">
                    <i data-lucide="layers" class="h-4 w-4 text-zinc-400"></i>
                    Rincian Upah Project
                </h4>

                <div class="space-y-3">
                    <div class="flex justify-between items-center py-5 px-6 rounded-2xl bg-zinc-50/50 border border-zinc-100/50">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-zinc-900">{{ $projectPayroll->project_name }}</span>
                            <span class="text-[11px] text-zinc-500 font-medium max-w-[400px]">
                                {{ $projectPayroll->keterangan ?? 'Pembayaran upah tambahan berdasarkan kinerja project.' }}
                            </span>
                        </div>
                        <span class="text-lg font-black text-zinc-900">Rp {{ number_format($projectPayroll->total_pay, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Summary Total -->
                <div class="mt-12 pt-8 border-t border-zinc-100">
                    <div class="flex justify-between items-end">
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Total Diterima (Netto)</p>
                            <h3 class="text-5xl font-black text-zinc-900 tracking-tight">Rp
                                {{ number_format($projectPayroll->total_pay, 0, ',', '.') }}</h3>
                        </div>
                        <div class="text-right hidden sm:block">
                            <p class="text-[11px] font-medium text-zinc-400 italic mb-4">Terbilang: #
                                {{ \App\Services\Terbilang::make($projectPayroll->total_pay) }} rupiah #</p>
                            <div class="mt-4 p-4 border-2 border-dashed border-zinc-100 rounded-3xl opacity-50 flex items-center justify-center">
                                <i data-lucide="qr-code" class="h-14 w-14 text-zinc-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="p-8 bg-zinc-900 text-white flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-[10px] font-medium text-zinc-400">Slip project ini dihasilkan secara otomatis oleh sistem HRIS
                    KAI dan sah tanpa tanda tangan basah.</p>
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-400">Verified System</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #payroll-slip,
            #payroll-slip * {
                visibility: visible;
            }

            #payroll-slip {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .sidebar-nav,
            .mobile-nav,
            .header-actions {
                display: none !important;
            }
        }
    </style>
@endsection
