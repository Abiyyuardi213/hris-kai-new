<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pegawai - PT KAI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-zinc-50 min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-[420px] space-y-8">
        <!-- Logo & Header -->
        <div class="flex flex-col items-center">
            <div class="mb-4">
                <img src="{{ asset('image/logo-kai.png') }}" alt="Logo KAI" class="h-10 w-auto">
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Portal Pegawai</h1>
            <p class="text-sm text-zinc-500 mt-2 text-center">Sistem Informasi Kepegawaian PT Kereta Api Indonesia
                (Persero)</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
            <div class="p-8">
                <form action="{{ route('employee.login') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="nip" class="block text-sm font-medium text-zinc-900 mb-1.5">Nomor Induk Pegawai
                            (NIP)</label>
                        <div class="relative">
                            <i data-lucide="user-square-2"
                                class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                            <input type="text" name="nip" id="nip" value="{{ old('nip') }}" required
                                autofocus placeholder="Masukkan NIP Anda"
                                class="block w-full rounded-lg border border-zinc-300 pl-10 pr-3 py-2.5 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all">
                        </div>
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-zinc-900 mb-1.5">Tanggal Lahir
                            (Password)</label>
                        <div class="relative">
                            <i data-lucide="key-round"
                                class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                            <input type="password" name="tanggal_lahir" id="tanggal_lahir" required
                                placeholder="YYYY-MM-DD"
                                class="block w-full rounded-lg border border-zinc-300 pl-10 pr-12 py-2.5 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all">
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-zinc-400 hover:text-zinc-900 transition-colors">
                                <i data-lucide="eye" id="eye-icon" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="flex gap-3 rounded-lg bg-red-50 p-4 text-sm text-red-600 border border-red-100">
                            <i data-lucide="alert-circle" class="h-5 w-5 shrink-0"></i>
                            <p>{{ $errors->first() }}</p>
                        </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember"
                                class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900">
                            <span class="text-sm text-zinc-600">Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full flex justify-center items-center gap-2 rounded-lg bg-zinc-900 px-4 py-3 text-sm font-bold text-white hover:bg-zinc-800 transition-all active:scale-[0.98] shadow-sm">
                        Masuk Ke Akun
                        <i data-lucide="log-in" class="h-4 w-4"></i>
                    </button>
                </form>
            </div>

            <!-- Footer Card -->
            <div class="bg-zinc-50 border-t border-zinc-100 p-4 text-center">
                <p class="text-xs text-zinc-500">
                    Gunakan tanggal lahir sebagai kata sandi standar Anda.
                </p>
            </div>
        </div>

        <!-- Back to Admin -->
        <div class="text-center">
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali ke Login Admin
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function togglePassword() {
            const input = document.getElementById('tanggal_lahir');
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
    </script>
</body>

</html>
