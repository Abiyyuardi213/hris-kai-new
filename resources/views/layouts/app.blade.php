<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GFI HRIS - Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-white">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Content -->
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <!-- Header -->
            <x-navbar-sistem />

            <!-- Content -->
            <main class="w-full flex-grow p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @include('services.LogoutModal')
    <x-toast />

    <script>
        lucide.createIcons();

        // Responsive Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            function toggleSidebar() {
                if (window.innerWidth >= 768) {
                    // Desktop Behavior: Toggle Visibility Completely
                    sidebar.classList.toggle('collapsed');
                    if (sidebar.classList.contains('collapsed')) {
                        sidebar.classList.remove('w-64');
                        sidebar.classList.add('w-0');
                        sidebar.classList.add('border-none'); // Hide border when collapsed
                    } else {
                        sidebar.classList.remove('w-0');
                        sidebar.classList.remove('border-none');
                        sidebar.classList.add('w-64');
                    }
                } else {
                    // Mobile Behavior: Toggle Off-canvas
                    if (sidebar.classList.contains('-translate-x-full')) {
                        sidebar.classList.remove('-translate-x-full');
                        sidebar.classList.add('translate-x-0');
                        sidebarOverlay.classList.remove('hidden');
                    } else {
                        sidebar.classList.add('-translate-x-full');
                        sidebar.classList.remove('translate-x-0');
                        sidebarOverlay.classList.add('hidden');
                    }
                }
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', toggleSidebar);
            }
        });
    </script>
</body>

</html>
