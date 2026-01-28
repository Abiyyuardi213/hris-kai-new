<!-- Overlay for mobile -->
<div id="employeeSidebarOverlay"
    class="fixed inset-0 z-40 bg-zinc-900/60 backdrop-blur-sm hidden md:hidden transition-opacity duration-300 opacity-0"
    onclick="toggleEmployeeSidebar()"></div>

<!-- Bottom Navigation for Mobile (Only 4 Items) -->
<nav
    class="fixed bottom-0 left-0 z-40 w-full h-16 bg-white border-t border-zinc-200 flex items-center justify-around px-2 md:hidden shadow-[0_-4px_10px_rgba(0,0,0,0.03)]">
    <a href="{{ route('employee.dashboard') }}"
        class="flex flex-col items-center justify-center gap-1 flex-1 transition-colors {{ Request::is('dashboard-pegawai*') ? 'text-zinc-900' : 'text-zinc-400' }}">
        <i data-lucide="layout-dashboard"
            class="h-5 w-5 {{ Request::is('dashboard-pegawai*') ? 'fill-zinc-900' : '' }}"></i>
        <span class="text-[10px] font-bold">Dashboard</span>
    </a>
    <a href="{{ route('employee.attendance.index') }}"
        class="flex flex-col items-center justify-center gap-1 flex-1 transition-colors {{ Request::is('attendance') ? 'text-zinc-900' : 'text-zinc-400' }}">
        <i data-lucide="camera" class="h-5 w-5 {{ Request::is('attendance') ? 'fill-zinc-900' : '' }}"></i>
        <span class="text-[10px] font-bold">Presensi</span>
    </a>
    <a href="{{ route('employee.attendance.history') }}"
        class="flex flex-col items-center justify-center gap-1 flex-1 transition-colors {{ Request::is('attendance-history*') ? 'text-zinc-900' : 'text-zinc-400' }}">
        <i data-lucide="history" class="h-5 w-5 {{ Request::is('attendance-history*') ? 'fill-zinc-900' : '' }}"></i>
        <span class="text-[10px] font-bold">Riwayat</span>
    </a>
    <a href="{{ route('employee.profile') }}"
        class="flex flex-col items-center justify-center gap-1 flex-1 transition-colors {{ Request::is('profile*') ? 'text-zinc-900' : 'text-zinc-400' }}">
        <i data-lucide="user-cog" class="h-5 w-5 {{ Request::is('profile*') ? 'fill-zinc-900' : '' }}"></i>
        <span class="text-[10px] font-bold">Profil</span>
    </a>
</nav>

<!-- Sidebar for Employee (Desktop & Mobile Drawer) -->
<aside id="employeeSidebar"
    class="fixed inset-y-0 left-0 z-50 w-72 flex flex-col border-r bg-white transition-all duration-300 ease-in-out -translate-x-full md:translate-x-0 h-[100dvh] overflow-hidden group shadow-2xl md:shadow-none">
    <div class="flex h-16 items-center border-b bg-white px-6 justify-between shrink-0">
        <a href="{{ route('employee.dashboard') }}" class="flex items-center gap-2">
            <img src="{{ asset('image/logo-kai.png') }}" alt="Logo" class="h-8 w-auto">
        </a>
        <button onclick="toggleEmployeeSidebar()"
            class="md:hidden p-2 text-zinc-400 hover:text-zinc-900 transition-colors">
            <i data-lucide="x" class="h-6 w-6"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto px-4 py-8 space-y-8 scrollbar-hide">
        <!-- Main Navigation -->
        <div class="space-y-1.5">
            <p class="px-4 text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-4">Utama</p>
            <a href="{{ route('employee.dashboard') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm transition-all {{ Request::is('dashboard-pegawai*') ? 'bg-zinc-900 text-white shadow-xl shadow-zinc-200' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}">
                <i data-lucide="layout-dashboard" class="h-5 w-5"></i>
                <span class="font-bold">Dashboard Pegawai</span>
            </a>
            <a href="{{ route('employee.attendance.index') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm transition-all {{ Request::is('attendance') ? 'bg-zinc-900 text-white shadow-xl shadow-zinc-200' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}">
                <i data-lucide="camera" class="h-5 w-5"></i>
                <span class="font-bold">Presensi Harian</span>
            </a>
        </div>

        <!-- Request Section -->
        <div class="space-y-1.5">
            <p class="px-4 text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-4">Pengajuan</p>
            <a href="{{ route('employee.izin.index') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm transition-all {{ Request::is('izin*') ? 'bg-zinc-900 text-white shadow-xl shadow-zinc-200' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}">
                <i data-lucide="file-text" class="h-5 w-5"></i>
                <span class="font-bold">Izin & Sakit</span>
            </a>
            <a href="{{ route('employee.overtime.index') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm transition-all {{ Request::is('overtime*') ? 'bg-zinc-900 text-white shadow-xl shadow-zinc-200' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}">
                <i data-lucide="clock" class="h-5 w-5"></i>
                <span class="font-bold">Lembur</span>
            </a>
            <a href="{{ route('employee.perjalanan_dinas.index') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm transition-all {{ Request::is('perjalanan-dinas*') ? 'bg-zinc-900 text-white shadow-xl shadow-zinc-200' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}">
                <i data-lucide="briefcase" class="h-5 w-5"></i>
                <span class="font-bold">Perjalanan Dinas</span>
            </a>
            <a href="{{ route('employee.performance.index') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm transition-all {{ Request::is('performance*') ? 'bg-zinc-900 text-white shadow-xl shadow-zinc-200' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}">
                <i data-lucide="award" class="h-5 w-5"></i>
                <span class="font-bold">Kinerja & KPI</span>
            </a>
        </div>

        <!-- Personal Section -->
        <div class="space-y-1.5">
            <p class="px-4 text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-4">Data Pribadi</p>
            <a href="{{ route('employee.attendance.history') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm transition-all {{ Request::is('attendance-history*') ? 'bg-zinc-900 text-white shadow-xl shadow-zinc-200' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}">
                <i data-lucide="history" class="h-5 w-5"></i>
                <span class="font-bold">Riwayat Presensi</span>
            </a>
            <a href="{{ route('employee.profile') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm transition-all {{ Request::is('profile*') ? 'bg-zinc-900 text-white shadow-xl shadow-zinc-200' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}">
                <i data-lucide="user-cog" class="h-5 w-5"></i>
                <span class="font-bold">Pengaturan Profil</span>
            </a>
            <a href="{{ route('employee.payroll.index') }}"
                class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm transition-all {{ Request::is('payroll*') ? 'bg-zinc-900 text-white shadow-xl shadow-zinc-200' : 'text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900' }}">
                <i data-lucide="banknote" class="h-5 w-5"></i>
                <span class="font-bold">Slip Gaji</span>
            </a>
        </div>
    </div>

    <!-- User Section Bottom -->
    <div class="p-4 border-t bg-zinc-50/50 pb-12 md:pb-4">
        <div class="flex items-center gap-3 p-2 mb-4 bg-white rounded-2xl border border-zinc-100 shadow-sm">
            @if (Auth::guard('employee')->user()->foto)
                <img src="{{ asset('storage/' . Auth::guard('employee')->user()->foto) }}" alt="Avatar"
                    class="h-10 w-10 rounded-xl object-cover shrink-0">
            @else
                <div class="h-10 w-10 rounded-xl bg-zinc-100 flex items-center justify-center text-zinc-400 shrink-0">
                    <i data-lucide="user" class="h-5 w-5"></i>
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-zinc-900 truncate">{{ Auth::guard('employee')->user()->nama_lengkap }}
                </p>
                <p class="text-[10px] font-medium text-zinc-500 truncate">{{ Auth::guard('employee')->user()->nip }}
                </p>
            </div>
        </div>
        <button type="button" onclick="showLogoutModal();"
            class="flex w-full items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-bold text-red-600 hover:bg-red-50 transition-all border border-transparent hover:border-red-100">
            <i data-lucide="log-out" class="h-5 w-5"></i>
            Keluar Aplikasi
        </button>
    </div>
</aside>
