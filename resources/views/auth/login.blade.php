<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT KAI - Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body>
    <div
        class="container relative min-h-screen flex-col items-center justify-center md:grid md:max-w-none md:grid-cols-2 md:px-0">
        <div class="flex h-full items-center justify-center p-8 bg-white">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <div class="flex flex-col space-y-2 text-center">
                    <h1 class="text-2xl font-semibold tracking-tight">Login Akun</h1>
                    <p class="text-sm text-gray-500">
                        Masukkan email dan password anda untuk masuk
                    </p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 text-red-500 p-3 rounded-md text-sm">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid gap-6">
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="grid gap-4">
                            <div class="grid gap-2">
                                <label class="text-sm font-medium leading-none" for="email">
                                    Email
                                </label>
                                <input
                                    class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    id="email" name="email" placeholder="nama@example.com" type="email"
                                    autocapitalize="none" autocomplete="email" autocorrect="off"
                                    value="{{ old('email') }}" required>
                            </div>
                            <div class="grid gap-2">
                                <label class="text-sm font-medium leading-none" for="password">
                                    Password
                                </label>
                                <input
                                    class="flex h-10 w-full rounded-md border border-input bg-white px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    id="password" name="password" type="password" required>
                            </div>
                            <button
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-[#0f172a] text-white hover:bg-[#0f172a]/90 h-10 px-4 py-2 w-full"
                                type="submit">
                                Masuk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
</body>

</html>
