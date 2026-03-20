@extends('layouts.app')
@section('title', 'Input Payroll Project')

@section('content')
    <div class="flex flex-col space-y-6 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.project-payroll.index') }}"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-zinc-200 bg-white text-zinc-400 hover:text-zinc-900 transition-all hover:border-zinc-300">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Input Payroll Project</h2>
                    <p class="text-zinc-500 text-sm mt-1">Tambahkan data pembayaran project untuk pegawai terpilih.</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-zinc-200/50 overflow-hidden">
            <form action="{{ route('admin.project-payroll.store') }}" method="POST" class="p-8 space-y-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Pegawai Selection -->
                    <div class="space-y-2.5">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest ml-1">Pilih Pegawai</label>
                        <select name="pegawai_id" required id="pegawai_id"
                            class="h-12 w-full rounded-xl border border-zinc-200 bg-white/50 px-4 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all focus:bg-white select2">
                            <option value="">Pilih Pegawai...</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('pegawai_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->nama_lengkap }} ({{ $employee->nip }})
                                </option>
                            @endforeach
                        </select>
                        @error('pegawai_id')
                            <p class="text-[10px] text-red-500 font-bold ml-1 tracking-wide">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Name -->
                    <div class="space-y-2.5">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest ml-1">Nama Project</label>
                        <div class="relative">
                            <i data-lucide="briefcase" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-zinc-400"></i>
                            <input type="text" name="project_name" value="{{ old('project_name') }}" required
                                placeholder="Contoh: Pengembangan Website KAI 2026"
                                class="h-12 w-full rounded-xl border border-zinc-200 bg-white/50 pl-11 pr-4 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all focus:bg-white">
                        </div>
                        @error('project_name')
                            <p class="text-[10px] text-red-500 font-bold ml-1 tracking-wide">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Pay -->
                    <div class="space-y-2.5">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest ml-1">Total Nominal (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-zinc-400">Rp</span>
                            <input type="number" name="total_pay" value="{{ old('total_pay', 0) }}" required
                                class="h-12 w-full rounded-xl border border-zinc-200 bg-white/50 pl-10 pr-4 text-sm font-bold text-emerald-600 focus:ring-2 focus:ring-zinc-900 outline-none transition-all focus:bg-white">
                        </div>
                        @error('total_pay')
                            <p class="text-[10px] text-red-500 font-bold ml-1 tracking-wide">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Period -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2.5">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest ml-1">Bulan</label>
                            <select name="month" required
                                class="h-12 w-full rounded-xl border border-zinc-200 bg-white/50 px-4 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all focus:bg-white">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}"
                                        {{ old('month', date('n')) == $i ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="space-y-2.5">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest ml-1">Tahun</label>
                            <select name="year" required
                                class="h-12 w-full rounded-xl border border-zinc-200 bg-white/50 px-4 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all focus:bg-white">
                                @for ($i = date('Y'); $i >= 2020; $i--)
                                    <option value="{{ $i }}"
                                        {{ old('year', date('Y')) == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="space-y-2.5">
                    <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest ml-1">Keterangan / Penilaian Kinerja</label>
                    <textarea name="keterangan" rows="4" placeholder="Jelaskan alasan pemberian payroll tambahan berdasarkan kinerja..."
                        class="w-full rounded-xl border border-zinc-200 bg-white/50 p-4 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all focus:bg-white resize-none">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-[10px] text-red-500 font-bold ml-1 tracking-wide">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Footer / Action -->
                <div class="pt-6 border-t border-zinc-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.project-payroll.index') }}"
                        class="inline-flex h-11 items-center justify-center rounded-xl border border-zinc-200 bg-white px-8 text-sm font-bold text-zinc-700 hover:bg-zinc-50 transition-all active:scale-95">
                        Batalkan
                    </a>
                    <button type="submit"
                        class="inline-flex h-11 items-center justify-center rounded-xl bg-zinc-900 px-8 text-sm font-bold text-white shadow-lg shadow-zinc-200 hover:bg-zinc-800 transition-all active:scale-95">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Styles for Select2 if used, or just native enhancement -->
    <style>
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280' %3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }
    </style>
@endsection
