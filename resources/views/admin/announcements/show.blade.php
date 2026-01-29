@extends('layouts.app')
@section('title', 'Detail Pengumuman')

@section('content')
    <div class="max-w-5xl mx-auto flex flex-col space-y-6">
        <!-- Header & Actions -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.announcements.index') }}"
                    class="h-10 w-10 rounded-xl border border-zinc-200 bg-white flex items-center justify-center text-zinc-500 hover:text-zinc-900 shadow-sm transition-all group">
                    <i data-lucide="arrow-left" class="h-5 w-5 transition-transform group-hover:-translate-x-1"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-zinc-900">Preview Pengumuman</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span
                            class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-widest border 
                        {{ $announcement->category == 'Penting'
                            ? 'bg-red-50 text-red-600 border-red-100'
                            : ($announcement->category == 'HR'
                                ? 'bg-blue-50 text-blue-600 border-blue-100'
                                : ($announcement->category == 'Acara'
                                    ? 'bg-amber-50 text-amber-600 border-amber-100'
                                    : 'bg-zinc-50 text-zinc-600 border-zinc-100')) }}">
                            {{ $announcement->category }}
                        </span>
                        <div class="flex items-center gap-1.5">
                            <div
                                class="h-1.5 w-1.5 rounded-full {{ $announcement->is_active ? 'bg-emerald-500' : 'bg-zinc-300' }}">
                            </div>
                            <span
                                class="text-[10px] font-bold {{ $announcement->is_active ? 'text-zinc-900' : 'text-zinc-400' }}">
                                {{ $announcement->is_active ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.announcements.edit', $announcement->id) }}"
                    class="px-4 py-2 bg-white border border-zinc-200 text-zinc-700 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-zinc-50 transition-all flex items-center gap-2">
                    <i data-lucide="edit-2" class="h-4 w-4"></i>
                    Edit
                </a>
                <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" method="POST"
                    onsubmit="return confirm('Hapus pengumuman ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-50 border border-red-100 text-red-600 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-red-100 transition-all flex items-center gap-2">
                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="bg-white rounded-[2.5rem] border border-zinc-200 shadow-xl overflow-hidden">
            <!-- Cover Image -->
            @if ($announcement->image)
                <div class="relative h-[300px] w-full bg-zinc-100">
                    <img src="{{ asset('storage/' . $announcement->image) }}"
                        class="absolute inset-0 h-full w-full object-cover">
                </div>
            @endif

            <div class="p-10 space-y-8">
                <!-- Title & Meta -->
                <div class="space-y-4 text-center max-w-3xl mx-auto">
                    <h1 class="text-4xl font-bold tracking-tight text-zinc-900 leading-none">
                        {{ $announcement->title }}
                    </h1>
                    <div
                        class="flex items-center justify-center gap-6 text-zinc-400 text-[10px] font-bold uppercase tracking-widest">
                        <span>{{ $announcement->published_at ? $announcement->published_at->format('d M Y, H:i') : 'Belum dipublish' }}</span>
                        <span>&bull;</span>
                        <span>Oleh: {{ $announcement->author->name }}</span>
                    </div>
                </div>

                <hr class="border-zinc-100">

                <!-- Content -->
                <div class="prose prose-zinc prose-lg max-w-none text-zinc-700 leading-relaxed mx-auto">
                    {!! $announcement->content !!}
                </div>

                <!-- Attachment -->
                @if ($announcement->file_attachment)
                    <div class="mt-8 max-w-2xl mx-auto">
                        <div
                            class="p-4 bg-zinc-50 rounded-2xl border border-zinc-100 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-10 w-10 bg-white rounded-xl border border-zinc-200 flex items-center justify-center text-zinc-400">
                                    <i data-lucide="paperclip" class="h-5 w-5"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-black text-zinc-900 uppercase tracking-widest">Lampiran</p>
                                    <p class="text-[10px] text-zinc-400 truncate">
                                        {{ basename($announcement->file_attachment) }}</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $announcement->file_attachment) }}" target="_blank"
                                class="px-4 py-2 bg-zinc-900 text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-zinc-800 transition-all">
                                Download
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .italic-p p {
            font-style: italic;
        }
    </style>
@endsection
