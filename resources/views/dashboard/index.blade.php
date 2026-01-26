@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="flex items-center justify-between space-y-2 mb-6">
        <h2 class="text-3xl font-bold tracking-tight">Dashboard</h2>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <!-- Card Total Users -->
        <div class="rounded-xl border bg-white text-zinc-950 shadow">
            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="tracking-tight text-sm font-medium">Total Users</h3>
                <i data-lucide="users" class="h-4 w-4 text-zinc-500"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ \App\Models\User::count() }}</div>
                <p class="text-xs text-zinc-500">User terdaftar</p>
                <a href="{{ route('users.index') }}" class="text-xs text-blue-600 hover:text-blue-800 mt-2 block">Lihat
                    Detail →</a>
            </div>
        </div>

        <!-- Card Total Roles -->
        <div class="rounded-xl border bg-white text-zinc-950 shadow">
            <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                <h3 class="tracking-tight text-sm font-medium">Total Peran</h3>
                <i data-lucide="shield" class="h-4 w-4 text-zinc-500"></i>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold">{{ \App\Models\Peran::count() }}</div>
                <p class="text-xs text-zinc-500">Peran tersedia</p>
                <a href="{{ route('role.index') }}" class="text-xs text-blue-600 hover:text-blue-800 mt-2 block">Lihat
                    Detail →</a>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div class="rounded-xl border bg-white text-zinc-950 shadow">
            <div class="p-6 flex flex-col space-y-1.5">
                <h3 class="font-semibold leading-none tracking-tight">Selamat Datang, {{ Auth::user()->name }}!</h3>
                <p class="text-sm text-zinc-500">Overview sistem HRIS.</p>
            </div>
            <div class="p-6 pt-0">
                <p>Anda login sebagai <span class="font-medium">{{ Auth::user()->role->role_name ?? '-' }}</span>.</p>
            </div>
        </div>
    </div>
@endsection
