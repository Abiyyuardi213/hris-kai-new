<!-- Bottom Navigation for Mobile -->
<nav
    class="fixed bottom-0 left-0 z-50 w-full h-16 bg-white border-t border-zinc-200 flex items-center justify-around px-2 md:hidden">
    <a href="{{ route('employee.dashboard') }}"
        class="flex flex-col items-center justify-center gap-1 flex-1 transition-colors {{ Request::is('dashboard-pegawai*') ? 'text-zinc-900 font-bold' : 'text-zinc-400 font-medium' }}">
        <i data-lucide="layout-dashboard" class="h-5 w-5"></i>
        <span class="text-[10px]">Dashboard</span>
    </a>
    <a href="{{ route('employee.attendance.index') }}"
        class="flex flex-col items-center justify-center gap-1 flex-1 transition-colors {{ Request::is('attendance*') ? 'text-zinc-900 font-bold' : 'text-zinc-400 font-medium' }}">
        <i data-lucide="camera" class="h-5 w-5"></i>
        <span class="text-[10px]">Presensi</span>
    </a>
    <a href="{{ route('employee.profile') }}"
        class="flex flex-col items-center justify-center gap-1 flex-1 transition-colors {{ Request::is('profile*') ? 'text-zinc-900 font-bold' : 'text-zinc-400 font-medium' }}">
        <i data-lucide="user-cog" class="h-5 w-5"></i>
        <span class="text-[10px]">Profil</span>
    </a>
    <button onclick="document.getElementById('logout-form-employee').submit();"
        class="flex flex-col items-center justify-center gap-1 flex-1 text-red-400 transition-colors">
        <i data-lucide="log-out" class="h-5 w-5"></i>
        <span class="text-[10px]">Keluar</span>
    </button>
</nav>

<!-- Sidebar for Desktop Employee -->
<aside class="hidden md:flex fixed inset-y-0 left-0 w-64 flex-col border-r bg-zinc-50/50 transition-all duration-300">
    <div class="flex h-16 items-center border-b bg-white px-6">
        <a href="{{ route('employee.dashboard') }}" class="flex items-center gap-2">
            <img src="{{ asset('image/logo-kai.png') }}" alt="Logo" class="h-8 w-auto">
        </a>
    </div>

    <div class="flex-1 px-4 py-6 space-y-2">
        <a href="{{ route('employee.dashboard') }}"
            class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm transition-all {{ Request::is('dashboard-pegawai*') ? 'bg-zinc-900 text-white shadow-lg shadow-zinc-200' : 'text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900' }}">
            <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
            <span class="font-bold">Dashboard</span>
        </a>
        <a href="{{ route('employee.attendance.index') }}"
            class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm transition-all {{ Request::is('attendance*') ? 'bg-zinc-900 text-white shadow-lg shadow-zinc-200' : 'text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900' }}">
            <i data-lucide="camera" class="h-4 w-4"></i>
            <span class="font-bold">Presensi Harian</span>
        </a>
        <a href="{{ route('employee.profile') }}"
            class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm transition-all {{ Request::is('profile*') ? 'bg-zinc-900 text-white shadow-lg shadow-zinc-200' : 'text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900' }}">
            <i data-lucide="user-cog" class="h-4 w-4"></i>
            <span class="font-bold">Pengaturan Profil</span>
        </a>
    </div>

    <div class="p-4 border-t bg-white">
        <button type="button" onclick="document.getElementById('logout-form-employee').submit();"
            class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 transition-all">
            <i data-lucide="log-out" class="h-4 w-4"></i>
            Keluar Aplikasi
        </button>
    </div>
</aside>
