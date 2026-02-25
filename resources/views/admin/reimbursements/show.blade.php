@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.reimbursements.index') }}"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 transition-colors">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900">Detail Reimbursement</h1>
                    <p class="text-sm text-zinc-500 mt-1">Tinjau dan proses pengajuan atas nama
                        <strong>{{ $reimbursement->pegawai->nama_lengkap ?? '-' }}</strong>
                    </p>
                </div>
            </div>
            <div>
                <span
                    class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider rounded-full shadow-sm
                {{ $reimbursement->status == 'Approved'
                    ? 'bg-green-100 text-green-700'
                    : ($reimbursement->status == 'Rejected'
                        ? 'bg-red-100 text-red-700'
                        : 'bg-amber-100 text-amber-700') }}">
                    Status: {{ $reimbursement->status }}
                </span>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Column: Detail Klaim -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden shadow-sm">
                    <div class="border-b border-zinc-100 px-6 py-4 flex items-center gap-2">
                        <i data-lucide="file-text" class="h-5 w-5 text-indigo-600"></i>
                        <h2 class="font-bold text-zinc-900">Informasi Pengajuan</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Tipe Reimbursement</p>
                                <p class="text-sm font-semibold text-zinc-900">{{ $reimbursement->tipe_reimbursement }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Tanggal Pengajuan</p>
                                <p class="text-sm font-semibold text-zinc-900">
                                    {{ \Carbon\Carbon::parse($reimbursement->tanggal_pengajuan)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Nominal Klaim</p>
                                <p class="text-lg font-bold text-zinc-900">Rp
                                    {{ number_format($reimbursement->nominal, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-zinc-100 space-y-2">
                            <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Keterangan / Tujuan</p>
                            <div class="bg-zinc-50 rounded-xl p-4 text-sm text-zinc-700">
                                {{ $reimbursement->keterangan ?? 'Tidak ada keterangan tambahan yang disertakan.' }}
                            </div>
                        </div>

                        @if ($reimbursement->lampiran)
                            <div class="mt-6 pt-6 border-t border-zinc-100 space-y-2">
                                <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Lampiran Bukti
                                    (Struk/Nota)</p>
                                <a href="{{ Storage::url($reimbursement->lampiran) }}" target="_blank"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-bold hover:bg-indigo-100 transition-colors">
                                    <i data-lucide="paperclip" class="h-4 w-4"></i>
                                    Buka / Download Dokumen Lampiran
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Panel Approval -->
            <div class="space-y-6">
                <!-- Profil Singkat Pemohon -->
                <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 text-center">
                    <div class="mx-auto w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center mb-4">
                        <span
                            class="text-2xl font-bold text-indigo-600">{{ substr($reimbursement->pegawai->nama_lengkap ?? 'A', 0, 1) }}</span>
                    </div>
                    <h3 class="font-bold text-zinc-900">{{ $reimbursement->pegawai->nama_lengkap ?? 'Anonim' }}</h3>
                    <p class="text-sm text-zinc-500 mt-1">NIP: {{ $reimbursement->pegawai->nip ?? '-' }}</p>
                    <div class="mt-4 inline-flex px-3 py-1 rounded-full bg-zinc-100 text-xs font-medium text-zinc-600">
                        {{ $reimbursement->pegawai->jabatan->name ?? 'Staf' }}
                    </div>
                </div>

                <!-- Form Approval -->
                <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                    <div class="border-b border-zinc-100 px-6 py-4">
                        <h2 class="font-bold text-zinc-900 flex flex-items gap-2">
                            <i data-lucide="shield-check" class="h-5 w-5 text-indigo-600"></i>
                            Persetujuan
                        </h2>
                    </div>

                    <div class="p-6">
                        @if ($reimbursement->status == 'Pending')
                            <form action="{{ route('admin.reimbursements.update-status', $reimbursement->id) }}"
                                method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="space-y-4">
                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-zinc-700">Keputusan</label>
                                        <select name="status" required
                                            class="w-full rounded-xl border-zinc-200 px-4 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            <option value="">Pilih Aksi...</option>
                                            <option value="Approved">Setujui (Approved)</option>
                                            <option value="Rejected">Tolak (Rejected)</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-bold text-zinc-700">Catatan Evaluasi <span
                                                class="text-zinc-400 font-normal">(opsional)</span></label>
                                        <textarea name="catatan_approval" rows="3"
                                            class="w-full rounded-xl border-zinc-200 px-4 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                            placeholder="Berikan alasan penolakan atau catatan tambahan untuk disetujui..."></textarea>
                                    </div>
                                    <button type="submit"
                                        class="w-full py-2.5 bg-zinc-900 text-white text-sm font-bold rounded-xl hover:bg-zinc-800 transition-colors mt-2">
                                        Simpan Keputusan
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <i data-lucide="calendar-check" class="h-5 w-5 text-zinc-400 mt-0.5"></i>
                                    <div>
                                        <p class="text-xs font-bold text-zinc-400 uppercase">Tanggal Keputusan</p>
                                        <p class="text-sm font-semibold text-zinc-900 mt-1">
                                            {{ \Carbon\Carbon::parse($reimbursement->tanggal_approval)->translatedFormat('d F Y') }}
                                        </p>
                                    </div>
                                </div>

                                <hr class="border-zinc-100">

                                <div class="flex items-start gap-3">
                                    <i data-lucide="user-check" class="h-5 w-5 text-zinc-400 mt-0.5"></i>
                                    <div>
                                        <p class="text-xs font-bold text-zinc-400 uppercase">Diproses Oleh</p>
                                        <p class="text-sm font-semibold text-zinc-900 mt-1">
                                            {{ $reimbursement->approver->name ?? 'Sistem' }}</p>
                                    </div>
                                </div>

                                <hr class="border-zinc-100">

                                <div class="flex items-start gap-3">
                                    <i class="h-5 w-5 mt-0.5 text-zinc-400" data-lucide="message-square"></i>
                                    <div>
                                        <p class="text-xs font-bold text-zinc-400 uppercase">Catatan</p>
                                        <p class="text-sm text-zinc-700 mt-1 italic">
                                            "{{ $reimbursement->catatan_approval ?? 'Tidak ada catatan.' }}"
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
