@extends('layouts.employee')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900">Riwayat Offboarding</h1>
                <p class="text-sm text-zinc-500 mt-1">Daftar pengajuan pengunduran diri / pensiun Anda.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('employee.offboardings.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-red-700 transition">
                    <i data-lucide="door-open" class="h-4 w-4"></i> Ajukan Resign / Pensiun
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 text-sm text-green-800 bg-green-50 rounded-xl font-medium">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 text-sm text-red-800 bg-red-50 rounded-xl font-medium">
                {{ session('error') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($offboardings as $item)
                <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-sm hover:shadow-md transition">
                    <div class="flex flex-col md:flex-row justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-zinc-100 text-zinc-600 rounded-full flex items-center justify-center">
                                <i data-lucide="clipboard-list" class="h-6 w-6"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-lg font-bold text-zinc-900">{{ $item->tipe_offboarding }}</h3>
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-bold rounded-full uppercase
                                    {{ $item->status == 'Completed'
                                        ? 'bg-green-100 text-green-700'
                                        : ($item->status == 'Rejected'
                                            ? 'bg-red-100 text-red-700'
                                            : ($item->status == 'In Progress'
                                                ? 'bg-blue-100 text-blue-700'
                                                : 'bg-amber-100 text-amber-700')) }}">
                                        {{ $item->status }}
                                    </span>
                                </div>
                                <p class="text-sm text-zinc-500 mt-1">Tanggal Efektif:
                                    {{ \Carbon\Carbon::parse($item->tanggal_efektif)->translatedFormat('d M Y') }}</p>
                                <p class="text-xs text-zinc-400 mt-1">Diajukan pada:
                                    {{ $item->created_at->translatedFormat('d F Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <a href="{{ route('employee.offboardings.show', $item->id) }}"
                                class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-bold text-sm">
                                Lihat Progress Checklist <i data-lucide="arrow-right" class="h-4 w-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="py-12 px-6 flex flex-col items-center bg-white rounded-xl border border-zinc-200 border-dashed text-zinc-500 text-center">
                    <i data-lucide="shield-question" class="h-12 w-12 text-zinc-300 mb-3"></i>
                    <h3 class="text-lg font-bold text-zinc-800">Tidak ada pengajuan Offboarding</h3>
                    <p class="text-sm max-w-sm mt-2">Anda belum memiliki riwayat pengajuan resgin, pensiun, maupun
                        offboarding lainnya.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
