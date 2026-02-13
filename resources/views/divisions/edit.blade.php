@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-control {
            border-radius: 0.5rem !important;
            padding: 0.5rem 0.75rem !important;
            border-color: #d4d4d8 !important;
            font-size: 0.875rem !important;
        }

        .ts-wrapper.focus .ts-control {
            box-shadow: 0 0 0 1px #18181b !important;
            border-color: #18181b !important;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Edit Divisi</h2>
            <a href="{{ route('divisions.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="rounded-xl border bg-white shadow-sm p-6 max-w-2xl">
            <form action="{{ route('divisions.update', $division->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="directorate_id" class="block text-sm font-medium text-zinc-900">Direktorat</label>
                        <select name="directorate_id" id="directorate_id" required
                            class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('directorate_id') border-red-500 @enderror">
                            <option value="">Pilih Direktorat</option>
                            @foreach ($directorates as $directorate)
                                <option value="{{ $directorate->id }}"
                                    {{ old('directorate_id', $division->directorate_id) == $directorate->id ? 'selected' : '' }}>
                                    {{ $directorate->code }} | {{ $directorate->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('directorate_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-zinc-900">Nama Divisi</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $division->name) }}"
                            required
                            class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('name') border-red-500 @enderror"
                            placeholder="Contoh: Information Technology" oninput="generateCode()">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-medium text-zinc-900">Kode Divisi (Otomatis)</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $division->code) }}"
                            required readonly
                            class="mt-1 block w-full rounded-lg border border-zinc-100 bg-zinc-50 px-3 py-2 text-sm text-zinc-500 cursor-not-allowed @error('code') border-red-500 @enderror"
                            placeholder="Contoh: IT-DEV">
                        @error('code')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-zinc-900">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('description') border-red-500 @enderror"
                            placeholder="Deskripsi singkat divisi...">{{ old('description', $division->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <a href="{{ route('divisions.index') }}"
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
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new TomSelect('#directorate_id', {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    placeholder: "Cari dan pilih direktorat...",
                    allowEmptyOption: true,
                });
            });

            function generateCode() {
                const name = document.getElementById('name').value;
                // Use the number extracted in controller, treated as string to preserve leading zeros
                const number = '{{ $number }}';

                if (!name) {
                    // If name is empty, we might want to keep the original code or clear it.
                    // But typically if name is empty, validation will fail efficiently.
                    // Let's mimic create behavior but keep number if possible or just clear.
                    // Ideally if name is cleared, code prefix disappears but what about number?
                    // Let's just clear it like in create.
                    document.getElementById('code').value = '';
                    return;
                }

                // Split name into words, remove empty strings
                const words = name.trim().toUpperCase().split(/\s+/);
                let abbreviation = '';

                words.forEach((word) => {
                    if (word.length > 0) {
                        // Logic for "UTM" if word is "UTAMA"
                        if (word === 'UTAMA') {
                            abbreviation += 'UTM-';
                        } else {
                            // Take first 3 letters
                            abbreviation += word.substring(0, 3) + '-';
                        }
                    }
                });

                document.getElementById('code').value = abbreviation + number;
            }
        </script>
    @endpush
@endsection
