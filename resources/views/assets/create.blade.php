@extends('layouts.app')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Tambah Aset</h2>
            <a href="{{ route('assets.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="rounded-xl border bg-white shadow-sm p-6 max-w-2xl">
            <form action="{{ route('assets.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="code" class="block text-sm font-medium text-zinc-900">Kode Aset</label>
                            <input type="text" name="code" id="code" value="{{ old('code') }}" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('code') border-red-500 @enderror"
                                placeholder="Contoh: AST-001">
                            @error('code')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-zinc-900">Nama Aset</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('name') border-red-500 @enderror"
                                placeholder="Contoh: Laptop Dell Latitude">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="category" class="block text-sm font-medium text-zinc-900">Kategori</label>
                            <input type="text" name="category" id="category" value="{{ old('category') }}"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('category') border-red-500 @enderror"
                                placeholder="Contoh: Elektronik">
                            @error('category')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="serial_number" class="block text-sm font-medium text-zinc-900">Serial Number</label>
                            <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('serial_number') border-red-500 @enderror"
                                placeholder="Contoh: CN-0X123">
                            @error('serial_number')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="purchase_date" class="block text-sm font-medium text-zinc-900">Tanggal
                                Pembelian</label>
                            <input type="date" name="purchase_date" id="purchase_date"
                                value="{{ old('purchase_date') }}"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('purchase_date') border-red-500 @enderror">
                            @error('purchase_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="condition" class="block text-sm font-medium text-zinc-900">Kondisi</label>
                            <select name="condition" id="condition" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('condition') border-red-500 @enderror">
                                <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Baik</option>
                                <option value="repair" {{ old('condition') == 'repair' ? 'selected' : '' }}>Perbaikan
                                </option>
                                <option value="broken" {{ old('condition') == 'broken' ? 'selected' : '' }}>Rusak</option>
                                <option value="lost" {{ old('condition') == 'lost' ? 'selected' : '' }}>Hilang</option>
                            </select>
                            @error('condition')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="office_id" class="block text-sm font-medium text-zinc-900">Unit Kerja
                                (Kantor)</label>
                            <select name="office_id" id="office_id"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('office_id') border-red-500 @enderror">
                                <option value="">Pilih Kantor</option>
                                @foreach ($offices as $off)
                                    <option value="{{ $off->id }}"
                                        {{ old('office_id') == $off->id ? 'selected' : '' }}>
                                        {{ $off->office_code }} | {{ $off->office_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('office_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="division_id" class="block text-sm font-medium text-zinc-900">Divisi</label>
                            <select name="division_id" id="division_id"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('division_id') border-red-500 @enderror">
                                <option value="">Pilih Divisi</option>
                                @foreach ($divisions as $div)
                                    <option value="{{ $div->id }}"
                                        {{ old('division_id') == $div->id ? 'selected' : '' }}>
                                        {{ $div->code }} | {{ $div->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('division_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-zinc-900">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('description') border-red-500 @enderror"
                            placeholder="Deskripsi detail aset...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <a href="{{ route('assets.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2">
                        Simpan Aset
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
