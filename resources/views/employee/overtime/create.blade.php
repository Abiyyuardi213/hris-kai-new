@extends('layouts.employee')
@section('title', 'Ajukan Lembur Baru')

@section('content')
    <div class="flex flex-col space-y-6 max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('employee.overtime.index') }}"
                class="h-10 w-10 flex items-center justify-center rounded-full bg-white border border-zinc-100 text-zinc-400 hover:text-zinc-900 transition-all shadow-sm">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Form Pengajuan Lembur</h2>
                <p class="text-sm text-zinc-500">Silakan ajukan lembur dengan memberikan keterangan yang jelas.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl border border-zinc-100 shadow-xl shadow-zinc-200/50 overflow-hidden">
            <form action="{{ route('employee.overtime.store') }}" method="POST">
                @csrf
                <div class="p-8 space-y-8">
                    <!-- Tanggal -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest ml-1">Tanggal Lembur</label>
                        <div class="relative">
                            <i data-lucide="calendar"
                                class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                                class="block w-full rounded-2xl border border-zinc-100 bg-zinc-50/50 pl-11 pr-4 py-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                        </div>
                        @error('date')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Waktu -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest ml-1">Jam Mulai</label>
                            <div class="relative">
                                <i data-lucide="clock"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                                <input type="time" name="start_time" value="{{ old('start_time') }}" required
                                    class="block w-full rounded-2xl border border-zinc-100 bg-zinc-50/50 pl-11 pr-4 py-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                            </div>
                            @error('start_time')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest ml-1">Jam
                                Selesai</label>
                            <div class="relative">
                                <i data-lucide="clock"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                                <input type="time" name="end_time" value="{{ old('end_time') }}" required
                                    class="block w-full rounded-2xl border border-zinc-100 bg-zinc-50/50 pl-11 pr-4 py-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                            </div>
                            @error('end_time')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Alasan -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest ml-1">Keterangan /
                            Keperluan</label>
                        <textarea name="reason" rows="4" required
                            class="block w-full rounded-2xl border border-zinc-100 bg-zinc-50/50 px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-zinc-900 outline-none transition-all placeholder:text-zinc-300"
                            placeholder="Proyek apa yang dikerjakan atau alasan lembur lainnya..."></textarea>
                        @error('reason')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="bg-zinc-50/50 p-8 flex gap-4 border-t border-zinc-100">
                    <a href="{{ route('employee.overtime.index') }}"
                        class="flex-1 px-6 py-4 rounded-2xl bg-white border border-zinc-200 text-sm font-bold text-zinc-700 hover:bg-zinc-100 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-[2] px-6 py-4 rounded-2xl bg-zinc-900 text-sm font-bold text-white hover:bg-zinc-800 transition-all active:scale-[0.98] shadow-lg shadow-zinc-200">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
