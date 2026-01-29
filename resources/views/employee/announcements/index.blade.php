@extends('layouts.employee')
@section('title', 'Pusat Informasi Internal')

@section('content')
    <div class="flex flex-col space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="space-y-1">
                <h2 class="text-4xl font-bold tracking-tight text-zinc-900 leading-none">Pusat Informasi
                </h2>
                <p class="text-zinc-500 text-sm font-medium">Update terbaru dan kebijakan internal PT KAI.</p>
            </div>
            <div class="flex items-center gap-2">
                <form action="{{ route('employee.announcements.index') }}" method="GET" class="flex items-center gap-2">
                    <select name="category" onchange="this.form.submit()"
                        class="px-4 py-2 bg-white rounded-xl border border-zinc-200 text-xs font-bold focus:ring-2 focus:ring-zinc-900 transition-all cursor-pointer">
                        <option value="">Semua Informasi</option>
                        <option value="Umum" {{ request('category') == 'Umum' ? 'selected' : '' }}>Umum</option>
                        <option value="Penting" {{ request('category') == 'Penting' ? 'selected' : '' }}>Penting</option>
                        <option value="HR" {{ request('category') == 'HR' ? 'selected' : '' }}>HR & Kebijakan</option>
                        <option value="Acara" {{ request('category') == 'Acara' ? 'selected' : '' }}>Acara</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Featured / Latest -->
        @php $latest = $announcements->first(); @endphp
        @if ($latest && $announcements->currentPage() == 1)
            <a href="{{ route('employee.announcements.show', $latest->id) }}"
                class="group relative w-full h-[400px] rounded-[2.5rem] overflow-hidden shadow-2xl shadow-zinc-200 block transition-transform active:scale-[0.99]">
                @if ($latest->image)
                    <img src="{{ asset('storage/' . $latest->image) }}"
                        class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-zinc-800 to-zinc-900"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                <div class="absolute bottom-0 left-0 w-full p-10 space-y-4">
                    <div class="flex items-center gap-3">
                        <span
                            class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-lg text-[10px] font-black uppercase tracking-widest text-white border border-white/20">
                            {{ $latest->category }}
                        </span>
                        <span
                            class="text-[10px] font-bold text-white/60 tracking-widest uppercase">{{ $latest->published_at->diffForHumans() }}</span>
                    </div>
                    <h3 class="text-4xl font-bold text-white leading-none tracking-tight max-w-3xl group-hover:underline">
                        {{ $latest->title }}</h3>
                    <p class="text-zinc-300 text-sm max-w-2xl font-medium leading-relaxed line-clamp-2">
                        "{{ $latest->short_content }}"</p>
                    <div class="pt-4 flex items-center gap-4">
                        <div
                            class="h-10 w-10 rounded-full border-2 border-white/20 overflow-hidden shrink-0 bg-white/10 flex items-center justify-center">
                            <i data-lucide="user" class="h-5 w-5 text-white/50"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-white uppercase tracking-widest leading-none">
                                {{ $latest->author->name }}</p>
                            <p class="text-[10px] text-white/50 font-bold uppercase tracking-widest mt-1">Administrator HRD
                            </p>
                        </div>
                    </div>
                </div>

                @if ($latest->category == 'Penting')
                    <div
                        class="absolute top-8 right-8 h-12 w-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-xl animate-pulse ring-4 ring-red-600/20">
                        <i data-lucide="alert-circle" class="h-6 w-6"></i>
                    </div>
                @endif
            </a>
        @endif

        <!-- Grid Feed -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($announcements as $index => $item)
                @if ($index == 0 && $announcements->currentPage() == 1)
                    @continue
                @endif
                <div
                    class="group bg-white rounded-[2rem] border border-zinc-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col h-[480px]">
                    <div class="relative h-48 shrink-0 overflow-hidden">
                        @if ($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="h-full w-full bg-zinc-50 flex items-center justify-center">
                                <i data-lucide="image" class="h-8 w-8 text-zinc-100"></i>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4">
                            <span
                                class="px-2.5 py-1 bg-white/90 backdrop-blur-sm rounded-lg text-[9px] font-black uppercase tracking-widest text-zinc-900 border border-zinc-200">
                                {{ $item->category }}
                            </span>
                        </div>
                    </div>

                    <div class="p-8 flex-1 flex flex-col justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-3">
                                {{ $item->published_at->format('d M Y') }}</p>
                            <h4
                                class="text-xl font-bold text-zinc-900 leading-tight tracking-tight mb-4 group-hover:text-blue-600 transition-colors line-clamp-3">
                                {{ $item->title }}</h4>
                            <p class="text-xs text-zinc-500 font-medium leading-relaxed line-clamp-3">
                                "{{ $item->short_content }}"</p>
                        </div>

                        <div class="pt-6 border-t border-zinc-50 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-lg bg-zinc-900 flex items-center justify-center text-white">
                                    <span class="text-[10px] font-black">{{ substr($item->author->name, 0, 1) }}</span>
                                </div>
                                <span
                                    class="text-[10px] font-bold text-zinc-900 uppercase tracking-tighter">{{ $item->author->name }}</span>
                            </div>
                            <a href="{{ route('employee.announcements.show', $item->id) }}"
                                class="h-8 w-8 rounded-full bg-zinc-50 flex items-center justify-center text-zinc-400 hover:bg-zinc-900 hover:text-white transition-all">
                                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                @if (!($latest && $announcements->currentPage() == 1))
                    <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border border-zinc-100">
                        <div
                            class="h-20 w-20 bg-zinc-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-zinc-200">
                            <i data-lucide="megaphone" class="h-10 w-10"></i>
                        </div>
                        <h3 class="text-xl font-bold text-zinc-900 uppercase tracking-tight">Tidak Ada Informasi
                        </h3>
                        <p class="text-sm text-zinc-500 mt-2 max-w-xs mx-auto font-medium">Belum ada pengumuman
                            terbaru untuk kategori ini.</p>
                    </div>
                @endif
            @endforelse
        </div>

        @if ($announcements->hasPages())
            <div class="pt-10 flex justify-center">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>
@endsection
