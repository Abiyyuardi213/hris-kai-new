@extends('layouts.candidate')
@section('title', 'Dashboard Pelamar')

@section('content')
<div class="max-w-[1000px] mx-auto space-y-8">
    <!-- Welcome Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Halo, {{ Auth::guard('candidate')->user()->name }}!</h2>
            <p class="text-sm text-zinc-500 mt-1">Selamat datang kembali di portal rekrutmen PT Kereta Api Indonesia (Persero).</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 bg-zinc-100 px-3 py-1 rounded-full italic">{{ now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl border border-zinc-200 p-6 shadow-sm flex items-center gap-4 transition-all hover:border-zinc-300">
            <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i data-lucide="send" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest leading-none mb-1">Total Lamaran</p>
                <p class="text-2xl font-black text-zinc-900 leading-none">{{ $applications->count() }}</p>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl border border-zinc-200 p-6 shadow-sm flex items-center gap-4 transition-all hover:border-zinc-300">
            <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="clock" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest leading-none mb-1">Proses Seleksi</p>
                <p class="text-2xl font-black text-zinc-900 leading-none">{{ $applications->whereNotIn('status', ['hired', 'rejected'])->count() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-zinc-200 p-6 shadow-sm flex items-center gap-4 transition-all hover:border-zinc-300">
            <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i data-lucide="award" class="h-6 w-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest leading-none mb-1">Berkas Terkirim</p>
                <p class="text-2xl font-black text-zinc-900 leading-none">{{ Auth::guard('candidate')->user()->documents()->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Active Applications -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-zinc-100 flex items-center justify-between bg-zinc-50/50">
            <h3 class="text-xs font-black uppercase tracking-[0.2em] text-zinc-900 italic">Status Lamaran Pekerjaan Anda</h3>
            <a href="{{ route('candidate.vacancies') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-tighter hover:underline">Lihat Lowongan Lainnya</a>
        </div>
        
        <div class="w-full overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50/50 text-zinc-500 border-b uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-6 py-4 font-medium">Lowongan Pekerjaan</th>
                        <th class="px-6 py-4 font-medium">Status Terkini</th>
                        <th class="px-6 py-4 font-medium text-right">Tanggal Lamar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 italic">
                    @forelse ($applications as $app)
                        <tr class="hover:bg-zinc-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-zinc-900 group-hover:text-zinc-900 uppercase leading-snug">{{ $app->jobVacancy->judul_lowongan }}</div>
                                <div class="text-[10px] text-zinc-400 font-bold uppercase tracking-tighter mt-0.5">{{ $app->jobVacancy->status == 'open' ? 'Pendaftaran Dibuka' : 'Pendaftaran Ditutup' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-zinc-100', 'text' => 'text-zinc-600', 'label' => 'TERKIRIM'],
                                        'reviewing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'DIREVIEW'],
                                        'interview' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'INTERVIEW'],
                                        'test' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'TES SELEKSI'],
                                        'hired' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'DITERIMA'],
                                        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'TIDAK LOLOS'],
                                    ];
                                    $cfg = $statusConfig[$app->status] ?? ['bg' => 'bg-zinc-100', 'text' => 'text-zinc-600', 'label' => strtoupper($app->status)];
                                @endphp
                                <div class="flex items-center gap-2">
                                     <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[9px] font-black {{ $cfg['bg'] }} {{ $cfg['text'] }} uppercase tracking-widest shadow-sm">
                                        {{ $cfg['label'] }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-xs font-bold text-zinc-500 italic">{{ $app->created_at->format('d/m/Y') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-16 w-16 rounded-full bg-zinc-50 flex items-center justify-center text-zinc-200 mb-4">
                                        <i data-lucide="inbox" class="h-8 w-8"></i>
                                    </div>
                                    <p class="text-sm font-bold text-zinc-400 uppercase tracking-widest italic">Belum ada lamaran terkirim</p>
                                    <a href="{{ route('candidate.vacancies') }}" class="mt-4 px-6 py-2 bg-zinc-900 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-lg hover:bg-zinc-800 transition-all italic">Cari Lowongan Sekarang</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Instructions / Tips Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-2xl border border-zinc-200 p-8 shadow-sm">
            <h4 class="text-xs font-black uppercase tracking-[0.2em] text-zinc-900 border-b pb-4 mb-6 italic flex items-center gap-3">
                <i data-lucide="info" class="h-4 w-4 text-blue-600"></i>
                Informasi Penting
            </h4>
            <div class="space-y-4">
                <div class="flex gap-4">
                    <div class="shrink-0 h-8 w-8 rounded-lg bg-zinc-50 flex items-center justify-center text-zinc-400 text-xs font-black italic shadow-inner">01</div>
                    <p class="text-xs text-zinc-600 leading-relaxed font-medium italic">Pastikan seluruh data profil dan pendidikan telah diisi secara lengkap sebelum melamar pekerjaan.</p>
                </div>
                <div class="flex gap-4">
                    <div class="shrink-0 h-8 w-8 rounded-lg bg-zinc-50 flex items-center justify-center text-zinc-400 text-xs font-black italic shadow-inner">02</div>
                    <p class="text-xs text-zinc-600 leading-relaxed font-medium italic">Unggah dokumen pendukung (CV, Ijazah, KTP) pada menu File Dokumen sesuai dengan format yang diminta.</p>
                </div>
                <div class="flex gap-4">
                    <div class="shrink-0 h-8 w-8 rounded-lg bg-zinc-50 flex items-center justify-center text-zinc-400 text-xs font-black italic shadow-inner">03</div>
                    <p class="text-xs text-zinc-600 leading-relaxed font-medium italic">Pantau secara berkala email Anda dan dashboard ini untuk pembaruan status kelulusan seleksi.</p>
                </div>
            </div>
        </div>

        <div class="bg-zinc-900 rounded-2xl p-8 shadow-xl shadow-zinc-200 relative overflow-hidden group">
            <div class="absolute -right-12 -top-12 h-40 w-40 bg-white/5 rounded-full blur-2xl group-hover:bg-white/10 transition-all"></div>
            <div class="relative z-10 h-full flex flex-col justify-between">
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] text-white/40 mb-4 italic">Bantuan & Support</h4>
                    <p class="text-sm text-white font-bold leading-relaxed">Mengalami kendala saat pendaftaran atau pengunggahan berkas?</p>
                </div>
                <div class="mt-8 flex items-center gap-4">
                    <a href="#" class="px-6 py-2.5 bg-white text-zinc-900 text-xs font-black uppercase tracking-[0.15em] rounded-lg hover:bg-zinc-100 transition-all italic">Pusat Bantuan</a>
                    <span class="text-white/20 font-black italic text-[10px]">PT KAI (PERSERO) 2026</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
