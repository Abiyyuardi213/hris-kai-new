@extends('layouts.app')
@section('title', 'Edit Shift')

@section('content')
    <div class="flex flex-col space-y-6 max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Edit Shift Kerja</h2>
                <p class="text-zinc-500 text-sm">Sesuaikan pengaturan waktu untuk {{ $shift->name }}.</p>
            </div>
            <a href="{{ route('shifts.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
            <form action="{{ route('shifts.update', $shift->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-6">
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-zinc-900">Nama Shift</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $shift->name) }}" required
                            placeholder="Pagi / Siang / Malam"
                            class="block w-full rounded-lg border {{ $errors->has('name') ? 'border-red-500' : 'border-zinc-300' }} px-3 py-2.5 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all">
                        @error('name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="start_time" class="block text-sm font-medium text-zinc-900">Jam Masuk</label>
                            <div class="relative">
                                <i data-lucide="clock"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                                <input type="time" name="start_time" id="start_time"
                                    value="{{ old('start_time', substr($shift->start_time, 0, 5)) }}" required
                                    class="block w-full rounded-lg border {{ $errors->has('start_time') ? 'border-red-500' : 'border-zinc-300' }} pl-10 pr-3 py-2.5 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all">
                            </div>
                            @error('start_time')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="end_time" class="block text-sm font-medium text-zinc-900">Jam Pulang</label>
                            <div class="relative">
                                <i data-lucide="clock"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                                <input type="time" name="end_time" id="end_time"
                                    value="{{ old('end_time', substr($shift->end_time, 0, 5)) }}" required
                                    class="block w-full rounded-lg border {{ $errors->has('end_time') ? 'border-red-500' : 'border-zinc-300' }} pl-10 pr-3 py-2.5 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all">
                            </div>
                            @error('end_time')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-zinc-50 px-8 py-4 flex justify-end gap-3 border-t border-zinc-100">
                    <a href="{{ route('shifts.index') }}"
                        class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="rounded-lg bg-zinc-900 px-6 py-2 text-sm font-bold text-white shadow-sm hover:bg-zinc-800 transition-all active:scale-[0.98]">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
