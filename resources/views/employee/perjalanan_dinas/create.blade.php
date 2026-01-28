@extends('layouts.employee')
@section('title', 'Ajukan Perjalanan Dinas')

@section('content')
    <div class="flex flex-col space-y-6 max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('employee.perjalanan_dinas.index') }}"
                class="h-10 w-10 rounded-xl border border-zinc-200 bg-white flex items-center justify-center text-zinc-500 hover:text-zinc-900 transition-all hover:bg-zinc-50 shadow-sm">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Formulir Pengajuan</h2>
                <p class="text-zinc-500 text-sm">Lengkapi data perjalanan dinas yang akan dilakukan.</p>
            </div>
        </div>

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl flex items-center gap-3">
                <i data-lucide="alert-circle" class="h-5 w-5"></i>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('employee.perjalanan_dinas.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-8 space-y-6">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Kota Tujuan</label>
                    <input type="text" name="tujuan" value="{{ old('tujuan') }}"
                        placeholder="Contoh: Jakarta Pusat, Bandung, dll" required
                        class="w-full px-5 py-3 rounded-2xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all font-medium">
                    @error('tujuan')
                        <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Keperluan / Alasan Dinas</label>
                    <textarea name="keperluan" rows="4" required
                        placeholder="Jelaskan secara detail maksud dan tujuan perjalanan dinas Anda..."
                        class="w-full px-5 py-3 rounded-2xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all leading-relaxed">{{ old('keperluan') }}</textarea>
                    @error('keperluan')
                        <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Tanggal Berangkat</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                            class="w-full px-5 py-3 rounded-2xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        @error('tanggal_mulai')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Tanggal Kembali</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required
                            class="w-full px-5 py-3 rounded-2xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        @error('tanggal_selesai')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Transportasi Utama</label>
                        <input type="text" name="jenis_transportasi" value="{{ old('jenis_transportasi') }}"
                            placeholder="Contoh: Kereta Api (Eksekutif)"
                            class="w-full px-5 py-3 rounded-2xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        @error('jenis_transportasi')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Estimasi Biaya (Rp)</label>
                        <div class="relative">
                            <span
                                class="absolute left-5 top-1/2 -translate-y-1/2 text-zinc-400 font-bold text-xs uppercase tracking-widest">Rp</span>
                            <input type="number" name="estimasi_biaya" value="{{ old('estimasi_biaya', 0) }}" required
                                min="0" step="0.01"
                                class="w-full pl-12 pr-5 py-3 rounded-2xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all font-black text-emerald-600">
                        </div>
                        @error('estimasi_biaya')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full py-4 rounded-2xl bg-zinc-900 text-white font-bold text-sm shadow-xl hover:bg-zinc-800 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i data-lucide="send" class="h-4 w-4"></i>
                        Kirim Pengajuan
                    </button>
                    <p class="text-[10px] text-zinc-400 text-center mt-4 font-medium italic">Pengajuan Anda akan ditinjau
                        oleh HRD/Admin untuk proses persetujuan.</p>
                </div>
            </div>
        </form>
    </div>
@endsection
