@extends('layouts.app')
@section('title', 'Data Terkait Divisi')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Data Terkait Divisi</h2>
                <p class="text-zinc-500 mt-1">
                    Divisi <span class="font-bold text-zinc-900">{{ $division->name }}</span> ({{ $division->code }})
                </p>
            </div>
            <a href="{{ route('divisions.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali
            </a>
        </div>

        <!-- Alert -->
        <div class="rounded-lg bg-red-50 p-4 border border-red-100">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i data-lucide="alert-circle" class="h-5 w-5 text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Tidak dapat menghapus divisi</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>
                            Divisi ini tidak dapat dihapus karena masih digunakan dalam data berikut.
                            Silakan pindahkan atau hapus data terkait terlebih dahulu sebelum menghapus divisi ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dependencies List -->
        <div class="space-y-6">
            <!-- Employees -->
            @if ($division->employees->count() > 0)
                <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-zinc-200 bg-zinc-50/50 flex justify-between items-center">
                        <h3 class="font-medium text-zinc-900">Pegawai Aktif</h3>
                        <span
                            class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-800">
                            {{ $division->employees->count() }} Data
                        </span>
                    </div>
                    <div>
                        <table class="w-full text-sm text-left">
                            <thead class="bg-zinc-50 text-zinc-500 border-b border-zinc-200">
                                <tr>
                                    <th class="px-6 py-3 font-medium">NIP</th>
                                    <th class="px-6 py-3 font-medium">Nama</th>
                                    <th class="px-6 py-3 font-medium">Jabatan</th>
                                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @foreach ($division->employees as $employee)
                                    <tr class="hover:bg-zinc-50/50 transition-colors">
                                        <td class="px-6 py-3 font-medium text-zinc-900">{{ $employee->nip }}</td>
                                        <td class="px-6 py-3">{{ $employee->nama_lengkap }}</td>
                                        <td class="px-6 py-3 text-zinc-600">{{ $employee->jabatan->name ?? '-' }}</td>
                                        <td class="px-6 py-3 text-right">
                                            <a href="{{ route('employees.edit', $employee->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 font-medium hover:underline">
                                                Edit Pegawai
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Mutations From (Asal Divisi) -->
            @if ($division->mutationsFrom->count() > 0)
                <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-zinc-200 bg-zinc-50/50 flex justify-between items-center">
                        <h3 class="font-medium text-zinc-900">Riwayat Mutasi (Sebagai Asal)</h3>
                        <span
                            class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-800">
                            {{ $division->mutationsFrom->count() }} Data
                        </span>
                    </div>
                    <div>
                        <table class="w-full text-sm text-left">
                            <thead class="bg-zinc-50 text-zinc-500 border-b border-zinc-200">
                                <tr>
                                    <th class="px-6 py-3 font-medium">Kode Mutasi</th>
                                    <th class="px-6 py-3 font-medium">Pegawai</th>
                                    <th class="px-6 py-3 font-medium">Tujuan</th>
                                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @foreach ($division->mutationsFrom as $mutation)
                                    <tr class="hover:bg-zinc-50/50 transition-colors">
                                        <td class="px-6 py-3 font-medium text-zinc-900">{{ $mutation->mutation_code }}</td>
                                        <td class="px-6 py-3">{{ $mutation->employee->nama_lengkap ?? '-' }}</td>
                                        <td class="px-6 py-3 text-zinc-600">{{ $mutation->toDivision->name ?? '-' }}</td>
                                        <td class="px-6 py-3 text-right">
                                            {{-- Assuming mutations.show exists --}}
                                            @if (Route::has('mutations.show'))
                                                <a href="{{ route('mutations.show', $mutation->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 font-medium hover:underline">
                                                    Detail
                                                </a>
                                            @else
                                                <span class="text-zinc-400">View Only</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Mutations To (Tujuan Divisi) -->
            @if ($division->mutationsTo->count() > 0)
                <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-zinc-200 bg-zinc-50/50 flex justify-between items-center">
                        <h3 class="font-medium text-zinc-900">Riwayat Mutasi (Sebagai Tujuan)</h3>
                        <span
                            class="inline-flex items-center rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-800">
                            {{ $division->mutationsTo->count() }} Data
                        </span>
                    </div>
                    <div>
                        <table class="w-full text-sm text-left">
                            <thead class="bg-zinc-50 text-zinc-500 border-b border-zinc-200">
                                <tr>
                                    <th class="px-6 py-3 font-medium">Kode Mutasi</th>
                                    <th class="px-6 py-3 font-medium">Pegawai</th>
                                    <th class="px-6 py-3 font-medium">Asal</th>
                                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @foreach ($division->mutationsTo as $mutation)
                                    <tr class="hover:bg-zinc-50/50 transition-colors">
                                        <td class="px-6 py-3 font-medium text-zinc-900">{{ $mutation->mutation_code }}</td>
                                        <td class="px-6 py-3">{{ $mutation->employee->nama_lengkap ?? '-' }}</td>
                                        <td class="px-6 py-3 text-zinc-600">{{ $mutation->fromDivision->name ?? '-' }}</td>
                                        <td class="px-6 py-3 text-right">
                                            @if (Route::has('mutations.show'))
                                                <a href="{{ route('mutations.show', $mutation->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 font-medium hover:underline">
                                                    Detail
                                                </a>
                                            @else
                                                <span class="text-zinc-400">View Only</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if ($division->employees->isEmpty() && $division->mutationsFrom->isEmpty() && $division->mutationsTo->isEmpty())
                <div class="p-8 text-center text-zinc-500 bg-white rounded-xl border border-dashed border-zinc-300">
                    <div class="flex flex-col items-center justify-center space-y-3">
                        <i data-lucide="info" class="h-8 w-8 text-zinc-300"></i>
                        <p>Tidak ditemukan data terkait langsung (Pegawai/Mutasi) yang mencegah penghapusan.</p>
                        <p class="text-sm">Silakan cek relasi lain yang mungkin tidak ditampilkan di sini.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
