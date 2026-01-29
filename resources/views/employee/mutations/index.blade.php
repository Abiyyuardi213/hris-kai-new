@extends('layouts.employee')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900">Riwayat Mutasi</h1>
                <p class="text-sm text-zinc-500 mt-1">Daftar riwayat perubahan jabatan, divisi, dan kantor Anda.</p>
            </div>

            <div class="px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-lg text-sm text-indigo-700">
                <span class="font-medium">Total:</span> {{ $mutations->total() }} Riwayat
            </div>
        </div>

        <!-- List Data -->
        @if ($mutations->count() > 0)
            <div class="space-y-4">
                @foreach ($mutations as $mutation)
                    <div
                        class="bg-white rounded-xl border border-zinc-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row gap-6">
                                <!-- Status Icon -->
                                <div class="flex-shrink-0">
                                    <div
                                        class="h-12 w-12 rounded-full flex items-center justify-center 
                                        {{ $mutation->type == 'promosi'
                                            ? 'bg-green-100 text-green-600'
                                            : ($mutation->type == 'demosi'
                                                ? 'bg-amber-100 text-amber-600'
                                                : 'bg-blue-100 text-blue-600') }}">
                                        @if ($mutation->type == 'promosi')
                                            <i data-lucide="trending-up" class="h-6 w-6"></i>
                                        @elseif($mutation->type == 'demosi')
                                            <i data-lucide="trending-down" class="h-6 w-6"></i>
                                        @else
                                            <i data-lucide="refresh-cw" class="h-6 w-6"></i>
                                        @endif
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-grow space-y-4">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-lg font-bold text-zinc-900 capitalize">
                                                    {{ $mutation->type }}</h3>
                                                <span
                                                    class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-zinc-100 text-zinc-600">
                                                    {{ \Carbon\Carbon::parse($mutation->mutation_date)->translatedFormat('d F Y') }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-zinc-500 mt-1">No. SK: Perubahan Struktur Organisasi</p>
                                        </div>
                                        <a href="{{ route('employee.mutations.show', $mutation->id) }}"
                                            class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                                            Lihat Detail
                                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                                        </a>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-zinc-100">
                                        <!-- From -->
                                        <div class="space-y-3">
                                            <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Sebelumnya
                                            </p>
                                            <div class="space-y-2">
                                                <div class="flex items-start gap-2 text-sm text-zinc-600">
                                                    <i data-lucide="briefcase" class="h-4 w-4 mt-0.5 text-zinc-400"></i>
                                                    <span
                                                        class="line-clamp-1 font-semibold">{{ $mutation->fromPosition->name ?? '-' }}</span>
                                                </div>
                                                <div class="flex items-start gap-2 text-sm text-zinc-600">
                                                    <i data-lucide="building-2" class="h-4 w-4 mt-0.5 text-zinc-400"></i>
                                                    <span
                                                        class="line-clamp-1">{{ $mutation->fromDivision->name ?? '-' }}</span>
                                                </div>
                                                <div class="flex items-start gap-2 text-sm text-zinc-600">
                                                    <i data-lucide="map-pin" class="h-4 w-4 mt-0.5 text-zinc-400"></i>
                                                    <span
                                                        class="line-clamp-1">{{ $mutation->fromOffice->office_name ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- To -->
                                        <div class="space-y-3">
                                            <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Menjadi
                                            </p>
                                            <div class="space-y-2">
                                                <div class="flex items-start gap-2 text-sm text-zinc-900 font-medium">
                                                    <i data-lucide="briefcase" class="h-4 w-4 mt-0.5 text-zinc-400"></i>
                                                    <span
                                                        class="line-clamp-1 font-bold">{{ $mutation->toPosition->name ?? '-' }}</span>
                                                </div>
                                                <div class="flex items-start gap-2 text-sm text-zinc-900 font-medium">
                                                    <i data-lucide="building-2" class="h-4 w-4 mt-0.5 text-zinc-400"></i>
                                                    <span
                                                        class="line-clamp-1">{{ $mutation->toDivision->name ?? '-' }}</span>
                                                </div>
                                                <div class="flex items-start gap-2 text-sm text-zinc-900 font-medium">
                                                    <i data-lucide="map-pin" class="h-4 w-4 mt-0.5 text-zinc-400"></i>
                                                    <span
                                                        class="line-clamp-1">{{ $mutation->toOffice->office_name ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $mutations->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div
                class="flex flex-col items-center justify-center py-12 px-4 bg-white rounded-xl border border-zinc-200 border-dashed text-center">
                <div class="h-16 w-16 bg-zinc-50 rounded-full flex items-center justify-center mb-4">
                    <i data-lucide="book-open" class="h-8 w-8 text-zinc-300"></i>
                </div>
                <h3 class="text-lg font-medium text-zinc-900">Belum Ada Riwayat Mutasi</h3>
                <p class="text-zinc-500 mt-2 max-w-sm">
                    Anda belum memiliki riwayat mutasi, promosi, atau rotasi jabatan.
                </p>
            </div>
        @endif
    </div>
@endsection
