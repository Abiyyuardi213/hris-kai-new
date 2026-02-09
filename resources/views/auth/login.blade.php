<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT KAI - Login Admin</title>

    <!-- PWA / Mobile Capable -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="HRIS KAI">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('image/logo-kai.jpg') }}?v=1.0">
    <link rel="icon" type="image/jpeg" href="{{ asset('image/logo-kai.jpg') }}?v=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-down {
            animation: slideDown 0.4s ease-out forwards;
        }
    </style>
</head>

<body>
    <div
        class="container relative min-h-screen flex-col items-center justify-center md:grid md:max-w-none md:grid-cols-2 md:px-0">
        <!-- Login Form Section -->
        <div class="flex h-full items-center justify-center p-8 bg-zinc-50">
            <div class="mx-auto flex w-full flex-col justify-center space-y-8 sm:w-[400px]">

                <!-- Logo & Header -->
                <div class="flex flex-col items-center">
                    <div class="mb-4">
                        <img src="{{ asset('image/logo-kai.png') }}" alt="Logo KAI" class="h-12 w-auto">
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Portal Admin</h1>
                    <p class="text-sm text-zinc-500 mt-2 text-center">
                        Masuk ke dashboard panel admin
                    </p>
                </div>

                <!-- Login Card -->
                <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                    <div class="p-8">
                        <form action="{{ route('login') }}" method="POST" class="space-y-6">
                            @csrf

                            <div>
                                <label for="email" class="block text-sm font-medium text-zinc-900 mb-1.5">Email
                                    Address</label>
                                <div class="relative">
                                    <i data-lucide="mail"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', $saved_email ?? '') }}" required autofocus
                                        placeholder="nama@example.com" autocomplete="username"
                                        class="block w-full rounded-lg border border-zinc-300 pl-10 pr-3 py-2.5 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all">
                                </div>
                            </div>

                            <div>
                                <label for="password"
                                    class="block text-sm font-medium text-zinc-900 mb-1.5">Password</label>
                                <div class="relative">
                                    <i data-lucide="lock"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                                    <input type="password" name="password" id="password" required
                                        placeholder="Masukkan password anda" autocomplete="current-password"
                                        class="block w-full rounded-lg border border-zinc-300 pl-10 pr-12 py-2.5 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all">
                                    <button type="button" onclick="togglePassword()"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-zinc-400 hover:text-zinc-900 transition-colors">
                                        <i data-lucide="eye" id="eye-icon" class="h-4 w-4"></i>
                                    </button>
                                </div>
                            </div>

                            @if ($errors->any())
                                <div
                                    class="flex gap-3 rounded-lg bg-red-50 p-4 text-sm text-red-600 border border-red-100">
                                    <i data-lucide="alert-circle" class="h-5 w-5 shrink-0"></i>
                                    <div>
                                        @foreach ($errors->all() as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="remember"
                                        {{ old('remember') || isset($saved_email) ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900">
                                    <span class="text-sm text-zinc-600">Ingat saya</span>
                                </label>
                            </div>

                            <button type="submit"
                                class="w-full flex justify-center items-center gap-2 rounded-lg bg-zinc-900 px-4 py-3 text-sm font-bold text-white hover:bg-zinc-800 transition-all active:scale-[0.98] shadow-sm">
                                Masuk
                                <i data-lucide="log-in" class="h-4 w-4"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Footer Text -->
                <div class="text-center text-sm text-zinc-500">
                    &copy; {{ date('Y') }} PT Kereta Api Indonesia (Persero)
                </div>
            </div>
        </div>

        <!-- Right Side Image Section -->
        <div class="relative hidden h-full flex-col bg-muted p-10 text-white dark:border-r lg:flex">
            <div class="absolute inset-0 bg-zinc-900"
                style="background-image: url('{{ asset('image/kai2.jpg') }}'); background-size: cover; background-position: center;">
            </div>
            <div class="absolute inset-0 bg-black/50"></div>
            <div class="relative z-20 flex items-center text-lg font-medium">
                <img src="{{ asset('image/logo-kai.png') }}" class="mr-2 h-8 w-auto" alt="Logo">
            </div>
            <div class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <p class="text-lg">
                        "Sistem Informasi Sumber Daya Manusia Terintegrasi untuk efisiensi dan keunggulan operasional."
                    </p>
                    <footer class="text-sm">Human Resources PT. Kereta Api Indonesia (Persero)</footer>
                </blockquote>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container"
        class="fixed top-5 left-1/2 -translate-x-1/2 z-50 flex flex-col gap-3 w-full max-w-xs px-4 sm:px-0">
        @if (session('success'))
            <div class="toast animate-slide-down flex items-center w-full p-4 space-x-4 text-gray-500 bg-white rounded-xl shadow-lg dark:text-gray-400 dark:bg-gray-800 border-l-4 border-green-500"
                role="alert">
                <div
                    class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                </div>
                <div class="ml-3 text-sm font-normal text-gray-800 dark:text-gray-100">{{ session('success') }}</div>
                <button type="button"
                    class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-success" aria-label="Close" onclick="this.parentElement.remove()">
                    <span class="sr-only">Close</span>
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="toast animate-slide-down flex items-center w-full p-4 space-x-4 text-gray-500 bg-white rounded-xl shadow-lg dark:text-gray-400 dark:bg-gray-800 border-l-4 border-red-500"
                role="alert">
                <div
                    class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                </div>
                <div class="ml-3 text-sm font-normal text-gray-800 dark:text-gray-100">{{ session('error') }}</div>
                <button type="button"
                    class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-danger" aria-label="Close" onclick="this.parentElement.remove()">
                    <span class="sr-only">Close</span>
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif
    </div>

    <script>
        lucide.createIcons();

        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }

        // Auto dismiss toast after 4 seconds
        document.addEventListener('DOMContentLoaded', () => {
            const toasts = document.querySelectorAll('.toast');
            if (toasts.length > 0) {
                setTimeout(() => {
                    toasts.forEach(t => {
                        t.style.transition = 'opacity 0.5s ease-out';
                        t.style.opacity = '0';
                        setTimeout(() => t.remove(), 500);
                    });
                }, 4000);
            }
        });
    </script>
</body>

</html>
