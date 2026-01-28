@extends('layouts.employee')
@section('title', 'Detail Perjalanan Dinas Saya')

@section('content')
    <div class="flex flex-col space-y-6 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('employee.perjalanan_dinas.index') }}"
                    class="h-10 w-10 rounded-xl border border-zinc-200 bg-white flex items-center justify-center text-zinc-500 hover:text-zinc-900 transition-all hover:bg-zinc-50 shadow-sm">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Detail Perjalanan</h2>
                    <p class="text-zinc-500 text-sm">Informasi lengkap perjalanan dinas Anda.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
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
                    class="px-4 py-1.5 rounded-full border text-xs font-bold uppercase tracking-widest {{ $class }}">
                    {{ $trip->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Info Card -->
                <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div
                                class="bg-zinc-900 text-white px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest leading-none">
                                {{ $trip->no_surat_tugas ?? 'Draft Pengajuan' }}
                            </div>
                            <div class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest">
                                Dibuat: {{ $trip->created_at->format('d M Y') }}
                            </div>
                        </div>

                        <h3 class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-2">Tujuan Perjalanan
                        </h3>
                        <p class="text-3xl font-black text-zinc-900 italic uppercase tracking-tighter leading-tight mb-6">
                            {{ $trip->tujuan }}
                        </p>

                        <div class="bg-zinc-50 rounded-2xl p-6 border border-zinc-100 mb-8">
                            <h4 class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-3">Keperluan</h4>
                            <p class="text-sm text-zinc-700 leading-relaxed italic">"{{ $trip->keperluan }}"</p>
                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 pt-6 border-t border-zinc-50">
                            <div>
                                <h4
                                    class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1 text-center lg:text-left">
                                    Mulai</h4>
                                <p class="text-xs font-bold text-zinc-900 text-center lg:text-left">
                                    {{ $trip->tanggal_mulai->format('d M Y') }}</p>
                            </div>
                            <div>
                                <h4
                                    class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1 text-center lg:text-left">
                                    Selesai</h4>
                                <p class="text-xs font-bold text-zinc-900 text-center lg:text-left">
                                    {{ $trip->tanggal_selesai->format('d M Y') }}</p>
                            </div>
                            <div>
                                <h4
                                    class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1 text-center lg:text-left">
                                    Kendaraan</h4>
                                <p class="text-xs font-bold text-zinc-900 text-center lg:text-left">
                                    {{ $trip->jenis_transportasi ?? '-' }}</p>
                            </div>
                            <div>
                                <h4
                                    class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest mb-1 text-center lg:text-left">
                                    Biaya</h4>
                                <p class="text-xs font-black text-emerald-600 text-center lg:text-left">Rp
                                    {{ number_format($trip->estimasi_biaya, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Participants -->
                <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-zinc-50 bg-zinc-50/50 flex items-center justify-between">
                        <h4 class="text-xs font-bold text-zinc-900">Peserta Perjalanan</h4>
                        <span class="text-[10px] font-bold text-zinc-400">{{ $trip->pegawaiPeserta->count() }}
                            Anggota</span>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($trip->pegawaiPeserta as $peserta)
                                <div class="flex items-center gap-3 p-3 rounded-2xl border border-zinc-50 bg-zinc-50/30">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-white border border-zinc-100 overflow-hidden flex items-center justify-center shrink-0">
                                        @if ($peserta->foto)
                                            <img src="{{ asset('storage/' . $peserta->foto) }}"
                                                class="h-full w-full object-cover">
                                        @else
                                            <i data-lucide="user" class="h-4 w-4 text-zinc-300"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-zinc-900 truncate">{{ $peserta->nama_lengkap }}
                                        </p>
                                        <p class="text-[9px] text-zinc-400 font-medium">NIP: {{ $peserta->nip }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Card -->
            <div class="space-y-6">
                <!-- Status/Approval Log -->
                <div class="bg-zinc-900 rounded-3xl p-6 text-white shadow-xl shadow-zinc-200 relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 h-24 w-24 bg-white/5 rounded-full blur-2xl"></div>
                    <h3 class="text-xs font-bold text-zinc-500 uppercase tracking-widest mb-6">Status Log</h3>

                    <div class="space-y-6 relative">
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
                                <div class="w-0.5 h-full bg-zinc-800"></div>
                            </div>
                            <div class="pb-1">
                                <p class="text-[10px] font-black uppercase text-zinc-500 leading-none mb-1">Pengajuan</p>
                                <p class="text-[11px] font-bold text-white">{{ $trip->created_at->format('d M Y, H:i') }}
                                </p>
                                <p class="text-[9px] text-zinc-500 italic mt-1">Dibuat oleh
                                    {{ $trip->pemohon->nama_lengkap }}</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div
                                    class="h-2 w-2 rounded-full {{ in_array($trip->status, ['Disetujui', 'Sedang Berjalan', 'Selesai']) ? 'bg-emerald-500' : 'bg-zinc-700' }}">
                                </div>
                            </div>
                            <div>
                                <p
                                    class="text-[10px] font-black uppercase {{ in_array($trip->status, ['Disetujui', 'Sedang Berjalan', 'Selesai']) ? 'text-emerald-500' : 'text-zinc-500' }} leading-none mb-1">
                                    Verifikasi</p>
                                @if ($trip->disetujui_oleh)
                                    <p class="text-[11px] font-bold text-white">{{ $trip->status }}</p>
                                    <p class="text-[9px] text-zinc-500 italic mt-1">Oleh:
                                        {{ $trip->pengetuju->name ?? 'Admin' }}</p>
                                    @if ($trip->catatan_persetujuan)
                                        <div
                                            class="mt-2 text-[10px] p-2 bg-white/5 rounded-lg border border-white/10 italic text-zinc-400">
                                            "{{ $trip->catatan_persetujuan }}"
                                        </div>
                                    @endif
                                @else
                                    <p class="text-[11px] font-bold text-zinc-600">Menunggu Verifikasi</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Finance Card -->
                @if ($trip->realisasi_biaya)
                    <div class="bg-emerald-600 rounded-3xl p-6 text-white shadow-xl shadow-emerald-100">
                        <h3 class="text-[10px] font-bold text-emerald-200 uppercase tracking-widest mb-4">Realisasi Biaya
                        </h3>
                        <p class="text-2xl font-black tracking-tighter italic mb-1">Rp
                            {{ number_format($trip->realisasi_biaya, 0, ',', '.') }}</p>
                        <p class="text-[9px] font-bold text-emerald-200 uppercase tracking-widest">Biaya Akhir Disetujui</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
