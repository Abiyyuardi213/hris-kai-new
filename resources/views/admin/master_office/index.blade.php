@extends('layouts.app')
@section('title', 'Master Office')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Dashboard Master Office</h2>
                <p class="text-zinc-500 mt-1">Kelola struktur organisasi, lokasi kantor, dan status kepegawaian.</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <!-- Kantor Card -->
            @if (Auth::user()->hasPermission('manage-offices'))
                <a href="{{ route('offices.index') }}"
                    class="group relative overflow-hidden rounded-xl border bg-white p-6 shadow-sm hover:shadow-md transition-all hover:border-blue-200">
                    <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3
                            class="tracking-tight text-sm font-medium text-zinc-500 group-hover:text-blue-600 transition-colors">
                            Total Kantor</h3>
                        <div class="p-2 bg-blue-50 rounded-lg group-hover:bg-blue-100 transition-colors">
                            <i data-lucide="building-2" class="h-4 w-4 text-blue-600"></i>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="text-2xl font-bold text-zinc-900">{{ $officesCount }}</div>
                        <p class="text-xs text-zinc-500 mt-1">Lokasi kantor aktif</p>
                    </div>
                </a>
            @endif

            <!-- Divisi Card -->
            @if (Auth::user()->hasPermission('manage-divisions'))
                <a href="{{ route('divisions.index') }}"
                    class="group relative overflow-hidden rounded-xl border bg-white p-6 shadow-sm hover:shadow-md transition-all hover:border-indigo-200">
                    <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3
                            class="tracking-tight text-sm font-medium text-zinc-500 group-hover:text-indigo-600 transition-colors">
                            Total Divisi</h3>
                        <div class="p-2 bg-indigo-50 rounded-lg group-hover:bg-indigo-100 transition-colors">
                            <i data-lucide="layers" class="h-4 w-4 text-indigo-600"></i>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="text-2xl font-bold text-zinc-900">{{ $divisionsCount }}</div>
                        <p class="text-xs text-zinc-500 mt-1">Unit organisasi</p>
                    </div>
                </a>
            @endif

            <!-- Jabatan Card -->
            @if (Auth::user()->hasPermission('manage-positions'))
                <a href="{{ route('positions.index') }}"
                    class="group relative overflow-hidden rounded-xl border bg-white p-6 shadow-sm hover:shadow-md transition-all hover:border-emerald-200">
                    <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3
                            class="tracking-tight text-sm font-medium text-zinc-500 group-hover:text-emerald-600 transition-colors">
                            Total Jabatan</h3>
                        <div class="p-2 bg-emerald-50 rounded-lg group-hover:bg-emerald-100 transition-colors">
                            <i data-lucide="briefcase" class="h-4 w-4 text-emerald-600"></i>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="text-2xl font-bold text-zinc-900">{{ $positionsCount }}</div>
                        <p class="text-xs text-zinc-500 mt-1">Posisi pekerjaan</p>
                    </div>
                </a>
            @endif

            <!-- Status Card -->
            @if (Auth::user()->hasPermission('manage-employee-statuses'))
                <a href="{{ route('employment-statuses.index') }}"
                    class="group relative overflow-hidden rounded-xl border bg-white p-6 shadow-sm hover:shadow-md transition-all hover:border-orange-200">
                    <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3
                            class="tracking-tight text-sm font-medium text-zinc-500 group-hover:text-orange-600 transition-colors">
                            Status Pegawai</h3>
                        <div class="p-2 bg-orange-50 rounded-lg group-hover:bg-orange-100 transition-colors">
                            <i data-lucide="user-check" class="h-4 w-4 text-orange-600"></i>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="text-2xl font-bold text-zinc-900">{{ $statusesCount }}</div>
                        <p class="text-xs text-zinc-500 mt-1">Kategori kontrak</p>
                    </div>
                </a>
            @endif
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
            <!-- Recent Offices -->
            @if (Auth::user()->hasPermission('manage-offices'))
                <div class="col-span-4 rounded-xl border bg-white shadow-sm">
                    <div class="p-6 pb-4 border-b border-zinc-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-zinc-900">Kantor Terbaru</h3>
                            <p class="text-sm text-zinc-500">Daftar lokasi kantor yang baru ditambahkan.</p>
                        </div>
                        <a href="{{ route('offices.index') }}"
                            class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat
                            Semua</a>
                    </div>
                    <div class="p-0">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-zinc-50 text-zinc-500">
                                    <tr>
                                        <th class="px-6 py-3 font-medium">Nama Kantor</th>
                                        <th class="px-6 py-3 font-medium">Kota</th>
                                        <th class="px-6 py-3 font-medium">Kode</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100">
                                    @foreach ($latestOffices as $office)
                                        <tr class="group hover:bg-zinc-50/50 transition-colors">
                                            <td class="px-6 py-3">
                                                <div class="font-medium text-zinc-900">{{ $office->office_name }}</div>
                                                <div class="text-xs text-zinc-500 truncate max-w-[200px]">
                                                    {{ $office->office_address }}</div>
                                            </td>
                                            <td class="px-6 py-3">
                                                <span class="text-zinc-600">{{ $office->city->name ?? 'N/A' }}</span>
                                            </td>
                                            <td class="px-6 py-3 text-zinc-500 uppercase font-mono text-xs">
                                                {{ $office->office_code }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Division Breakdown -->
            @if (Auth::user()->hasPermission('manage-divisions'))
                <div class="col-span-3 rounded-xl border bg-white shadow-sm">
                    <div class="p-6 pb-4 border-b border-zinc-100">
                        <h3 class="font-semibold text-zinc-900">Distribusi Pegawai</h3>
                        <p class="text-sm text-zinc-500">Jumlah pegawai per divisi.</p>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse ($divisionStats as $stat)
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="font-medium text-zinc-700 truncate max-w-[180px]"
                                        title="{{ $stat->name }}">{{ $stat->name }}</span>
                                    <span class="text-zinc-500">{{ $stat->employees_count }} Pegawai</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-zinc-100 overflow-hidden">
                                    @php
                                        $max = $divisionStats->first()->employees_count ?? 1;
                                        $width = $max > 0 ? ($stat->employees_count / $max) * 100 : 0;
                                    @endphp
                                    <div class="h-full rounded-full bg-indigo-600" style="width: {{ $width }}%">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-zinc-500 text-center py-4">Belum ada data divisi.</p>
                        @endforelse
                    </div>
                    <div class="p-4 bg-zinc-50 border-t border-zinc-100 rounded-b-xl flex justify-center">
                        <a href="{{ route('divisions.index') }}"
                            class="text-sm font-medium text-zinc-600 hover:text-zinc-900 flex items-center gap-2">
                            Kelola Seluruh Divisi
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @if (Auth::user()->hasPermission('manage-offices'))
                <a href="{{ route('offices.create') }}"
                    class="flex flex-col items-center justify-center p-6 bg-white border rounded-xl hover:border-zinc-300 hover:shadow-sm transition-all group">
                    <div
                        class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i data-lucide="plus-circle" class="h-5 w-5 text-blue-600"></i>
                    </div>
                    <span class="text-sm font-medium text-zinc-900">Tambah Kantor</span>
                </a>
            @endif

            @if (Auth::user()->hasPermission('manage-divisions'))
                <a href="{{ route('divisions.create') }}"
                    class="flex flex-col items-center justify-center p-6 bg-white border rounded-xl hover:border-zinc-300 hover:shadow-sm transition-all group">
                    <div
                        class="h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i data-lucide="plus-circle" class="h-5 w-5 text-indigo-600"></i>
                    </div>
                    <span class="text-sm font-medium text-zinc-900">Tambah Divisi</span>
                </a>
            @endif
            @if (Auth::user()->hasPermission('manage-positions'))
                <a href="{{ route('positions.create') }}"
                    class="flex flex-col items-center justify-center p-6 bg-white border rounded-xl hover:border-zinc-300 hover:shadow-sm transition-all group">
                    <div
                        class="h-10 w-10 rounded-full bg-emerald-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i data-lucide="plus-circle" class="h-5 w-5 text-emerald-600"></i>
                    </div>
                    <span class="text-sm font-medium text-zinc-900">Tambah Jabatan</span>
                </a>
            @endif
            @if (Auth::user()->hasPermission('manage-employee-statuses'))
                <a href="{{ route('employment-statuses.create') }}"
                    class="flex flex-col items-center justify-center p-6 bg-white border rounded-xl hover:border-zinc-300 hover:shadow-sm transition-all group">
                    <div
                        class="h-10 w-10 rounded-full bg-orange-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i data-lucide="plus-circle" class="h-5 w-5 text-orange-600"></i>
                    </div>
                    <span class="text-sm font-medium text-zinc-900">Tambah Status</span>
                </a>
            @endif
        </div>
    </div>
@endsection
