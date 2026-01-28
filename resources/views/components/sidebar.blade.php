<!-- Overlay for mobile -->
<div id="sidebarOverlay" class="fixed inset-0 z-40 bg-black/50 hidden md:hidden transition-opacity"></div>

<aside id="sidebar"
    class="fixed md:static inset-y-0 left-0 z-50 w-64 flex flex-col border-r bg-white transition-all duration-300 ease-in-out -translate-x-full md:translate-x-0 h-[100dvh] md:h-screen shadow-2xl md:shadow-none group">
    <div
        class="flex h-14 items-center border-b px-4 lg:h-[60px] lg:px-6 transition-all duration-300 overflow-hidden whitespace-nowrap">
        <a href="/" class="flex items-center gap-2 font-semibold">
            <img src="{{ asset('image/logo-kai.png') }}" alt="Logo" class="h-8 w-auto">
        </a>
    </div>
    <div class="flex-1 overflow-x-hidden overflow-y-auto py-2">
        <nav class="grid items-start px-2 text-sm font-medium lg:px-4 space-y-1">
            @if (Auth::guard('web')->check())
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/dashboard*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                    <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                    <span class="sidebar-text group-[.collapsed]:hidden">Dashboard Admin</span>
                </a>

                @if (Auth::user()->role && strtolower(Auth::user()->role->role_name) != 'pegawai')
                    <div
                        class="mt-4 mb-2 px-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider sidebar-text group-[.collapsed]:hidden">
                        Master Data</div>

                    <a href="{{ route('master.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/master-data*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="database" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Dashboard Master</span>
                    </a>

                    @if (Auth::user()->hasPermission('manage-roles'))
                        <a href="{{ route('role.index') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/role*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                            <i data-lucide="shield" class="h-4 w-4"></i>
                            <span class="sidebar-text group-[.collapsed]:hidden">Manajemen Peran</span>
                        </a>
                    @endif

                    @if (Auth::user()->hasPermission('manage-users'))
                        <a href="{{ route('users.index') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/users*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                            <i data-lucide="users" class="h-4 w-4"></i>
                            <span class="sidebar-text group-[.collapsed]:hidden">Manajemen Users</span>
                        </a>
                    @endif

                    <a href="{{ route('cities.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/cities*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="map-pin" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Manajemen Kota</span>
                    </a>

                    <div
                        class="mt-4 mb-2 px-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider sidebar-text group-[.collapsed]:hidden">
                        Master Office</div>

                    <a href="{{ route('master.office') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/master-office*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="layout-grid" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Dashboard Office</span>
                    </a>

                    <a href="{{ route('offices.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/offices*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="building-2" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Manajemen Kantor</span>
                    </a>

                    <a href="{{ route('divisions.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/divisions*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="layers" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Manajemen Divisi</span>
                    </a>

                    <a href="{{ route('positions.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/positions*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="briefcase" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Manajemen Jabatan</span>
                    </a>

                    <a href="{{ route('employment-statuses.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/employment-statuses*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="user-check" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Status Pegawai</span>
                    </a>

                    <div
                        class="mt-4 mb-2 px-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider sidebar-text group-[.collapsed]:hidden">
                        Pegawai</div>

                    <a href="{{ route('master.employee') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/master-employee*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="layout-grid" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Dashboard Pegawai</span>
                    </a>

                    <a href="{{ route('employees.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/employees*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="users" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Manajemen Pegawai</span>
                    </a>

                    <a href="{{ route('mutations.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/mutations*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="arrow-right-left" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Mutasi Pegawai</span>
                    </a>

                    <a href="{{ route('shifts.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/shifts*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="clock" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Manajemen Shift</span>
                    </a>

                    <a href="{{ route('employee-shifts.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/employee-shifts*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="calendar-clock" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Jadwal Shift</span>
                    </a>

                    <a href="{{ route('admin.presensi.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/presensi*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="clipboard-check" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Daftar Presensi</span>
                    </a>

                    @if (Auth::user()->hasPermission('manage-izin'))
                        <a href="{{ route('admin.izin.index') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/izin*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                            <i data-lucide="file-clock" class="h-4 w-4"></i>
                            <span class="sidebar-text group-[.collapsed]:hidden">Izin / Sakit</span>
                        </a>
                    @endif

                    @if (Auth::user()->hasPermission('manage-overtime'))
                        <a href="{{ route('admin.overtime.index') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/overtime*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                            <i data-lucide="clock-arrow-up" class="h-4 w-4"></i>
                            <span class="sidebar-text group-[.collapsed]:hidden">Manajemen Lembur</span>
                        </a>
                    @endif

                    <a href="{{ route('admin.payroll.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/payroll*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="banknote" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Manajemen Payroll</span>
                    </a>

                    <a href="{{ route('admin.perjalanan_dinas.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/perjalanan-dinas*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="briefcase" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Perjalanan Dinas</span>
                    </a>

                    <a href="{{ route('admin.performance.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/performance*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="star" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Evaluasi Kinerja</span>
                    </a>

                    <div
                        class="mt-4 mb-2 px-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider sidebar-text group-[.collapsed]:hidden">
                        Lainnya</div>

                    <a href="{{ route('holidays.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/holidays*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                        <i data-lucide="calendar" class="h-4 w-4"></i>
                        <span class="sidebar-text group-[.collapsed]:hidden">Hari Libur</span>
                    </a>

                    <div
                        class="mt-4 mb-2 px-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider sidebar-text group-[.collapsed]:hidden">
                        Assets</div>

                    @if (Auth::user()->hasPermission('manage-assets'))
                        <a href="{{ route('assets.index') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/assets*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                            <i data-lucide="box" class="h-4 w-4"></i>
                            <span class="sidebar-text group-[.collapsed]:hidden">Manajemen Aset</span>
                        </a>
                    @endif
                @endif
            @endif
        </nav>
    </div>
    <div class="mt-auto border-t p-4 sidebar-footer transition-all duration-300">
        <div class="flex items-center gap-3">
            @php
                $user = Auth::user();
            @endphp

            @if ($user && $user->foto)
                <img src="{{ asset('storage/' . $user->foto) }}" alt="Avatar"
                    class="h-9 w-9 rounded-full object-cover">
            @else
                <div class="h-9 w-9 rounded-full bg-zinc-200 flex items-center justify-center">
                    <i data-lucide="user" class="h-5 w-5 text-zinc-500"></i>
                </div>
            @endif
            <div class="text-xs sidebar-text group-[.collapsed]:hidden whitespace-nowrap">
                <div class="font-medium text-zinc-900">
                    {{ $user->name ?? 'Admin' }}</div>
                <div class="text-zinc-500">
                    {{ $user && $user->role ? $user->role->role_name : 'Administrator' }}
                </div>
            </div>
        </div>
    </div>
</aside>
