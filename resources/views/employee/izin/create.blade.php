@extends('layouts.employee')
@section('title', 'Ajukan Izin Baru')

@section('content')
    <div class="flex flex-col space-y-6 max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('employee.izin.index') }}"
                class="h-10 w-10 flex items-center justify-center rounded-full bg-white border border-zinc-100 text-zinc-400 hover:text-zinc-900 transition-all shadow-sm">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Form Pengajuan</h2>
                <p class="text-sm text-zinc-500">Lengkapi data untuk mengajukan izin, sakit, atau cuti.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl border border-zinc-100 shadow-xl shadow-zinc-200/50 overflow-hidden">
            <form action="{{ route('employee.izin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-8 space-y-8">
                    <!-- Jenis Pengajuan -->
                    <div class="space-y-3">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest ml-1">Jenis
                            Pengajuan</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach (['izin' => 'Izin', 'sakit' => 'Sakit', 'cuti' => 'Cuti', 'dispensasi' => 'Dispensasi', 'lainnya' => 'Lainnya'] as $val => $label)
                                <label
                                    class="relative flex cursor-pointer rounded-2xl border border-zinc-100 bg-zinc-50/50 p-4 shadow-sm focus:outline-none hover:bg-zinc-100 transition-all has-[:checked]:bg-zinc-900 has-[:checked]:border-zinc-900 has-[:checked]:text-white">
                                    <input type="radio" name="type" value="{{ $val }}" class="sr-only"
                                        {{ old('type', 'izin') == $val ? 'checked' : '' }}>
                                    <span
                                        class="flex flex-1 items-center justify-center text-sm font-bold">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('type')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Periode -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest ml-1">Mulai
                                Tanggal</label>
                            <div class="relative">
                                <i data-lucide="calendar"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                                <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                                    required
                                    class="block w-full rounded-2xl border border-zinc-100 bg-zinc-50/50 pl-11 pr-4 py-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                            </div>
                            @error('start_date')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest ml-1">Sampai
                                Tanggal</label>
                            <div class="relative">
                                <i data-lucide="calendar"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                                <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required
                                    class="block w-full rounded-2xl border border-zinc-100 bg-zinc-50/50 pl-11 pr-4 py-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                            </div>
                            @error('end_date')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Alasan -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest ml-1">Alasan
                            Pengajuan</label>
                        <textarea name="reason" rows="4" required
                            class="block w-full rounded-2xl border border-zinc-100 bg-zinc-50/50 px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-zinc-900 outline-none transition-all placeholder:text-zinc-300"
                            placeholder="Berikan alasan yang jelas..."></textarea>
                        @error('reason')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lampiran -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest ml-1">Lampiran / Bukti <span
                                class="text-[10px] normal-case font-normal text-zinc-400">(Opsional)</span></label>
                        <div class="relative group">
                            <input type="file" name="attachment" id="attachmentInput"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                onchange="updateFileName(this)">
                            <div id="dropzone"
                                class="block w-full rounded-2xl border-2 border-dashed border-zinc-100 bg-zinc-50/30 px-6 py-10 text-center group-hover:bg-zinc-50 transition-all">
                                <div
                                    class="mx-auto h-12 w-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                                    <i data-lucide="upload-cloud" class="h-6 w-6 text-zinc-400"></i>
                                </div>
                                <span id="fileName" class="text-xs font-bold text-zinc-500">Klik atau geser file PDF /
                                    Gambar ke sini</span>
                                <p class="text-[10px] text-zinc-400 mt-1">Maksimal ukuran file: 2MB</p>
                            </div>
                        </div>
                        @error('attachment')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="bg-zinc-50/50 p-8 flex gap-4 border-t border-zinc-100">
                    <a href="{{ route('employee.izin.index') }}"
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

    <script>
        function updateFileName(input) {
            const fileNameDisplay = document.getElementById('fileName');
            const dropzone = document.getElementById('dropzone');

            if (input.files && input.files.length > 0) {
                fileNameDisplay.textContent = input.files[0].name;
                fileNameDisplay.classList.remove('text-zinc-500');
                fileNameDisplay.classList.add('text-emerald-600');
                dropzone.classList.add('bg-emerald-50/30', 'border-emerald-100');
            } else {
                fileNameDisplay.textContent = "Klik atau geser file PDF / Gambar ke sini";
                fileNameDisplay.classList.remove('text-emerald-600');
                fileNameDisplay.classList.add('text-zinc-500');
                dropzone.classList.remove('bg-emerald-50/30', 'border-emerald-100');
            }
        }
    </script>
@endsection
