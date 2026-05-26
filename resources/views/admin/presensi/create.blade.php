@extends('layouts.app')

@section('title', 'Tambah Presensi Manual')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 42px;
        border-radius: 0.5rem;
        border: 1px solid #e4e4e7;
        display: flex;
        align-items: center;
        padding-left: 0.5rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #3f3f46;
        padding-left: 0;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #18181b;
        box-shadow: 0 0 0 2px rgba(24, 24, 27, 0.2);
    }
</style>
@endpush

@section('content')
<div class="flex flex-col space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Tambah Presensi Manual</h2>
            <p class="text-zinc-500 text-sm">Input data kehadiran pegawai secara manual.</p>
        </div>
        <a href="{{ route('admin.presensi.index') }}"
            class="bg-white border border-zinc-200 text-zinc-600 text-sm font-bold py-2 px-4 rounded-lg hover:bg-zinc-50 transition-all inline-flex items-center gap-2">
            <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
        <form action="{{ route('admin.presensi.store') }}" method="POST">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Select Pegawai -->
                <div class="space-y-1.5">
                    <label for="pegawai_id" class="text-sm font-bold text-zinc-700">
                        Pegawai <span class="text-red-500">*</span>
                    </label>
                    <select name="pegawai_id" id="pegawai_id" required
                        class="w-full px-4 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        <option value="" disabled selected>Pilih Pegawai...</option>
                        @foreach($pegawais as $pegawai)
                            <option value="{{ $pegawai->id }}" {{ old('pegawai_id') == $pegawai->id ? 'selected' : '' }}>
                                {{ $pegawai->nama_lengkap }} ({{ $pegawai->nip }})
                            </option>
                        @endforeach
                    </select>
                    @error('pegawai_id')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal -->
                <div class="space-y-1.5">
                    <label for="tanggal" class="text-sm font-bold text-zinc-700">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}"
                        class="w-full px-4 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                    @error('tanggal')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Kehadiran -->
                <div class="space-y-1.5">
                    <label for="status" class="text-sm font-bold text-zinc-700">
                        Status Kehadiran <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all appearance-none bg-no-repeat bg-[right_1rem_center] bg-[length:1em_1em]"
                        style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%239ca3af%22 stroke-width=%222%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M19 9l-7 7-7-7%22 /%3E%3C/svg%3E')">
                        <option value="Hadir" {{ old('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Izin" {{ old('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Sakit" {{ old('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Alpa" {{ old('status') == 'Alpa' ? 'selected' : '' }}>Alpa</option>
                    </select>
                    @error('status')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Jam Masuk -->
                    <div class="space-y-1.5">
                        <label for="jam_masuk" class="text-sm font-bold text-zinc-700">Jam Masuk</label>
                        <input type="time" name="jam_masuk" id="jam_masuk" value="{{ old('jam_masuk') }}"
                            class="w-full px-4 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        <p class="text-xs text-zinc-400">Kosongkan jika status bukan Hadir.</p>
                        @error('jam_masuk')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jam Pulang -->
                    <div class="space-y-1.5">
                        <label for="jam_pulang" class="text-sm font-bold text-zinc-700">Jam Pulang</label>
                        <input type="time" name="jam_pulang" id="jam_pulang" value="{{ old('jam_pulang') }}"
                            class="w-full px-4 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        <p class="text-xs text-zinc-400">Kosongkan jika status bukan Hadir.</p>
                        @error('jam_pulang')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="space-y-1.5">
                    <label for="keterangan" class="text-sm font-bold text-zinc-700">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                        class="w-full px-4 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all"
                        placeholder="Tambahkan keterangan opsional (Misal: Lupa Absen)...">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-zinc-50 border-t border-zinc-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.presensi.index') }}"
                    class="px-4 py-2 text-sm font-bold text-zinc-600 hover:text-zinc-900 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="bg-zinc-900 text-white text-sm font-bold py-2 px-6 rounded-lg hover:bg-zinc-800 transition-all flex items-center gap-2">
                    <i data-lucide="save" class="h-4 w-4"></i>
                    Simpan Presensi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#pegawai_id').select2({
            placeholder: "Cari Nama atau NIP...",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush
