@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900">Manajemen Offboarding / Pensiun</h1>
                <p class="text-sm text-zinc-500 mt-1">Sistem kontrol Offboarding, Resign (Pengunduran Diri), PHK/Demosi, dan
                    Exit Clearance.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.offboardings.create') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-zinc-900 px-4 text-sm font-bold text-white transition-all hover:bg-zinc-800">
                    <i data-lucide="user-minus" class="h-4 w-4"></i> Buat Data PHK/Pemecatan
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-xl bg-green-50 font-medium">
                <i data-lucide="check-circle" class="h-5 w-5 inline-block mr-1 -mt-1"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50/50 text-zinc-500 font-bold border-b border-zinc-200 text-xs">
                        <tr>
                            <th class="px-6 py-4">Data Pegawai</th>
                            <th class="px-6 py-4">Tipe Offboarding</th>
                            <th class="px-6 py-4">Status & Clearance</th>
                            <th class="px-6 py-4 text-center">Masuk Tgl</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse ($offboardings as $data)
                            <tr class="hover:bg-zinc-50 transistion">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-zinc-900">
                                        {{ $data->pegawai->nama_lengkap ?? 'Pegawai tidak ditemukan' }}</div>
                                    <div class="text-xs text-zinc-500 uppercase">{{ $data->pegawai->nip ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="font-bold text-zinc-700 bg-zinc-100 px-2 py-1 rounded-lg text-xs">{{ $data->tipe_offboarding }}</span>
                                    <div class="text-xs text-zinc-400 mt-1">Efektif:
                                        {{ \Carbon\Carbon::parse($data->tanggal_efektif)->translatedFormat('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 text-xs font-bold uppercase tracking-wider rounded-xl mb-1 inline-block
                                    {{ $data->status == 'Completed'
                                        ? 'bg-green-100 text-green-700'
                                        : ($data->status == 'Rejected'
                                            ? 'bg-red-100 text-red-700'
                                            : ($data->status == 'In Progress'
                                                ? 'bg-blue-100 text-blue-700'
                                                : 'bg-amber-100 text-amber-700')) }}">
                                        {{ $data->status }}
                                    </span>
                                    <!-- Tanda Clearance Indicator -->
                                    <div class="flex gap-1 mt-1">
                                        <span title="ID Card"
                                            class="w-5 h-5 rounded flex items-center justify-center text-[10px] {{ $data->clearance_id_card ? 'bg-green-100 text-green-600' : 'bg-zinc-100 text-zinc-400' }}"><i
                                                data-lucide="id-card" class="h-3 w-3"></i></span>
                                        <span title="Aset/Laptop"
                                            class="w-5 h-5 rounded flex items-center justify-center text-[10px] {{ $data->clearance_laptop ? 'bg-green-100 text-green-600' : 'bg-zinc-100 text-zinc-400' }}"><i
                                                data-lucide="laptop" class="h-3 w-3"></i></span>
                                        <span title="Dokumen"
                                            class="w-5 h-5 rounded flex items-center justify-center text-[10px] {{ $data->clearance_dokumen ? 'bg-green-100 text-green-600' : 'bg-zinc-100 text-zinc-400' }}"><i
                                                data-lucide="file-check-2" class="h-3 w-3"></i></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-zinc-500">
                                    {{ $data->created_at->translatedFormat('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.offboardings.show', $data->id) }}"
                                            class="p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition"
                                            title="Tinjau & Proses">
                                            <i data-lucide="check-square" class="h-4 w-4"></i>
                                        </a>
                                        <form action="{{ route('admin.offboardings.destroy', $data->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus permanent riwayat ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center border-dashed border-t border-zinc-200">
                                    <div class="flex flex-col items-center">
                                        <i data-lucide="package-open" class="h-10 w-10 text-zinc-300"></i>
                                        <p class="text-zinc-500 font-medium text-sm mt-3">Tidak ada data offboarding yang
                                            aktif saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
