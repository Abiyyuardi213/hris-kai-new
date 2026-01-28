@extends('layouts.employee')
@section('title', 'Perjalanan Dinas Saya')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Perjalanan Dinas</h2>
                <p class="text-zinc-500 text-sm">Daftar penugasan dan pengajuan perjalanan dinas Anda.</p>
            </div>
            <a href="{{ route('employee.perjalanan_dinas.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-zinc-800 transition-all active:scale-95">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Ajukan Perjalanan
            </a>
        </div>

        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
                <i data-lucide="check-circle" class="h-5 w-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($trips as $trip)
                <div
                    class="group bg-white rounded-3xl border border-zinc-200 shadow-sm hover:shadow-xl hover:border-zinc-300 transition-all duration-300 overflow-hidden flex flex-col">
                    <div class="p-6 flex-1">
                        <div class="flex items-start justify-between mb-4">
                            @php
                                $colors = [
                                    'Pengajuan' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'Disetujui' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'Ditolak' => 'bg-red-50 text-red-600 border-red-100',
                                    'Sedang Berjalan' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'Selesai' => 'bg-zinc-50 text-zinc-600 border-zinc-100',
                                    'Dibatalkan' => 'bg-zinc-50 text-zinc-400 border-zinc-100',
                                ];
                                $class = $colors[$trip->status] ?? 'bg-zinc-50 text-zinc-600 border-zinc-100';
                            @endphp
                            <span
                                class="px-2.5 py-1 rounded-lg border text-[9px] font-black uppercase tracking-widest {{ $class }}">
                                {{ $trip->status }}
                            </span>
                            <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-tighter">
                                {{ $trip->no_surat_tugas ?? 'Draft No. Surat' }}
                            </div>
                        </div>

                        <h3
                            class="text-xl font-black text-zinc-900 leading-tight mb-2 group-hover:text-blue-600 transition-colors uppercase italic italic-tighter tracking-tighter">
                            {{ $trip->tujuan }}
                        </h3>
                        <p class="text-xs text-zinc-500 line-clamp-2 mb-6 font-medium leading-relaxed italic">
                            "{{ $trip->keperluan }}"</p>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-zinc-50">
                            <div>
                                <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest">Waktu Pelaksanaan
                                </p>
                                <p class="text-[11px] font-bold text-zinc-900 mt-1">
                                    {{ $trip->tanggal_mulai->format('d M') }} -
                                    {{ $trip->tanggal_selesai->format('d M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest">Estimasi Biaya</p>
                                <p class="text-[11px] font-black text-emerald-600 mt-1">Rp
                                    {{ number_format($trip->estimasi_biaya, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-zinc-50/50 border-t border-zinc-100 flex items-center justify-between">
                        <div class="flex -space-x-1.5 overflow-hidden">
                            @foreach ($trip->pegawaiPeserta->take(3) as $peserta)
                                <div class="h-6 w-6 rounded-full bg-zinc-200 border-2 border-white flex items-center justify-center overflow-hidden"
                                    title="{{ $peserta->nama_lengkap }}">
                                    @if ($peserta->foto)
                                        <img src="{{ asset('storage/' . $peserta->foto) }}"
                                            class="h-full w-full object-cover">
                                    @else
                                        <span
                                            class="text-[8px] font-bold text-zinc-400">{{ substr($peserta->nama_lengkap, 0, 1) }}</span>
                                    @endif
                                </div>
                            @endforeach
                            @if ($trip->pegawaiPeserta->count() > 3)
                                <div
                                    class="h-6 w-6 rounded-full bg-zinc-900 border-2 border-white flex items-center justify-center text-[7px] font-bold text-white">
                                    +{{ $trip->pegawaiPeserta->count() - 3 }}
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('employee.perjalanan_dinas.show', $trip->id) }}"
                            class="text-[10px] font-bold text-zinc-900 uppercase tracking-widest hover:underline flex items-center gap-1 group/btn">
                            Lihat Detail
                            <i data-lucide="chevron-right"
                                class="h-3 w-3 group-hover/btn:translate-x-0.5 transition-transform"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-zinc-100">
                    <div
                        class="h-16 w-16 bg-zinc-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-zinc-200">
                        <i data-lucide="briefcase" class="h-8 w-8"></i>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900">Belum Ada Perjalanan</h3>
                    <p class="text-sm text-zinc-500 max-w-xs mx-auto mt-1">Daftar perjalanan dinas yang Anda ajukan atau
                        tugaskan akan tampil di sini.</p>
                    <a href="{{ route('employee.perjalanan_dinas.create') }}"
                        class="mt-6 inline-flex items-center text-sm font-bold text-blue-600 hover:underline">
                        Ajukan Sekarang →
                    </a>
                </div>
            @endforelse
        </div>

        @if ($trips->hasPages())
            <div class="mt-8">
                {{ $trips->links() }}
            </div>
        @endif
    </div>
@endsection
