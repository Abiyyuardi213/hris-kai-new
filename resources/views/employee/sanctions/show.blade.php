@extends('layouts.employee')

@section('title', 'Detail Sanksi')

@section('content')
    <div class="font-medium">

        <div class="flex items-center gap-3 md:gap-4 mb-6">
            <a href="{{ route('employee.sanctions.index') }}"
                class="h-8 w-8 md:h-10 md:w-10 rounded-xl bg-white border border-zinc-200 flex items-center justify-center text-zinc-500 hover:text-zinc-900 shadow-sm transition-all hover:bg-zinc-50">
                <i data-lucide="arrow-left" class="h-4 w-4 md:h-5 md:w-5"></i>
            </a>
            <div>
                <h1 class="text-xl md:text-2xl font-bold tracking-tight text-zinc-900">Detail Sanksi</h1>
                <p class="text-zinc-500 text-xs md:text-sm">Rincian surat peringatan atau sanksi disiplin.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
            <!-- Header Status -->
            <div
                class="p-4 md:p-8 bg-zinc-50 border-b border-zinc-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="h-12 w-12 md:h-14 md:w-14 rounded-2xl flex items-center justify-center
                    {{ $sanction->type === 'Termination'
                        ? 'bg-red-100 text-red-600'
                        : ($sanction->type === 'SP3'
                            ? 'bg-orange-100 text-orange-600'
                            : ($sanction->type === 'SP2'
                                ? 'bg-yellow-100 text-yellow-600'
                                : 'bg-blue-100 text-blue-600')) }}">
                        <i data-lucide="alert-triangle" class="h-6 w-6 md:h-7 md:w-7"></i>
                    </div>
                    <div>
                        <h2 class="text-lg md:text-xl font-bold text-zinc-900">{{ $sanction->type }}</h2>
                        <p class="text-zinc-500 text-xs md:text-sm">Diterbitkan pada
                            {{ $sanction->created_at->translatedFormat('d F Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if ($sanction->status === 'Active')
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 md:px-3 md:py-1.5 rounded-full text-xs md:text-sm font-bold bg-emerald-100 text-emerald-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                            Status: Aktif
                        </span>
                    @else
                        <span
                            class="inline-flex px-2.5 py-1 md:px-3 md:py-1.5 rounded-full text-xs md:text-sm font-bold bg-zinc-100 text-zinc-600">
                            Status: {{ $sanction->status === 'Expired' ? 'Berakhir' : 'Dicabut' }}
                        </span>
                    @endif

                    <a href="{{ route('employee.sanctions.print', $sanction->id) }}" target="_blank"
                        class="hidden md:inline-flex items-center gap-2 px-4 py-2 bg-zinc-900 text-white rounded-xl font-bold text-sm hover:bg-zinc-800 transition-all shadow-lg shadow-zinc-900/10">
                        <i data-lucide="printer" class="h-4 w-4"></i>
                        Cetak Surat Bukti
                    </a>
                </div>
            </div>

            <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Left Column: Details -->
                <div class="md:col-span-2 space-y-8">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-400 uppercase tracking-wider mb-2">Deskripsi Pelanggaran</h3>
                        <div
                            class="p-5 bg-zinc-50 rounded-2xl border border-zinc-100 text-zinc-700 leading-relaxed text-sm md:text-base">
                            {{ $sanction->description }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-bold text-zinc-400 uppercase tracking-wider mb-1">Tanggal Mulai</h3>
                            <p class="text-lg font-bold text-zinc-900 flex items-center gap-2">
                                <i data-lucide="calendar" class="h-5 w-5 text-zinc-400"></i>
                                {{ \Carbon\Carbon::parse($sanction->start_date)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-zinc-400 uppercase tracking-wider mb-1">Tanggal Berakhir</h3>
                            <p class="text-lg font-bold text-zinc-900 flex items-center gap-2">
                                <i data-lucide="calendar-off" class="h-5 w-5 text-zinc-400"></i>
                                {{ $sanction->end_date ? \Carbon\Carbon::parse($sanction->end_date)->translatedFormat('d F Y') : 'Selamanya' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Document -->
                <div class="bg-blue-50/50 rounded-2xl p-6 border border-blue-100 flex flex-col justify-between">
                    <div>
                        <div class="h-12 w-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                            <i data-lucide="file-text" class="h-6 w-6"></i>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900 mb-1">Dokumen Pendukung</h3>
                        <p class="text-zinc-500 text-sm mb-6">Bukti atau lampiran terkait sanksi ini yang diunggah oleh
                            admin.</p>
                    </div>

                    @if ($sanction->document_path)
                        <a href="{{ asset('storage/' . $sanction->document_path) }}" target="_blank"
                            class="w-full py-3 bg-white border border-blue-200 text-blue-600 rounded-xl font-bold text-sm flex items-center justify-center gap-2 hover:bg-blue-50 transition-all shadow-sm">
                            <i data-lucide="download" class="h-4 w-4"></i>
                            Unduh Dokumen
                        </a>
                    @else
                        <div
                            class="w-full py-3 bg-zinc-100 border border-zinc-200 text-zinc-400 rounded-xl font-bold text-sm flex items-center justify-center gap-2 cursor-not-allowed">
                            <i data-lucide="file-x" class="h-4 w-4"></i>
                            Tidak Ada Dokumen
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mobile Print Button -->
            <div class="p-6 border-t border-zinc-100 md:hidden">
                <a href="{{ route('employee.sanctions.print', $sanction->id) }}" target="_blank"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-zinc-900 text-white rounded-xl font-bold text-sm hover:bg-zinc-800 transition-all shadow-lg shadow-zinc-900/10">
                    <i data-lucide="printer" class="h-4 w-4"></i>
                    Cetak Surat Bukti
                </a>
            </div>
        </div>
    </div>
@endsection
