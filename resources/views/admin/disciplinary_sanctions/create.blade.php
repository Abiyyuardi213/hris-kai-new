@extends('layouts.app')

@section('title', 'Buat Sanksi Baru')

@section('content')
    <div class="flex flex-col space-y-6 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.sanctions.index') }}"
                class="h-10 w-10 rounded-xl border border-zinc-200 bg-white flex items-center justify-center text-zinc-500 hover:text-zinc-900 transition-all hover:bg-zinc-50 shadow-sm">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Buat Sanksi Baru</h2>
                <p class="text-zinc-500 text-sm">Formulir pemberian sanksi atau surat peringatan kepada pegawai.</p>
            </div>
        </div>

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl flex items-center gap-3">
                <i data-lucide="alert-circle" class="h-5 w-5"></i>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.sanctions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Informasi Dasar -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50/50">
                    <h3 class="font-bold text-zinc-900 text-sm">Informasi Dasar</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Pilih Pegawai</label>
                        <select name="employee_id" required
                            class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                            <option value="">-- Cari Nama / NIP --</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->nama_lengkap }} - {{ $emp->nip }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Jenis Sanksi</label>
                        <select name="type" id="type" required
                            class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Verbal" {{ old('type') == 'Verbal' ? 'selected' : '' }}>Teguran Lisan</option>
                            <option value="SP1" {{ old('type') == 'SP1' ? 'selected' : '' }}>Surat Peringatan 1 (SP1)
                            </option>
                            <option value="SP2" {{ old('type') == 'SP2' ? 'selected' : '' }}>Surat Peringatan 2 (SP2)
                            </option>
                            <option value="SP3" {{ old('type') == 'SP3' ? 'selected' : '' }}>Surat Peringatan 3 (SP3)
                            </option>
                            <option value="Termination" {{ old('type') == 'Termination' ? 'selected' : '' }}>Pemutusan
                                Hubungan Kerja (PHK)</option>
                        </select>
                        @error('type')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Periode & Detail -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50/50">
                    <h3 class="font-bold text-zinc-900 text-sm">Periode & Detail Pelanggaran</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Tanggal Mulai</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="calendar" class="h-4 w-4 text-zinc-400"></i>
                            </div>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ old('start_date', date('Y-m-d')) }}" required
                                class="w-full pl-10 px-4 py-2 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        </div>
                        @error('start_date')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex justify-between">
                            <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Tanggal Berakhir</label>
                            <span class="text-[10px] text-zinc-400 italic">Opsional</span>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="calendar-off" class="h-4 w-4 text-zinc-400"></i>
                            </div>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                class="w-full pl-10 px-4 py-2 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        </div>
                        <p class="text-[10px] text-zinc-400 mt-1">Kosongkan jika berlaku selamanya.</p>
                        @error('end_date')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Alasan / Keterangan</label>
                        <textarea name="description" id="description" rows="5" required
                            class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all"
                            placeholder="Jelaskan secara rinci pelanggaran yang dilakukan...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs text-red-500 px-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Dokumen Pendukung -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50/50">
                    <h3 class="font-bold text-zinc-900 text-sm">Dokumen Pendukung</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center w-full">
                        <label for="document"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-zinc-300 border-dashed rounded-xl cursor-pointer bg-zinc-50 hover:bg-zinc-100 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i data-lucide="upload-cloud" class="h-8 w-8 text-zinc-400 mb-3"></i>
                                <p class="text-sm text-zinc-500"><span class="font-semibold text-zinc-900">Klik untuk
                                        unggah</span> atau drag and drop</p>
                                <p class="text-xs text-zinc-400 mt-1">PDF, JPG, PNG (Maks. 2MB)</p>
                            </div>
                            <input id="document" name="document" type="file" accept=".pdf,.jpg,.jpeg,.png"
                                class="hidden" onchange="previewFile()" />
                        </label>
                    </div>
                    <div id="file-preview"
                        class="hidden items-center gap-3 p-3 mt-4 bg-blue-50 text-blue-700 rounded-xl border border-blue-100">
                        <div class="p-2 bg-white rounded-lg">
                            <i data-lucide="file-check" class="h-5 w-5 text-blue-600"></i>
                        </div>
                        <span id="file-name" class="text-sm font-medium truncate"></span>
                        <button type="button" onclick="removeFile()"
                            class="ml-auto p-1 hover:bg-white rounded-lg text-blue-400 hover:text-blue-700 transition-all">
                            <i data-lucide="x" class="h-4 w-4"></i>
                        </button>
                    </div>

                    @error('document')
                        <p class="text-xs text-red-500 mt-2 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 justify-end pt-4">
                <a href="{{ route('admin.sanctions.index') }}"
                    class="px-6 py-3 rounded-xl bg-zinc-100 text-zinc-600 font-bold text-sm hover:bg-zinc-200 transition-all">
                    Batal
                </a>
                <button type="submit"
                    class="px-8 py-3 rounded-xl bg-zinc-900 text-white font-bold text-sm shadow-xl hover:bg-zinc-800 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="save" class="h-4 w-4"></i>
                    Simpan Sanksi
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function previewFile() {
                const input = document.getElementById('document');
                const preview = document.getElementById('file-preview');
                const nameSpan = document.getElementById('file-name');

                if (input.files && input.files[0]) {
                    nameSpan.textContent = input.files[0].name;
                    preview.classList.remove('hidden');
                    preview.classList.add('flex');
                    lucide.createIcons(); // Re-render icons for dynamic content
                }
            }

            function removeFile() {
                const input = document.getElementById('document');
                const preview = document.getElementById('file-preview');

                input.value = ''; // Clear input
                preview.classList.add('hidden');
                preview.classList.remove('flex');
            }
        </script>
    @endpush
@endsection
