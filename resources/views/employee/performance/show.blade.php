@extends('layouts.employee')
@section('title', 'Laporan Kinerja Saya')

@section('content')
    <div class="max-w-4xl mx-auto flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between no-print">
            <div class="flex items-center gap-4">
                <a href="{{ route('employee.performance.index') }}"
                    class="h-10 w-10 rounded-xl border border-zinc-200 bg-white flex items-center justify-center text-zinc-500">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Hasil Penilaian Kinerja</h2>
            </div>
            <a href="{{ route('employee.performance.print', $appraisal->id) }}" target="_blank"
                class="px-4 py-2 bg-white border border-zinc-200 rounded-xl text-zinc-600 text-sm font-bold flex items-center gap-2 hover:bg-zinc-50 transition-all">
                <i data-lucide="printer" class="h-4 w-4"></i>
                Cetak Hasil
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
                        <h3 class="text-2xl font-bold uppercase tracking-tight">Personal Performance Result</h3>
                        <p class="text-xs text-zinc-400 font-bold uppercase tracking-widest">Periode Tahun
                            {{ $appraisal->tahun }}</p>
                    </div>
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
                        <h4 class="text-xl font-bold text-zinc-900 uppercase tracking-tight">
                            {{ $appraisal->pegawai->nama_lengkap }}</h4>
                        <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mb-2">
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
                            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Total Score</p>
                            <p class="text-4xl font-bold text-zinc-900 tracking-tight">
                                {{ number_format($appraisal->total_score, 1) }}</p>
                        </div>
                        <div
                            class="h-16 w-16 bg-zinc-900 text-white rounded-2xl flex flex-col items-center justify-center shadow-lg transform rotate-3">
                            <p class="text-[8px] font-black uppercase opacity-50">Rating</p>
                            <p class="text-2xl font-bold">{{ $appraisal->rating }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indicators Breakdown -->
            <div class="p-10">
                <h4 class="text-xs font-black text-zinc-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="h-4 w-4"></i>
                    Rincian Pencapaian KPI
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    @foreach ($appraisal->items as $item)
                        <div class="relative group">
                            <div class="flex justify-between items-end mb-2">
                                <h5 class="text-sm font-bold text-zinc-900">{{ $item->indicator->name }}</h5>
                                <div class="text-right">
                                    <span class="text-sm font-black text-zinc-900">{{ $item->score }}</span>
                                    <span class="text-[10px] text-zinc-400">/100</span>
                                </div>
                            </div>
                            <div class="h-1.5 w-full bg-zinc-100 rounded-full overflow-hidden">
                                @php
                                    $barColor =
                                        $item->score >= 80
                                            ? 'bg-emerald-500'
                                            : ($item->score >= 60
                                                ? 'bg-amber-500'
                                                : 'bg-red-500');
                                @endphp
                                <div class="h-full {{ $barColor }} rounded-full group-hover:brightness-110 transition-all"
                                    style="width: {{ $item->score }}%"></div>
                            </div>
                            @if ($item->comment)
                                <div
                                    class="mt-2 p-2 bg-zinc-50 rounded-lg border border-zinc-100 text-[10px] text-zinc-500 font-medium">
                                    "{{ $item->comment }}"
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Reviewer Note -->
            <div class="p-10 border-t border-zinc-100 bg-zinc-50/20">
                <h4 class="text-xs font-bold text-zinc-900 uppercase tracking-widest mb-4">Ulasan Penilai untuk Anda</h4>
                <div
                    class="bg-white p-6 rounded-2xl border border-zinc-100 shadow-sm text-sm text-zinc-600 leading-relaxed font-medium">
                    "{{ $appraisal->catatan_reviewer ?? 'Tidak ada catatan tambahan.' }}"
                </div>
            </div>

            <!-- Signatures Section (Always at bottom) -->
            <div class="p-10 grid grid-cols-2 gap-20 border-t border-zinc-50">
                <div class="text-center">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-16 text-center">Tanda Tangan
                        Penilai</p>
                    <div class="border-b border-zinc-900 mx-auto w-48 mb-1"></div>
                    <p class="text-xs font-bold uppercase tracking-tight">{{ $appraisal->appraiser->name }}</p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-16 text-center">Penerima
                        Laporan</p>
                    <div class="border-b border-zinc-900 mx-auto w-48 mb-1"></div>
                    <p class="text-xs font-bold uppercase tracking-tight">{{ $appraisal->pegawai->nama_lengkap }}
                    </p>
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

            .max-w-4xl {
                max-width: 100%;
            }
        }
    </style>
@endsection
