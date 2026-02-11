@extends('layouts.app')

@section('title', 'Kedisiplinan & SP')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Sanksi & Kedisiplinan</h2>
                <p class="text-sm text-zinc-500">Kelola data pelanggaran dan surat peringatan (SP) pegawai.</p>
            </div>
            <div>
                <a href="{{ route('admin.sanctions.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 transition-all">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Buat SP Baru
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-xl border border-zinc-200 shadow-sm">
            <form action="{{ route('admin.sanctions.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama pegawai atau NIP..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-zinc-200 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900">
                    </div>
                </div>
                <div class="w-full md:w-48">
                    <select name="type" onchange="this.form.submit()"
                        class="w-full px-4 py-2 rounded-lg border border-zinc-200 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900">
                        <option value="">Semua Tipe</option>
                        <option value="Verbal" {{ request('type') == 'Verbal' ? 'selected' : '' }}>Teguran Lisan</option>
                        <option value="SP1" {{ request('type') == 'SP1' ? 'selected' : '' }}>SP 1</option>
                        <option value="SP2" {{ request('type') == 'SP2' ? 'selected' : '' }}>SP 2</option>
                        <option value="SP3" {{ request('type') == 'SP3' ? 'selected' : '' }}>SP 3</option>
                        <option value="Termination" {{ request('type') == 'Termination' ? 'selected' : '' }}>PHK</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50 text-zinc-500 font-medium border-b border-zinc-100">
                        <tr>
                            <th class="px-6 py-4">Pegawai</th>
                            <th class="px-6 py-4">Tipe Sanksi</th>
                            <th class="px-6 py-4">Tanggal Berlaku</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($sanctions as $sanction)
                            <tr class="hover:bg-zinc-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-10 w-10 rounded-full bg-zinc-100 flex items-center justify-center overflow-hidden shrink-0">
                                            @if ($sanction->employee->foto)
                                                <img src="{{ asset('storage/' . $sanction->employee->foto) }}"
                                                    class="h-full w-full object-cover">
                                            @else
                                                <span
                                                    class="font-bold text-xs text-zinc-400">{{ substr($sanction->employee->nama_lengkap, 0, 2) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium text-zinc-900">{{ $sanction->employee->nama_lengkap }}
                                            </p>
                                            <p class="text-xs text-zinc-500">{{ $sanction->employee->nip }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $sanction->type === 'Termination'
                                            ? 'bg-red-100 text-red-800'
                                            : ($sanction->type === 'SP3'
                                                ? 'bg-orange-100 text-orange-800'
                                                : ($sanction->type === 'SP2'
                                                    ? 'bg-yellow-100 text-yellow-800'
                                                    : 'bg-blue-100 text-blue-800')) }}">
                                        {{ $sanction->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-zinc-900">{{ \Carbon\Carbon::parse($sanction->start_date)->format('d M Y') }}</span>
                                        @if ($sanction->end_date)
                                            <span class="text-xs text-zinc-500">s/d
                                                {{ \Carbon\Carbon::parse($sanction->end_date)->format('d M Y') }}</span>
                                        @else
                                            <span class="text-xs text-zinc-500">Selamanya</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($sanction->status === 'Active')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @elseif($sanction->status === 'Expired')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-600 border border-zinc-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-zinc-400"></span>
                                            Berakhir
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                            Dicabut
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.sanctions.print', $sanction) }}" target="_blank"
                                            class="p-2 text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 rounded-lg transition-colors"
                                            title="Cetak SP">
                                            <i data-lucide="printer" class="h-4 w-4"></i>
                                        </a>
                                        <a href="{{ route('admin.sanctions.show', $sanction) }}"
                                            class="p-2 text-zinc-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Detail">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>
                                        <form action="{{ route('admin.sanctions.destroy', $sanction) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data sanksi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-zinc-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-zinc-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="h-12 w-12 rounded-full bg-zinc-100 flex items-center justify-center mb-4">
                                            <i data-lucide="shield-check" class="h-6 w-6 text-zinc-400"></i>
                                        </div>
                                        <p class="font-medium">Tidak ada data sanksi ditemukan</p>
                                        <p class="text-sm mt-1">Belum ada pegawai yang diberikan sanksi.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($sanctions->hasPages())
                <div class="p-4 border-t border-zinc-100">
                    {{ $sanctions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
