@extends('layouts.employee')
{{-- Using existing layout but might need to adjust sidebar or navbar if they are admin-only --}}

@section('title', 'Dashboard Pegawai')

@section('content')
    <div class="space-y-8">
        <!-- Welcome Header -->
        <div class="relative overflow-hidden rounded-3xl bg-zinc-900 p-8 md:p-12 text-white shadow-2xl">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div class="flex items-center gap-6">
                    <!-- Avatar -->
                    <div
                        class="h-24 w-24 md:h-28 md:w-28 rounded-2xl overflow-hidden border-2 border-zinc-700 shadow-xl bg-zinc-800 shrink-0">
                        @if ($employee->foto)
                            <img src="{{ asset('storage/' . $employee->foto) }}" alt="Foto Profile"
                                class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center text-zinc-500">
                                <i data-lucide="user" class="h-12 w-12"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold">Halo, {{ $employee->nama_lengkap }}!</h2>
                        <p class="text-zinc-400 mt-1 flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                            {{ $employee->jabatan->name ?? 'Pegawai' }} • {{ $employee->nip }}
                        </p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span
                                class="px-3 py-1 bg-zinc-800 rounded-full text-[10px] font-bold uppercase tracking-wider text-zinc-300 border border-zinc-700">
                                {{ $employee->statusPegawai->name ?? '-' }}
                            </span>
                            <span
                                class="px-3 py-1 bg-zinc-800 rounded-full text-[10px] font-bold uppercase tracking-wider text-zinc-300 border border-zinc-700">
                                Shift: {{ $employee->shift->name ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('employee.attendance.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-white text-zinc-900 rounded-xl font-bold transition-all hover:bg-zinc-100 shadow-lg">
                        <i data-lucide="camera" class="h-5 w-5"></i>
                        Presensi
                    </a>
                    <a href="{{ route('employee.profile') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-zinc-800 text-white rounded-xl font-bold transition-all hover:bg-zinc-700 border border-zinc-700">
                        <i data-lucide="user-cog" class="h-5 w-5"></i>
                        Profil
                    </a>
                    <a href="{{ route('employee.id-card') }}" target="_blank"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-zinc-800 text-white rounded-xl font-bold transition-all hover:bg-zinc-700 border border-zinc-700">
                        <i data-lucide="credit-card" class="h-5 w-5"></i>
                        ID Card
                    </a>
                </div>
            </div>

            <!-- Decoration -->
            <div
                class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-96 h-96 bg-zinc-800 rounded-full blur-3xl opacity-20 transition-all">
            </div>
            <div
                class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-64 h-64 bg-zinc-800 rounded-full blur-2xl opacity-10 transition-all">
            </div>
        </div>

        <!-- Announcements Section -->
        @if ($announcements->count() > 0)
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-zinc-400">Informasi & Pengumuman
                    </h3>
                    <a href="{{ route('employee.announcements.index') }}"
                        class="text-[10px] font-bold uppercase tracking-widest text-zinc-900 border-b border-zinc-900 leading-none">Lihat
                        Semua</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($announcements as $announcement)
                        <a href="{{ route('employee.announcements.show', $announcement->id) }}"
                            class="group bg-white p-6 rounded-[2rem] border border-zinc-100 shadow-sm hover:shadow-xl transition-all">
                            <div class="flex flex-col h-full justify-between gap-4">
                                <div>
                                    <span
                                        class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $announcement->category == 'Penting' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-zinc-50 text-zinc-500 border border-zinc-100' }}">
                                        {{ $announcement->category }}
                                    </span>
                                    <h4
                                        class="mt-4 text-sm font-bold text-zinc-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-tight">
                                        {{ $announcement->title }}
                                    </h4>
                                </div>
                                <div class="flex items-center justify-between pt-4 border-t border-zinc-50">
                                    <span class="text-[9px] text-zinc-400 font-bold uppercase tracking-widest">
                                        {{ $announcement->published_at->diffForHumans() }}
                                    </span>
                                    <i data-lucide="arrow-right"
                                        class="h-3 w-3 text-zinc-300 group-hover:text-zinc-900 transition-colors"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all">
                <div
                    class="h-14 w-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="calendar-check" class="h-7 w-7"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-zinc-500">Sisa Cuti</p>
                    <h4 class="text-2xl font-bold text-zinc-900">{{ $employee->sisa_cuti }} Hari</h4>
                </div>
            </div>
            <div
                class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all">
                <div
                    class="h-14 w-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="clock" class="h-7 w-7"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-zinc-500">Shift Hari Ini</p>
                    <h4 class="text-xl font-bold text-zinc-900">{{ $employee->shift->start_time ?? '--:--' }} -
                        {{ $employee->shift->end_time ?? '--:--' }}</h4>
                </div>
            </div>
            <div
                class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all">
                <div
                    class="h-14 w-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="map-pin" class="h-7 w-7"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-zinc-500">Penempatan</p>
                    <h4 class="text-xl font-bold text-zinc-900">{{ $employee->kantor->office_name ?? '-' }}</h4>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 gap-8">
            <!-- Job Info -->
            <div class="bg-white rounded-3xl border border-zinc-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-zinc-50 flex items-center justify-between bg-zinc-50/50">
                    <h3 class="font-bold text-zinc-900 flex items-center gap-2">
                        <i data-lucide="briefcase" class="h-5 w-5 text-zinc-400"></i>
                        Informasi Pekerjaan
                    </h3>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex justify-between items-center border-b border-zinc-50 pb-4">
                        <span class="text-sm text-zinc-500">NIP</span>
                        <span class="font-semibold text-zinc-900">{{ $employee->nip }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-zinc-50 pb-4">
                        <span class="text-sm text-zinc-500">Divisi</span>
                        <span class="font-semibold text-zinc-900">{{ $employee->divisi->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-zinc-50 pb-4">
                        <span class="text-sm text-zinc-500">Tanggal Masuk</span>
                        <span class="font-semibold text-zinc-900 text-emerald-600">{{ $employee->tanggal_masuk }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-zinc-50 pb-4">
                        <span class="text-sm text-zinc-500">Hubungi (Kontak)</span>
                        <span class="font-semibold text-zinc-900">{{ $employee->no_hp ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center md:col-span-2">
                        <span class="text-sm text-zinc-500">Email Pribadi</span>
                        <span class="font-semibold text-zinc-900">{{ $employee->email_pribadi ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
