<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT KAI - Registrasi Rekrutmen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-zinc-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-white rounded-xl shadow-lg border border-zinc-200 overflow-hidden">
        <div class="p-6 border-b border-zinc-100 flex items-center gap-4">
            <img src="{{ asset('image/logo-kai.png') }}" alt="Logo KAI" class="h-10 w-auto">
            <div>
                <h1 class="text-lg font-bold text-zinc-900 uppercase">Form Registrasi Peserta Rekrutmen</h1>
                <p class="text-xs text-zinc-500">Peserta diwajibkan registrasi sebelum apply lowongan</p>
            </div>
        </div>

        <form action="{{ route('candidate.register.submit') }}" method="POST" class="px-8 py-4 space-y-3">
            @csrf
            
            @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-3">
                <!-- Nomor Identitas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-zinc-600">Nomor Identitas :</label>
                    <div class="md:col-span-2 relative">
                        <input type="text" name="identity_number" value="{{ old('identity_number') }}" required placeholder="Masukkan KTP/SIM/Passport"
                            class="w-full h-11 border border-zinc-200 rounded-lg px-4 pr-10 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <i data-lucide="file-text" class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                    </div>
                </div>

                <!-- Nama -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-zinc-600">Nama :</label>
                    <div class="md:col-span-2 relative">
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap"
                            class="w-full h-11 border border-zinc-200 rounded-lg px-4 pr-10 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <i data-lucide="user" class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                    </div>
                </div>

                <!-- Nomor Telepon -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-zinc-600">Nomor Telepon :</label>
                    <div class="md:col-span-2 relative">
                        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="Masukkan nomor telepon"
                            class="w-full h-11 border border-zinc-200 rounded-lg px-4 pr-10 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <i data-lucide="phone" class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                    </div>
                </div>

                <!-- Email -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-zinc-600">Email :</label>
                    <div class="md:col-span-2 relative">
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="Masukkan alamat email"
                            class="w-full h-11 border border-zinc-200 rounded-lg px-4 pr-10 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <i data-lucide="mail" class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                    </div>
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-zinc-600">Password :</label>
                    <div class="md:col-span-2 relative">
                        <input type="password" name="password" required placeholder="Masukkan kata sandi"
                            class="w-full h-11 border border-zinc-200 rounded-lg px-4 pr-10 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <i data-lucide="lock" class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                    </div>
                </div>

                <!-- Re-Type Password -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 items-center">
                    <label class="text-sm font-medium text-zinc-600">Re-Type Password :</label>
                    <div class="md:col-span-2 relative">
                        <input type="password" name="password_confirmation" required placeholder="Masukkan ulang kata sandi"
                            class="w-full h-11 border border-zinc-200 rounded-lg px-4 pr-10 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <i data-lucide="lock" class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                    </div>
                </div>

                <!-- Kode Verifikasi -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 items-center pt-2">
                    <label class="text-sm font-medium text-zinc-600">Kode Verifikasi</label>
                    <div class="md:col-span-2 flex gap-3">
                        <div class="h-11 px-4 bg-indigo-600 rounded flex items-center justify-center text-white font-bold tracking-widest italic shadow-sm select-none">
                            {{ $captcha }}
                        </div>
                        <div class="relative flex-1">
                            <input type="text" name="verification_code" required placeholder="Masukkan kode"
                                class="w-full h-11 border border-zinc-200 rounded-lg px-4 pr-10 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            <i data-lucide="key" class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-center gap-3 pt-6 border-t border-zinc-50 text-center">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-blue-600 text-white font-bold text-sm rounded shadow-lg hover:bg-blue-700 transition-all uppercase">
                    <i data-lucide="check-circle" class="h-4 w-4"></i>
                    Daftar Sekarang
                </button>
                <button type="reset" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-white border border-zinc-200 text-zinc-700 font-bold text-sm rounded hover:bg-zinc-50 transition-all uppercase">
                    <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                    Reset
                </button>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-xs text-zinc-500">Sudah punya akun? <a href="{{ route('candidate.login') }}" class="text-blue-600 font-bold hover:underline">Login disini</a></p>
            </div>
        </form>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
