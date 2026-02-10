@extends('layouts.app')
@section('title', 'Master Data')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Dashboard Master Data</h2>
                <p class="text-zinc-500 mt-1">Overview statistik dan manajemen data utama sistem.</p>
            </div>
            <div class="flex space-x-2">
                <!-- Additional controls can go here -->
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <!-- Users Card -->
            @if (Auth::user()->hasPermission('manage-users'))
                <a href="{{ route('users.index') }}"
                    class="group relative overflow-hidden rounded-xl border bg-white p-6 shadow-sm hover:shadow-md transition-all hover:border-blue-200">
                    <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3
                            class="tracking-tight text-sm font-medium text-zinc-500 group-hover:text-blue-600 transition-colors">
                            Total User</h3>
                        <div class="p-2 bg-blue-50 rounded-lg group-hover:bg-blue-100 transition-colors">
                            <i data-lucide="users" class="h-4 w-4 text-blue-600"></i>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="text-2xl font-bold text-zinc-900">{{ $usersCount }}</div>
                        <p class="text-xs text-zinc-500 mt-1">
                            <span class="text-emerald-600 font-medium flex items-center gap-1">
                                <i data-lucide="zap" class="h-3 w-3"></i> Aktif
                            </span>
                            di platform
                        </p>
                    </div>
                </a>
            @endif

            <!-- Roles Card -->
            @if (Auth::user()->hasPermission('manage-roles'))
                <a href="{{ route('role.index') }}"
                    class="group relative overflow-hidden rounded-xl border bg-white p-6 shadow-sm hover:shadow-md transition-all hover:border-indigo-200">
                    <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3
                            class="tracking-tight text-sm font-medium text-zinc-500 group-hover:text-indigo-600 transition-colors">
                            Role & Akses</h3>
                        <div class="p-2 bg-indigo-50 rounded-lg group-hover:bg-indigo-100 transition-colors">
                            <i data-lucide="shield" class="h-4 w-4 text-indigo-600"></i>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="text-2xl font-bold text-zinc-900">{{ $rolesCount }}</div>
                        <p class="text-xs text-zinc-500 mt-1">
                            <span class="text-indigo-600 font-medium">{{ $activeRoles }} Role Aktif</span>
                            tersedia
                        </p>
                    </div>
                </a>
            @endif

            <!-- Cities Card -->
            @if (Auth::user()->hasPermission('manage-cities'))
                <a href="{{ route('cities.index') }}"
                    class="group relative overflow-hidden rounded-xl border bg-white p-6 shadow-sm hover:shadow-md transition-all hover:border-emerald-200">
                    <div class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3
                            class="tracking-tight text-sm font-medium text-zinc-500 group-hover:text-emerald-600 transition-colors">
                            Data Kota</h3>
                        <div class="p-2 bg-emerald-50 rounded-lg group-hover:bg-emerald-100 transition-colors">
                            <i data-lucide="map-pin" class="h-4 w-4 text-emerald-600"></i>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="text-2xl font-bold text-zinc-900">{{ $citiesCount }}</div>
                        <p class="text-xs text-zinc-500 mt-1">
                            Tersebar di seluruh provinsi
                        </p>
                    </div>
                </a>
            @endif
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
            <!-- Recent Users -->
            @if (Auth::user()->hasPermission('manage-users'))
                <div class="col-span-4 rounded-xl border bg-white shadow-sm">
                    <div class="p-6 pb-4 border-b border-zinc-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-zinc-900">User Terbaru</h3>
                            <p class="text-sm text-zinc-500">Daftar 5 user yang baru ditambahkan.</p>
                        </div>
                        <a href="{{ route('users.index') }}"
                            class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat
                            Semua</a>
                    </div>
                    <div class="p-0">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-zinc-50 text-zinc-500">
                                    <tr>
                                        <th class="px-6 py-3 font-medium">Nama</th>
                                        <th class="px-6 py-3 font-medium">Role</th>
                                        <th class="px-6 py-3 font-medium">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100">
                                    @foreach ($latestUsers as $user)
                                        <tr class="group hover:bg-zinc-50/50 transition-colors">
                                            <td class="px-6 py-3">
                                                <div class="flex items-center gap-3">
                                                    @if ($user->foto)
                                                        <img src="{{ asset('storage/' . $user->foto) }}" alt=""
                                                            class="h-8 w-8 rounded-full object-cover">
                                                    @else
                                                        <div
                                                            class="h-8 w-8 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-500">
                                                            <span
                                                                class="text-xs font-semibold">{{ substr($user->name, 0, 2) }}</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="font-medium text-zinc-900">{{ $user->name }}</div>
                                                        <div class="text-xs text-zinc-500">{{ $user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-3">
                                                <span
                                                    class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-1 text-xs font-medium text-zinc-700">
                                                    {{ $user->role->role_name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3">
                                                @if ($user->status)
                                                    <span class="flex h-2 w-2 rounded-full bg-emerald-500"
                                                        title="Aktif"></span>
                                                @else
                                                    <span class="flex h-2 w-2 rounded-full bg-red-500"
                                                        title="Inactive"></span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Top Provinces Stats -->
            @if (Auth::user()->hasPermission('manage-cities'))
                <div class="col-span-3 rounded-xl border bg-white shadow-sm">
                    <div class="p-6 pb-4 border-b border-zinc-100">
                        <h3 class="font-semibold text-zinc-900">Sebaran Kota per Provinsi</h3>
                        <p class="text-sm text-zinc-500">Top 5 provinsi dengan jumlah kota terbanyak.</p>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach ($topProvinces as $prov)
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="font-medium text-zinc-700 truncate max-w-[200px]"
                                        title="{{ $prov->province_name }}">{{ $prov->province_name }}</span>
                                    <span class="text-zinc-500">{{ $prov->total }} Kota</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-zinc-100 overflow-hidden">
                                    @php
                                        // Calculate simple percentage relative to the max count (using first item as max since ordered desc)
                                        $max = $topProvinces->first()->total;
                                        $width = ($prov->total / $max) * 100;
                                    @endphp
                                    <div class="h-full rounded-full bg-zinc-900" style="width: {{ $width }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if ($citiesCount > 0)
                        <div class="p-4 bg-zinc-50 border-t border-zinc-100 rounded-b-xl flex justify-center">
                            <a href="{{ route('cities.index') }}"
                                class="text-sm font-medium text-zinc-600 hover:text-zinc-900 flex items-center gap-2">
                                Kelola Seluruh Data Kota
                                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Quick Access Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @if (Auth::user()->hasPermission('manage-users'))
                <a href="{{ route('users.create') }}"
                    class="flex flex-col items-center justify-center p-6 bg-white border rounded-xl hover:border-zinc-300 hover:shadow-sm transition-all group">
                    <div
                        class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i data-lucide="user-plus" class="h-5 w-5 text-blue-600"></i>
                    </div>
                    <span class="text-sm font-medium text-zinc-900">Tambah User</span>
                </a>
            @endif

            @if (Auth::user()->hasPermission('manage-roles'))
                <a href="{{ route('role.create') }}"
                    class="flex flex-col items-center justify-center p-6 bg-white border rounded-xl hover:border-zinc-300 hover:shadow-sm transition-all group">
                    <div
                        class="h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i data-lucide="shield-plus" class="h-5 w-5 text-indigo-600"></i>
                    </div>
                    <span class="text-sm font-medium text-zinc-900">Tambah Role</span>
                </a>
            @endif

            @if (Auth::user()->hasPermission('manage-cities'))
                <a href="{{ route('cities.create') }}"
                    class="flex flex-col items-center justify-center p-6 bg-white border rounded-xl hover:border-zinc-300 hover:shadow-sm transition-all group">
                    <div
                        class="h-10 w-10 rounded-full bg-emerald-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i data-lucide="map-pin-plus" class="h-5 w-5 text-emerald-600"></i>
                    </div>
                    <span class="text-sm font-medium text-zinc-900">Tambah Kota</span>
                </a>
                <form action="{{ route('cities.sync') }}" method="POST" class="w-full"
                    onsubmit="return confirm('Mulai sinkronisasi?');">
                    @csrf
                    <button type="submit"
                        class="flex w-full flex-col items-center justify-center p-6 bg-white border rounded-xl hover:border-zinc-300 hover:shadow-sm transition-all group h-full">
                        <div
                            class="h-10 w-10 rounded-full bg-orange-50 flex items-center justify-center mb-3 group-hover:rotate-180 transition-transform duration-500">
                            <i data-lucide="refresh-cw" class="h-5 w-5 text-orange-600"></i>
                        </div>
                        <span class="text-sm font-medium text-zinc-900">Sync Kota</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
