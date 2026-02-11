@extends('layouts.app')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Detail Mutasi</h2>
            <div class="flex gap-2">
                <a href="{{ route('mutations.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                    Kembali
                </a>
                <a href="{{ route('mutations.print', $mutation->id) }}" target="_blank"
                    class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors shadow-lg shadow-zinc-900/10">
                    <i data-lucide="printer" class="h-4 w-4"></i>
                    Cetak SK
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-zinc-200 overflow-hidden">
            <div class="p-6 border-b border-zinc-200 bg-zinc-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-zinc-900">SK Mutasi
                        {{ $mutation->mutation_code ?? '#' . $mutation->id }}</h3>
                    <p class="text-sm text-zinc-500">Dibuat pada {{ $mutation->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div
                    class="inline-flex items-center rounded-md px-2.5 py-1 text-sm font-medium bg-blue-100 text-blue-800 uppercase">
                    {{ $mutation->type }}
                </div>
            </div>

            <div class="p-6">
                <!-- Employee Info -->
                <div class="flex items-center gap-4 mb-8">
                    @if ($mutation->employee->foto)
                        <img src="{{ asset('storage/' . $mutation->employee->foto) }}" alt=""
                            class="h-16 w-16 rounded-full object-cover ring-1 ring-zinc-200">
                    @else
                        <div
                            class="h-16 w-16 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-400 ring-1 ring-zinc-200">
                            <i data-lucide="user" class="h-8 w-8"></i>
                        </div>
                    @endif
                    <div>
                        <h4 class="text-xl font-bold text-zinc-900">{{ $mutation->employee->nama_lengkap }}</h4>
                        <p class="text-zinc-500">{{ $mutation->employee->nip }} - {{ $mutation->employee->nik }}</p>
                    </div>
                </div>

                <!-- Mutation Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Before -->
                    <div class="p-6 rounded-lg bg-red-50/50 border border-red-100">
                        <h4
                            class="text-sm font-semibold text-red-900 uppercase tracking-wider mb-4 border-b border-red-200 pb-2">
                            Posisi Lama</h4>
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="block text-red-700/70 text-xs">Divisi</span>
                                <span class="font-medium text-red-950">{{ $mutation->fromDivision->name ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-red-700/70 text-xs">Jabatan</span>
                                <span class="font-medium text-red-950">{{ $mutation->fromPosition->name ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-red-700/70 text-xs">Kantor</span>
                                <span
                                    class="font-medium text-red-950">{{ $mutation->fromOffice->office_name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- After -->
                    <div class="p-6 rounded-lg bg-green-50/50 border border-green-100">
                        <h4
                            class="text-sm font-semibold text-green-900 uppercase tracking-wider mb-4 border-b border-green-200 pb-2">
                            Posisi Baru</h4>
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="block text-green-700/70 text-xs">Divisi</span>
                                <span class="font-medium text-green-950">{{ $mutation->toDivision->name ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-green-700/70 text-xs">Jabatan</span>
                                <span class="font-medium text-green-950">{{ $mutation->toPosition->name ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-green-700/70 text-xs">Kantor</span>
                                <span
                                    class="font-medium text-green-950">{{ $mutation->toOffice->office_name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-zinc-100">
                    <h4 class="text-sm font-medium text-zinc-900 mb-3">Informasi Tambahan</h4>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4">
                        <div>
                            <dt class="text-xs text-zinc-500">Tanggal Efektif Mutasi</dt>
                            <dd class="text-sm font-medium text-zinc-900">
                                {{ \Carbon\Carbon::parse($mutation->mutation_date)->format('d F Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-zinc-500">Alasan</dt>
                            <dd class="text-sm text-zinc-700 bg-zinc-50 p-3 rounded-lg border border-zinc-100 mt-1">
                                {{ $mutation->reason }}</dd>
                        </div>

                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
