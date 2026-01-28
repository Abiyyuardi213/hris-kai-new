<header
    class="sticky top-0 z-30 flex h-14 shrink-0 items-center gap-4 border-b bg-white/80 backdrop-blur-md px-6 lg:h-[60px]">
    <div class="flex-1">
        <button id="sidebarToggle" class="p-2 text-zinc-500 hover:text-zinc-900 focus:outline-none">
            <i data-lucide="menu" class="h-6 w-6"></i>
        </button>
    </div>

    <div class="flex items-center gap-4">
        <!-- Notification Dropdown -->
        <div class="relative">
            <button onclick="toggleNotificationDropdown(event)"
                class="relative rounded-full bg-zinc-100 p-2 text-zinc-500 hover:text-zinc-900 transition-all active:scale-95">
                <i data-lucide="bell" class="h-5 w-5"></i>
                @if (Auth::user()->unreadNotifications->count() > 0)
                    <span
                        class="absolute top-0 right-0 h-4 min-w-[1rem] flex items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white px-1">
                        {{ Auth::user()->unreadNotifications->count() }}
                    </span>
                @endif
            </button>

            <!-- Notification Menu -->
            <div id="notificationDropdown"
                class="hidden fixed sm:absolute inset-x-4 sm:inset-auto sm:right-0 sm:mt-3 sm:w-80 top-16 sm:top-auto origin-top-right rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 focus:outline-none z-50 overflow-hidden border border-zinc-50">
                <div class="px-5 py-4 bg-zinc-900 text-white flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold">Notifikasi</h3>
                        <p class="text-[10px] text-zinc-400 font-medium">Anda memiliki
                            {{ Auth::user()->unreadNotifications->count() }} pesan baru</p>
                    </div>
                    @if (Auth::user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="text-[10px] font-bold text-zinc-400 hover:text-white transition-colors">
                                Tandai Semua Dibaca
                            </button>
                        </form>
                    @endif
                </div>
                <div class="max-h-96 overflow-y-auto divide-y divide-zinc-50">
                    @forelse(Auth::user()->unreadNotifications->take(5) as $notif)
                        <a href="{{ route('notifications.read', $notif->id) }}"
                            class="flex items-start gap-4 px-5 py-4 hover:bg-zinc-50 transition-colors">
                            <div
                                class="mt-1 h-8 w-8 rounded-xl flex items-center justify-center shrink-0 {{ $notif->data['type'] == 'success' ? 'bg-emerald-50 text-emerald-600' : ($notif->data['type'] == 'warning' ? 'bg-orange-50 text-orange-600' : 'bg-blue-50 text-blue-600') }}">
                                <i data-lucide="{{ $notif->data['icon'] ?? 'bell' }}" class="h-4 w-4"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-zinc-900">{{ $notif->data['title'] }}</p>
                                <p class="text-[11px] text-zinc-500 line-clamp-2 mt-0.5">{{ $notif->data['message'] }}
                                </p>
                                <p class="text-[9px] text-zinc-400 mt-1 font-medium">
                                    {{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="px-5 py-12 text-center text-zinc-500 italic">
                            <i data-lucide="bell-off" class="h-10 w-10 mx-auto mb-2 text-zinc-200"></i>
                            <p class="text-xs font-medium">Tidak ada notifikasi baru</p>
                        </div>
                    @endforelse
                </div>
                @if (Auth::user()->notifications->count() > 0)
                    <div class="p-2 border-t border-zinc-50 bg-zinc-50/50">
                        <button
                            class="w-full py-2 text-[11px] font-bold text-zinc-500 hover:text-zinc-900 transition-colors text-center">
                            Lihat Semua Notifikasi
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="relative">
            <button onclick="toggleAccountDropdown(event)"
                class="flex items-center gap-2 rounded-full bg-zinc-100 px-3 py-1.5 text-sm font-medium text-zinc-500 hover:text-zinc-900">
                <span class="max-w-[100px] truncate">{{ explode(' ', Auth::user()->name)[0] }}</span>
                <i data-lucide="chevron-down" class="h-4 w-4"></i>
            </button>

            <div id="accountDropdown"
                class="hidden absolute right-0 mt-0 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                <div class="py-1">
                    <a href="{{ route('profile.edit') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                    <hr class="my-1 border-gray-100">
                    <button type="button" data-toggle="modal" data-target="#logoutModal"
                        onclick="document.getElementById('logoutModal').classList.remove('hidden');"
                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleAccountDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('accountDropdown');
        const notifDropdown = document.getElementById('notificationDropdown');

        notifDropdown.classList.add('hidden');
        dropdown.classList.toggle('hidden');
    }

    function toggleNotificationDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('notificationDropdown');
        const accountDropdown = document.getElementById('accountDropdown');

        accountDropdown.classList.add('hidden');
        dropdown.classList.toggle('hidden');
    }

    document.addEventListener('click', function(event) {
        const accountDropdown = document.getElementById('accountDropdown');
        const accountButton = accountDropdown.previousElementSibling;

        const notifDropdown = document.getElementById('notificationDropdown');
        const notifButton = notifDropdown.previousElementSibling;

        if (!accountDropdown.contains(event.target) && !accountButton.contains(event.target)) {
            accountDropdown.classList.add('hidden');
        }

        if (!notifDropdown.contains(event.target) && !notifButton.contains(event.target)) {
            notifDropdown.classList.add('hidden');
        }
    });
</script>
