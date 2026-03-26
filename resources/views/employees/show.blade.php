@extends('layouts.app')
@section('title', 'Detail Pegawai - ' . $employee->nama_lengkap)

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('employees.index') }}"
                    class="p-2 rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-zinc-900 transition-colors">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">Detail Pegawai</h2>
                    <p class="text-zinc-500 mt-1">Informasi lengkap data kepegawaian.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('employees.edit', $employee->id) }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-50 transition-colors">
                    <i data-lucide="edit-2" class="h-4 w-4"></i>
                    Edit Data
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="space-y-6">
                <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                    <div class="aspect-square bg-zinc-50 border-b border-zinc-100 flex items-center justify-center">
                        @if ($employee->foto)
                            <img src="{{ asset('storage/' . $employee->foto) }}" alt="{{ $employee->nama_lengkap }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="flex flex-col items-center justify-center text-zinc-300">
                                <i data-lucide="user" class="h-20 w-20"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-zinc-900">{{ $employee->nama_lengkap }}</h3>
                        <p class="text-zinc-500 text-sm mt-1">{{ $employee->nip }}</p>
                        <div class="mt-4 flex flex-wrap justify-center gap-2">
                            <span
                                class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                {{ $employee->statusPegawai->name ?? '-' }}
                            </span>
                            <span
                                class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-1 text-xs font-medium text-zinc-600 ring-1 ring-inset ring-zinc-500/10">
                                {{ $employee->shift->name ?? 'Default' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm space-y-4">
                    <h4 class="font-bold text-zinc-900 flex items-center gap-2">
                        <i data-lucide="contact" class="h-4 w-4 text-zinc-400"></i>
                        Kontak Cepat
                    </h4>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm">
                            <i data-lucide="phone" class="h-4 w-4 text-zinc-400"></i>
                            <span class="text-zinc-600">{{ $employee->no_hp ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <i data-lucide="mail" class="h-4 w-4 text-zinc-400"></i>
                            <span class="text-zinc-600 text-teal-600 truncate">{{ $employee->email_pribadi ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Employment Info -->
                <div class="rounded-xl border border-zinc-200 bg-white shadow-sm">
                    <div class="px-6 py-4 border-b border-zinc-100">
                        <h4 class="font-bold text-zinc-900 flex items-center gap-2">
                            <i data-lucide="briefcase" class="h-5 w-5 text-zinc-400"></i>
                            Informasi Pekerjaan
                        </h4>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Jabatan</label>
                                <p class="text-sm font-semibold text-zinc-900 mt-1">{{ $employee->jabatan->name ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Divisi</label>
                                <p class="text-sm font-semibold text-zinc-900 mt-1">{{ $employee->divisi->name ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Kantor</label>
                                <p class="text-sm font-semibold text-zinc-900 mt-1">
                                    {{ $employee->kantor->office_name ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Tanggal Masuk</label>
                                <p class="text-sm font-semibold text-zinc-900 mt-1">
                                    {{ $employee->tanggal_masuk ? \Carbon\Carbon::parse($employee->tanggal_masuk)->translatedFormat('d F Y') : '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Sisa Cuti Thn Ini</label>
                                <p class="text-sm font-bold text-zinc-900 mt-1 text-blue-600">{{ $employee->sisa_cuti ?? 0 }} Hari</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Info -->
                <div class="rounded-xl border border-zinc-200 bg-white shadow-sm">
                    <div class="px-6 py-4 border-b border-zinc-100">
                        <h4 class="font-bold text-zinc-900 flex items-center gap-2">
                            <i data-lucide="user-info" class="h-5 w-5 text-zinc-400"></i>
                            Informasi Personal
                        </h4>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">NIK</label>
                                <p class="text-sm font-medium text-zinc-900 mt-1">{{ $employee->nik ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Tempat, Tgl
                                    Lahir</label>
                                <p class="text-sm font-medium text-zinc-900 mt-1">
                                    {{ $employee->tempat_lahir ?? '-' }},
                                    {{ $employee->tanggal_lahir ? \Carbon\Carbon::parse($employee->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Jenis Kelamin</label>
                                <p class="text-sm font-medium text-zinc-900 mt-1">
                                    {{ $employee->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Agama</label>
                                <p class="text-sm font-medium text-zinc-900 mt-1">{{ $employee->agama ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Status Pernikahan</label>
                                <p class="text-sm font-medium text-zinc-900 mt-1">{{ $employee->status_pernikahan ?? '-' }}
                                </p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Alamat Domisili</label>
                                <p class="text-sm font-medium text-zinc-900 mt-1 leading-relaxed">{{ $employee->alamat_domisili ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
