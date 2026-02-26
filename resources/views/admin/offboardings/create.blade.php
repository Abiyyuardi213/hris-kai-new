@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.offboardings.index') }}"
                class="w-10 h-10 flex items-center justify-center rounded-full border border-zinc-200 hover:bg-zinc-50 transition text-zinc-600 bg-white">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-zinc-900">Inisiasi Offboarding oleh Admin</h1>
                <p class="text-sm text-zinc-500 mt-1">Daftarkan pegawai ke status offboarding (untuk kasus Pemecatan / Demosi
                    / Meninggal Dunia dll)</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-red-50 text-red-800 text-sm rounded-xl">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm p-6 max-w-2xl">
            <form action="{{ route('admin.offboardings.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-3">
                    <label class="text-sm font-bold text-zinc-700">Pilih Pegawai (Karyawan) <span
                            class="text-red-500">*</span></label>
                    <select name="pegawai_id" required
                        class="w-full rounded-xl border-zinc-200 px-4 py-2.5 focus:ring-zinc-900 focus:border-zinc-900 shadow-sm text-sm">
                        <option value="">Pilih Pegawai...</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('pegawai_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->nama_lengkap }} ({{ $emp->nip }}) - {{ $emp->jabatan->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <label class="text-sm font-bold text-zinc-700">Tipe / Alasan Utama <span
                                class="text-red-500">*</span></label>
                        <select name="tipe_offboarding" required
                            class="w-full rounded-xl border-zinc-200 px-4 py-2.5 focus:ring-zinc-900 focus:border-zinc-900 shadow-sm text-sm">
                            <option value="">Pilih Tipe</option>
                            <option value="Pemutusan Hubungan Kerja (PHK)">Pemutusan Hubungan Kerja (PHK)</option>
                            <option value="Pensiun">Pensiun</option>
                            <option value="Meninggal Dunia / Force Majeure">Meninggal Dunia / Force Majeure</option>
                            <option value="Habis Kontrak Kerja">Habis Kontrak Kerja</option>
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="text-sm font-bold text-zinc-700">Tgl Pembebasan Tugas <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_efektif" required
                            value="{{ old('tanggal_efektif', date('Y-m-d')) }}"
                            class="w-full rounded-xl border-zinc-200 px-4 py-2.5 focus:ring-zinc-900 focus:border-zinc-900 shadow-sm text-sm">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-zinc-700">Catatan Khusus Admin & Internal</label>
                    <textarea name="catatan_admin" rows="3" placeholder="Masukkan detail alasan pemberhentian kerja..."
                        class="w-full rounded-xl border-zinc-200 px-4 py-2.5 focus:ring-zinc-900 focus:border-zinc-900 shadow-sm text-sm">{{ old('catatan_admin') }}</textarea>
                    <p class="text-xs text-zinc-400">Status secara otomatis akan menjadi "In Progress" untuk pengembalian
                        barang.</p>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-zinc-100">
                    <a href="{{ route('admin.offboardings.index') }}"
                        class="px-5 py-2.5 text-sm font-bold text-zinc-600 hover:bg-zinc-100 rounded-xl transition">Batal</a>
                    <button type="submit" onclick="return confirm('Inisiasi rekam jejak offboarding untuk pegawai ini?')"
                        class="px-6 py-2.5 flex items-center gap-2 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-xl text-sm transition">
                        Proses Pemberhentian <i data-lucide="shield-alert" class="h-4 w-4"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
