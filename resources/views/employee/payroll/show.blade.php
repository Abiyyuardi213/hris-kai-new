@extends('layouts.employee')
@section('title', 'Slip Gaji Detail')

@section('content')
    <div class="flex flex-col space-y-6 max-w-3xl mx-auto pb-20">
        <!-- Header & Back -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('employee.payroll.index') }}"
                    class="h-10 w-10 flex items-center justify-center rounded-full bg-white border border-zinc-100 text-zinc-400 hover:text-zinc-900 transition-all shadow-sm">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Slip Gaji Digital</h2>
                    <p class="text-sm text-zinc-500">Periode
                        {{ \Carbon\Carbon::create()->month($payroll->month)->translatedFormat('F') }} {{ $payroll->year }}
                    </p>
                </div>
            </div>
            <button onclick="window.print()"
                class="hidden md:flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-bold text-white hover:bg-zinc-800 transition-all shadow-lg active:scale-95">
                <i data-lucide="printer" class="h-4 w-4"></i>
                Cetak Slip
            </button>
        </div>

        <!-- Slip content -->
        <div id="payroll-slip"
            class="bg-white rounded-3xl border border-zinc-100 shadow-2xl shadow-zinc-200/50 overflow-hidden print:border-none print:shadow-none">
            <!-- Slip Header -->
            <div class="p-8 border-b border-zinc-100 flex flex-col md:flex-row justify-between gap-6 bg-zinc-50/30">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('image/logo-kai.png') }}" alt="Logo KAI" class="h-12 w-auto">
                    <div class="border-l border-zinc-200 pl-4">
                        <h3 class="font-bold text-zinc-900 text-lg">PT KERETA API INDONESIA</h3>
                        <p class="text-xs text-zinc-500 font-medium">Divisi Sumber Daya Manusia</p>
                    </div>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">ID Referensi</p>
                    <p class="text-sm font-mono font-bold text-zinc-900 uppercase">#PAY-{{ substr($payroll->id, 0, 8) }}</p>
                </div>
            </div>

            <!-- Employee Info -->
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-zinc-50">
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Nama Pegawai</p>
                        <p class="text-md font-bold text-zinc-900">{{ $payroll->pegawai->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">NIP / Jabatan</p>
                        <p class="text-sm font-bold text-zinc-600">{{ $payroll->pegawai->nip }} •
                            {{ $payroll->pegawai->jabatan->name }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Status Pembayaran</p>
                        <span
                            class="inline-flex items-center rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase {{ $payroll->status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ $payroll->status === 'paid' ? 'LUNAS / DIBAYARKAN' : 'PENDING' }}
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
                    Rincian Penghasilan
                </h4>

                <div class="space-y-3">
                    <div class="flex justify-between items-center py-3 px-4 rounded-2xl bg-zinc-50/50">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-zinc-900">Gaji Pokok (Harian)</span>
                            <span class="text-[10px] text-zinc-500 font-medium">Rp
                                {{ number_format($payroll->gaji_harian, 0, ',', '.') }} x {{ $payroll->jumlah_hadir }}
                                Hari Hadir</span>
                        </div>
                        <span class="font-bold text-zinc-900">Rp
                            {{ number_format($payroll->gaji_harian * $payroll->jumlah_hadir, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 px-4 rounded-2xl bg-zinc-50/50">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-zinc-900">Tunjangan Jabatan</span>
                            <span class="text-[10px] text-zinc-500 font-medium">Tunjangan Tetap Bulanan</span>
                        </div>
                        <span class="font-bold text-zinc-900">Rp
                            {{ number_format($payroll->tunjangan_jabatan, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Summary Total -->
                <div class="mt-12 pt-8 border-t border-zinc-100">
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Total Diterima
                                (Netto)</p>
                            <h3 class="text-4xl font-black text-zinc-900 tracking-tight">Rp
                                {{ number_format($payroll->total_gaji, 0, ',', '.') }}</h3>
                        </div>
                        <div class="text-right hidden sm:block">
                            <p class="text-[11px] font-medium text-zinc-400 italic mb-4">Terbilang: #
                                {{ \App\Services\Terbilang::make($payroll->total_gaji) }} rupiah #</p>
                            <div class="mt-4 p-4 border-2 border-dashed border-zinc-100 rounded-3xl opacity-50">
                                <i data-lucide="qr-code" class="h-12 w-12 text-zinc-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="p-8 bg-zinc-900 text-white flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-[10px] font-medium text-zinc-400">Slip gaji ini dihasilkan secara otomatis oleh sistem HRIS
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
