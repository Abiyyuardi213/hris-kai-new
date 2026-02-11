@extends('layouts.app')

@section('title', 'Detail Sanksi')

@section('content')
    <div class="max-w-3xl mx-auto flex flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between no-print">
            <a href="{{ route('admin.sanctions.index') }}"
                class="inline-flex items-center gap-2 text-zinc-500 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali
            </a>
            <div class="flex gap-2">
                <a href="{{ route('admin.sanctions.print', $sanction) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 border border-blue-200 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                    <i data-lucide="printer" class="h-4 w-4"></i>
                    Cetak SP
                </a>
                <form action="{{ route('admin.sanctions.destroy', $sanction) }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data sanksi ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-zinc-200 overflow-hidden">
            <!-- Alert Header -->
            <div class="p-6 bg-red-50 border-b border-red-100 flex items-start gap-4">
                <div class="p-3 bg-red-100 rounded-full text-red-600 shrink-0">
                    <i data-lucide="alert-triangle" class="h-6 w-6"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-red-900">Sanksi Disiplin: {{ $sanction->type }}</h3>
                    <p class="text-red-700 mt-1 text-sm">Diberikan kepada pegawai atas pelanggaran yang dilakukan.</p>
                </div>
            </div>

            <div class="p-8 space-y-8">
                <!-- Employee Info -->
                <div class="flex items-center gap-4 pb-6 border-b border-zinc-100">
                    <div class="h-16 w-16 rounded-full bg-zinc-100 overflow-hidden shrink-0">
                        @if ($sanction->employee->foto)
                            <img src="{{ asset('storage/' . $sanction->employee->foto) }}"
                                class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center bg-zinc-200 text-zinc-400">
                                <i data-lucide="user" class="h-8 w-8"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-zinc-900">{{ $sanction->employee->nama_lengkap }}</h4>
                        <p class="text-zinc-500 font-mono">{{ $sanction->employee->nip }}</p>
                        <span class="text-xs text-zinc-400">{{ $sanction->employee->jabatan->name ?? '-' }} -
                            {{ $sanction->employee->divisi->name ?? '-' }}</span>
                    </div>
                </div>

                <!-- Sanction Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-zinc-500 mb-1">Jenis Pelanggaran</p>
                        <p class="text-base font-semibold text-zinc-900">{{ $sanction->type }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-500 mb-1">Status Sanksi</p>
                        <span
                            class="inline-flex px-2 py-1 rounded text-xs font-bold
                            {{ $sanction->status == 'Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-zinc-100 text-zinc-600' }}">
                            {{ $sanction->status == 'Active' ? 'AKTIF' : 'BERAKHIR' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-500 mb-1">Tanggal Mulai</p>
                        <p class="text-base text-zinc-900">
                            {{ \Carbon\Carbon::parse($sanction->start_date)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-500 mb-1">Tanggal Berakhir</p>
                        <p class="text-base text-zinc-900">
                            @if ($sanction->end_date)
                                {{ \Carbon\Carbon::parse($sanction->end_date)->translatedFormat('d F Y') }}
                            @else
                                <span class="italic text-zinc-400">Selamanya</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <p class="text-sm font-medium text-zinc-500 mb-2">Deskripsi Pelanggaran</p>
                    <div class="p-4 bg-zinc-50 rounded-lg border border-zinc-100 text-zinc-700 leading-relaxed text-sm">
                        {{ $sanction->description }}
                    </div>
                </div>

                <!-- Attachment -->
                @if ($sanction->document_path)
                    <div class="pt-4 border-t border-zinc-100">
                        <p class="text-sm font-medium text-zinc-500 mb-2">Dokumen Pendukung</p>
                        <a href="{{ asset('storage/' . $sanction->document_path) }}" target="_blank"
                            class="inline-flex items-center gap-2 p-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium border border-blue-100">
                            <i data-lucide="file-text" class="h-4 w-4"></i>
                            Lihat Dokumen Terlampir
                        </a>
                    </div>
                @endif
            </div>

            <div class="bg-zinc-50 p-6 text-center text-xs text-zinc-400 border-t border-zinc-100">
                Data sanksi dibuat pada {{ $sanction->created_at->translatedFormat('d F Y H:i') }}
            </div>
        </div>
    </div>
@endsection
