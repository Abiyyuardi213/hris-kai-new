<header
    class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b bg-white/80 backdrop-blur-md px-6 shadow-sm">
    <div></div>

    <div class="flex items-center gap-4">
        <div class="relative group">
            <button
                class="flex items-center gap-2 rounded-full border border-zinc-200 p-1 pr-3 hover:bg-zinc-50 transition-colors">
                @if (Auth::guard('employee')->user()->foto)
                    <img src="{{ asset('storage/' . Auth::guard('employee')->user()->foto) }}" alt="Avatar"
                        class="h-8 w-8 rounded-full object-cover">
                @else
                    <div class="h-8 w-8 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-400">
                        <i data-lucide="user" class="h-4 w-4"></i>
                    </div>
                @endif
                <span
                    class="text-xs font-bold text-zinc-700 hidden sm:block">{{ explode(' ', Auth::guard('employee')->user()->nama_lengkap)[0] }}</span>
            </button>
            <div
                class="hidden group-hover:block absolute right-0 mt-0 w-48 origin-top-right rounded-xl bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none z-50 overflow-hidden border border-zinc-100">
                <div class="px-4 py-3 bg-zinc-50 border-b border-zinc-100">
                    <p class="text-xs font-medium text-zinc-500">Masuk sebagai</p>
                    <p class="text-sm font-bold text-zinc-900 truncate">
                        {{ Auth::guard('employee')->user()->nama_lengkap }}</p>
                </div>
                <div class="py-1">
                    <a href="{{ route('employee.profile') }}"
                        class="flex items-center gap-2 px-4 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors">
                        <i data-lucide="user-cog" class="h-4 w-4 text-zinc-400"></i>
                        Edit Profil
                    </a>
                    <hr class="border-zinc-100">
                    <button type="button" onclick="document.getElementById('logout-form-employee').submit();"
                        class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <i data-lucide="log-out" class="h-4 w-4"></i>
                        Keluar
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
