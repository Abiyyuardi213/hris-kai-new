@extends('layouts.employee')

@section('content')
    <div class="space-y-6">
        <!-- Back Button -->
        <div>
            <a href="{{ route('employee.mutations.index') }}"
                class="inline-flex items-center gap-2 text-sm text-zinc-500 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali ke Riwayat
            </a>
        </div>

        <!-- Header Card -->
        <div
            class="bg-gradient-to-br from-indigo-500 to-violet-600 rounded-2xl p-6 md:p-8 text-white shadow-lg overflow-hidden relative">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-sm border border-white/20 uppercase tracking-wide">
                            {{ $mutation->type }}
                        </span>
                        <span class="text-indigo-100 text-sm">
                            {{ \Carbon\Carbon::parse($mutation->mutation_date)->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold tracking-tight">Detail Mutasi Jabatan</h1>
                    <p class="text-indigo-100 mt-2 max-w-xl">
                        Informasi lengkap mengenai perubahan status kepegawaian Anda.
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('employee.mutations.print', $mutation->id) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl border border-white/20 backdrop-blur-sm transition-all text-sm font-semibold">
                        <i data-lucide="download" class="h-4 w-4"></i>
                        Download SK
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Change Details -->
                <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-zinc-100">
                        <h2 class="text-lg font-semibold text-zinc-900 flex items-center gap-2">
                            <i data-lucide="git-compare" class="h-5 w-5 text-indigo-500"></i>
                            Perubahan Posisi
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-zinc-100">
                        <!-- Before -->
                        <div class="p-6 bg-zinc-50/50">
                            <p
                                class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-zinc-300"></span>
                                Sebelumnya
                            </p>

                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs text-zinc-500 mb-1">Jabatan</label>
                                    <p class="font-medium text-zinc-900 text-lg">
                                        {{ $mutation->fromPosition->name ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-zinc-500 mb-1">Divisi</label>
                                    <div class="flex items-center gap-2 text-zinc-700">
                                        <i data-lucide="building-2" class="h-4 w-4 text-zinc-400"></i>
                                        {{ $mutation->fromDivision->name ?? '-' }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs text-zinc-500 mb-1">Lokasi Kantor</label>
                                    <div class="flex items-center gap-2 text-zinc-700">
                                        <i data-lucide="map-pin" class="h-4 w-4 text-zinc-400"></i>
                                        {{ $mutation->fromOffice->office_name ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- After -->
                        <div class="p-6 bg-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-indigo-50 rounded-bl-full -mr-10 -mt-10"></div>

                            <p
                                class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                Menjadi
                            </p>

                            <div class="space-y-6 relative z-10">
                                <div>
                                    <label class="block text-xs text-zinc-500 mb-1">Jabatan Baru</label>
                                    <p class="font-bold text-indigo-700 text-lg">
                                        {{ $mutation->toPosition->name ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-zinc-500 mb-1">Divisi Baru</label>
                                    <div class="flex items-center gap-2 text-zinc-900 font-medium">
                                        <i data-lucide="building-2" class="h-4 w-4 text-indigo-400"></i>
                                        {{ $mutation->toDivision->name ?? '-' }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs text-zinc-500 mb-1">Lokasi Baru</label>
                                    <div class="flex items-center gap-2 text-zinc-900 font-medium">
                                        <i data-lucide="map-pin" class="h-4 w-4 text-indigo-400"></i>
                                        {{ $mutation->toOffice->office_name ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reason & Notes -->
                <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-zinc-900 mb-3">Keterangan / Alasan Mutasi</h3>
                    <div class="bg-zinc-50 rounded-lg p-4 text-sm text-zinc-700 leading-relaxed border border-zinc-100">
                        {{ $mutation->reason ?? 'Tidak ada keterangan tambahan.' }}
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
@endsection
