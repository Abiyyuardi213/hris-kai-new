@extends('layouts.app')
@section('title', 'Laporan Kinerja - ' . $appraisal->pegawai->nama_lengkap)

@section('content')
    <div class="max-w-4xl mx-auto flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between no-print">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.performance.index') }}"
                    class="h-10 w-10 rounded-xl border border-zinc-200 bg-white flex items-center justify-center text-zinc-500">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Laporan Penilaian Kinerja</h2>
            </div>
            <a href="{{ route('admin.performance.print', $appraisal->id) }}" target="_blank"
                class="px-4 py-2 bg-white border border-zinc-200 rounded-xl text-zinc-600 text-sm font-bold flex items-center gap-2 hover:bg-zinc-50 transition-all">
                <i data-lucide="printer" class="h-4 w-4"></i>
                Cetak Laporan
            </a>
        </div>

        <!-- Main Report Card -->
        <div
            class="bg-white rounded-3xl border border-zinc-200 shadow-xl overflow-hidden print:shadow-none print:border-none">
            <!-- Branding / Header -->
            <div class="px-10 py-8 bg-zinc-900 text-white flex justify-between items-start">
                <div class="space-y-4">
                    <img src="{{ asset('image/logo-kai.png') }}" class="h-10 w-auto brightness-0 invert">
                    <div>
                        <h3 class="text-2xl font-black italic uppercase tracking-tighter">Performance Result Card</h3>
                        <p class="text-xs text-zinc-400 font-bold uppercase tracking-widest">Tahun Anggaran
                            {{ $appraisal->tahun }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest mb-1">Status Penilaian</div>
                    <span
                        class="px-3 py-1 bg-emerald-500 text-white rounded-lg text-[10px] font-black uppercase tracking-widest">
                        {{ $appraisal->status }}
                    </span>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 p-10 border-b border-zinc-100">
                <div class="flex gap-6 items-center">
                    @if ($appraisal->pegawai->foto)
                        <img src="{{ asset('storage/' . $appraisal->pegawai->foto) }}"
                            class="h-24 w-24 rounded-2xl object-cover border-4 border-zinc-50">
                    @else
                        <div
                            class="h-24 w-24 rounded-2xl bg-zinc-100 flex items-center justify-center border-4 border-zinc-50 text-zinc-300">
                            <i data-lucide="user" class="h-10 w-10"></i>
                        </div>
                    @endif
                    <div>
                        <h4 class="text-xl font-black text-zinc-900 uppercase italic tracking-tighter">
                            {{ $appraisal->pegawai->nama_lengkap }}</h4>
                        <p class="text-[10px] text-zinc-400 font-black uppercase tracking-widest mb-2">
                            {{ $appraisal->pegawai->jabatan->name ?? '-' }}</p>
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-bold text-zinc-600">NIP: {{ $appraisal->pegawai->nip }}</span>
                            <span class="text-xs font-bold text-zinc-500">Divisi:
                                {{ $appraisal->pegawai->divisi->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-center items-end text-right">
                    <div class="bg-zinc-50 p-6 rounded-3xl border border-zinc-100 flex items-center gap-6">
                        <div>
                            <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Final Score</p>
                            <p class="text-4xl font-black text-zinc-900 tracking-tighter">
                                {{ number_format($appraisal->total_score, 1) }}</p>
                        </div>
                        <div
                            class="h-16 w-16 bg-zinc-900 text-white rounded-2xl flex flex-col items-center justify-center shadow-lg">
                            <p class="text-[8px] font-black uppercase opacity-50">Rating</p>
                            <p class="text-2xl font-black italic">{{ $appraisal->rating }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indicators Breakdown -->
            <div class="p-10">
                <h4 class="text-xs font-black text-zinc-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="h-4 w-4"></i>
                    Breakdown Indikator KPI
                </h4>
                <div class="space-y-6">
                    @foreach ($appraisal->items as $item)
                        <div class="relative">
                            <div class="flex justify-between items-end mb-2">
                                <div>
                                    <span
                                        class="text-[9px] font-black text-zinc-400 uppercase tracking-tighter">[{{ $item->indicator->category }}]</span>
                                    <h5 class="text-sm font-bold text-zinc-900">{{ $item->indicator->name }}</h5>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-black text-zinc-900">{{ $item->score }}</span>
                                    <span class="text-[10px] text-zinc-400">/100</span>
                                </div>
                            </div>
                            <div class="h-2 w-full bg-zinc-100 rounded-full overflow-hidden">
                                @php
                                    $barColor =
                                        $item->score >= 80
                                            ? 'bg-emerald-500'
                                            : ($item->score >= 60
                                                ? 'bg-amber-500'
                                                : 'bg-red-500');
                                @endphp
                                <div class="h-full {{ $barColor }} rounded-full" style="width: {{ $item->score }}%">
                                </div>
                            </div>
                            @if ($item->comment)
                                <p class="mt-2 text-[11px] text-zinc-500 italic font-medium">"{{ $item->comment }}"</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Reviewer Note -->
            <div class="p-10 border-t border-zinc-100 bg-zinc-50/50">
                <h4 class="text-xs font-black text-zinc-900 uppercase tracking-widest mb-4">Catatan Akhir Penilai</h4>
                <div
                    class="bg-white p-6 rounded-2xl border border-zinc-100 shadow-sm italic text-sm text-zinc-600 leading-relaxed font-medium">
                    "{{ $appraisal->catatan_reviewer ?? 'Tidak ada catatan tambahan.' }}"
                </div>
            </div>

            <!-- Signatures -->
            <div class="p-10 grid grid-cols-2 gap-20">
                <div class="text-center">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-20 text-center">Petugas
                        Penilai</p>
                    <div class="border-b border-zinc-900 mx-auto w-48 mb-1"></div>
                    <p class="text-xs font-black uppercase italic tracking-tighter">{{ $appraisal->appraiser->name }}</p>
                    <p class="text-[9px] font-bold text-zinc-400 uppercase">Administrator HRIS</p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-20 text-center">Pegawai
                        Terkait</p>
                    <div class="border-b border-zinc-900 mx-auto w-48 mb-1"></div>
                    <p class="text-xs font-black uppercase italic tracking-tighter">{{ $appraisal->pegawai->nama_lengkap }}
                    </p>
                    <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-tighter">NIP:
                        {{ $appraisal->pegawai->nip }}</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body {
                background: white;
            }

            .no-print {
                display: none;
            }

            .print\:shadow-none {
                shadow: none;
            }

            .max-w-4xl {
                max-width: 100%;
            }
        }
    </style>
@endsection
