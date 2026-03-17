<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>e-Recruitment PT KAI - @yield('title', 'Portal Pelamar')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-[#F8FAFC]">
    <!-- Main Header (Full Width) -->
    <x-navbar-candidate />

    <!-- Container for Sidebar + Content -->
    <div class="max-w-[1400px] mx-auto p-6 md:p-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar Area -->
            <div class="w-full md:w-[380px] shrink-0">
                <x-sidebar-candidate />
            </div>

            <!-- Content Area -->
            <main class="flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast Notification -->
    @if(session('success') || session('error'))
    <div id="toast-container" class="fixed top-6 right-6 z-[9999] animate-in fade-in slide-in-from-right-8 duration-500">
        <div class="flex items-center gap-3 bg-white border border-zinc-200 shadow-2xl rounded-2xl px-6 py-4 min-w-[320px]">
            <div class="h-10 w-10 rounded-full {{ session('success') ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }} flex items-center justify-center shrink-0">
                <i data-lucide="{{ session('success') ? 'check-circle' : 'alert-circle' }}" class="h-6 w-6"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-zinc-900">{{ session('success') ? 'Berhasil' : 'Kesalahan' }}</p>
                <p class="text-xs font-medium text-zinc-500 mt-0.5">{{ session('success') ?? session('error') }}</p>
            </div>
            <button onclick="document.getElementById('toast-container').remove()" class="text-zinc-300 hover:text-zinc-500 transition-colors">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>
        </div>
    </div>
    @endif

    <script>
        lucide.createIcons();

        // Auto hide toast after 5 seconds
        const toast = document.getElementById('toast-container');
        if (toast) {
            setTimeout(() => {
                toast.classList.add('animate-out', 'fade-out', 'slide-out-to-right-8');
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }
    </script>
    @stack('scripts')
</body>

</html>
