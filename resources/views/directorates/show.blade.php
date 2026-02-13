@extends('layouts.app')
@section('title', 'Detail Direktorat')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('directorates.index') }}"
                    class="flex items-center gap-2 text-sm text-zinc-500 hover:text-zinc-900 transition-colors mb-2">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                    Kembali ke Daftar Direktorat
                </a>
                <h2 class="text-3xl font-bold tracking-tight">{{ $directorate->name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span
                        class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-1 text-xs font-medium text-zinc-600 ring-1 ring-inset ring-zinc-500/10">
                        {{ $directorate->code }}
                    </span>
                    <p class="text-zinc-500">{{ $directorate->description ?? 'Tidak ada deskripsi' }}</p>
                </div>
            </div>
            <div>
                <a href="{{ route('directorates.edit', $directorate->id) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors shadow-sm">
                    <i data-lucide="edit-2" class="h-4 w-4"></i>
                    Edit Direktorat
                </a>
            </div>
        </div>

        <!-- Division List -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-zinc-900">Daftar Divisi</h3>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-zinc-50/50 text-zinc-500 border-b border-zinc-200">
                            <tr>
                                <th class="px-6 py-4 font-medium w-[120px]">Kode</th>
                                <th class="px-6 py-4 font-medium">Nama Divisi</th>
                                <th class="px-6 py-4 font-medium text-right w-[120px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse ($directorate->divisions as $division)
                                <tr class="group hover:bg-zinc-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-1 text-xs font-medium text-zinc-600 ring-1 ring-inset ring-zinc-500/10">
                                            {{ $division->code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-zinc-900">{{ $division->name }}</div>
                                        <div class="text-xs text-zinc-500 mt-0.5">{{ $division->description ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('divisions.show', $division->id) }}"
                                                class="p-2 text-zinc-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                title="Lihat Detail">
                                                <i data-lucide="eye" class="h-4 w-4"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-16 text-center text-zinc-500">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <div class="p-4 rounded-full bg-zinc-50 border border-zinc-100">
                                                <i data-lucide="layers" class="h-8 w-8 text-zinc-300"></i>
                                            </div>
                                            <div class="text-center">
                                                <p class="font-medium text-zinc-900">Belum ada divisi</p>
                                                <p class="text-sm mt-1">Direktorat ini belum memiliki divisi yang terdaftar.
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
