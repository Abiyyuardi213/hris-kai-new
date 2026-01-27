@extends(Auth::guard('web')->check() ? 'layouts.app' : 'layouts.employee')

@section('title', 'Edit Profil')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Pengaturan Profil</h2>
            <p class="text-zinc-500 hidden md:block">Kelola informasi pribadi dan keamanan akun Anda.</p>
        </div>

        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center gap-3 max-w-2xl">
                <i data-lucide="check-circle" class="h-5 w-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Profile Summary Card -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-xl border border-zinc-200 p-6 shadow-sm flex flex-col items-center text-center">
                    <div class="relative group">
                        <div
                            class="h-32 w-32 rounded-full overflow-hidden border-4 border-zinc-50 bg-zinc-100 mb-4 shadow-inner">
                            @if ($user->foto)
                                <img src="{{ asset('storage/' . $user->foto) }}" alt="Profile"
                                    class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center">
                                    <i data-lucide="user" class="h-16 w-16 text-zinc-300"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900">{{ $guard === 'web' ? $user->name : $user->nama_lengkap }}
                    </h3>
                    <p class="text-sm text-zinc-500">{{ $guard === 'web' ? $user->email : $user->nip }}</p>
                    <div
                        class="mt-4 px-3 py-1 rounded-full bg-zinc-100 text-[10px] font-bold uppercase tracking-wider text-zinc-600">
                        {{ $guard === 'web' ? ($user->role ? $user->role->role_name : 'Admin') : ($user->jabatan ? $user->jabatan->name : 'Pegawai') }}
                    </div>
                </div>

                <div class="bg-zinc-50 border border-zinc-200 rounded-xl p-5">
                    <div class="flex gap-3 text-zinc-600">
                        <i data-lucide="info" class="h-5 w-5 shrink-0"></i>
                        <div class="text-xs leading-relaxed">
                            <p class="font-bold mb-1 text-zinc-900">Catatan Keamanan</p>
                            <p>Pastikan email dan nomor handphone Anda tetap aktif untuk keperluan notifikasi sistem dan
                                pemulihan akun.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="lg:col-span-8">
                <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                    <form action="{{ $guard === 'web' ? route('profile.update') : route('employee.profile.update') }}"
                        method="POST" enctype="multipart/form-data" class="divide-y divide-zinc-100">
                        @csrf

                        <div class="p-6 md:p-8 space-y-8">
                            <div>
                                <h3 class="text-base font-bold text-zinc-900 mb-6 flex items-center gap-2">
                                    <i data-lucide="user-cog" class="h-5 w-5 text-zinc-400"></i>
                                    Informasi Dasar
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @if ($guard === 'web')
                                        <div class="md:col-span-2 space-y-2">
                                            <label class="text-sm font-medium text-zinc-900">Nama</label>
                                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                                class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 transition-all outline-none @error('name') border-red-500 @enderror">
                                            @error('name')
                                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="md:col-span-2 space-y-2">
                                            <label class="text-sm font-medium text-zinc-900">Email Utama</label>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                                class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 transition-all outline-none @error('email') border-red-500 @enderror">
                                            @error('email')
                                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @else
                                        <div class="md:col-span-2 space-y-2">
                                            <label class="text-sm font-medium text-zinc-900">Nama Lengkap</label>
                                            <input type="text" name="nama_lengkap"
                                                value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                                                class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 transition-all outline-none @error('nama_lengkap') border-red-500 @enderror">
                                            @error('nama_lengkap')
                                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-zinc-900">Email Pribadi</label>
                                            <input type="email" name="email_pribadi"
                                                value="{{ old('email_pribadi', $user->email_pribadi) }}"
                                                class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 transition-all outline-none @error('email_pribadi') border-red-500 @enderror">
                                            @error('email_pribadi')
                                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif

                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-zinc-900">Nomor Handphone</label>
                                        <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                                            class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 transition-all outline-none @error('no_hp') border-red-500 @enderror">
                                        @error('no_hp')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2 space-y-2">
                                        <label class="text-sm font-medium text-zinc-900 text-left block">Foto Profil</label>
                                        <div
                                            class="mt-1 flex items-center gap-4 p-4 rounded-lg border border-zinc-100 bg-zinc-50/50">
                                            <input type="file" name="foto"
                                                class="block w-full text-xs text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-zinc-900 file:text-white hover:file:bg-zinc-800 transition-all">
                                        </div>
                                        @error('foto')
                                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-base font-bold text-zinc-900 mb-6 flex items-center gap-2">
                                    <i data-lucide="lock" class="h-5 w-5 text-zinc-400"></i>
                                    Keamanan Akun
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-zinc-900">Kata Sandi Baru</label>
                                        <input type="password" name="password" placeholder="Minimal 8 karakter"
                                            class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 transition-all outline-none">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-zinc-900">Konfirmasi Kata Sandi</label>
                                        <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi"
                                            class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 transition-all outline-none">
                                    </div>
                                </div>
                                <p class="text-[11px] text-zinc-400 mt-3 italic">* Kosongkan jika tidak ingin mengubah kata
                                    sandi.</p>
                                @error('password')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="bg-zinc-50/50 px-6 md:px-8 py-5 flex justify-end gap-3 border-t">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center justify-center rounded-lg border border-zinc-200 bg-white px-6 py-2 target-sm text-sm font-semibold text-zinc-900 hover:bg-zinc-50 transition-colors">Batal</a>
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-8 py-2 text-sm font-bold text-white shadow-sm hover:bg-zinc-800 transition-all active:scale-[0.98]">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
