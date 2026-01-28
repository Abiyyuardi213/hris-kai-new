@extends('layouts.app')
@section('title', 'Manajemen Perjalanan Dinas')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Perjalanan Dinas</h2>
                <p class="text-zinc-500 text-sm">Kelola penugasan dan pengajuan perjalanan dinas pegawai.</p>
            </div>
            <a href="{{ route('admin.perjalanan_dinas.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-zinc-800 transition-all active:scale-95">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Buat Penugasan
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-6">
            <form action="{{ route('admin.perjalanan_dinas.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Cari Data</label>
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="No. Surat, Tujuan, atau Nama Pegawai..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all appearance-none bg-no-repeat bg-[right_1rem_center] bg-[length:1em_1em]"
                        style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%239ca3af%22 stroke-width=%222%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M19 9l-7 7-7-7%22 /%3E%3C/svg%3E')">
                        <option value="">Semua Status</option>
                        @foreach (['Pengajuan', 'Disetujui', 'Ditolak', 'Sedang Berjalan', 'Selesai', 'Dibatalkan'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 bg-zinc-900 text-white text-sm font-bold py-2 rounded-lg hover:bg-zinc-800 transition-all">Filter</button>
                    <a href="{{ route('admin.perjalanan_dinas.index') }}"
                        class="px-3 py-2 bg-zinc-100 text-zinc-600 rounded-lg hover:bg-zinc-200 transition-all">
                        <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead
                        class="bg-zinc-50/50 border-b border-zinc-100 text-zinc-400 uppercase text-[11px] font-bold tracking-widest">
                        <tr>
                            <th class="px-6 py-4">No. Surat / Tujuan</th>
                            <th class="px-6 py-4">Pemohon</th>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Peserta</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($trips as $trip)
                            <tr class="hover:bg-zinc-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-zinc-900 truncate max-w-[200px]">
                                        {{ $trip->no_surat_tugas ?? 'MENUNGGU NOMOR' }}</div>
                                    <div class="text-[11px] text-zinc-500 font-medium truncate max-w-[200px]">📍
                                        {{ $trip->tujuan }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-zinc-900">{{ $trip->pemohon->nama_lengkap }}</div>
                                    <div class="text-[10px] text-zinc-400 font-bold uppercase tracking-tighter">NIP:
                                        {{ $trip->pemohon->nip }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-zinc-900 font-medium">{{ $trip->tanggal_mulai->format('d M') }} -
                                        {{ $trip->tanggal_selesai->format('d M Y') }}</div>
                                    <div class="text-[10px] text-zinc-400 font-bold uppercase">
                                        {{ $trip->tanggal_mulai->diffInDays($trip->tanggal_selesai) + 1 }} HARI</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex -space-x-2">
                                        @foreach ($trip->pegawaiPeserta->take(3) as $peserta)
                                            <div class="h-7 w-7 rounded-full bg-zinc-100 border-2 border-white flex items-center justify-center overflow-hidden"
                                                title="{{ $peserta->nama_lengkap }}">
                                                @if ($peserta->foto)
                                                    <img src="{{ asset('storage/' . $peserta->foto) }}"
                                                        class="h-full w-full object-cover">
                                                @else
                                                    <span
                                                        class="text-[9px] font-bold text-zinc-400">{{ substr($peserta->nama_lengkap, 0, 1) }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                        @if ($trip->pegawaiPeserta->count() > 3)
                                            <div
                                                class="h-7 w-7 rounded-full bg-zinc-900 border-2 border-white flex items-center justify-center text-[9px] font-bold text-white">
                                                +{{ $trip->pegawaiPeserta->count() - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $colors = [
                                            'Pengajuan' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            'Disetujui' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'Ditolak' => 'bg-red-50 text-red-600 border-red-100',
                                            'Sedang Berjalan' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'Selesai' => 'bg-zinc-50 text-zinc-600 border-zinc-100',
                                            'Dibatalkan' => 'bg-zinc-50 text-zinc-400 border-zinc-100',
                                        ];
                                        $class = $colors[$trip->status] ?? 'bg-zinc-50 text-zinc-600 border-zinc-100';
                                    @endphp
                                    <span
                                        class="px-2.5 py-1 rounded-lg border text-[10px] font-bold uppercase tracking-wider {{ $class }}">
                                        {{ $trip->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.perjalanan_dinas.show', $trip->id) }}"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 transition-colors shadow-sm">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>
                                        <form action="{{ route('admin.perjalanan_dinas.destroy', $trip->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-100 bg-white text-red-500 hover:bg-red-50 transition-colors shadow-sm">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-zinc-500">
                                    <i data-lucide="inbox" class="h-12 w-12 mx-auto mb-4 text-zinc-200"></i>
                                    <p>Tidak ada data perjalanan dinas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($trips->hasPages())
                <div class="px-6 py-4 border-t border-zinc-100 bg-zinc-50/50">
                    {{ $trips->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
