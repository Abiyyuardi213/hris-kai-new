@extends('layouts.app')
@section('title', 'Mulai Penilaian Baru')

@section('content')
    <div class="max-w-3xl mx-auto flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.performance.index') }}"
                class="h-10 w-10 rounded-xl border border-zinc-200 bg-white flex items-center justify-center text-zinc-500 hover:text-zinc-900 transition-all hover:bg-zinc-50 shadow-sm">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Mulai Penilaian</h2>
                <p class="text-zinc-500 text-sm">Pilih pegawai dan periode penilaian sebelum memasukkan skor KPI.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl flex flex-col gap-1">
                @foreach ($errors->all() as $error)
                    <p class="text-xs font-bold">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.performance.store') }}" method="POST">
            @csrf
            <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-zinc-100 bg-zinc-50/50">
                    <h3 class="font-bold text-zinc-900 flex items-center gap-2">
                        <i data-lucide="user-check" class="h-5 w-5 text-zinc-400"></i>
                        Informasi Penilaian
                    </h3>
                </div>
                <div class="p-8 space-y-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Pilih Pegawai</label>
                        <select name="pegawai_id" required
                            class="w-full px-5 py-3 rounded-2xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 transition-all font-bold">
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->nama_lengkap }} ({{ $emp->nip }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Tahun Penilaian</label>
                            <select name="tahun" required
                                class="w-full px-5 py-3 rounded-2xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 transition-all font-bold">
                                @for ($i = date('Y'); $i >= 2024; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-zinc-50">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Periode Mulai</label>
                            <input type="date" name="periode_mulai" value="{{ date('Y-01-01') }}" required
                                class="w-full px-5 py-3 rounded-2xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Periode Selesai</label>
                            <input type="date" name="periode_selesai" value="{{ date('Y-12-31') }}" required
                                class="w-full px-5 py-3 rounded-2xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 transition-all">
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit"
                            class="w-full py-4 rounded-2xl bg-zinc-900 text-white font-bold text-sm shadow-xl hover:bg-zinc-800 transition-all active:scale-95 flex items-center justify-center gap-2">
                            Lanjutkan ke Pengisian Skor KPI
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
