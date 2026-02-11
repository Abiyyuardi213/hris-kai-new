@extends('layouts.employee')

@section('title', 'Riwayat Sanksi')

@section('content')
    <div class="font-medium">
        <div class="w-full">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold tracking-tight text-zinc-900">Riwayat Sanksi Disiplin</h1>
                    <p class="text-zinc-500 text-sm">Informasi surat peringatan atau sanksi yang pernah diterima.</p>
                </div>
            </div>

            @if ($sanctions->count() > 0)
                <!-- Grid Kartu Sanksi -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    @foreach ($sanctions as $sanction)
                        <a href="{{ route('employee.sanctions.show', $sanction->id) }}"
                            class="group block bg-white rounded-2xl border border-zinc-200 hover:border-zinc-300 hover:shadow-lg transition-all p-4 md:p-6 relative overflow-hidden">
                            <!-- Status Tag -->
                            <div class="absolute top-4 right-4">
                                @if ($sanction->status === 'Active')
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2 py-0.5 md:px-2.5 md:py-1 rounded-full text-[10px] md:text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @elseif($sanction->status === 'Expired')
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2 py-0.5 md:px-2.5 md:py-1 rounded-full text-[10px] md:text-xs font-bold bg-zinc-100 text-zinc-500 border border-zinc-200">
                                        Berakhir
                                    </span>
                                @else
                                    <span
                                        class="inline-flex px-2 py-0.5 md:px-2.5 md:py-1 rounded-full text-[10px] md:text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                                        Dicabut
                                    </span>
                                @endif
                            </div>

                            <div class="mb-4">
                                <div
                                    class="h-10 w-10 md:h-12 md:w-12 rounded-xl flex items-center justify-center mb-3
                        {{ $sanction->type === 'Termination'
                            ? 'bg-red-50 text-red-600'
                            : ($sanction->type === 'SP3'
                                ? 'bg-orange-50 text-orange-600'
                                : ($sanction->type === 'SP2'
                                    ? 'bg-yellow-50 text-yellow-600'
                                    : 'bg-blue-50 text-blue-600')) }}">
                                    <i data-lucide="alert-triangle" class="h-5 w-5 md:h-6 md:w-6"></i>
                                </div>
                                <h3
                                    class="font-bold text-base md:text-lg text-zinc-900 group-hover:text-blue-600 transition-colors">
                                    {{ $sanction->type }}</h3>
                                <p class="text-xs text-zinc-500 mt-1 line-clamp-2">{{ $sanction->description }}</p>
                            </div>

                            <div class="pt-4 border-t border-zinc-100 flex justify-between items-center text-sm">
                                <div>
                                    <p class="text-[10px] uppercase tracking-wider text-zinc-400 font-bold mb-0.5">Berlaku
                                        Mulai</p>
                                    <p class="font-semibold text-zinc-700 text-xs md:text-sm">
                                        {{ \Carbon\Carbon::parse($sanction->start_date)->format('d M Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] uppercase tracking-wider text-zinc-400 font-bold mb-0.5">Berakhir
                                    </p>
                                    <p class="font-semibold text-zinc-700 text-xs md:text-sm">
                                        {{ $sanction->end_date ? \Carbon\Carbon::parse($sanction->end_date)->format('d M Y') : 'Selamanya' }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $sanctions->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div
                    class="flex flex-col items-center justify-center py-12 md:py-20 bg-zinc-50 rounded-3xl border-2 border-dashed border-zinc-200 mt-6">
                    <div
                        class="h-16 w-16 md:h-20 md:w-20 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                        <i data-lucide="shield-check" class="h-8 w-8 md:h-10 md:w-10 text-emerald-500"></i>
                    </div>
                    <h3 class="text-base md:text-lg font-bold text-zinc-900">Bersih dari Sanksi</h3>
                    <p class="text-zinc-500 text-xs md:text-sm mt-1 max-w-sm text-center px-4">Tidak ada catatan sanksi atau
                        pelanggaran
                        disiplin yang tercatat. Pertahankan kinerja baik Anda!</p>
                </div>
            @endif
        </div>
    </div>
@endsection
