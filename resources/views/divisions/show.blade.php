@extends('layouts.app')
@section('title', 'Detail Divisi')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('divisions.index') }}"
                    class="flex items-center gap-2 text-sm text-zinc-500 hover:text-zinc-900 transition-colors mb-2">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                    Kembali ke Daftar Divisi
                </a>
                <h2 class="text-3xl font-bold tracking-tight">{{ $division->name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span
                        class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-1 text-xs font-medium text-zinc-600 ring-1 ring-inset ring-zinc-500/10">
                        {{ $division->code }}
                    </span>
                    <p class="text-zinc-500">{{ $division->description ?? 'Tidak ada deskripsi' }}</p>
                </div>
            </div>
            <div>
                <a href="{{ route('divisions.edit', $division->id) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors shadow-sm">
                    <i data-lucide="edit-2" class="h-4 w-4"></i>
                    Edit Divisi
                </a>
            </div>
        </div>

        <!-- Jabatan List -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-zinc-900">Daftar Jabatan</h3>
                {{-- Optional: Add button to add position to this division --}}
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-zinc-50/50 text-zinc-500 border-b border-zinc-200">
                            <tr>
                                <th class="px-6 py-4 font-medium w-[120px]">Kode</th>
                                <th class="px-6 py-4 font-medium">Nama Jabatan</th>
                                <th class="px-6 py-4 font-medium">Gaji Per Hari</th>
                                <th class="px-6 py-4 font-medium">Tunjangan</th>
                                <th class="px-6 py-4 font-medium text-right">Jumlah Pegawai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse ($division->positions as $position)
                                <tr class="group hover:bg-zinc-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-1 text-xs font-medium text-zinc-600 ring-1 ring-inset ring-zinc-500/10">
                                            {{ $position->code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-zinc-900">{{ $position->name }}</div>
                                        <div class="text-xs text-zinc-500 mt-0.5">{{ $position->description ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        Rp {{ number_format($position->gaji_per_hari, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        Rp {{ number_format($position->tunjangan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                                            <i data-lucide="users" class="h-3 w-3"></i>
                                            {{ $position->employees_count ?? 0 }} Pegawai
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-zinc-500">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <div class="p-4 rounded-full bg-zinc-50 border border-zinc-100">
                                                <i data-lucide="briefcase" class="h-8 w-8 text-zinc-300"></i>
                                            </div>
                                            <div class="text-center">
                                                <p class="font-medium text-zinc-900">Belum ada jabatan</p>
                                                <p class="text-sm mt-1">Divisi ini belum memiliki jabatan yang terdaftar.
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
