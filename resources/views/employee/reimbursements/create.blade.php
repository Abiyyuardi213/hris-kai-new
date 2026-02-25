@extends('layouts.employee')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('employee.reimbursements.index') }}"
                class="inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-white border border-zinc-200 text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-zinc-900">Ajukan Reimbursement</h1>
                <p class="text-sm text-zinc-500 mt-1">Isi formulir pengajuan reimbursement klaim/biaya operasional.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden shadow-sm">
            <div class="p-6">
                <form action="{{ route('employee.reimbursements.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tipe Reimbursement -->
                            <div class="space-y-2">
                                <label for="tipe_reimbursement" class="text-sm font-bold text-zinc-700">Tipe Reimbursement
                                    <span class="text-red-500">*</span></label>
                                <select name="tipe_reimbursement" id="tipe_reimbursement" required
                                    class="w-full rounded-xl border-zinc-200 px-4 py-2.5 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 text-sm">
                                    <option value="">Pilih Tipe Reimbursement</option>
                                    <option value="Kesehatan / Medis"
                                        {{ old('tipe_reimbursement') == 'Kesehatan / Medis' ? 'selected' : '' }}>Kesehatan /
                                        Medis</option>
                                    <option value="Transportasi"
                                        {{ old('tipe_reimbursement') == 'Transportasi' ? 'selected' : '' }}>Transportasi
                                    </option>
                                    <option value="Operasional Kantor"
                                        {{ old('tipe_reimbursement') == 'Operasional Kantor' ? 'selected' : '' }}>
                                        Operasional Kantor</option>
                                    <option value="Internet / Pulsa"
                                        {{ old('tipe_reimbursement') == 'Internet / Pulsa' ? 'selected' : '' }}>Internet /
                                        Pulsa</option>
                                    <option value="Lainnya" {{ old('tipe_reimbursement') == 'Lainnya' ? 'selected' : '' }}>
                                        Lainnya</option>
                                </select>
                            </div>

                            <!-- Tanggal Pengajuan -->
                            <div class="space-y-2">
                                <label for="tanggal_pengajuan" class="text-sm font-bold text-zinc-700">Tanggal Pengajuan
                                    <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_pengajuan" id="tanggal_pengajuan" required
                                    value="{{ old('tanggal_pengajuan', date('Y-m-d')) }}"
                                    class="w-full rounded-xl border-zinc-200 px-4 py-2.5 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 text-sm">
                            </div>

                            <!-- Nominal -->
                            <div class="space-y-2">
                                <label for="nominal" class="text-sm font-bold text-zinc-700">Nominal (Rp) <span
                                        class="text-red-500">*</span></label>
                                <input type="number" name="nominal" id="nominal" required min="0" step="1"
                                    value="{{ old('nominal') }}" placeholder="Contoh: 150000"
                                    class="w-full rounded-xl border-zinc-200 px-4 py-2.5 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 text-sm">
                            </div>

                            <!-- Lampiran -->
                            <div class="space-y-2">
                                <label for="lampiran" class="text-sm font-bold text-zinc-700">Lampiran Bukti (Struk/Nota)
                                    <span class="text-zinc-400 font-normal">(Opsional, Max 5MB)</span></label>
                                <input type="file" name="lampiran" id="lampiran" accept=".jpg,.jpeg,.png,.pdf"
                                    class="w-full rounded-xl border border-zinc-200 p-2 text-sm text-zinc-500 file:mr-4 file:rounded-lg file:border-0 file:bg-zinc-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-zinc-600 hover:file:bg-zinc-200 transition-all">
                                <p class="text-xs text-zinc-500">Format: JPG, PNG, PDF</p>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="space-y-2">
                            <label for="keterangan" class="text-sm font-bold text-zinc-700">Keterangan / Tujuan</label>
                            <textarea name="keterangan" id="keterangan" rows="4" placeholder="Jelaskan penggunaan dana ini..."
                                class="w-full rounded-xl border-zinc-200 px-4 py-2.5 shadow-sm focus:border-zinc-900 focus:ring-zinc-900 text-sm">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-zinc-100">
                        <a href="{{ route('employee.reimbursements.index') }}"
                            class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-bold text-zinc-600 hover:bg-zinc-100 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-zinc-900 px-6 py-2.5 text-sm font-bold text-white transition-all hover:bg-zinc-800 focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2">
                            <i data-lucide="send" class="h-4 w-4"></i>
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
