@extends('layouts.app')

@section('title', 'Presensi Pegawai')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Presensi Pegawai</h2>
                <p class="text-zinc-500 text-sm">Pilih pegawai untuk melihat kalender kehadiran.</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-6">
            <form action="{{ route('admin.presensi-pegawai.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Cari Pegawai</label>
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau NIP..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                    </div>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="px-6 bg-zinc-900 text-white text-sm font-bold py-2 rounded-lg hover:bg-zinc-800 transition-all">Cari</button>
                    <a href="{{ route('admin.presensi-pegawai.index') }}"
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
                    <thead class="bg-zinc-50/50 border-b border-zinc-100 text-zinc-400 uppercase text-[11px] font-bold tracking-widest">
                        <tr>
                            <th class="px-6 py-4 w-12">No</th>
                            <th class="px-6 py-4">Pegawai</th>
                            <th class="px-6 py-4">Divisi / Jabatan</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($pegawais as $pegawai)
                            <tr class="hover:bg-zinc-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-zinc-400">
                                    {{ ($pegawais->currentPage() - 1) * $pegawais->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-zinc-100 overflow-hidden border border-zinc-200 shrink-0">
                                            @if ($pegawai->foto)
                                                <img src="{{ asset('storage/' . $pegawai->foto) }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center text-zinc-400">
                                                    <i data-lucide="user" class="h-5 w-5"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-zinc-900">{{ $pegawai->nama_lengkap }}</div>
                                            <div class="text-[11px] text-zinc-500 font-medium">NIP: {{ $pegawai->nip }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-zinc-900">{{ $pegawai->divisi->name ?? '-' }}</div>
                                    <div class="text-[11px] text-zinc-500">{{ $pegawai->jabatan->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.presensi-pegawai.show', $pegawai->id) }}"
                                        class="inline-flex h-8 px-3 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-600 hover:bg-zinc-900 hover:text-white transition-colors text-xs font-bold gap-2">
                                        <i data-lucide="calendar" class="h-3 w-3"></i> Lihat Kalender
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-zinc-500">
                                    <i data-lucide="users" class="h-12 w-12 mx-auto mb-4 text-zinc-200"></i>
                                    <p>Tidak ada data pegawai yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($pegawais->hasPages())
                <div class="px-6 py-4 border-t border-zinc-100 bg-zinc-50/50">
                    {{ $pegawais->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
