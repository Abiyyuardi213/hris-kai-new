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

        <!-- Jabatan List Removed as relation is removed -->
    </div>
@endsection
