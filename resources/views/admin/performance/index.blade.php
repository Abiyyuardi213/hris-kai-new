@extends('layouts.app')
@section('title', 'Manajemen KPI & Kinerja')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Performance Appraisal</h2>
                <p class="text-zinc-500 text-sm">Kelola penilaian kinerja tahunan pegawai berdasarkan KPI.</p>
            </div>
            <a href="{{ route('admin.performance.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-zinc-800 transition-all active:scale-95">
                <i data-lucide="plus-circle" class="h-4 w-4"></i>
                Mulai Penilaian Baru
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-zinc-200 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-zinc-100 flex items-center justify-center text-zinc-600">
                        <i data-lucide="users" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-zinc-400 uppercase tracking-wider">Total Dinilai</p>
                        <p class="text-2xl font-black text-zinc-900">{{ $appraisals->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-zinc-200 shadow-sm border-l-4 border-l-emerald-500">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i data-lucide="award" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-zinc-400 uppercase tracking-wider">Rata-rata Rating</p>
                        <p class="text-2xl font-black text-emerald-600">A</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-zinc-200 shadow-sm border-l-4 border-l-amber-500">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <i data-lucide="clock" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-zinc-400 uppercase tracking-wider">Status Draft</p>
                        <p class="text-2xl font-black text-amber-600">{{ $appraisals->where('status', 'Draft')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center gap-3">
                <i data-lucide="check-circle" class="h-5 w-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- List Table -->
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-zinc-100 flex flex-col md:flex-row gap-4 items-center justify-between">
                <form action="{{ route('admin.performance.index') }}" method="GET"
                    class="flex items-center gap-2 w-full md:w-auto">
                    <div class="relative flex-1">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Pegawai..."
                            class="pl-10 pr-4 py-2 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 w-full">
                    </div>
                    <select name="tahun"
                        class="px-4 py-2 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 appearance-none bg-white font-bold">
                        <option value="">Semua Tahun</option>
                        @for ($i = date('Y'); $i >= 2024; $i--)
                            <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>
                                {{ $i }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-zinc-100 p-2 rounded-xl hover:bg-zinc-200 transition-all">
                        <i data-lucide="filter" class="h-5 w-5 text-zinc-600"></i>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead
                        class="bg-zinc-50 border-b border-zinc-100 text-zinc-500 font-bold uppercase text-[10px] tracking-widest">
                        <tr>
                            <th class="px-6 py-4">Tahun / Periode</th>
                            <th class="px-6 py-4">Pegawai</th>
                            <th class="px-6 py-4">Skor / Rating</th>
                            <th class="px-6 py-4">Penilai</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($appraisals as $appraisal)
                            <tr class="hover:bg-zinc-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-black text-zinc-900">FY-{{ $appraisal->tahun }}</div>
                                    <div class="text-[10px] text-zinc-500 font-medium">
                                        {{ $appraisal->periode_mulai->format('M Y') }} -
                                        {{ $appraisal->periode_selesai->format('M Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-zinc-900">{{ $appraisal->pegawai->nama_lengkap }}</div>
                                    <div class="text-[10px] text-zinc-400 font-bold tracking-tighter uppercase">NIP:
                                        {{ $appraisal->pegawai->nip }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($appraisal->status == 'Selesai')
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-lg font-black text-zinc-900">{{ number_format($appraisal->total_score, 1) }}</span>
                                            <span
                                                class="px-2 py-0.5 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-black">{{ $appraisal->rating }}</span>
                                        </div>
                                    @else
                                        <span class="text-zinc-400 italic text-xs">Menunggu penilaian</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-medium text-zinc-600">{{ $appraisal->appraiser->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider 
                                        {{ $appraisal->status == 'Selesai' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                                        {{ $appraisal->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($appraisal->status == 'Draft')
                                            <a href="{{ route('admin.performance.edit', $appraisal->id) }}"
                                                class="p-2 bg-zinc-900 text-white rounded-lg hover:bg-zinc-800 transition-all shadow-sm">
                                                <i data-lucide="edit-3" class="h-4 w-4"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('admin.performance.show', $appraisal->id) }}"
                                                class="p-2 bg-white border border-zinc-200 text-zinc-600 rounded-lg hover:bg-zinc-50 transition-all shadow-sm">
                                                <i data-lucide="eye" class="h-4 w-4"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('admin.performance.destroy', $appraisal->id) }}"
                                            method="POST" onsubmit="return confirm('Hapus penilaian ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 bg-white border border-zinc-200 text-red-500 rounded-lg hover:bg-red-50 transition-all shadow-sm">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center text-zinc-500">
                                    <div
                                        class="h-16 w-16 bg-zinc-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <i data-lucide="file-warning" class="h-8 w-8 text-zinc-200"></i>
                                    </div>
                                    <p class="font-bold text-zinc-900">Belum Ada Data Penilaian</p>
                                    <p class="text-xs text-zinc-500 mt-1">Silakan mulai penilaian performa pegawai Anda.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($appraisals->hasPages())
                <div class="p-6 bg-zinc-50 border-t border-zinc-100">
                    {{ $appraisals->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
