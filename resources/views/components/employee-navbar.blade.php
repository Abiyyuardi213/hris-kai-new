<header
    class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b bg-white/80 backdrop-blur-md px-4 md:px-8 shadow-sm">
    <div class="flex items-center gap-4">
        <!-- Sidebar Toggle -->
        <button onclick="toggleEmployeeSidebar()"
            class="flex md:hidden h-10 w-10 items-center justify-center rounded-xl border border-zinc-100 bg-white text-zinc-600 hover:bg-zinc-50 transition-all active:scale-95">
            <i data-lucide="menu" class="h-5 w-5"></i>
        </button>

        <div class="hidden md:block">
            <h1 class="text-sm font-bold text-zinc-900">Portal Pegawai PT KAI</h1>
            <p class="text-[10px] text-zinc-500 font-medium">Sistem Monitoring & Presensi Mandiri</p>
        </div>
        <div class="md:hidden">
            <img src="{{ asset('image/logo-kai.png') }}" alt="Logo" class="h-7 w-auto">
        </div>
    </div>

    <div class="flex items-center gap-3">
        <!-- Profile Dropdown -->
        <div class="relative">
            <button onclick="toggleProfileDropdown(event)"
                class="flex items-center gap-2 rounded-2xl border border-zinc-100 bg-white p-1.5 pr-4 hover:shadow-md transition-all active:scale-95">
                @if (Auth::guard('employee')->user()->foto)
                    <img src="{{ asset('storage/' . Auth::guard('employee')->user()->foto) }}" alt="Avatar"
                        class="h-8 w-8 rounded-xl object-cover">
                @else
                    <div class="h-8 w-8 rounded-xl bg-zinc-50 flex items-center justify-center text-zinc-400">
                        <i data-lucide="user" class="h-4 w-4"></i>
                    </div>
                @endif
                <div class="hidden sm:flex flex-col items-start translate-y-[-1px]">
                    <span class="text-xs font-bold text-zinc-900 leading-none mb-0.5">
                        {{ explode(' ', Auth::guard('employee')->user()->nama_lengkap)[0] }}
                    </span>
                    <span
                        class="text-[9px] font-bold text-emerald-600 uppercase tracking-wider leading-none">Online</span>
                </div>
            </button>

            <!-- Dropdown Menu -->
            <div id="profileDropdown"
                class="invisible absolute right-0 mt-3 w-56 origin-top-right rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 focus:outline-none z-50 overflow-hidden border border-zinc-50 transition-all duration-200 opacity-0 translate-y-2">
                <div class="px-5 py-4 bg-zinc-900 text-white">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Masuk Sebagai</p>
                    <p class="text-sm font-bold truncate">{{ Auth::guard('employee')->user()->nama_lengkap }}</p>
                    <p class="text-[11px] text-zinc-400 truncate">{{ Auth::guard('employee')->user()->nip }}</p>
                </div>
                <div class="p-2">
                    <a href="{{ route('employee.profile') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 rounded-xl transition-colors">
                        <i data-lucide="user-cog" class="h-5 w-5 text-zinc-400"></i>
                        Pengaturan Profil
                    </a>
                    <hr class="my-1 border-zinc-100">
                    <button type="button" onclick="showLogoutModal();"
                        class="flex w-full items-center gap-3 px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                        <i data-lucide="log-out" class="h-5 w-5"></i>
                        Keluar Aplikasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleProfileDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('profileDropdown');
        const isVisible = !dropdown.classList.contains('invisible');

        if (isVisible) {
            dropdown.classList.add('invisible', 'opacity-0', 'translate-y-2');
            dropdown.classList.remove('visible', 'opacity-100', 'translate-y-0');
        } else {
            dropdown.classList.remove('invisible', 'opacity-0', 'translate-y-2');
            dropdown.classList.add('visible', 'opacity-100', 'translate-y-0');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        const profileButton = dropdown.previousElementSibling;

        if (!dropdown.contains(event.target) && !profileButton.contains(event.target)) {
            dropdown.classList.add('invisible', 'opacity-0', 'translate-y-2');
            dropdown.classList.remove('visible', 'opacity-100', 'translate-y-0');
        }
    });
</script>
