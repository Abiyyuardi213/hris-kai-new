@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900">Manajemen Reimbursement</h1>
                <p class="text-sm text-zinc-500 mt-1">Kelola dan verifikasi klaim reimbursement pegawai.</p>
            </div>
            <div class="flex gap-2">
                <!-- Bisa ditambah button export / dsb -->
            </div>
        </div>

        <!-- Alert -->
        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 flex items-center gap-2">
                <i data-lucide="check-circle" class="h-4 w-4"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-zinc-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-zinc-500 bg-zinc-50/50 border-b border-zinc-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold">Pegawai</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Tipe Klaim</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Tanggal</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-right">Nominal (Rp)</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">Status</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse ($reimbursements as $item)
                            <tr class="hover:bg-zinc-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                            {{ substr($item->pegawai->nama_lengkap ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-zinc-900">{{ $item->pegawai->nama_lengkap ?? '-' }}
                                            </div>
                                            <div class="text-xs text-zinc-500">{{ $item->pegawai->nip ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-zinc-700">{{ $item->tipe_reimbursement }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-zinc-500">
                                    {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-zinc-900">
                                    {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium rounded-full
                                    {{ $item->status == 'Approved'
                                        ? 'bg-green-100 text-green-700'
                                        : ($item->status == 'Rejected'
                                            ? 'bg-red-100 text-red-700'
                                            : 'bg-amber-100 text-amber-700') }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.reimbursements.show', $item->id) }}"
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                            title="Detail">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>
                                        <form action="{{ route('admin.reimbursements.destroy', $item->id) }}"
                                            method="POST" class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Delete">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-zinc-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-lucide="inbox" class="h-8 w-8 text-zinc-300 mb-2"></i>
                                        <p>Belum ada data pengajuan reimbursement.</p>
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
