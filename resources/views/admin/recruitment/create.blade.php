@extends('layouts.app')
@section('title', 'Buat Lowongan Baru')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.recruitment.index') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <h2 class="text-3xl font-bold tracking-tight">Buat Lowongan Baru</h2>
        </div>

        <form action="{{ route('admin.recruitment.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 rounded-xl border border-zinc-200 bg-white shadow-sm">
                <div class="md:col-span-2 space-y-1">
                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Judul Lowongan</label>
                    <input type="text" name="title" required placeholder="Contoh: Senior Web Developer"
                        class="h-12 w-full rounded-lg border border-zinc-200 px-4 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Posisi / Jabatan</label>
                    <select name="position_id" required
                        class="h-12 w-full rounded-lg border border-zinc-200 px-4 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                        <option value="">Pilih Posisi</option>
                        @foreach ($positions as $pos)
                            <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Jumlah Dibutuhkan</label>
                    <input type="number" name="quantity" value="1" min="1" required
                        class="h-12 w-full rounded-lg border border-zinc-200 px-4 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Status</label>
                    <select name="status" required
                        class="h-12 w-full rounded-lg border border-zinc-200 px-4 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                        <option value="draft">Draft</option>
                        <option value="open">Open / Publish</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Batas Akhir (Deadline)</label>
                    <input type="date" name="deadline"
                        class="h-12 w-full rounded-lg border border-zinc-200 px-4 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                </div>

                <div class="md:col-span-2 space-y-1">
                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Deskripsi Pekerjaan</label>
                    <textarea name="description" rows="4" required
                        class="w-full rounded-lg border border-zinc-200 p-4 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all"></textarea>
                </div>

                <div class="md:col-span-2 space-y-1">
                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Persyaratan</label>
                    <textarea name="requirements" rows="4" required
                        class="w-full rounded-lg border border-zinc-200 p-4 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.recruitment.index') }}"
                    class="h-12 px-6 flex items-center justify-center rounded-lg border border-zinc-200 bg-white text-sm font-bold text-zinc-700 hover:bg-zinc-50 transition-all">Batal</a>
                <button type="submit"
                    class="h-12 px-8 flex items-center justify-center rounded-lg bg-zinc-900 text-sm font-bold text-white hover:bg-zinc-800 transition-all shadow-sm">Simpan Lowongan</button>
            </div>
        </form>
    </div>
@endsection
