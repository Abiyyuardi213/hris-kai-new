@extends('layouts.employee')
@section('title', 'Jaminan Kesehatan Mandiri Inhealth')

@section('content')
    <div class="flex flex-col space-y-6 max-w-5xl mx-auto pb-20">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col gap-4">
            <div class="flex flex-col">
                <h2 class="text-xl md:text-3xl font-black tracking-tight text-zinc-900 uppercase italic">Layanan Kesehatan</h2>
                <nav class="flex mt-1" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-[9px] md:text-[10px] font-black uppercase tracking-widest text-zinc-400">
                        <li class="inline-flex items-center">
                            <a href="{{ route('employee.dashboard') }}" class="hover:text-zinc-900 transition-colors uppercase italic">Dashboard</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i data-lucide="chevron-right" class="h-3 w-3 mx-1"></i>
                                <span class="uppercase italic">Mandiri Inhealth</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="flex">
                <a href="{{ route('employee.insurance.print') }}" target="_blank"
                    class="flex items-center justify-center gap-3 w-full md:w-auto px-6 py-3.5 rounded-2xl bg-blue-600 text-white text-[10px] md:text-xs font-black uppercase italic tracking-widest hover:bg-blue-700 transition-all shadow-xl shadow-blue-200 active:scale-[0.98]">
                    <i data-lucide="download" class="h-4 w-4"></i>
                    Unduh Sertifikat PDF
                </a>
            </div>
        </div>

        <!-- Main Card Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Insurance Card Visualization -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Digital Card Preview -->
                <div class="relative overflow-hidden rounded-[1.5rem] md:rounded-[2.5rem] bg-gradient-to-br from-blue-700 via-blue-800 to-blue-900 p-6 md:p-10 text-white shadow-2xl shadow-blue-900/40 min-h-[400px] flex flex-col justify-between group">
                    <!-- Background Decoration -->
                    <div class="absolute top-0 right-0 -mr-20 -mt-20 h-80 w-80 rounded-full bg-white/5 blur-3xl group-hover:bg-white/10 transition-all duration-700"></div>
                    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 h-64 w-64 rounded-full bg-blue-400/10 blur-3xl"></div>
                    
                    <!-- Card Top -->
                    <div class="flex flex-col md:flex-row items-start justify-between gap-6 relative z-10">
                        <div class="flex items-center gap-3 md:gap-4">
                            <img src="{{ asset('image/logo-mandiri-inhealth.png') }}" alt="Mandiri Inhealth" class="h-8 md:h-14 w-auto brightness-0 invert">
                            <div class="h-6 md:h-10 w-[1px] bg-white/20"></div>
                            <img src="{{ asset('image/logo-kai.png') }}" alt="PT KAI" class="h-6 md:h-10 w-auto brightness-0 invert opacity-80">
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-[8px] md:text-[10px] font-black uppercase tracking-[0.2em] text-blue-200 mb-1 leading-none">Status Kepesertaan</p>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 md:py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-[9px] md:text-[10px] font-black uppercase tracking-widest border border-emerald-500/30">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Active Member
                            </span>
                        </div>
                    </div>

                    <!-- Card Center -->
                    <div class="relative z-10 space-y-3 md:space-y-4">
                        <p class="text-[8px] md:text-[10px] font-black uppercase tracking-[0.3em] text-blue-300 italic">Member Identification Card</p>
                        <div>
                            <h3 class="text-xl md:text-4xl font-black italic tracking-tight uppercase leading-tight">{{ $employee->nama_lengkap }}</h3>
                            <p class="text-[10px] md:text-sm font-bold text-blue-200 mt-2 uppercase tracking-widest opacity-80 leading-relaxed">{{ $employee->jabatan->name }} • {{ $employee->nip }}</p>
                        </div>
                        <div class="pt-4 flex flex-col md:flex-row md:items-center gap-4 md:gap-8 border-t border-white/10">
                            <div>
                                <p class="text-[8px] md:text-[9px] font-black uppercase tracking-[0.2em] text-blue-400 mb-1">Nomor Peserta</p>
                                <p class="text-sm md:text-lg font-black font-mono tracking-widest break-all md:break-normal">{{ $insuranceData['card_number'] }}</p>
                            </div>
                            <div>
                                <p class="text-[8px] md:text-[9px] font-black uppercase tracking-[0.2em] text-blue-400 mb-1">Paket Layanan</p>
                                <p class="text-sm md:text-lg font-black italic tracking-tighter">{{ $insuranceData['plan_name'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Bottom -->
                    <div class="flex items-end justify-between relative z-10 mt-6 md:mt-0">
                        <div class="space-y-4 hidden md:block">
                            <p class="text-[8px] font-medium leading-relaxed max-w-xs text-blue-200/60 uppercase italic tracking-wider">
                                Kartu ini merupakan bukti sah kepesertaan asuransi kesehatan Mandiri Inhealth bagi seluruh keluarga pegawai PT Kereta Api Indonesia (Persero).
                            </p>
                        </div>
                        <div class="flex items-center gap-4 ml-auto">
                            <div class="p-1.5 md:p-2 bg-white rounded-lg md:rounded-xl shadow-lg ring-4 ring-white/10">
                                <i data-lucide="qr-code" class="h-8 w-8 md:h-10 md:w-10 text-zinc-900"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Simulation Info Table -->
                <div class="bg-white rounded-2xl md:rounded-3xl border border-zinc-100 shadow-xl shadow-zinc-200/50 overflow-hidden">
                    <div class="p-5 md:p-6 border-b border-zinc-50 bg-zinc-50/50 flex items-center gap-3">
                        <div class="h-7 w-7 md:h-8 md:w-8 rounded-lg md:rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i data-lucide="shield-check" class="h-4 w-4"></i>
                        </div>
                        <h4 class="text-[10px] md:text-xs font-black text-zinc-900 uppercase italic tracking-widest">Detail Manfaat Layanan</h4>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-[10px] md:text-xs text-left border-collapse">
                            <tbody class="divide-y divide-zinc-50">
                                @foreach($insuranceData['benefits'] as $benefit)
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-4 md:px-8 py-4 w-10 md:w-12 text-blue-400">
                                        <i data-lucide="check-circle-2" class="h-3.5 w-3.5 md:h-4 md:w-4"></i>
                                    </td>
                                    <td class="px-2 py-4 font-black text-zinc-700 uppercase italic tracking-tight transition-all">{{ $benefit }}</td>
                                    <td class="px-4 md:px-8 py-4 text-right">
                                        <span class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-600 font-bold text-[8px] md:text-[9px] uppercase tracking-tighter whitespace-nowrap">Covered</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Side Info Panels -->
            <div class="space-y-6 md:space-y-8">
                <!-- Summary Card -->
                <div class="bg-blue-900 rounded-3xl md:rounded-[2rem] p-6 md:p-8 text-white shadow-2xl shadow-blue-900/20 relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/5 blur-2xl"></div>
                    <div class="relative z-10 space-y-6">
                        <p class="text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] text-blue-300">Konfirmasi Premi</p>
                        <div class="space-y-1">
                            <h4 class="text-2xl md:text-3xl font-black italic tracking-tighter leading-none">Rp {{ number_format($insuranceData['yearly_premium'], 0, ',', '.') }}</h4>
                            <p class="text-[9px] md:text-[10px] font-bold text-blue-200 uppercase tracking-widest italic opacity-60">Premi Per Tahun (Ultimate Plan)</p>
                        </div>
                        <div class="pt-6 border-t border-white/10">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-[9px] md:text-[10px] font-bold uppercase text-blue-300 tracking-widest">Potongan Payroll</span>
                                <span class="text-xs md:text-sm font-black italic">Rp {{ number_format($insuranceData['yearly_premium'] / 12, 0, ',', '.') }}<span class="text-[10px] opacity-40 ml-1">/bln</span></span>
                            </div>
                            <p class="text-[9px] font-medium text-blue-200 leading-relaxed uppercase italic opacity-50">
                                Pemotongan melalui sistem payroll PT KAI setiap bulan untuk fasilitas jaminan kesehatan terbaik.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Support Card -->
                <div class="bg-white rounded-3xl md:rounded-[2rem] border border-zinc-100 p-6 md:p-8 shadow-xl shadow-zinc-100 flex flex-col items-center text-center space-y-6">
                    <div class="h-14 w-14 md:h-16 md:w-16 rounded-2xl md:rounded-3xl bg-zinc-50 flex items-center justify-center text-blue-600 shadow-inner">
                        <i data-lucide="headphones" class="h-7 w-7 md:h-8 md:w-8"></i>
                    </div>
                    <div>
                        <h4 class="text-xs md:text-sm font-black text-zinc-900 uppercase italic leading-none">Inhealth Support</h4>
                        <p class="text-[9px] md:text-[10px] font-bold text-zinc-400 uppercase tracking-widest mt-2">Bantuan 24 Jam</p>
                    </div>
                    <div class="w-full pt-6 border-t border-zinc-50 space-y-3">
                        <a href="tel:1500500" class="flex items-center justify-center gap-2 w-full py-3.5 rounded-xl bg-zinc-900 text-white text-[10px] md:text-xs font-black italic hover:bg-zinc-800 transition-all uppercase tracking-widest">
                            <i data-lucide="phone" class="h-3 w-3"></i>
                            Call 1500 500
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
