@extends('layouts.app')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Proses Mutasi Pegawai</h2>
            <a href="{{ route('mutations.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="rounded-xl border bg-white shadow-sm p-6 mb-8">
            <form action="{{ route('mutations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Column 1 -->
                    <div class="space-y-6">
                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-zinc-900">Pilih Pegawai</label>
                            <select name="employee_id" id="employee_id" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                <option value="">Cari Nama Pegawai...</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->nama_lengkap }} - {{ $employee->nip }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-zinc-500">Pilih pegawai yang akan dimutasi.</p>
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-zinc-900">Jenis Perubahan</label>
                            <select name="type" id="type" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                <option value="mutasi">Mutasi (Pindah Lokasi/Tugas)</option>
                                <option value="promosi">Promosi (Naik Jabatan)</option>
                                <option value="demosi">Demosi (Turun Jabatan)</option>
                                <option value="rotasi">Rotasi (Pergantian Posisi Setara)</option>
                            </select>
                        </div>

                        <div>
                            <label for="mutation_date" class="block text-sm font-medium text-zinc-900">Tanggal
                                Efektif</label>
                            <input type="date" name="mutation_date" id="mutation_date" value="{{ old('mutation_date') }}"
                                required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                        </div>

                        <div>
                            <label for="reason" class="block text-sm font-medium text-zinc-900">Alasan Mutasi</label>
                            <textarea name="reason" id="reason" rows="4" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900"
                                placeholder="Jelaskan alasan perubahan ini...">{{ old('reason') }}</textarea>
                        </div>
                    </div>

                    <!-- Column 2: Target -->
                    <div class="space-y-6 bg-zinc-50 p-6 rounded-lg border border-zinc-100">
                        <h3 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider mb-2">Posisi / Lokasi Baru
                        </h3>
                        <div>
                            <label for="to_division_id" class="block text-sm font-medium text-zinc-900">Divisi
                                Tujuan</label>
                            <select name="to_division_id" id="to_division_id"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                <option value="">(Tetap / Tidak Berubah)</option>
                                @foreach ($divisions as $div)
                                    <option value="{{ $div->id }}"
                                        {{ old('to_division_id') == $div->id ? 'selected' : '' }}>{{ $div->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="to_position_id" class="block text-sm font-medium text-zinc-900">Jabatan
                                Tujuan</label>
                            <select name="to_position_id" id="to_position_id"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                <option value="">(Tetap / Tidak Berubah)</option>
                                @foreach ($positions as $pos)
                                    <option value="{{ $pos->id }}"
                                        {{ old('to_position_id') == $pos->id ? 'selected' : '' }}>{{ $pos->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="to_office_id" class="block text-sm font-medium text-zinc-900">Kantor Tujuan</label>
                            <select name="to_office_id" id="to_office_id"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                <option value="">(Tetap / Tidak Berubah)</option>
                                @foreach ($offices as $office)
                                    <option value="{{ $office->id }}"
                                        {{ old('to_office_id') == $office->id ? 'selected' : '' }}>
                                        {{ $office->office_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="file_sk" class="block text-sm font-medium text-zinc-900">Upload SK Mutasi
                                (Opsional)</label>
                            <input type="file" name="file_sk" id="file_sk" accept=".pdf,.png,.jpg,.jpeg"
                                class="mt-1 block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-zinc-200 file:text-zinc-700 hover:file:bg-zinc-300">
                            <p class="mt-1 text-xs text-zinc-500">PDF, JPG atau PNG (Max 2MB).</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <a href="{{ route('mutations.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2">
                        Simpan & Proses Mutasi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
