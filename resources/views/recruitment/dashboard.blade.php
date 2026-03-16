@extends('layouts.candidate')
@section('title', 'Informasi Biodata')

@section('content')
<div class="max-w-[720px] mx-auto space-y-6">
    <!-- Header Title -->
    <div class="flex flex-col mb-4">
        <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Informasi Biodata</h2>
        <p class="text-sm text-zinc-500 mt-2">Silakan lengkapi biodata diri Anda dengan benar untuk keperluan administrasi rekrutmen.</p>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="p-8">
            <form action="#" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <!-- Profile Picture Section -->
                <div class="flex flex-col items-center sm:items-start sm:flex-row gap-8 pb-8 border-b border-zinc-100">
                    <div class="relative group">
                        <div class="h-44 w-32 rounded-xl bg-zinc-900 overflow-hidden shadow-sm flex items-center justify-center border-4 border-white">
                            @if(Auth::guard('candidate')->user()->photo)
                                <img src="{{ asset('storage/' . Auth::guard('candidate')->user()->photo) }}" alt="Foto" class="w-full h-full object-cover">
                            @else
                                <div class="flex flex-col items-center text-white/30">
                                    <i data-lucide="user-square-2" class="h-12 w-12 mb-2"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-center">3x4 Profile</span>
                                </div>
                            @endif
                        </div>
                        <label for="photo_upload" class="absolute -bottom-2 -right-2 h-9 w-9 bg-white shadow-md border border-zinc-200 rounded-lg flex items-center justify-center cursor-pointer hover:bg-zinc-50 transition-all text-zinc-900">
                            <i data-lucide="camera" class="h-4 w-4"></i>
                            <input type="file" id="photo_upload" name="photo" class="hidden">
                        </label>
                    </div>
                    <div class="flex-1 pt-2 text-center sm:text-left">
                        <h4 class="text-sm font-bold text-zinc-900 uppercase tracking-wider mb-2">Foto Profil</h4>
                        <p class="text-xs text-zinc-500 leading-relaxed max-w-xs">Pastikan foto Anda rapi, latar belakang satu warna (merah/biru), dan wajah terlihat jelas sesuai standar PT KAI.</p>
                        <div class="mt-4 flex gap-2 justify-center sm:justify-start">
                            <button type="button" class="text-[11px] font-bold py-2 px-4 bg-zinc-100 text-zinc-600 rounded-lg hover:bg-zinc-200 transition-colors">Ganti Foto</button>
                            <button type="button" class="text-[11px] font-bold py-2 px-4 text-red-600 hover:bg-red-50 rounded-lg transition-colors">Hapus</button>
                        </div>
                    </div>
                </div>

                <!-- Form Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    @php
                        $user = Auth::guard('candidate')->user();
                        $fields = [
                            ['label' => 'Nomor Identitas (KTP)', 'name' => 'identity_number', 'value' => $user->identity_number, 'readonly' => true, 'icon' => 'user-square-2'],
                            ['label' => 'Nama Lengkap', 'name' => 'name', 'value' => $user->name, 'placeholder' => 'Masukkan Nama Lengkap', 'icon' => 'user'],
                            ['label' => 'Alamat Email', 'name' => 'email', 'value' => $user->email, 'type' => 'email', 'icon' => 'mail'],
                            ['label' => 'Nomor Telepon/WA', 'name' => 'phone', 'value' => $user->phone, 'icon' => 'phone'],
                            ['label' => 'Tempat Lahir', 'name' => 'place_of_birth', 'value' => $user->place_of_birth, 'placeholder' => 'Contoh: SURABAYA', 'icon' => 'map-pin'],
                            ['label' => 'Tanggal Lahir', 'name' => 'date_of_birth', 'value' => $user->date_of_birth, 'type' => 'date', 'icon' => 'key-round'],
                        ];
                    @endphp

                    @foreach($fields as $field)
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-zinc-900 mb-1.5">{{ $field['label'] }}</label>
                            <div class="relative">
                                <i data-lucide="{{ $field['icon'] }}" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                                <input type="{{ $field['type'] ?? 'text' }}" name="{{ $field['name'] }}" value="{{ $field['value'] }}"
                                    class="block w-full rounded-lg border border-zinc-300 pl-10 pr-3 py-2.5 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all font-medium text-zinc-900 {{ ($field['readonly'] ?? false) ? 'bg-zinc-50 opacity-70 cursor-not-allowed' : '' }}"
                                    placeholder="{{ $field['placeholder'] ?? '#' }}"
                                    {{ ($field['readonly'] ?? false) ? 'readonly' : '' }}>
                            </div>
                        </div>
                    @endforeach

                    <!-- Agama (Select) -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-zinc-900 mb-1.5">Agama</label>
                        <div class="relative">
                            <i data-lucide="heart" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                            <select name="religion" class="block w-full rounded-lg border border-zinc-300 pl-10 pr-10 py-2.5 text-sm font-medium text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all appearance-none cursor-pointer">
                                <option value="ISLAM" {{ $user->religion == 'ISLAM' ? 'selected' : '' }}>ISLAM</option>
                                <option value="KRISTEN" {{ $user->religion == 'KRISTEN' ? 'selected' : '' }}>KRISTEN</option>
                                <option value="KATOLIK" {{ $user->religion == 'KATOLIK' ? 'selected' : '' }}>KATOLIK</option>
                                <option value="HINDU" {{ $user->religion == 'HINDU' ? 'selected' : '' }}>HINDU</option>
                                <option value="BUDHA" {{ $user->religion == 'BUDHA' ? 'selected' : '' }}>BUDHA</option>
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-400">
                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Kelamin (Custom Styled Radio) -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-zinc-900 mb-1.5">Jenis Kelamin</label>
                        <div class="flex items-center gap-3">
                            <label class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 border border-zinc-300 rounded-lg cursor-pointer hover:bg-zinc-50 transition-all group has-[:checked]:bg-zinc-900 has-[:checked]:border-zinc-900 has-[:checked]:text-white">
                                <input type="radio" name="gender" value="Lelaki" class="hidden peer" {{ $user->gender == 'Lelaki' ? 'checked' : '' }}>
                                <i data-lucide="user" class="h-4 w-4"></i>
                                <span class="text-sm font-bold">Laki-laki</span>
                            </label>
                            <label class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 border border-zinc-300 rounded-lg cursor-pointer hover:bg-zinc-50 transition-all group has-[:checked]:bg-zinc-900 has-[:checked]:border-zinc-900 has-[:checked]:text-white">
                                <input type="radio" name="gender" value="Perempuan" class="hidden peer" {{ $user->gender == 'Perempuan' ? 'checked' : '' }}>
                                <i data-lucide="user-2" class="h-4 w-4"></i>
                                <span class="text-sm font-bold">Perempuan</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-8 flex flex-col items-center">
                    <button type="submit" class="w-full sm:w-auto flex justify-center items-center gap-2 rounded-lg bg-zinc-900 px-12 py-3.5 text-sm font-bold text-white hover:bg-zinc-800 transition-all active:scale-[0.98] shadow-sm uppercase tracking-widest">
                        Simpan Pembaruan Data
                        <i data-lucide="save" class="h-4 w-4"></i>
                    </button>
                    <p class="text-[10px] text-zinc-400 mt-4 uppercase font-bold tracking-tighter italic">Data Anda akan diverifikasi oleh sistem rekrutmen PT KAI (Persero)</p>
                </div>
            </form>
        </div>
        
        <!-- Footer Card Theme -->
        <div class="bg-zinc-50 border-t border-zinc-100 py-4 px-8 text-center sm:text-left">
             <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="info" class="h-3 w-3"></i>
                    Status Akun: Peserta Aktif
                </span>
                <span class="text-[11px] font-medium text-zinc-400 italic font-mono">
                    Last Update: {{ now()->translatedFormat('d F Y H:i') }}
                </span>
             </div>
        </div>
    </div>
</div>
@endsection
