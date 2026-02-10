@extends('layouts.app')

@section('title', 'Dashboard Master Pegawai')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Dashboard Master Pegawai</h2>
                <p class="text-zinc-500">Ringkasan data kepegawaian, shift, dan mutasi.</p>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @if (Auth::user()->hasPermission('view-employees') || Auth::user()->hasPermission('manage-employees'))
                <div class="bg-white p-6 rounded-2xl border border-zinc-200 shadow-sm flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i data-lucide="users" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Total Pegawai</p>
                        <h4 class="text-2xl font-bold text-zinc-900">{{ $employeeCount }}</h4>
                    </div>
                </div>
            @endif

            @if (Auth::user()->hasPermission('manage-shifts'))
                <div class="bg-white p-6 rounded-2xl border border-zinc-200 shadow-sm flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i data-lucide="clock" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Master Shift</p>
                        <h4 class="text-2xl font-bold text-zinc-900">{{ $shiftCount }}</h4>
                    </div>
                </div>
            @endif

            @if (Auth::user()->hasPermission('manage-mutations'))
                <div class="bg-white p-6 rounded-2xl border border-zinc-200 shadow-sm flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                        <i data-lucide="arrow-right-left" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Total Mutasi</p>
                        <h4 class="text-2xl font-bold text-zinc-900">{{ $mutationCount }}</h4>
                    </div>
                </div>
            @endif

            @if (Auth::user()->hasPermission('manage-holidays'))
                <div class="bg-white p-6 rounded-2xl border border-zinc-200 shadow-sm flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i data-lucide="calendar" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Hari Libur</p>
                        <h4 class="text-2xl font-bold text-zinc-900">{{ $holidayCount }}</h4>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Latest Mutations -->
            @if (Auth::user()->hasPermission('manage-mutations'))
                <div class="lg:col-span-2 bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-zinc-100 flex items-center justify-between">
                        <h3 class="font-bold text-zinc-900">Mutasi Terbaru</h3>
                        <a href="{{ route('mutations.index') }}"
                            class="text-xs font-bold text-blue-600 hover:underline">Lihat
                            Semua</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-zinc-50 text-zinc-500">
                                <tr>
                                    <th class="px-6 py-3 font-medium">Pegawai</th>
                                    <th class="px-6 py-3 font-medium">Perubahan</th>
                                    <th class="px-6 py-3 font-medium">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @forelse($latestMutations as $mutation)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-zinc-900">{{ $mutation->employee->nama_lengkap }}
                                            </div>
                                            <div class="text-xs text-zinc-500">{{ $mutation->employee->nip }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="text-xs font-medium text-zinc-400">{{ $mutation->fromPosition->name ?? '-' }}</span>
                                                <i data-lucide="arrow-right" class="h-3 w-3 text-zinc-300"></i>
                                                <span
                                                    class="text-xs font-bold text-zinc-900">{{ $mutation->toPosition->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-zinc-500">
                                            {{ \Carbon\Carbon::parse($mutation->mutation_date)->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 text-center text-zinc-500">Belum ada data
                                            mutasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Upcoming Holidays -->
            @if (Auth::user()->hasPermission('manage-holidays'))
                <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-zinc-100 flex items-center justify-between">
                        <h3 class="font-bold text-zinc-900">Hari Libur Terdekat</h3>
                        <a href="{{ route('holidays.index') }}"
                            class="text-xs font-bold text-blue-600 hover:underline">Kelola</a>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($upcomingHolidays as $holiday)
                            <div class="flex items-center gap-4">
                                <div
                                    class="h-10 w-10 rounded-lg bg-zinc-50 flex flex-col items-center justify-center border border-zinc-100">
                                    <span
                                        class="text-[10px] uppercase font-bold text-red-500">{{ \Carbon\Carbon::parse($holiday->date)->format('M') }}</span>
                                    <span
                                        class="text-sm font-bold text-zinc-900 leading-none">{{ \Carbon\Carbon::parse($holiday->date)->format('d') }}</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-zinc-900 truncate">{{ $holiday->name }}</p>
                                    <p class="text-xs text-zinc-500">
                                        {{ \Carbon\Carbon::parse($holiday->date)->format('l') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-center text-zinc-500 py-4">Tidak ada libur terdekat.</p>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Gender Stats -->
            @if (Auth::user()->hasPermission('view-employees') || Auth::user()->hasPermission('manage-employees'))
                <div class="bg-white p-6 rounded-2xl border border-zinc-200 shadow-sm">
                    <h3 class="font-bold text-zinc-900 mb-6">Komposisi Jenis Kelamin</h3>
                    <div class="space-y-6">
                        @foreach ($genderStats as $stat)
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span
                                        class="font-medium text-zinc-600">{{ $stat->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                    <span class="font-bold text-zinc-900">{{ $stat->total }} Orang</span>
                                </div>
                                <div class="w-full bg-zinc-100 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $stat->jenis_kelamin == 'L' ? 'bg-blue-500' : 'bg-pink-500' }}"
                                        style="width: {{ ($stat->total / max($employeeCount, 1)) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Division breakdown -->
            @if (Auth::user()->hasPermission('view-employees') || Auth::user()->hasPermission('manage-employees'))
                <div class="bg-white p-6 rounded-2xl border border-zinc-200 shadow-sm">
                    <h3 class="font-bold text-zinc-900 mb-6">Pegawai per Divisi (Top 5)</h3>
                    <div class="space-y-4">
                        @foreach ($divisionStats as $div)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-zinc-50 border border-zinc-100">
                                <div class="font-medium text-zinc-700">{{ $div->name }}</div>
                                <div
                                    class="px-3 py-1 bg-white rounded-lg border border-zinc-200 text-xs font-bold text-zinc-900">
                                    {{ $div->employees_count }} Pegawai
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
