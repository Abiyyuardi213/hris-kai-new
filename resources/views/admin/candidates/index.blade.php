@extends('layouts.app')
@section('title', 'Manajemen Akun Pelamar')

@section('content')
    <div class="flex flex-col space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Daftar Akun Pelamar</h2>
                <p class="text-sm text-zinc-500 mt-1">Daftar seluruh kandidat yang telah mendaftarkan akun di sistem rekrutmen.</p>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
            <form action="{{ route('admin.candidates.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[300px] relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama, email, atau NIK..." 
                        class="h-10 w-full rounded-lg border border-zinc-300 pl-10 pr-3 text-xs font-bold focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                </div>
                <button type="submit" class="h-10 px-6 bg-zinc-900 text-white text-xs font-black uppercase tracking-widest rounded-lg hover:bg-zinc-800 transition-all italic">Cari Pelamar</button>
                @if(request('search'))
                    <a href="{{ route('admin.candidates.index') }}" class="text-xs font-bold text-zinc-400 hover:text-zinc-900 underline italic uppercase">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50/50 text-zinc-500 border-b uppercase text-[10px] font-black tracking-widest">
                        <tr>
                            <th class="px-6 py-4 font-medium">Informasi Pelamar</th>
                            <th class="px-6 py-4 font-medium">NIK / Identitas</th>
                            <th class="px-6 py-4 font-medium">Pendidikan Terakhir</th>
                            <th class="px-6 py-4 font-medium">Tanggal Daftar</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 italic">
                        @forelse ($candidates as $candidate)
                            <tr class="hover:bg-zinc-50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-zinc-100 overflow-hidden shrink-0 border border-zinc-200">
                                            @if($candidate->photo)
                                                <img src="{{ asset('storage/' . $candidate->photo) }}" alt="Foto" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-zinc-400">
                                                    <i data-lucide="user" class="h-5 w-5"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-zinc-900 group-hover:text-blue-600 transition-colors uppercase leading-none">{{ $candidate->name }}</div>
                                            <div class="text-[10px] text-zinc-400 font-bold tracking-tighter mt-1">{{ $candidate->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-zinc-600 font-mono">
                                    {{ $candidate->identity_number }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded bg-zinc-100 text-[10px] font-black text-zinc-500 uppercase">{{ $candidate->last_education ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-zinc-400">
                                    {{ $candidate->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.candidates.show', $candidate->id) }}" class="p-2 rounded-lg bg-zinc-50 border border-zinc-200 text-zinc-400 hover:text-zinc-900 hover:border-zinc-300 transition-all" title="Lihat Detail Lengkap">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>
                                        <form action="{{ route('admin.candidates.destroy', $candidate->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pelamar ini? Semua data lamaran juga akan terhapus.')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-zinc-50 border border-zinc-200 text-zinc-400 hover:text-red-600 hover:border-red-200 transition-all">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-zinc-400 italic">
                                    <i data-lucide="user-x" class="h-10 w-10 mx-auto mb-3 opacity-20"></i>
                                    <p class="text-xs font-bold uppercase tracking-widest">Tidak ada data pelamar ditemukan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($candidates->hasPages())
                <div class="p-4 border-t bg-zinc-50/50">
                    {{ $candidates->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
