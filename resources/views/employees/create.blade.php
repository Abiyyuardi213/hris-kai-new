@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css">
    <style>
        .ts-control {
            border-radius: 0.5rem !important;
            padding: 0.5rem 0.75rem !important;
            border-color: #d4d4d8 !important;
            font-size: 0.875rem !important;
            background-color: white !important;
        }

        .ts-control:focus {
            border-color: #18181b !important;
            box-shadow: 0 0 0 1px #18181b !important;
        }

        .ts-dropdown {
            border-radius: 0.5rem !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Tambah Pegawai</h2>
            <a href="{{ route('employees.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="rounded-xl border bg-white shadow-sm p-6">
            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Identitas Pribadi -->
                <div>
                    <h3 class="text-lg font-medium leading-6 text-zinc-900 mb-4">Identitas Pribadi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="nama_lengkap" class="block text-sm font-medium text-zinc-900">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('nama_lengkap') border-red-500 @enderror">
                            @error('nama_lengkap')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nik" class="block text-sm font-medium text-zinc-900">NIK (KTP)</label>
                            <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required
                                maxlength="16"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('nik') border-red-500 @enderror">
                            @error('nik')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between">
                                <label for="nip" class="block text-sm font-medium text-zinc-900">NIP</label>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" id="custom_nip"
                                        class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900 h-3.5 w-3.5"
                                        onchange="toggleNip()">
                                    <span
                                        class="text-[11px] text-zinc-500 group-hover:text-zinc-900 transition-colors">Custom
                                        NIP?</span>
                                </label>
                            </div>
                            <input type="text" name="nip" id="nip" value="{{ old('nip', $nextNip) }}" required
                                readonly
                                class="mt-1 block w-full rounded-lg border border-zinc-100 bg-zinc-50 px-3 py-2 text-sm text-zinc-500 cursor-not-allowed @error('nip') border-red-500 @enderror">
                            <p id="nip_note" class="mt-1 text-[10px] text-zinc-400">Nomor Induk Pegawai dibuat otomatis
                                oleh sistem.</p>
                            @error('nip')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tempat_lahir" class="block text-sm font-medium text-zinc-900">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('tempat_lahir') border-red-500 @enderror">
                            @error('tempat_lahir')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-zinc-900">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                value="{{ old('tanggal_lahir') }}" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('tanggal_lahir') border-red-500 @enderror">
                            @error('tanggal_lahir')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-medium text-zinc-900">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('jenis_kelamin') border-red-500 @enderror">
                                <option value="">Pilih</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="agama" class="block text-sm font-medium text-zinc-900">Agama</label>
                            <select name="agama" id="agama"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                <option value="">Pilih</option>
                                <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="border-zinc-200">

                <!-- Informasi Kepegawaian -->
                <div>
                    <h3 class="text-lg font-medium leading-6 text-zinc-900 mb-4">Informasi Kepegawaian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status_pegawai_id" class="block text-sm font-medium text-zinc-900">Status
                                Pegawai</label>
                            <select name="status_pegawai_id" id="status_pegawai_id" required class="searchable">
                                <option value="">Pilih Status</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}"
                                        {{ old('status_pegawai_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->code }} | {{ $status->name }}</option>
                                @endforeach
                            </select>
                            @error('status_pegawai_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="directorate_id" class="block text-sm font-medium text-zinc-900">Direktorat</label>
                            <select name="directorate_id" id="directorate_id" class="searchable">
                                <option value="">Pilih Direktorat</option>
                                @foreach ($directorates as $directorate)
                                    <option value="{{ $directorate->id }}"
                                        {{ old('directorate_id') == $directorate->id ? 'selected' : '' }}>
                                        {{ $directorate->code }} | {{ $directorate->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="divisi_id" class="block text-sm font-medium text-zinc-900">Divisi</label>
                            <select name="divisi_id" id="divisi_id" class="searchable">
                                <option value="">Pilih Divisi</option>
                                @foreach ($divisions as $div)
                                    <option value="{{ $div->id }}" data-directorate-id="{{ $div->directorate_id }}"
                                        {{ old('divisi_id') == $div->id ? 'selected' : '' }}>{{ $div->code }} |
                                        {{ $div->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="jabatan_id" class="block text-sm font-medium text-zinc-900">Jabatan</label>
                            <select name="jabatan_id" id="jabatan_id" class="searchable">
                                <option value="">Pilih Jabatan</option>
                                @foreach ($positions as $pos)
                                    <option value="{{ $pos->id }}"
                                        {{ old('jabatan_id') == $pos->id ? 'selected' : '' }}>{{ $pos->code }} |
                                        {{ $pos->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="kantor_id" class="block text-sm font-medium text-zinc-900">Kantor</label>
                            <select name="kantor_id" id="kantor_id" class="searchable">
                                <option value="">Pilih Kantor</option>
                                @foreach ($offices as $office)
                                    <option value="{{ $office->id }}"
                                        {{ old('kantor_id') == $office->id ? 'selected' : '' }}>{{ $office->office_code }}
                                        | {{ $office->office_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tanggal_masuk" class="block text-sm font-medium text-zinc-900">Tanggal
                                Masuk</label>
                            <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                                value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('tanggal_masuk') border-red-500 @enderror">
                            @error('tanggal_masuk')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="shift_kerja_id" class="block text-sm font-medium text-zinc-900">Shift
                                Kerja</label>
                            <select name="shift_kerja_id" id="shift_kerja_id" class="searchable">
                                <option value="">Pilih Shift (Opsional)</option>
                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}"
                                        {{ old('shift_kerja_id') == $shift->id ? 'selected' : '' }}>{{ $shift->name }} |
                                        {{ substr($shift->start_time, 0, 5) }} - {{ substr($shift->end_time, 0, 5) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="sisa_cuti" class="block text-sm font-medium text-zinc-900">Jatah Cuti
                                Tahunan</label>
                            <input type="number" name="sisa_cuti" id="sisa_cuti" value="{{ old('sisa_cuti', 12) }}"
                                required min="0"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                        </div>


                    </div>
                </div>

                <hr class="border-zinc-200">

                <!-- Kontak & Alamat -->
                <div>
                    <h3 class="text-lg font-medium leading-6 text-zinc-900 mb-4">Kontak & Alamat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="no_hp" class="block text-sm font-medium text-zinc-900">Nomor HP</label>
                            <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                        </div>
                        <div>
                            <label for="email_pribadi" class="block text-sm font-medium text-zinc-900">Email
                                Pribadi</label>
                            <input type="email" name="email_pribadi" id="email_pribadi"
                                value="{{ old('email_pribadi') }}"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                        </div>
                        <div class="md:col-span-2">
                            <label for="alamat_ktp" class="block text-sm font-medium text-zinc-900">Alamat KTP</label>
                            <textarea name="alamat_ktp" id="alamat_ktp" rows="3"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">{{ old('alamat_ktp') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label for="alamat_domisili" class="block text-sm font-medium text-zinc-900">Alamat
                                Domisili</label>
                            <textarea name="alamat_domisili" id="alamat_domisili" rows="3"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">{{ old('alamat_domisili') }}</textarea>
                        </div>
                    </div>
                </div>

                <hr class="border-zinc-200">

                <!-- Foto -->
                <div>
                    <h3 class="text-lg font-medium leading-6 text-zinc-900 mb-4">Foto Profil</h3>
                    <div class="flex items-center gap-6">
                        <div class="relative group">
                            <div
                                class="h-24 w-24 overflow-hidden rounded-xl bg-zinc-100 border-2 border-dashed border-zinc-200 group-hover:border-zinc-300 transition-colors flex items-center justify-center relative">
                                <img id="image-preview" src="" alt=""
                                    class="h-full w-full object-cover hidden">
                                <i id="upload-icon" data-lucide="image" class="h-8 w-8 text-zinc-300"></i>
                            </div>
                            <button type="button" id="remove-image"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-sm hidden hover:bg-red-600 transition-colors">
                                <i data-lucide="x" class="h-3 w-3"></i>
                            </button>
                        </div>
                        <div class="flex-1 max-w-sm">
                            <label class="block">
                                <span class="sr-only">Pilih foto</span>
                                <input type="file" id="foto_input" accept="image/*"
                                    class="block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-zinc-900 file:text-white hover:file:bg-zinc-800 transition-all cursor-pointer">
                            </label>
                            <p class="mt-2 text-[11px] text-zinc-500">JPG, PNG atau GIF (Max 2MB). Disarankan rasio 1:1.
                            </p>
                            <input type="hidden" name="foto_cropped" id="foto_cropped">
                        </div>
                    </div>
                </div>

                <!-- Cropper Modal -->
                <div id="cropperModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog"
                    aria-modal="true">
                    <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm"></div>
                    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                            <div
                                class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                <div class="bg-white px-4 py-4 border-b border-zinc-100 flex items-center justify-between">
                                    <h3 class="text-base font-semibold text-zinc-900">Potong Foto Profil</h3>
                                    <button type="button" onclick="closeCropper()"
                                        class="text-zinc-400 hover:text-zinc-600">
                                        <i data-lucide="x" class="h-5 w-5"></i>
                                    </button>
                                </div>
                                <div class="p-4">
                                    <div class="max-h-[400px] overflow-hidden rounded-lg bg-zinc-50">
                                        <img id="cropper-image" src="" class="max-w-full block">
                                    </div>
                                </div>
                                <div class="bg-zinc-50 px-4 py-3 flex justify-end gap-2 border-t border-zinc-100">
                                    <button type="button" onclick="closeCropper()"
                                        class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50">Batal</button>
                                    <button type="button" onclick="cropImage()"
                                        class="rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800">Terapkan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <a href="{{ route('employees.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2">
                        Simpan Data Pegawai
                    </button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
        <script>
            // Initialize TomSelect
            document.querySelectorAll('.searchable').forEach(el => {
                new TomSelect(el, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    placeholder: el.getAttribute('placeholder') || 'Pilih data...',
                    allowEmptyOption: true,
                });
            });

            // Store all division options
            const allDivisions = [
                @foreach ($divisions as $div)
                    {
                        id: "{{ $div->id }}",
                        directorate_id: "{{ $div->directorate_id }}",
                        text: "{{ $div->code }} | {{ $div->name }}"
                    },
                @endforeach
            ];

            // Handle Directorate Change
            const directorateSelect = document.getElementById('directorate_id');
            const divisionSelect = document.getElementById('divisi_id');
            let divisionTomSelect;

            // Wait for TomSelect to initialize
            setTimeout(() => {
                if (divisionSelect.tomselect) {
                    divisionTomSelect = divisionSelect.tomselect;

                    // Initial filter if directorate is selected (e.g. old value)
                    if (directorateSelect.value) {
                        filterDivisions(directorateSelect.value);
                    }
                }
            }, 100);

            // Listen for changes on Directorate (using TomSelect if applied, or standard change)
            // Since we applied .searchable to directorate, it will be a TomSelect instance
            setTimeout(() => {
                if (directorateSelect.tomselect) {
                    directorateSelect.tomselect.on('change', (value) => {
                        filterDivisions(value);
                    });
                } else {
                    directorateSelect.addEventListener('change', (e) => {
                        filterDivisions(e.target.value);
                    });
                }
            }, 100);

            function filterDivisions(directorateId) {
                if (!divisionTomSelect) return;

                divisionTomSelect.clear();
                divisionTomSelect.clearOptions();

                if (directorateId) {
                    const filteredDivisions = allDivisions.filter(div => div.directorate_id == directorateId);
                    filteredDivisions.forEach(div => {
                        divisionTomSelect.addOption({
                            value: div.id,
                            text: div.text
                        });
                    });
                } else {
                    // If no directorate selected, show all (or maybe show none? usually better to show none or all)
                    // Let's show all for now, or we can choose to reset.
                    // User request implies hierarchy, so maybe show none if no directorate? 
                    // "ketika sudah memilih direktorat, maka divisi yang di munculkan..." 
                    // implies dependency. But let's keep all if nothing selected to avoid confusion if they want to search directly.
                    // Actually, stricter is better: only show relevant ones.

                    allDivisions.forEach(div => {
                        divisionTomSelect.addOption({
                            value: div.id,
                            text: div.text
                        });
                    });
                }
            }

            // NIP Toggle
            function toggleNip() {
                const isCustom = document.getElementById('custom_nip').checked;
                const nipInput = document.getElementById('nip');
                const nipNote = document.getElementById('nip_note');

                if (isCustom) {
                    nipInput.readOnly = false;
                    nipInput.classList.remove('bg-zinc-50', 'text-zinc-500', 'cursor-not-allowed', 'border-zinc-100');
                    nipInput.classList.add('bg-white', 'text-zinc-900', 'border-zinc-300', 'focus:border-zinc-900',
                        'focus:ring-zinc-900');
                    nipNote.textContent = 'Masukkan NIP secara manual.';
                    nipNote.classList.remove('text-zinc-400');
                    nipNote.classList.add('text-amber-600');
                } else {
                    nipInput.readOnly = true;
                    nipInput.value = "{{ $nextNip }}";
                    nipInput.classList.add('bg-zinc-50', 'text-zinc-500', 'cursor-not-allowed', 'border-zinc-100');
                    nipInput.classList.remove('bg-white', 'text-zinc-900', 'border-zinc-300', 'focus:border-zinc-900',
                        'focus:ring-zinc-900');
                    nipNote.textContent = 'Nomor Induk Pegawai dibuat otomatis oleh sistem.';
                    nipNote.classList.remove('text-amber-600');
                    nipNote.classList.add('text-zinc-400');
                }
            }

            // Cropper Logic
            let cropper;
            const fotoInput = document.getElementById('foto_input');
            const cropperModal = document.getElementById('cropperModal');
            const cropperImg = document.getElementById('cropper-image');
            const imagePreview = document.getElementById('image-preview');
            const uploadIcon = document.getElementById('upload-icon');
            const removeBtn = document.getElementById('remove-image');
            const croppedInput = document.getElementById('foto_cropped');

            fotoInput.onchange = function(e) {
                const files = e.target.files;
                if (files && files.length > 0) {
                    const reader = new FileReader();
                    reader.onload = function() {
                        cropperImg.src = reader.result;
                        cropperModal.classList.remove('hidden');
                        if (cropper) cropper.destroy();
                        cropper = new Cropper(cropperImg, {
                            aspectRatio: 1,
                            viewMode: 2,
                            autoCropArea: 1,
                        });
                    };
                    reader.readAsDataURL(files[0]);
                }
            };

            function closeCropper() {
                cropperModal.classList.add('hidden');
                fotoInput.value = '';
            }

            function cropImage() {
                const canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 400,
                });
                const dataUrl = canvas.toDataURL('image/jpeg');
                imagePreview.src = dataUrl;
                imagePreview.classList.remove('hidden');
                uploadIcon.classList.add('hidden');
                removeBtn.classList.remove('hidden');
                croppedInput.value = dataUrl;
                cropperModal.classList.add('hidden');
            }

            removeBtn.onclick = function() {
                imagePreview.src = '';
                imagePreview.classList.add('hidden');
                uploadIcon.classList.remove('hidden');
                removeBtn.classList.add('hidden');
                croppedInput.value = '';
                fotoInput.value = '';
            };

            document.addEventListener('keydown', function(event) {
                if (event.key === "Escape" && !cropperModal.classList.contains('hidden')) {
                    closeCropper();
                }
            });
        </script>
    @endpush
@endsection
