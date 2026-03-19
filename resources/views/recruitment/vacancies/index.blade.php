@extends('layouts.candidate')
@section('title', 'Lowongan Tersedia')

@section('content')
    <div class="flex flex-col space-y-6">
        <div class="flex flex-col gap-1">
            <h2 class="text-2xl font-black text-zinc-900 uppercase tracking-tight italic">Lowongan Tersedia</h2>
            <p class="text-sm font-medium text-zinc-500">Temukan karir impianmu bersama PT Kereta Api Indonesia (Persero).</p>
        </div>

        <div class="grid grid-cols-1 gap-4">
            @forelse ($vacancies as $item)
                <div class="bg-white rounded-2xl border border-zinc-200 p-6 shadow-sm hover:border-blue-300 hover:shadow-md transition-all group">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="space-y-2">
                            <h3 class="text-lg font-bold text-zinc-900 group-hover:text-blue-600 transition-colors">{{ $item->judul_lowongan }}</h3>
                            <div class="flex items-center gap-4 text-xs font-bold text-zinc-400 uppercase tracking-widest">
                                <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="h-3.5 w-3.5"></i> {{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('candidate.vacancies.show', $item->id) }}" class="inline-flex items-center justify-center h-11 px-6 bg-zinc-900 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-zinc-800 transition-all shadow-xl shadow-zinc-200">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border-2 border-dashed border-zinc-200 p-16 flex flex-col items-center justify-center text-center space-y-4">
                    <div class="h-16 w-16 rounded-full bg-zinc-50 flex items-center justify-center text-zinc-300">
                        <i data-lucide="briefcase" class="h-8 w-8"></i>
                    </div>
                    <div>
                        <p class="text-zinc-900 font-bold uppercase tracking-widest italic">Belum Ada Lowongan</p>
                        <p class="text-zinc-400 text-sm mt-1">Saat ini belum ada lowongan rekrutmen yang dibuka.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($vacancies->hasPages())
            <div class="mt-6">
                {{ $vacancies->links() }}
            </div>
        @endif
    </div>
@endsection
