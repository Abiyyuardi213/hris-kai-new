@extends('layouts.app')
@section('title', 'Buat Penugasan Perjalanan Dinas')

@section('content')
    <div class="flex flex-col space-y-6 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.perjalanan_dinas.index') }}"
                class="h-10 w-10 rounded-xl border border-zinc-200 bg-white flex items-center justify-center text-zinc-500 hover:text-zinc-900 transition-all hover:bg-zinc-50 shadow-sm">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Buat Penugasan</h2>
                <p class="text-zinc-500 text-sm">Formulir penugasan perjalanan dinas pegawai.</p>
            </div>
        </div>

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl flex items-center gap-3">
                <i data-lucide="alert-circle" class="h-5 w-5"></i>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.perjalanan_dinas.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50/50">
                    <h3 class="font-bold text-zinc-900 text-sm">Informasi Dasar</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Pemohon / Penanggung
                            Jawab</label>
                        <select name="pegawai_id" required
                            class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                            <option value="">Pilih Pegawai</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('pegawai_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->nama_lengkap }} ({{ $emp->nip }})
                                </option>
                            @endforeach
                        </select>
                        @error('pegawai_id')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">No. Surat Tugas
                            (Auto-generate jika kosong)</label>
                        <input type="text" name="no_surat_tugas" value="{{ old('no_surat_tugas') }}"
                            placeholder="Contoh: ST/001/KAI/I/2026"
                            class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        @error('no_surat_tugas')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Tujuan</label>
                        <input type="text" name="tujuan" value="{{ old('tujuan') }}"
                            placeholder="Contoh: Jakarta, Bandung, dll" required
                            class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        @error('tujuan')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Jenis Transportasi</label>
                        <input type="text" name="jenis_transportasi" value="{{ old('jenis_transportasi') }}"
                            placeholder="Contoh: Kereta Api, Pesawat, dll"
                            class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        @error('jenis_transportasi')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Keperluan</label>
                        <textarea name="keperluan" rows="3" required placeholder="Jelaskan tujuan perjalanan dinas..."
                            class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50/50">
                    <h3 class="font-bold text-zinc-900 text-sm">Waktu & Biaya</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                            class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        @error('tanggal_mulai')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required
                            class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        @error('tanggal_selesai')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Estimasi Biaya (Rp)</label>
                        <input type="number" name="estimasi_biaya" value="{{ old('estimasi_biaya', 0) }}" required
                            min="0" step="0.01"
                            class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        @error('estimasi_biaya')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden text-sm">
                <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-zinc-900 text-sm">Peserta (Multiselect)</h3>
                    <div class="text-[10px] bg-zinc-900 text-white px-2 py-0.5 rounded font-black tracking-widest uppercase"
                        id="counter">0 Peserta Pilih</div>
                </div>
                <div class="p-6">
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach ($employees as $emp)
                            <label
                                class="relative flex items-center gap-3 p-3 rounded-xl border border-zinc-100 hover:border-zinc-300 transition-all cursor-pointer group">
                                <input type="checkbox" name="peserta_ids[]" value="{{ $emp->id }}"
                                    class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900 checkbox-peserta"
                                    {{ is_array(old('peserta_ids')) && in_array($emp->id, old('peserta_ids')) ? 'checked' : '' }}>
                                <div class="flex-1 min-w-0">
                                    <p
                                        class="font-bold text-zinc-900 text-xs truncate group-hover:text-black transition-colors">
                                        {{ $emp->nama_lengkap }}</p>
                                    <p class="text-[10px] text-zinc-400 font-medium">NIP: {{ $emp->nip }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('peserta_ids')
                        <p class="text-xs text-red-500 mt-4 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 justify-end pt-4">
                <button type="reset"
                    class="px-6 py-3 rounded-xl bg-zinc-100 text-zinc-600 font-bold text-sm hover:bg-zinc-200 transition-all">Reset
                    Form</button>
                <button type="submit"
                    class="px-8 py-3 rounded-xl bg-zinc-900 text-white font-bold text-sm shadow-xl hover:bg-zinc-800 transition-all active:scale-95">Simpan
                    Penugasan</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.checkbox-peserta');
            const counter = document.getElementById('counter');

            function updateCounter() {
                const checkedCount = document.querySelectorAll('.checkbox-peserta:checked').length;
                counter.textContent = checkedCount + ' Peserta Pilih';
                if (checkedCount > 0) {
                    counter.className =
                        'text-[10px] bg-blue-600 text-white px-2 py-0.5 rounded font-black tracking-widest uppercase';
                } else {
                    counter.className =
                        'text-[10px] bg-zinc-900 text-white px-2 py-0.5 rounded font-black tracking-widest uppercase';
                }
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateCounter);
            });

            updateCounter();
        });
    </script>
@endsection
