@extends('layouts.app')
@section('title', 'Manajemen Pengumuman')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Pengumuman & Broadcast</h2>
                <p class="text-zinc-500 text-sm">Kelola informasi internal dan pengumuman untuk seluruh pegawai.</p>
            </div>
            <a href="{{ route('admin.announcements.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-zinc-800 transition-all active:scale-95">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Buat Pengumuman Baru
            </a>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white rounded-3xl border border-zinc-200 p-6 shadow-sm">
            <form action="{{ route('admin.announcements.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul pengumuman..."
                        class="w-full pl-11 pr-4 py-2.5 rounded-2xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 transition-all">
                </div>
                <div class="flex gap-4">
                    <select name="category"
                        class="px-4 py-2.5 rounded-2xl border border-zinc-200 text-sm font-medium focus:ring-2 focus:ring-zinc-900 transition-all min-w-[150px]">
                        <option value="">Semua Kategori</option>
                        <option value="Umum" {{ request('category') == 'Umum' ? 'selected' : '' }}>Umum</option>
                        <option value="Penting" {{ request('category') == 'Penting' ? 'selected' : '' }}>Penting</option>
                        <option value="HR" {{ request('category') == 'HR' ? 'selected' : '' }}>HR & Kebijakan</option>
                        <option value="Acara" {{ request('category') == 'Acara' ? 'selected' : '' }}>Acara</option>
                    </select>
                    <button type="submit"
                        class="px-6 py-2.5 bg-zinc-100 text-zinc-900 rounded-2xl text-sm font-bold hover:bg-zinc-200 transition-all">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-zinc-50/50 border-b border-zinc-200">
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-zinc-400">Pengumuman
                            </th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-zinc-400">Kategori
                            </th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-zinc-400">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-zinc-400">Publish Pada
                            </th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-wider text-zinc-400 text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($announcements as $item)
                            <tr class="hover:bg-zinc-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-12 w-12 rounded-xl bg-zinc-100 flex items-center justify-center shrink-0 overflow-hidden border border-zinc-200">
                                            @if ($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}"
                                                    class="h-full w-full object-cover">
                                            @else
                                                <i data-lucide="megaphone" class="h-5 w-5 text-zinc-400"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p
                                                class="text-sm font-bold text-zinc-900 truncate leading-tight tracking-tight">
                                                {{ $item->title }}</p>
                                            <p class="text-[10px] text-zinc-400 font-bold mt-1">Oleh:
                                                {{ $item->author->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border 
                                    {{ $item->category == 'Penting'
                                        ? 'bg-red-50 text-red-600 border-red-100'
                                        : ($item->category == 'HR'
                                            ? 'bg-blue-50 text-blue-600 border-blue-100'
                                            : ($item->category == 'Acara'
                                                ? 'bg-amber-50 text-amber-600 border-amber-100'
                                                : 'bg-zinc-50 text-zinc-600 border-zinc-100')) }}">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5">
                                        <div
                                            class="h-1.5 w-1.5 rounded-full {{ $item->is_active ? 'bg-emerald-500' : 'bg-zinc-300' }}">
                                        </div>
                                        <span
                                            class="text-[10px] font-bold {{ $item->is_active ? 'text-zinc-900' : 'text-zinc-400' }}">
                                            {{ $item->is_active ? 'Aktif' : 'Draft/Nonaktif' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-[11px] font-bold text-zinc-900 leading-none">
                                        {{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}</p>
                                    <p class="text-[10px] text-zinc-400 mt-1">
                                        {{ $item->published_at ? $item->published_at->format('H:i') : '' }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div
                                        class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.announcements.show', $item->id) }}"
                                            class="p-2 rounded-lg bg-white border border-zinc-200 text-zinc-600 hover:text-emerald-600 hover:border-emerald-100 transition-all">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>
                                        <a href="{{ route('admin.announcements.edit', $item->id) }}"
                                            class="p-2 rounded-lg bg-white border border-zinc-200 text-zinc-600 hover:text-blue-600 hover:border-blue-100 transition-all">
                                            <i data-lucide="edit-2" class="h-4 w-4"></i>
                                        </a>
                                        <form action="{{ route('admin.announcements.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus pengumuman ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 rounded-lg bg-white border border-zinc-200 text-zinc-600 hover:text-red-600 hover:border-red-100 transition-all">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div
                                        class="h-16 w-16 bg-zinc-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-zinc-200">
                                        <i data-lucide="megaphone-off" class="h-8 w-8"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-zinc-900">Belum Ada Pengumuman</h3>
                                    <p class="text-xs text-zinc-500 mt-1">Buat pengumuman pertama Anda untuk dibagikan ke
                                        seluruh pegawai.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($announcements->hasPages())
                <div class="px-6 py-4 border-t border-zinc-100">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
