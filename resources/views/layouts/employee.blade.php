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

    <form id="logout-form-employee" action="{{ route('employee.logout') }}" method="POST" class="hidden">
        @csrf
    </form>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>

</html>
