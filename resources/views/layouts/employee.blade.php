<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pegawai - @yield('title', 'PT KAI')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Mobile height adjustment for bottom nav */
        @media (max-width: 767px) {
            .main-content {
                padding-bottom: 5rem;
            }
        }
    </style>
    @stack('styles')
</head>

<body class="bg-zinc-50 border-zinc-100">
    <div class="flex min-h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-employee-sidebar />

        <!-- Main Content Area -->
        <div
            class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden md:ml-64 transition-all duration-300">
            <!-- Header/Navbar -->
            <x-employee-navbar />

            <!-- Content -->
            <main class="w-full flex-grow p-4 md:p-8 main-content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Modals & Toasts -->
    <x-toast />

    <!-- Logout Confirmation Modal -->
    <div id="logout-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div id="logout-modal-overlay"
                class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm transition-opacity opacity-0" aria-hidden="true">
            </div>

            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

            <!-- Modal Content -->
            <div id="logout-modal-content"
                class="relative inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:align-middle opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="bg-white px-6 pt-8 pb-6 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-50 mb-4">
                        <i data-lucide="log-out" class="h-8 w-8 text-red-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 mb-2" id="modal-title">Konfirmasi Keluar</h3>
                    <p class="text-zinc-500 text-sm">Apakah Anda yakin ingin keluar dari aplikasi portal pegawai?</p>
                </div>
                <div class="bg-zinc-50 px-6 py-4 flex flex-col gap-2">
                    <button type="button" onclick="document.getElementById('logout-form-employee').submit();"
                        class="inline-flex w-full justify-center rounded-xl bg-red-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-red-200 hover:bg-red-700 transition-all">
                        Ya, Keluar Sekarang
                    </button>
                    <button type="button" onclick="closeLogoutModal()"
                        class="inline-flex w-full justify-center rounded-xl bg-white border border-zinc-200 px-4 py-3 text-sm font-bold text-zinc-700 hover:bg-zinc-50 transition-all">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form-employee" action="{{ route('employee.logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <script>
        lucide.createIcons();

        function showLogoutModal() {
            const modal = document.getElementById('logout-modal');
            const overlay = document.getElementById('logout-modal-overlay');
            const content = document.getElementById('logout-modal-content');

            modal.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                content.classList.remove('opacity-0', 'translate-y-4', 'sm:scale-95');
                content.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logout-modal');
            const overlay = document.getElementById('logout-modal-overlay');
            const content = document.getElementById('logout-modal-content');

            overlay.classList.add('opacity-0');
            content.classList.add('opacity-0', 'translate-y-4', 'sm:scale-95');
            content.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Close on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeLogoutModal();
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
