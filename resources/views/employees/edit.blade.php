@extends('layouts.app')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Edit Pegawai</h2>
            <a href="{{ route('employees.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="rounded-xl border bg-white shadow-sm p-6">
            <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Identitas Pribadi -->
                <div>
                    <h3 class="text-lg font-medium leading-6 text-zinc-900 mb-4">Identitas Pribadi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="nama_lengkap" class="block text-sm font-medium text-zinc-900">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap"
                                value="{{ old('nama_lengkap', $employee->nama_lengkap) }}" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('nama_lengkap') border-red-500 @enderror">
                            @error('nama_lengkap')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nik" class="block text-sm font-medium text-zinc-900">NIK (KTP)</label>
                            <input type="text" name="nik" id="nik" value="{{ old('nik', $employee->nik) }}"
                                required maxlength="16"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('nik') border-red-500 @enderror">
                            @error('nik')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nip" class="block text-sm font-medium text-zinc-900">NIP (Permanen)</label>
                            <input type="text" name="nip" id="nip" value="{{ old('nip', $employee->nip) }}"
                                required readonly
                                class="mt-1 block w-full rounded-lg border border-zinc-100 bg-zinc-50 px-3 py-2 text-sm text-zinc-500 cursor-not-allowed @error('nip') border-red-500 @enderror">
                            <p class="mt-1 text-[10px] text-zinc-400">NIP adalah identitas unik permanen.</p>
                            @error('nip')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tempat_lahir" class="block text-sm font-medium text-zinc-900">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir"
                                value="{{ old('tempat_lahir', $employee->tempat_lahir) }}" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('tempat_lahir') border-red-500 @enderror">
                            @error('tempat_lahir')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-zinc-900">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $employee->tanggal_lahir) }}" required
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
                                <option value="L"
                                    {{ old('jenis_kelamin', $employee->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="P"
                                    {{ old('jenis_kelamin', $employee->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan
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
                                @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $rel)
                                    <option value="{{ $rel }}"
                                        {{ old('agama', $employee->agama) == $rel ? 'selected' : '' }}>{{ $rel }}
                                    </option>
                                @endforeach
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
                            <select name="status_pegawai_id" id="status_pegawai_id" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('status_pegawai_id') border-red-500 @enderror">
                                <option value="">Pilih Status</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}"
                                        {{ old('status_pegawai_id', $employee->status_pegawai_id) == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}</option>
                                @endforeach
                            </select>
                            @error('status_pegawai_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="divisi_id" class="block text-sm font-medium text-zinc-900">Divisi</label>
                            <select name="divisi_id" id="divisi_id"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                <option value="">Pilih Divisi</option>
                                @foreach ($divisions as $div)
                                    <option value="{{ $div->id }}"
                                        {{ old('divisi_id', $employee->divisi_id) == $div->id ? 'selected' : '' }}>
                                        {{ $div->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="jabatan_id" class="block text-sm font-medium text-zinc-900">Jabatan</label>
                            <select name="jabatan_id" id="jabatan_id"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                <option value="">Pilih Jabatan</option>
                                @foreach ($positions as $pos)
                                    <option value="{{ $pos->id }}"
                                        {{ old('jabatan_id', $employee->jabatan_id) == $pos->id ? 'selected' : '' }}>
                                        {{ $pos->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="kantor_id" class="block text-sm font-medium text-zinc-900">Kantor</label>
                            <select name="kantor_id" id="kantor_id"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                <option value="">Pilih Kantor</option>
                                @foreach ($offices as $office)
                                    <option value="{{ $office->id }}"
                                        {{ old('kantor_id', $employee->kantor_id) == $office->id ? 'selected' : '' }}>
                                        {{ $office->office_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tanggal_masuk" class="block text-sm font-medium text-zinc-900">Tanggal
                                Masuk</label>
                            <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                                value="{{ old('tanggal_masuk', $employee->tanggal_masuk) }}" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('tanggal_masuk') border-red-500 @enderror">
                            @error('tanggal_masuk')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="shift_kerja_id" class="block text-sm font-medium text-zinc-900">Shift
                                Kerja</label>
                            <select name="shift_kerja_id" id="shift_kerja_id"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                <option value="">Pilih Shift (Opsional)</option>
                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}"
                                        {{ old('shift_kerja_id', $employee->shift_kerja_id) == $shift->id ? 'selected' : '' }}>
                                        {{ $shift->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="sisa_cuti" class="block text-sm font-medium text-zinc-900">Jatah Cuti
                                Tahunan</label>
                            <input type="number" name="sisa_cuti" id="sisa_cuti"
                                value="{{ old('sisa_cuti', $employee->sisa_cuti) }}" required min="0"
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
                            <input type="text" name="no_hp" id="no_hp"
                                value="{{ old('no_hp', $employee->no_hp) }}"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                        </div>
                        <div>
                            <label for="email_pribadi" class="block text-sm font-medium text-zinc-900">Email
                                Pribadi</label>
                            <input type="email" name="email_pribadi" id="email_pribadi"
                                value="{{ old('email_pribadi', $employee->email_pribadi) }}"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                        </div>
                        <div class="md:col-span-2">
                            <label for="alamat_ktp" class="block text-sm font-medium text-zinc-900">Alamat KTP</label>
                            <textarea name="alamat_ktp" id="alamat_ktp" rows="3"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">{{ old('alamat_ktp', $employee->alamat_ktp) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label for="alamat_domisili" class="block text-sm font-medium text-zinc-900">Alamat
                                Domisili</label>
                            <textarea name="alamat_domisili" id="alamat_domisili" rows="3"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">{{ old('alamat_domisili', $employee->alamat_domisili) }}</textarea>
                        </div>
                    </div>
                </div>

                <hr class="border-zinc-200">

                <!-- Foto -->
                <div>
                    <h3 class="text-lg font-medium leading-6 text-zinc-900 mb-4">Foto Profil</h3>
                    <div class="flex items-center gap-4">
                        <div class="h-16 w-16 overflow-hidden rounded-full bg-zinc-100 border border-zinc-200">
                            @if ($employee->foto)
                                <img src="{{ asset('storage/' . $employee->foto) }}" alt="Current Photo"
                                    class="h-full w-full object-cover">
                            @else
                                <i data-lucide="user" class="h-full w-full p-3 text-zinc-300 block"></i>
                            @endif
                        </div>
                        <div class="flex-1 max-w-sm">
                            <label for="foto" class="block text-sm font-medium text-zinc-900">Upload Foto Baru</label>
                            <input type="file" name="foto" id="foto" accept="image/*"
                                class="mt-1 block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-zinc-50 file:text-zinc-700 hover:file:bg-zinc-100">
                            <p class="mt-1 text-xs text-zinc-500">JPG, PNG atau GIF (Max 2MB).</p>
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
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
