@extends('layouts.app')

@section('content')
    <div class="space-y-6 flex-1">
        <div class="flex items-center gap-4 border-b border-zinc-100 pb-4">
            <a href="{{ route('admin.offboardings.index') }}"
                class="inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border border-zinc-200 text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 transition-colors bg-white">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-zinc-900">Proses Offboarding / Clearance</h1>
                <p class="text-sm text-zinc-500 mt-1">Lakukan validasi kepulangan aset, exit interview, dan estimasi
                    pesangon.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="px-4 py-3 bg-green-50 text-green-800 rounded-xl font-medium border border-green-100 text-sm">
                <i data-lucide="check-circle" class="h-4 w-4 inline-block mr-1 -mt-1"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="px-4 py-3 bg-red-50 text-red-800 rounded-xl text-sm border border-red-100 mt-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Kolom Info & Kuisioner -->
            <div class="xl:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 space-y-4">
                    <div class="text-center pb-4 border-b border-zinc-100">
                        <div class="w-16 h-16 rounded-full bg-indigo-50 mx-auto flex items-center justify-center mb-4">
                            <span
                                class="text-2xl font-bold text-indigo-700">{{ substr($offboarding->pegawai->nama_lengkap ?? 'O', 0, 1) }}</span>
                        </div>
                        <h2 class="font-bold text-lg text-zinc-900">{{ $offboarding->pegawai->nama_lengkap ?? 'Anonim' }}
                        </h2>
                        <p class="text-zinc-500 text-sm mt-1">{{ $offboarding->pegawai->nip ?? '-' }} •
                            {{ $offboarding->pegawai->jabatan->name ?? 'Pegawai' }}</p>

                        <span
                            class="inline-block mt-4 px-3 py-1 bg-red-50 border border-red-100 text-red-700 rounded-lg text-xs font-bold uppercase tracking-wider">
                            {{ \Carbon\Carbon::parse($offboarding->tanggal_efektif)->translatedFormat('d F Y') }}
                        </span>
                        <p class="text-[10px] text-zinc-400 mt-1 uppercase font-bold tracking-widest">Tgl Efektif / Tgl
                            Keluar</p>
                    </div>

                    <div class="space-y-4 pt-2">
                        <div class="bg-zinc-50 p-4 rounded-xl space-y-2 border border-zinc-100">
                            <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider flex items-center gap-1"><i
                                    data-lucide="message-square" class="w-3 h-3"></i> Alasan Keluar:</p>
                            <p class="text-sm text-zinc-900 font-medium">
                                {{ $offboarding->alasan_keluar ?? 'Didaftarkan oleh Manajemen/Admin' }}</p>
                        </div>

                        <div class="bg-zinc-50 p-4 rounded-xl space-y-2 border border-zinc-100">
                            <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider flex items-center gap-1"><i
                                    data-lucide="lightbulb" class="w-3 h-3"></i> Saran & Masukan:</p>
                            <p class="text-sm text-zinc-700">
                                {{ $offboarding->saran_masukan ?? 'Tidak ada saran yang diisi.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Form Admin -->
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-zinc-100 flex items-center gap-2 bg-zinc-50/50">
                        <i data-lucide="shield-alert" class="h-5 w-5 text-indigo-600"></i>
                        <h3 class="font-bold text-zinc-900">Panel Validasi HR / Admin</h3>
                    </div>

                    <form action="{{ route('admin.offboardings.update-process', $offboarding->id) }}" method="POST"
                        class="p-6">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-8">
                            <!-- Progress Status -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-zinc-700">Ubah Status Tiket <span
                                            class="text-red-500">*</span></label>
                                    <select name="status"
                                        class="w-full rounded-xl border-zinc-200 px-4 py-2.5 bg-zinc-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold">
                                        <option value="Pending" {{ $offboarding->status == 'Pending' ? 'selected' : '' }}>
                                            Pending (Draft)</option>
                                        <option value="In Progress"
                                            {{ $offboarding->status == 'In Progress' ? 'selected' : '' }}>In Progress
                                            (Dalam Proses Clearance)</option>
                                        <option value="Completed"
                                            {{ $offboarding->status == 'Completed' ? 'selected' : '' }}>Completed (Selesai
                                            & Non-Aktif)</option>
                                        <option value="Rejected"
                                            {{ $offboarding->status == 'Rejected' ? 'selected' : '' }}>Rejected (Ditolak /
                                            Batal Keluar)</option>
                                    </select>
                                    <p class="text-xs text-zinc-500">Jika "Completed", status kepegawaian otomatis diubah ke
                                        'Non-Aktif/Resign'.</p>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-zinc-700">Estimasi Uang Pesangon (Rp) <span
                                            class="font-normal text-zinc-400">(Bila Ada)</span></label>
                                    <input type="number" name="uang_pesangon" min="0"
                                        value="{{ old('uang_pesangon', $offboarding->uang_pesangon != null ? round($offboarding->uang_pesangon) : '0') }}"
                                        class="w-full rounded-xl border-zinc-200 px-4 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm bg-emerald-50 text-emerald-900 border-emerald-200 placeholder:text-emerald-300">
                                </div>
                            </div>

                            <!-- Checklist -->
                            <hr class="border-zinc-100">
                            <div class="space-y-4">
                                <h4
                                    class="text-sm font-bold text-zinc-900 uppercase tracking-widest flex items-center gap-2">
                                    <i data-lucide="check-square" class="w-4 h-4 text-zinc-400"></i> Form Exit Clearance
                                    Aset</h4>

                                <div class="flex flex-col gap-3">
                                    <label
                                        class="flex items-center gap-3 p-3 border border-zinc-200 rounded-xl hover:bg-zinc-50 cursor-pointer transition">
                                        <input type="checkbox" name="clearance_id_card" value="1"
                                            {{ $offboarding->clearance_id_card ? 'checked' : '' }}
                                            class="w-5 h-5 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer">
                                        <div class="select-none">
                                            <p class="text-sm font-bold text-zinc-900">Pengembalian ID Card KAI & Akses</p>
                                            <p class="text-xs text-zinc-500">Kunci Ruang, Smart Card Akses Gedung.</p>
                                        </div>
                                    </label>

                                    <label
                                        class="flex items-center gap-3 p-3 border border-zinc-200 rounded-xl hover:bg-zinc-50 cursor-pointer transition">
                                        <input type="checkbox" name="clearance_laptop" value="1"
                                            {{ $offboarding->clearance_laptop ? 'checked' : '' }}
                                            class="w-5 h-5 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer">
                                        <div class="select-none">
                                            <p class="text-sm font-bold text-zinc-900">Pengembalian Aset & Inventaris Kantor
                                            </p>
                                            <p class="text-xs text-zinc-500">Laptop IT, Kendaraan Dinas, ATK Hak Pakai.</p>
                                        </div>
                                    </label>

                                    <label
                                        class="flex items-center gap-3 p-3 border border-zinc-200 rounded-xl hover:bg-zinc-50 cursor-pointer transition">
                                        <input type="checkbox" name="clearance_dokumen" value="1"
                                            {{ $offboarding->clearance_dokumen ? 'checked' : '' }}
                                            class="w-5 h-5 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer">
                                        <div class="select-none">
                                            <p class="text-sm font-bold text-zinc-900">Penyelesaian Dokumen / File Pekerjaan
                                            </p>
                                            <p class="text-xs text-zinc-500">Berita Acara Handover Data Pekerjaan ke Pegawai
                                                Lain.</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <hr class="border-zinc-100">

                            <div class="space-y-2">
                                <label class="text-sm font-bold text-zinc-700">Catatan Privasi Admin ke Pegawai</label>
                                <textarea name="catatan_admin" rows="3"
                                    placeholder="Informasi pesangon dikirim tanggal xxx atau mohon segera kembalikan laptop..."
                                    class="w-full rounded-xl border-zinc-200 px-4 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('catatan_admin', $offboarding->catatan_admin) }}</textarea>
                                <p class="text-xs text-zinc-400">Pegawai akan dapat melihat riwayat catatan ini di
                                    aplikasinya.</p>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit"
                                    class="w-full sm:w-auto px-6 py-3 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-xl text-sm transition shadow-sm">
                                    Perbarui Dokumentasi Offboarding
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
