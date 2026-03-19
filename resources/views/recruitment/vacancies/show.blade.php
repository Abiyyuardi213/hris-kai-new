@extends('layouts.candidate')
@section('title', $vacancy->judul_lowongan)

@section('content')
    <div class="flex flex-col space-y-8" x-data="{ activeTab: 'description' }">
        <!-- Main Title Area -->
        <div class="bg-white rounded-[24px] border border-zinc-200 p-10 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-64 h-64 bg-zinc-50 rounded-full blur-3xl -mr-32 -mt-32 opacity-20 pointer-events-none group-hover:bg-blue-50 transition-colors"></div>
            
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-8 animate-in slide-in-from-bottom-4 duration-500">
                <div class="space-y-4 max-w-2xl">
                    <h2 class="text-3xl font-black text-zinc-900 leading-tight uppercase tracking-tight italic">{{ $vacancy->judul_lowongan }}</h2>
                    <div class="flex items-center gap-4 flex-wrap text-sm font-bold text-zinc-400 uppercase tracking-[0.1em]">
                        <span class="flex items-center gap-2"><i data-lucide="calendar" class="h-4 w-4"></i> {{ \Carbon\Carbon::parse($vacancy->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($vacancy->end_date)->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="shrink-0">
                    <button class="h-14 px-10 bg-zinc-900 text-white text-sm font-black uppercase tracking-[0.2em] rounded-2xl shadow-2xl shadow-zinc-200 hover:bg-zinc-800 transition-all hover:scale-105 active:scale-95">
                        Lamar Sekarang
                    </button>
                </div>
            </div>
        </div>

        <!-- Tab Area -->
        <div class="flex flex-col space-y-6">
            <div class="flex flex-wrap items-center gap-4 border-b-2 border-zinc-100 p-1 pb-1">
                <button @click="activeTab = 'description'" :class="activeTab === 'description' ? 'border-zinc-900 text-zinc-900' : 'border-transparent text-zinc-400 hover:text-zinc-600 hover:border-zinc-200'" class="px-6 py-4 text-xs font-black uppercase tracking-[0.15em] border-b-4 transition-all -mb-[4px] relative group italic">
                    <div class="flex items-center gap-3">
                        <i data-lucide="file-text" class="h-4 w-4"></i>
                        <span>Deskripsi</span>
                    </div>
                </button>
                <button @click="activeTab = 'requirements'" :class="activeTab === 'requirements' ? 'border-zinc-900 text-zinc-900' : 'border-transparent text-zinc-400 hover:text-zinc-600 hover:border-zinc-200'" class="px-6 py-4 text-xs font-black uppercase tracking-[0.15em] border-b-4 transition-all -mb-[4px] relative group italic">
                    <div class="flex items-center gap-3">
                        <i data-lucide="shield-check" class="h-4 w-4"></i>
                        <span>Persyaratan</span>
                    </div>
                </button>
                <button @click="activeTab = 'formations'" :class="activeTab === 'formations' ? 'border-zinc-900 text-zinc-900' : 'border-transparent text-zinc-400 hover:text-zinc-600 hover:border-zinc-200'" class="px-6 py-4 text-xs font-black uppercase tracking-[0.15em] border-b-4 transition-all -mb-[4px] relative group italic">
                    <div class="flex items-center gap-3">
                        <i data-lucide="layers" class="h-4 w-4"></i>
                        <span>Formasi</span>
                    </div>
                </button>
            </div>

            <!-- Content Area -->
            <div class="animate-in fade-in duration-500">
                <div x-show="activeTab === 'description'">
                    <div class="bg-white rounded-[24px] border border-zinc-100 p-10 shadow-sm leading-relaxed">
                        <h4 class="text-sm font-black uppercase tracking-[0.2em] text-zinc-400 border-b pb-6 mb-8 italic flex items-center gap-3">
                            <i data-lucide="info" class="h-5 w-5 opacity-40"></i>
                            Informasi Detail Deskripsi Lowongan
                        </h4>
                        <div class="prose prose-zinc max-w-none text-zinc-600 text-[15px] space-y-4 ql-editor border-none p-0 leading-[1.8]">
                        {!! $vacancy->detail->description ?? 'Deskripsi belum tersedia.' !!}
                    </div>
                    </div>
                </div>

                <div x-show="activeTab === 'requirements'" style="display: none;">
                    <div class="bg-white rounded-[24px] border border-zinc-100 p-10 shadow-sm leading-relaxed">
                        <h4 class="text-sm font-black uppercase tracking-[0.2em] text-zinc-400 border-b pb-6 mb-8 italic flex items-center gap-3">
                            <i data-lucide="list" class="h-5 w-5 opacity-40"></i>
                            Persyaratan Kualifikasi Kandidat
                        </h4>
                        <div class="prose prose-zinc max-w-none text-zinc-600 text-[15px] space-y-4 ql-editor border-none p-0 leading-[1.8]">
                        {!! $vacancy->detail->requirements ?? 'Persyaratan belum tersedia.' !!}
                    </div>
                    </div>
                </div>

                <div x-show="activeTab === 'formations'" style="display: none;">
                    <div class="bg-white rounded-[24px] border border-zinc-100 p-1 shadow-sm overflow-hidden">
                        <div class="p-8 pb-4">
                            <h4 class="text-sm font-black uppercase tracking-[0.2em] text-zinc-400 italic flex items-center gap-3">
                                <i data-lucide="map-pin" class="h-5 w-5 opacity-40"></i>
                                Penempatan Formasi Yang Tersedia
                            </h4>
                        </div>
                        <div class="w-full overflow-x-auto p-4 pt-0">
                            <table class="w-full text-xs text-left border-separate border-spacing-y-2">
                                <thead>
                                    <tr class="bg-zinc-900 text-white uppercase tracking-tighter">
                                        <th class="px-6 py-5 font-black text-[10px] tracking-widest first:rounded-l-xl last:rounded-r-xl">Formasi</th>
                                        <th class="px-6 py-5 font-black text-[10px] tracking-widest first:rounded-l-xl last:rounded-r-xl">Pendidikan</th>
                                        <th class="px-6 py-5 font-black text-[10px] tracking-widest first:rounded-l-xl last:rounded-r-xl">Jurusan</th>
                                        <th class="px-6 py-5 font-black text-[10px] tracking-widest first:rounded-l-xl last:rounded-r-xl">Kelamin</th>
                                        <th class="px-6 py-5 font-black text-[10px] tracking-widest first:rounded-l-xl last:rounded-r-xl">Syarat Dokumen</th>
                                        <th class="px-6 py-5 font-black text-[10px] tracking-widest first:rounded-l-xl last:rounded-r-xl text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($vacancy->formations as $formation)
                                        <tr class="bg-zinc-50 border border-zinc-100 hover:bg-zinc-100/50 transition-all transition-colors group">
                                            <td class="px-6 py-6 font-black text-zinc-900 uppercase leading-snug first:rounded-l-xl last:rounded-r-xl">{{ $formation->formation_name }}</td>
                                            <td class="px-6 py-6 text-zinc-600 uppercase font-black first:rounded-l-xl last:rounded-r-xl">{{ $formation->education }}</td>
                                            <td class="px-6 py-6 text-zinc-500 font-bold first:rounded-l-xl last:rounded-r-xl">
                                                <ul class="space-y-1">
                                                    @foreach(explode("\n", $formation->major) as $m)
                                                        @if(trim($m))
                                                            <li class="flex items-start gap-2"><div class="w-1 h-1 bg-zinc-300 rounded-full mt-1.5 shrink-0"></div> {{ trim($m) }}</li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="px-6 py-6 text-zinc-600 font-black uppercase first:rounded-l-xl last:rounded-r-xl">{{ $formation->gender }}</td>
                                            <td class="px-6 py-6 text-zinc-500 font-bold first:rounded-l-xl last:rounded-r-xl">
                                                <ul class="space-y-1">
                                                    @foreach(explode("\n", $formation->document_requirements) as $doc)
                                                        @if(trim($doc))
                                                            <li class="flex items-start gap-2"><div class="w-1 h-1 bg-zinc-300 rounded-full mt-1.5 shrink-0"></div> {{ trim($doc) }}</li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="px-6 py-6 text-center first:rounded-l-xl last:rounded-r-xl">
                                                <span class="text-[9px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100 hover:bg-emerald-100 cursor-pointer transition-colors transition-all">
                                                    Buka Segera!
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-20 text-center text-zinc-400 italic font-bold uppercase tracking-[0.2em] italic">Belum ada formasi yang ditambahkan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush
@endsection
