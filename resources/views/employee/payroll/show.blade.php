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
            <div class="flex gap-2">
                <a href="{{ route('employee.payroll.print', $payroll->id) }}" target="_blank"
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
                            <span class="text-sm font-bold text-zinc-900">Upah Pokok</span>
                            <span class="text-[10px] text-zinc-500 font-medium">Gaji Bulanan Tetap</span>
                        </div>
                        <span class="font-bold text-zinc-900">Rp {{ number_format($payroll->gaji_pokok, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 px-4 rounded-2xl bg-zinc-50/50">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-zinc-900">Tunjangan Jabatan</span>
                            <span class="text-[10px] text-zinc-500 font-medium">Tunjangan Sesuai Posisi</span>
                        </div>
                        <span class="font-bold text-zinc-900">Rp {{ number_format($payroll->tunjangan_jabatan, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 px-4 rounded-2xl bg-zinc-50/50">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-zinc-900">Tunjangan Perumahan</span>
                            <span class="text-[10px] text-zinc-500 font-medium">Fasilitas Tempat Tinggal</span>
                        </div>
                        <span class="font-bold text-zinc-900">Rp {{ number_format($payroll->tunjangan_perumahan, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 px-4 rounded-2xl bg-zinc-50/50">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-zinc-900">Tunjangan Admin Bank</span>
                            <span class="text-[10px] text-zinc-500 font-medium">Biaya Administrasi Payroll</span>
                        </div>
                        <span class="font-bold text-zinc-900">Rp {{ number_format($payroll->tunjangan_admin_bank, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 px-4 rounded-2xl bg-zinc-50/50">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-zinc-900">Tunjangan Iuran JPK</span>
                            <span class="text-[10px] text-zinc-500 font-medium">BPJS Kesehatan (4%)</span>
                        </div>
                        <span class="font-bold text-zinc-900">Rp {{ number_format($payroll->tunjangan_jpk, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 px-4 rounded-2xl bg-zinc-50/50">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-zinc-900">Tunjangan Pajak</span>
                            <span class="text-[10px] text-zinc-500 font-medium">Pajak PPh 21 Ditanggung Perusahaan</span>
                        </div>
                        <span class="font-bold text-zinc-900">Rp {{ number_format($payroll->tunjangan_pajak, 0, ',', '.') }}</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex justify-between items-center py-3 px-4 rounded-2xl bg-blue-50/50">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-blue-800">ER Jamsostek JKK</span>
                                <span class="text-[9px] text-blue-600 font-medium">Premier JKK (0.24%)</span>
                            </div>
                            <span class="font-bold text-blue-800 text-sm">Rp {{ number_format($payroll->er_jamsostek_jkk, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 px-4 rounded-2xl bg-blue-50/50">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-blue-800">ER Jamsostek JHT</span>
                                <span class="text-[9px] text-blue-600 font-medium">Tabungan Hari Tua (3.7%)</span>
                            </div>
                            <span class="font-bold text-blue-800 text-sm">Rp {{ number_format($payroll->er_jamsostek_jht, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 px-4 rounded-2xl bg-blue-50/50">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-blue-800">ER Jamsostek JKM</span>
                                <span class="text-[9px] text-blue-600 font-medium">Jaminan Kematian (0.3%)</span>
                            </div>
                            <span class="font-bold text-blue-800 text-sm">Rp {{ number_format($payroll->er_jamsostek_jkm, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 px-4 rounded-2xl bg-emerald-50/50">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-emerald-800">JPK Pensiun</span>
                                <span class="text-[9px] text-emerald-600 font-medium">Iuran Pensiun (2%)</span>
                            </div>
                            <span class="font-bold text-emerald-800 text-sm">Rp {{ number_format($payroll->tunjangan_jpk_pensiun, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 px-4 rounded-2xl bg-emerald-50/50">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-emerald-800">JP BPJS</span>
                                <span class="text-[9px] text-emerald-600 font-medium">Jaminan Pensiun (2%)</span>
                            </div>
                            <span class="font-bold text-emerald-800 text-sm">Rp {{ number_format($payroll->tunjangan_jp_bpjs, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 px-4 rounded-2xl bg-red-50/50 border border-red-100">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-red-800 uppercase italic">Mandiri Inhealth</span>
                                <span class="text-[9px] text-red-600 font-medium uppercase tracking-tight">Tagihan Asuransi Kesehatan</span>
                            </div>
                            <span class="font-bold text-red-800 text-sm italic">- Rp {{ number_format($payroll->potongan_mandiri_inhealth, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if ($payroll->thr > 0)
                        <div class="flex justify-between items-center py-3 px-4 rounded-2xl bg-amber-50/50">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-amber-700">Tunjangan Hari Raya (THR)</span>
                                <span class="text-[10px] text-amber-600 font-medium">Tunjangan Khusus Hari Raya</span>
                            </div>
                            <span class="font-bold text-amber-700">Rp {{ number_format($payroll->thr, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if ($payroll->bonus > 0)
                        <div class="flex justify-between items-center py-3 px-4 rounded-2xl bg-emerald-50/50">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-emerald-700">Bonus & Insentif</span>
                                <span class="text-[10px] text-emerald-600 font-medium">{{ $payroll->keterangan_bonus ?? 'Bonus Tambahan' }}</span>
                            </div>
                            <span class="font-bold text-emerald-700">Rp {{ number_format($payroll->bonus, 0, ',', '.') }}</span>
                        </div>
                    @endif
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
