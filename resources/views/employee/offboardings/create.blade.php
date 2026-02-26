@extends('layouts.employee')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('employee.offboardings.index') }}"
                class="w-10 h-10 flex items-center justify-center rounded-full border border-zinc-200 hover:bg-zinc-50 transition text-zinc-600">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-zinc-900">Formulir Offboarding / Pengunduran Diri</h1>
                <p class="text-sm text-zinc-500 mt-1">Isi Exit Interview beserta permohonan Offboarding Anda</p>
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

        <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm p-6 overflow-hidden">
            <form action="{{ route('employee.offboardings.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tipe & Tanggal -->
                    <div class="space-y-3">
                        <label class="text-sm font-bold text-zinc-700">Tipe Pengajuan <span
                                class="text-red-500">*</span></label>
                        <select name="tipe_offboarding" required
                            class="w-full rounded-xl border-zinc-200 px-4 py-2.5 focus:ring-zinc-900 focus:border-zinc-900 shadow-sm text-sm">
                            <option value="">Pilih Tipe</option>
                            <option value="Resign (Pengunduran Diri)">Resign (Pengunduran Diri)</option>
                            <option value="Pensiun">Pensiun / Pensiun Dini</option>
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="text-sm font-bold text-zinc-700">Tanggal Efektif Keluar <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_efektif" required min="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl border-zinc-200 px-4 py-2.5 focus:ring-zinc-900 focus:border-zinc-900 shadow-sm text-sm">
                        <p class="text-xs text-zinc-400">Pilih hari terakhir masuk / resmi berhenti bekerja.</p>
                    </div>
                </div>

                <!-- Exit Interview -->
                <hr class="border-zinc-100">
                <h2 class="text-lg font-bold text-zinc-900 items-center flex gap-2">
                    <i data-lucide="file-question" class="h-5 w-5 text-indigo-600"></i>
                    Exit Interview (Kuesioner)
                </h2>

                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-zinc-700">Alasan Utama Berhenti Bekerja <span
                                class="text-red-500">*</span></label>
                        <textarea name="alasan_keluar" rows="4" required placeholder="Jelaskan alasan pengunduran diri..."
                            class="w-full rounded-xl border-zinc-200 px-4 py-2.5 focus:ring-zinc-900 focus:border-zinc-900 shadow-sm text-sm">{{ old('alasan_keluar') }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-zinc-700">Saran, Masukan, atau Kesan Pesan untuk Perusahaan
                            <span class="text-red-500">*</span></label>
                        <textarea name="saran_masukan" rows="4" required
                            placeholder="Saran Anda tentang lingkungan kerja, sistem, atau hal lain untuk perbaikan manajemen KAI..."
                            class="w-full rounded-xl border-zinc-200 px-4 py-2.5 focus:ring-zinc-900 focus:border-zinc-900 shadow-sm text-sm">{{ old('saran_masukan') }}</textarea>
                    </div>
                </div>

                <!-- Catatan Aturan -->
                <div class="bg-amber-50 p-4 rounded-xl flex items-start gap-3 mt-6 border border-amber-100 text-amber-800">
                    <i data-lucide="alert-triangle" class="h-5 w-5 shrink-0"></i>
                    <div class="text-xs sm:text-sm">
                        <p class="font-bold mb-1">Pernyataan dan Ketentuan:</p>
                        <ul class="list-disc pl-4 space-y-1 opacity-90">
                            <li>Dengan mengajukan ini, Anda menyatakan keputusan diambil secara sadar & tidak ada paksaan.
                            </li>
                            <li>Proses Clearance Aset (Laptop, ID Card, dan Dokumen) akan ditinjau langsung oleh staf
                                Administrasi/HR.</li>
                            <li>Pesangon dan dokumen terkait exit clearance akan diproses setelah formulir disetujui.</li>
                        </ul>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-zinc-100">
                    <a href="{{ route('employee.offboardings.index') }}"
                        class="px-5 py-2.5 text-sm font-bold text-zinc-600 hover:bg-zinc-100 rounded-xl transition">Batal</a>
                    <button type="submit"
                        onclick="return confirm('Apakah Anda yakin akan mengirim form pengunduran diri ini?')"
                        class="px-6 py-2.5 flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-sm transition">
                        Kirim Form Offboarding <i data-lucide="send" class="h-4 w-4"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
