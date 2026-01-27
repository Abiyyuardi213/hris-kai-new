@extends('layouts.app')
@section('title', 'Hak Akses Peran')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('role.index') }}"
                class="h-10 w-10 flex items-center justify-center rounded-lg bg-white border border-zinc-200 text-zinc-400 hover:text-zinc-900 transition-all">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Hak Akses: {{ $role->role_name }}</h2>
                <p class="text-sm text-zinc-500">Kelola izin akses untuk modul dan fitur sistem.</p>
            </div>
        </div>

        <form action="{{ route('role.update-permissions', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($permissions as $module => $modulePermissions)
                    <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden flex flex-col h-full">
                        <div class="bg-zinc-50 px-4 py-3 border-b border-zinc-200 flex items-center justify-between">
                            <h3 class="font-bold text-zinc-900 flex items-center gap-2">
                                <i data-lucide="package" class="h-4 w-4 text-zinc-400"></i>
                                {{ $module }}
                            </h3>
                            <span
                                class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">{{ $modulePermissions->count() }}
                                Izin</span>
                        </div>

                        <div class="p-4 space-y-3 flex-grow">
                            @foreach ($modulePermissions as $permission)
                                <label
                                    class="relative flex items-start p-3 rounded-lg border border-zinc-100 hover:bg-zinc-50 transition-all cursor-pointer group">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                            {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <span
                                            class="font-bold text-zinc-900 block group-hover:text-zinc-800">{{ $permission->display_name }}</span>
                                        <span
                                            class="text-xs text-zinc-400 uppercase tracking-tighter">{{ $permission->name }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sticky Footer -->
            <div class="sticky bottom-6 mt-8 flex justify-end">
                <div class="bg-white p-4 rounded-xl border border-zinc-200 shadow-xl flex items-center gap-4 min-w-[300px]">
                    <div class="flex-grow">
                        <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Aksi Perubahan</p>
                        <p class="text-[11px] text-zinc-500">Pastikan akses sudah sesuai sebelum menyimpan.</p>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-6 py-2.5 text-sm font-bold text-white hover:bg-zinc-800 transition-all active:scale-95">
                        <i data-lucide="save" class="h-4 w-4"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
