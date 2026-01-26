<!-- Overlay for mobile -->
<div id="sidebarOverlay" class="fixed inset-0 z-40 bg-black/50 hidden md:hidden transition-opacity"></div>

<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 flex-col border-r bg-white transition-all duration-300 ease-in-out -translate-x-full md:translate-x-0 md:static md:flex h-screen overflow-hidden group">
    <div
        class="flex h-14 items-center border-b px-4 lg:h-[60px] lg:px-6 transition-all duration-300 overflow-hidden whitespace-nowrap">
        <a href="/" class="flex items-center gap-2 font-semibold">
            <img src="{{ asset('image/logo-kai.png') }}" alt="Logo" class="h-8 w-auto">
        </a>
    </div>
    <div class="flex-1 overflow-x-hidden overflow-y-auto py-2">
        <nav class="grid items-start px-2 text-sm font-medium lg:px-4 space-y-1">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/dashboard*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                <span class="sidebar-text group-[.collapsed]:hidden">Dashboard</span>
            </a>

            @if (Auth::check() && Auth::user()->role && strtolower(Auth::user()->role->role_name) != 'pegawai')
                <div
                    class="mt-4 mb-2 px-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider sidebar-text group-[.collapsed]:hidden">
                    Master Data</div>

                <a href="{{ route('master.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/master-data*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                    <i data-lucide="database" class="h-4 w-4"></i>
                    <span class="sidebar-text group-[.collapsed]:hidden">Dashboard Master</span>
                </a>

                <a href="{{ route('role.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/role*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                    <i data-lucide="shield" class="h-4 w-4"></i>
                    <span class="sidebar-text group-[.collapsed]:hidden">Manajemen
                        Peran</span>
                </a>

                <a href="{{ route('users.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/users*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                    <i data-lucide="users" class="h-4 w-4"></i>
                    <span class="sidebar-text group-[.collapsed]:hidden">Manajemen
                        Users</span>
                </a>

                <a href="{{ route('cities.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/cities*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                    <i data-lucide="map-pin" class="h-4 w-4"></i>
                    <span class="sidebar-text group-[.collapsed]:hidden">Manajemen
                        Kota</span>
                </a>

                <div
                    class="mt-4 mb-2 px-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider sidebar-text group-[.collapsed]:hidden">
                    Master Office</div>

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

                <a href="{{ route('employee-shifts.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/employee-shifts*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                    <i data-lucide="calendar-clock" class="h-4 w-4"></i>
                    <span class="sidebar-text group-[.collapsed]:hidden">Jadwal Shift</span>
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

                <a href="{{ route('assets.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary {{ Request::is('admin/assets*') ? 'bg-zinc-200 text-black' : 'text-zinc-500' }}">
                    <i data-lucide="box" class="h-4 w-4"></i>
                    <span class="sidebar-text group-[.collapsed]:hidden">Manajemen Aset</span>
                </a>
            @endif
        </nav>
    </div>
    <div class="mt-auto border-t p-4 sidebar-footer transition-all duration-300">
        <div class="flex items-center gap-3">
            @if (Auth::check() && Auth::user()->foto)
                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Avatar"
                    class="h-9 w-9 rounded-full object-cover">
            @else
                <div class="h-9 w-9 rounded-full bg-zinc-200 flex items-center justify-center">
                    <i data-lucide="user" class="h-5 w-5 text-zinc-500"></i>
                </div>
            @endif
            <div class="text-xs sidebar-text group-[.collapsed]:hidden whitespace-nowrap">
                <div class="font-medium text-zinc-900">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</div>
                <div class="text-zinc-500">
                    {{ Auth::check() && Auth::user()->role ? Auth::user()->role->role_name : '-' }}</div>
            </div>
        </div>
    </div>
</aside>
