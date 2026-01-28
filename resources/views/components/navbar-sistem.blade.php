<header
    class="sticky top-0 z-30 flex h-14 shrink-0 items-center gap-4 border-b bg-white/80 backdrop-blur-md px-6 lg:h-[60px]">
    <div class="flex-1">
        <button id="sidebarToggle" class="p-2 text-zinc-500 hover:text-zinc-900 focus:outline-none">
            <i data-lucide="menu" class="h-6 w-6"></i>
        </button>
    </div>

    <div class="flex items-center gap-4">
        <button class="relative rounded-full bg-zinc-100 p-2 text-zinc-500 hover:text-zinc-900">
            <i data-lucide="bell" class="h-5 w-5"></i>
            <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
        </button>

        <div class="relative">
            <button onclick="toggleAccountDropdown(event)"
                class="flex items-center gap-2 rounded-full bg-zinc-100 px-3 py-1.5 text-sm font-medium text-zinc-500 hover:text-zinc-900">
                <span>Account</span>
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
        dropdown.classList.toggle('hidden');
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('accountDropdown');
        const accountButton = dropdown.previousElementSibling;

        if (!dropdown.contains(event.target) && !accountButton.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
