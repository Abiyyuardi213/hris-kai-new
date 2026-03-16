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

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>

</html>
