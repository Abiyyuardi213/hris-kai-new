@extends('layouts.employee')
@section('title', 'Kinerja Saya')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Performa & KPI</h2>
                <p class="text-zinc-500 text-sm">Lihat hasil penilaian kinerja Anda setiap periode.</p>
            </div>
        </div>

        @forelse($appraisals as $appraisal)
            <div
                class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden hover:shadow-xl hover:border-zinc-300 transition-all duration-300">
                <div class="p-8 flex flex-col md:flex-row items-center gap-8">
                    <!-- Rating Badge -->
                    <div
                        class="flex flex-col items-center justify-center h-32 w-32 bg-zinc-900 rounded-3xl text-white shrink-0 shadow-lg shadow-zinc-200 transform hover:scale-105 transition-transform">
                        <p class="text-[10px] font-black uppercase opacity-50 tracking-widest mb-1">Rating</p>
                        <p class="text-5xl font-black italic tracking-tighter">{{ $appraisal->rating }}</p>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0 text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                            <span
                                class="px-2 py-0.5 bg-zinc-100 text-zinc-500 rounded text-[10px] font-black uppercase tracking-widest leading-none">TAHUN
                                {{ $appraisal->tahun }}</span>
                            <span
                                class="text-xs text-zinc-400 font-medium italic">{{ $appraisal->periode_mulai->format('M Y') }}
                                - {{ $appraisal->periode_selesai->format('M Y') }}</span>
                        </div>
                        <h3 class="text-2xl font-black text-zinc-900 italic uppercase tracking-tighter mb-2">Penilaian
                            Kinerja Tahunan</h3>
                        <p class="text-sm text-zinc-500 line-clamp-2 italic font-medium">
                            "{{ Str::limit($appraisal->catatan_reviewer, 150) }}"</p>
                    </div>

                    <!-- Score & Action -->
                    <div
                        class="flex flex-col items-center md:items-end gap-3 shrink-0 py-4 border-t md:border-t-0 md:border-l border-zinc-100 md:pl-8">
                        <div class="text-right">
                            <p
                                class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1 text-center md:text-right">
                                Final Score</p>
                            <p class="text-3xl font-black text-zinc-900 tracking-tighter text-center md:text-right">
                                {{ number_format($appraisal->total_score, 1) }}</p>
                        </div>
                        <a href="{{ route('employee.performance.show', $appraisal->id) }}"
                            class="px-6 py-2.5 rounded-xl bg-zinc-900 text-white text-xs font-bold hover:bg-zinc-800 transition-all flex items-center gap-2">
                            Lihat Detail Laporan
                            <i data-lucide="chevron-right" class="h-3 w-3"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl border border-zinc-100 p-20 text-center">
                <div class="h-20 w-20 bg-zinc-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-zinc-200">
                    <i data-lucide="award" class="h-10 w-10"></i>
                </div>
                <h3 class="text-xl font-black text-zinc-900 uppercase italic tracking-tighter">Belum Ada Riwayat Penilaian
                </h3>
                <p class="text-sm text-zinc-500 mt-2 max-w-xs mx-auto">Hasil penilaian kinerja tahunan Anda akan muncul di
                    sini setelah diproses oleh HRD.</p>
            </div>
        @endforelse

        @if ($appraisals->hasPages())
            <div class="pt-6">
                {{ $appraisals->links() }}
            </div>
        @endif
    </div>
@endsection
