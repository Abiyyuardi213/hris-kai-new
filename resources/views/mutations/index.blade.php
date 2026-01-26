@extends('layouts.app')
@section('title', 'Mutasi')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Riwayat Mutasi</h2>
            <a href="{{ route('mutations.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors shadow-sm">
                <i data-lucide="arrow-right-left" class="h-4 w-4"></i>
                Proses Mutasi
            </a>
        </div>

        <!-- Content -->
        <div class="space-y-4">
            <!-- Search -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-zinc-200">
                <form action="{{ route('mutations.index') }}" method="GET"
                    class="flex w-full md:max-w-md items-center gap-2">
                    <div class="relative flex-1">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pegawai..."
                            class="flex h-10 w-full rounded-lg border border-zinc-300 pl-10 pr-3 py-2 text-sm placeholder:text-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                    </div>
                    <button type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors">
                        Cari
                    </button>
                    @if (request('search'))
                        <a href="{{ route('mutations.index') }}"
                            class="inline-flex h-10 items-center justify-center rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 transition-colors">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table -->
            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-zinc-50/50 text-zinc-500 border-b border-zinc-200">
                            <tr>
                                <th class="px-6 py-4 font-medium">Pegawai</th>
                                <th class="px-6 py-4 font-medium">Jenis</th>
                                <th class="px-6 py-4 font-medium">Dari</th>
                                <th class="px-6 py-4 font-medium">Ke</th>
                                <th class="px-6 py-4 font-medium">Tanggal Efektif</th>
                                <th class="px-6 py-4 font-medium text-right w-[100px]">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse ($mutations as $mutation)
                                <tr class="group hover:bg-zinc-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-zinc-900">{{ $mutation->employee->nama_lengkap }}</div>
                                        <div class="text-xs text-zinc-500">{{ $mutation->employee->nip }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-blue-50 text-blue-700 uppercase ring-1 ring-inset ring-blue-700/10">
                                            {{ $mutation->type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <div class="text-zinc-500">Div: <span
                                                class="text-zinc-900">{{ $mutation->fromDivision->name ?? '-' }}</span>
                                        </div>
                                        <div class="text-zinc-500">Pos: <span
                                                class="text-zinc-900">{{ $mutation->fromPosition->name ?? '-' }}</span>
                                        </div>
                                        <div class="text-zinc-500">Off: <span
                                                class="text-zinc-900">{{ $mutation->fromOffice->office_name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <div class="text-zinc-500">Div: <span
                                                class="text-zinc-900">{{ $mutation->toDivision->name ?? '-' }}</span></div>
                                        <div class="text-zinc-500">Pos: <span
                                                class="text-zinc-900">{{ $mutation->toPosition->name ?? '-' }}</span></div>
                                        <div class="text-zinc-500">Off: <span
                                                class="text-zinc-900">{{ $mutation->toOffice->office_name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-zinc-600">
                                        {{ \Carbon\Carbon::parse($mutation->mutation_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('mutations.show', $mutation->id) }}"
                                            class="text-zinc-400 hover:text-zinc-900">
                                            <i data-lucide="file-text" class="h-4 w-4"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-zinc-500">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <i data-lucide="history" class="h-8 w-8 text-zinc-300"></i>
                                            <div class="text-center">
                                                <p class="font-medium text-zinc-900">Belum ada riwayat mutasi</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($mutations->hasPages())
                    <div class="p-4 border-t border-zinc-200 bg-zinc-50/50">
                        {{ $mutations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
