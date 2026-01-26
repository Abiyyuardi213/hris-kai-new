@extends('layouts.app')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Edit Kantor</h2>
            <a href="{{ route('offices.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="rounded-xl border bg-white shadow-sm p-6 max-w-2xl">
            <form action="{{ route('offices.update', $office->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="office_code" class="block text-sm font-medium text-zinc-900">Kode Kantor</label>
                            <input type="text" name="office_code" id="office_code"
                                value="{{ old('office_code', $office->office_code) }}" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('office_code') border-red-500 @enderror"
                                placeholder="Contoh: HO-001">
                            @error('office_code')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="office_name" class="block text-sm font-medium text-zinc-900">Nama Kantor</label>
                            <input type="text" name="office_name" id="office_name"
                                value="{{ old('office_name', $office->office_name) }}" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('office_name') border-red-500 @enderror"
                                placeholder="Contoh: Kantor Pusat">
                            @error('office_name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="city_id" class="block text-sm font-medium text-zinc-900">Kota</label>
                        <select name="city_id" id="city_id" required
                            class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('city_id') border-red-500 @enderror">
                            <option value="">Pilih Kota</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}"
                                    {{ old('city_id', $office->city_id) == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }} ({{ $city->province_name }})
                                </option>
                            @endforeach
                        </select>
                        @error('city_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="office_address" class="block text-sm font-medium text-zinc-900">Alamat Lengkap</label>
                        <textarea name="office_address" id="office_address" rows="3" required
                            class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('office_address') border-red-500 @enderror"
                            placeholder="Alamat lengkap kantor...">{{ old('office_address', $office->office_address) }}</textarea>
                        @error('office_address')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-zinc-900">Nomor Telepon</label>
                            <input type="text" name="phone_number" id="phone_number"
                                value="{{ old('phone_number', $office->phone_number) }}"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('phone_number') border-red-500 @enderror"
                                placeholder="Contoh: 021-1234567">
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-zinc-900">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $office->email) }}"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('email') border-red-500 @enderror"
                                placeholder="Contoh: office@company.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <a href="{{ route('offices.index') }}"
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
