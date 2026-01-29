@extends('layouts.employee')
@section('title', $announcement->title)

@section('content')
    <div class="max-w-6xl mx-auto flex flex-col space-y-8 pb-20">
        <!-- Breadcrumb & Action -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('employee.announcements.index') }}"
                    class="h-10 w-10 rounded-xl border border-zinc-200 bg-white flex items-center justify-center text-zinc-500 hover:text-zinc-900 shadow-sm transition-all group">
                    <i data-lucide="arrow-left" class="h-5 w-5 transition-transform group-hover:-translate-x-1"></i>
                </a>
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-zinc-400">
                    <a href="{{ route('employee.announcements.index') }}" class="hover:text-zinc-900 transition-colors">Pusat
                        Informasi</a>
                    <i data-lucide="chevron-right" class="h-3 w-3"></i>
                    <span
                        class="text-zinc-900 underline decoration-blue-500/30 underline-offset-4">{{ $announcement->category }}</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.print()"
                    class="h-10 px-4 rounded-xl border border-zinc-200 bg-white flex items-center justify-center gap-2 text-zinc-500 hover:text-zinc-900 text-xs font-bold transition-all">
                    <i data-lucide="printer" class="h-4 w-4"></i>
                    Cetak
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Main Content -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Cover Image -->
                @if ($announcement->image)
                    <div class="relative h-[450px] w-full rounded-[2.5rem] overflow-hidden shadow-2xl shadow-zinc-200">
                        <img src="{{ asset('storage/' . $announcement->image) }}"
                            class="absolute inset-0 h-full w-full object-cover">
                    </div>
                @endif

                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span
                            class="px-3 py-1 bg-zinc-900 text-white rounded-lg text-[10px] font-black uppercase tracking-widest leading-none">
                            {{ $announcement->category }}
                        </span>
                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest leading-none">
                            Dipublikasikan: {{ $announcement->published_at->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                    <h1 class="text-5xl font-bold tracking-tight text-zinc-900 leading-none">
                        {{ $announcement->title }}
                    </h1>

                    <div class="flex items-center gap-4 py-4 border-y border-zinc-50">
                        <div
                            class="h-12 w-12 rounded-2xl bg-zinc-50 border border-zinc-100 flex items-center justify-center shrink-0">
                            <i data-lucide="user-check" class="h-6 w-6 text-zinc-400"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-zinc-900 uppercase tracking-widest leading-none">
                                {{ $announcement->author->name }}</p>
                            <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mt-1">Administrator
                                Sistem • hrd@kai.id</p>
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="prose prose-zinc prose-lg max-w-none text-zinc-700 leading-relaxed font-medium">
                    {!! $announcement->content !!}
                </div>

                <!-- Attachment -->
                @if ($announcement->file_attachment)
                    <div
                        class="bg-zinc-50 rounded-[2rem] p-8 border border-zinc-100 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div
                                class="h-16 w-16 bg-white rounded-2xl border border-zinc-200 shadow-sm flex items-center justify-center text-zinc-400 shrink-0">
                                <i data-lucide="file-text" class="h-8 w-8"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm font-bold text-zinc-900 uppercase tracking-widest">Dokumen
                                    Lampiran</h4>
                                <p
                                    class="text-xs text-zinc-400 font-bold mt-1 max-w-[200px] truncate uppercase tracking-tighter">
                                    {{ basename($announcement->file_attachment) }}</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $announcement->file_attachment) }}" target="_blank"
                            class="px-8 py-3.5 bg-zinc-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-zinc-200 hover:bg-zinc-800 transition-all active:scale-95 flex items-center gap-2">
                            Unduh Dokumen
                            <i data-lucide="download" class="h-4 w-4"></i>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4 space-y-8 text-white no-print">
                <div class="bg-zinc-900 rounded-[2.5rem] p-8 space-y-6 shadow-2xl shadow-zinc-200">
                    <h3 class="text-lg font-bold uppercase tracking-widest flex items-center gap-2">
                        <i data-lucide="megaphone" class="h-5 w-5 text-zinc-400"></i>
                        Info Terbaru
                    </h3>

                    <div class="space-y-6">
                        @foreach ($recent as $r)
                            <a href="{{ route('employee.announcements.show', $r->id) }}"
                                class="flex gap-4 group cursor-pointer border-b border-white/5 pb-6 last:border-0 last:pb-0">
                                <div
                                    class="h-20 w-20 rounded-2xl bg-white/5 border border-white/10 shrink-0 overflow-hidden group-hover:border-white/30 transition-all">
                                    @if ($r->image)
                                        <img src="{{ asset('storage/' . $r->image) }}"
                                            class="h-full w-full object-cover grayscale group-hover:grayscale-0 transition-all">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center">
                                            <i data-lucide="bookmark" class="h-4 w-4 text-white/20"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-white/40 mb-1">
                                        {{ $r->published_at->format('d M Y') }}</p>
                                    <h4
                                        class="text-xs font-bold uppercase tracking-tight text-white/90 leading-tight group-hover:text-white transition-colors line-clamp-2">
                                        {{ $r->title }}</h4>
                                    <span
                                        class="inline-block mt-2 text-[8px] font-bold uppercase tracking-widest text-zinc-500">Baca
                                        Selengkapnya →</span>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <a href="{{ route('employee.announcements.index') }}"
                        class="block w-full py-4 bg-white/10 rounded-2xl text-center text-[10px] font-black uppercase tracking-widest hover:bg-white/20 transition-all">
                        Lihat Semua Informasi
                    </a>
                </div>

                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-[2.5rem] p-8 space-y-4 shadow-xl shadow-blue-200">
                    <i data-lucide="info" class="h-10 w-10 text-white/30"></i>
                    <h3 class="text-xl font-bold uppercase tracking-widest leading-tight">Butuh Informasi Lebih
                        Lanjut?</h3>
                    <p class="text-xs text-white/70 font-medium leading-relaxed">Hubungi pusat bantuan HRD kami jika
                        ada pertanyaan terkait pengumuman ini.</p>
                    <button
                        class="w-full py-3.5 bg-white text-blue-600 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg transition-all active:scale-95">
                        Hubungi Tim HRD
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                background: white;
            }

            .max-w-6xl {
                max-width: 100%;
                padding: 0;
            }
        }
    </style>
@endsection
