@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .flatpickr-input {
            background-color: white !important;
        }
    </style>
@endpush

@section('title', 'Edit Agenda')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Edit Agenda</h2>
            <a href="{{ route('admin.events.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="rounded-xl border bg-white shadow-sm p-6 w-full">
            <form action="{{ route('admin.events.update', $event->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-zinc-900">Judul Agenda</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}"
                            required
                            class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('title') border-red-500 @enderror"
                            placeholder="Contoh: Rapat Evaluasi Bulanan">
                        @error('title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-zinc-900">Mulai</label>
                            <input type="datetime-local" name="start_date" id="start_date"
                                value="{{ old('start_date', $event->start_date) }}" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('start_date') border-red-500 @enderror">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-zinc-900">Selesai</label>
                            <input type="datetime-local" name="end_date" id="end_date"
                                value="{{ old('end_date', $event->end_date) }}" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('end_date') border-red-500 @enderror">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-zinc-900">Tipe Agenda</label>
                            <select name="type" id="type" required
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                <option value="event" {{ old('type', $event->type) == 'event' ? 'selected' : '' }}>Event /
                                    Acara</option>
                                <option value="meeting" {{ old('type', $event->type) == 'meeting' ? 'selected' : '' }}>
                                    Meeting / Rapat</option>
                                <option value="holiday" {{ old('type', $event->type) == 'holiday' ? 'selected' : '' }}>Hari
                                    Libur</option>
                                <option value="other" {{ old('type', $event->type) == 'other' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-zinc-900">Lokasi</label>
                            <input type="text" name="location" id="location"
                                value="{{ old('location', $event->location) }}"
                                class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900"
                                placeholder="Contoh: Ruang Rapat Utama / Google Meet">
                            @error('location')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-zinc-900">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900"
                            placeholder="Detail agenda...">{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="hidden" name="is_public" value="0">
                        <input type="checkbox" name="is_public" id="is_public" value="1"
                            {{ old('is_public', $event->is_public) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900">
                        <label for="is_public" class="text-sm font-medium text-zinc-900">Tampilkan ke Publik
                            (Pegawai)</label>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <a href="{{ route('admin.events.index') }}"
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("input[type=datetime-local]", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true
        });
    </script>
@endpush
