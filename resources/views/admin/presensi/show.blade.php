@extends('layouts.app')

@section('title', 'Detail Presensi - ' . $presensi->pegawai->nama_lengkap)

@section('content')
    <div class="flex flex-col space-y-6 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.presensi.index') }}"
                    class="h-10 w-10 rounded-xl border border-zinc-200 bg-white flex items-center justify-center text-zinc-500 hover:text-zinc-900 transition-all hover:bg-zinc-50 shadow-sm">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Detail Presensi</h2>
                    <p class="text-zinc-500 text-sm">Informasi lengkap kehadiran pegawai pada tanggal
                        {{ \Carbon\Carbon::parse($presensi->tanggal)->format('d F Y') }}.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $statusClasses = [
                        'Hadir' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'Izin' => 'bg-blue-50 text-blue-600 border-blue-100',
                        'Sakit' => 'bg-orange-50 text-orange-600 border-orange-100',
                        'Alpa' => 'bg-red-50 text-red-600 border-red-100',
                    ];
                    $class = $statusClasses[$presensi->status] ?? 'bg-zinc-50 text-zinc-600 border-zinc-100';
                @endphp
                <span
                    class="px-4 py-1.5 rounded-full border text-xs font-bold uppercase tracking-widest {{ $class }}">
                    {{ $presensi->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Info Pegawai -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 overflow-hidden">
                <h3 class="text-sm font-bold text-zinc-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i data-lucide="user" class="h-4 w-4"></i>
                    Identitas Pegawai
                </h3>
                <div class="flex items-center gap-6 mb-8">
                    <div
                        class="h-24 w-24 rounded-2xl bg-zinc-100 border-4 border-zinc-50 overflow-hidden shadow-inner shrink-0">
                        @if ($presensi->pegawai->foto)
                            <img src="{{ asset('storage/' . $presensi->pegawai->foto) }}"
                                class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center text-zinc-300">
                                <i data-lucide="user" class="h-10 w-10"></i>
                            </div>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-xl font-bold text-zinc-900 leading-tight">{{ $presensi->pegawai->nama_lengkap }}
                        </h4>
                        <p class="text-sm font-medium text-zinc-500">NIP: {{ $presensi->pegawai->nip }}</p>
                        <div
                            class="inline-flex items-center gap-2 mt-2 px-3 py-1 rounded-lg bg-zinc-50 border border-zinc-100 text-[11px] font-bold text-zinc-600">
                            <i data-lucide="briefcase" class="h-3 w-3"></i>
                            {{ $presensi->pegawai->jabatan->name ?? '-' }}
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-zinc-50">
                    <div>
                        <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Shift Kerja</p>
                        <p class="text-sm font-bold text-zinc-900 mt-1">{{ $presensi->shift->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Jadwal</p>
                        <p class="text-sm font-bold text-zinc-900 mt-1">{{ $presensi->shift->start_time ?? '--:--' }} -
                            {{ $presensi->shift->end_time ?? '--:--' }}</p>
                    </div>
                </div>
            </div>

            <!-- Info Waktu & Statistika -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">
                <h3 class="text-sm font-bold text-zinc-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i data-lucide="clock" class="h-4 w-4"></i>
                    Waktu & Status
                </h3>
                <div class="space-y-6">
                    <div class="flex items-center justify-between p-4 rounded-xl bg-zinc-50 border border-zinc-100">
                        <div>
                            <p class="text-[10px] font-bold text-zinc-400 uppercase">Jam Masuk</p>
                            <p class="text-xl font-black text-zinc-900">{{ $presensi->jam_masuk ?? '--:--' }}</p>
                        </div>
                        @if ($presensi->terlambat > 0)
                            <div class="text-right">
                                <span
                                    class="px-2 py-0.5 rounded bg-red-100 text-red-600 text-[10px] font-bold uppercase">Terlambat</span>
                                <p class="text-xs font-bold text-red-600 mt-1">{{ $presensi->terlambat }} Menit</p>
                            </div>
                        @else
                            <div class="text-emerald-500">
                                <i data-lucide="check-circle-2" class="h-6 w-6"></i>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between p-4 rounded-xl bg-zinc-50 border border-zinc-100">
                        <div>
                            <p class="text-[10px] font-bold text-zinc-400 uppercase">Jam Pulang</p>
                            <p class="text-xl font-black text-zinc-900">{{ $presensi->jam_pulang ?? '--:--' }}</p>
                        </div>
                        @if ($presensi->pulang_cepat > 0)
                            <div class="text-right">
                                <span
                                    class="px-2 py-0.5 rounded bg-orange-100 text-orange-600 text-[10px] font-bold uppercase">Pulang
                                    Cepat</span>
                                <p class="text-xs font-bold text-orange-600 mt-1">{{ $presensi->pulang_cepat }} Menit</p>
                            </div>
                        @endif
                    </div>

                    <div class="p-4 rounded-xl bg-zinc-900 text-white flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-zinc-500 uppercase">Durasi Kerja</p>
                            @php
                                $duration = '-';
                                if ($presensi->jam_masuk && $presensi->jam_pulang) {
                                    $start = \Carbon\Carbon::parse($presensi->jam_masuk);
                                    $end = \Carbon\Carbon::parse($presensi->jam_pulang);
                                    $duration = $start->diff($end)->format('%H jam %I menit');
                                }
                            @endphp
                            <p class="text-lg font-bold">{{ $duration }}</p>
                        </div>
                        <i data-lucide="timer" class="h-6 w-6 text-zinc-600"></i>
                    </div>
                </div>
            </div>

            <!-- Bukti Foto Masuk -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">
                <h3 class="text-sm font-bold text-zinc-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i data-lucide="image" class="h-4 w-4"></i>
                    Bukti Foto Masuk
                </h3>
                <div class="aspect-video rounded-xl bg-zinc-100 border border-zinc-200 overflow-hidden relative group">
                    @if ($presensi->foto_masuk)
                        <img src="{{ asset('storage/' . $presensi->foto_masuk) }}" class="h-full w-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                            <div class="flex items-center gap-2 text-white/90 text-[10px] font-medium leading-none">
                                <i data-lucide="map-pin" class="h-3 w-3"></i>
                                {{ $presensi->lokasi_masuk ?? 'Lokasi tidak tersedia' }}
                            </div>
                        </div>
                    @else
                        <div class="h-full w-full flex flex-col items-center justify-center text-zinc-400">
                            <i data-lucide="camera-off" class="h-10 w-10 mb-2"></i>
                            <p class="text-[10px] font-bold uppercase">Belum ada foto</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bukti Foto Pulang -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">
                <h3 class="text-sm font-bold text-zinc-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i data-lucide="image" class="h-4 w-4"></i>
                    Bukti Foto Pulang
                </h3>
                <div class="aspect-video rounded-xl bg-zinc-100 border border-zinc-200 overflow-hidden relative group">
                    @if ($presensi->foto_pulang)
                        <img src="{{ asset('storage/' . $presensi->foto_pulang) }}" class="h-full w-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                            <div class="flex items-center gap-2 text-white/90 text-[10px] font-medium leading-none">
                                <i data-lucide="map-pin" class="h-3 w-3"></i>
                                {{ $presensi->lokasi_pulang ?? 'Lokasi tidak tersedia' }}
                            </div>
                        </div>
                    @else
                        <div class="h-full w-full flex flex-col items-center justify-center text-zinc-400">
                            <i data-lucide="camera-off" class="h-10 w-10 mb-2"></i>
                            <p class="text-[10px] font-bold uppercase">Belum ada foto</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6">
            <h3 class="text-sm font-bold text-zinc-400 uppercase tracking-widest mb-4">Catatan / Keterangan</h3>
            <p class="text-zinc-700 text-sm italic leading-relaxed">
                {{ $presensi->keterangan ?? 'Tidak ada catatan tambahan untuk presensi ini.' }}</p>
        </div>
    </div>
@endsection
