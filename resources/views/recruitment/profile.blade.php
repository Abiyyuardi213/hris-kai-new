@extends('layouts.candidate')
@section('title', 'Informasi Biodata')

@section('content')
<div class="max-w-[800px] mx-auto space-y-8">
    <!-- Header Title -->
    <div class="flex flex-col mb-4">
        <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Informasi Biodata</h2>
        <p class="text-sm text-zinc-500 mt-2">Silakan lengkapi biodata diri Anda dengan benar sesuai dengan dokumen identitas resmi Anda.</p>
    </div>

    @if($errors->any())
    <div class="bg-rose-50 border border-rose-100 rounded-2xl p-6 mb-6 animate-in fade-in slide-in-from-top-4 duration-500">
        <div class="flex items-center gap-3 mb-3">
            <div class="h-8 w-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                <i data-lucide="alert-circle" class="h-5 w-5"></i>
            </div>
            <h3 class="text-sm font-bold text-rose-900 uppercase tracking-widest">Terdapat Kesalahan Input</h3>
        </div>
        <ul class="space-y-1 ml-11 list-disc">
            @foreach($errors->all() as $error)
                <li class="text-xs font-medium text-rose-600 italic">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden mb-12">
        <div class="p-8">
            <form action="{{ route('candidate.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                @csrf
                
                <!-- Section 1: Data Utama & Foto -->
                <div class="space-y-8">
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-zinc-400 border-b pb-2">Informasi Dasar</h3>
                    
                    <!-- Foto -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                        <div class="text-sm font-bold text-zinc-500 uppercase tracking-tight pt-2">Foto Formal</div>
                        <div class="md:col-span-3">
                            <div class="relative inline-block group">
                                <div class="h-44 w-32 rounded-xl bg-zinc-900 overflow-hidden shadow-sm flex items-center justify-center border-4 border-white">
                                    @if(Auth::guard('candidate')->user()->photo)
                                        <img src="{{ asset('storage/' . Auth::guard('candidate')->user()->photo) }}" alt="Foto" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex flex-col items-center text-white/30 text-center">
                                            <i data-lucide="user-square-2" class="h-10 w-10 mb-2"></i>
                                            <span class="text-[11px] font-bold uppercase tracking-widest">Formal Photo</span>
                                        </div>
                                    @endif
                                </div>
                                <label for="photo_upload" class="absolute -top-2 -right-2 h-9 w-9 bg-white shadow-md border border-zinc-200 rounded-lg flex items-center justify-center cursor-pointer hover:bg-zinc-50 transition-all text-zinc-900">
                                    <i data-lucide="camera" class="h-4 w-4"></i>
                                    <input type="file" id="photo_upload" name="photo" class="hidden" accept="image/*">
                                </label>
                            </div>
                            <p class="text-[10px] text-zinc-400 mt-3 font-medium flex items-center gap-1.5">
                                <i data-lucide="info" class="h-3 w-3"></i>
                                Gunakan foto latar merah/biru, format JPG/PNG, maksimal 2MB.
                            </p>
                        </div>
                    </div>

                    @php
                        $user = Auth::guard('candidate')->user();
                        $basic_fields = [
                            ['label' => 'Nomor Identitas', 'name' => 'identity_number', 'value' => $user->identity_number, 'readonly' => true, 'icon' => 'user-square-2'],
                            ['label' => 'Nama Lengkap', 'name' => 'name', 'value' => $user->name, 'placeholder' => 'Ahmad Syarif', 'icon' => 'user'],
                            ['label' => 'Email', 'name' => 'email', 'value' => $user->email, 'type' => 'email', 'icon' => 'mail'],
                            ['label' => 'Phone', 'name' => 'phone', 'value' => $user->phone, 'icon' => 'phone'],
                            ['label' => 'Tempat Lahir', 'name' => 'place_of_birth', 'value' => $user->place_of_birth, 'placeholder' => 'JAKARTA', 'icon' => 'map-pin', 'searchable' => true],
                            ['label' => 'Tanggal Lahir', 'name' => 'date_of_birth', 'value' => $user->date_of_birth, 'type' => 'date', 'icon' => 'calendar'],
                        ];
                    @endphp

                    @foreach($basic_fields as $field)
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                            <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">{{ $field['label'] }}</label>
                            <div class="md:col-span-3">
                                <div class="relative">
                                    <i data-lucide="{{ $field['icon'] }}" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                                    @if($field['searchable'] ?? false)
                                        <div class="relative city-search-container">
                                            <input type="text" name="{{ $field['name'] }}" value="{{ $field['value'] }}" placeholder="{{ $field['placeholder'] }}" autocomplete="off"
                                                class="city-input block w-full rounded-lg border border-zinc-300 pl-10 pr-3 py-3 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all font-medium text-zinc-900 {{ ($field['readonly'] ?? false) ? 'bg-zinc-50 opacity-70 cursor-not-allowed' : '' }}"
                                                {{ ($field['readonly'] ?? false) ? 'readonly' : '' }}>
                                            <div class="city-suggestions absolute z-[100] top-full left-0 right-0 mt-2 bg-white border border-zinc-200 rounded-xl shadow-2xl hidden max-h-[300px] overflow-y-auto overflow-hidden animate-fade-in"></div>
                                        </div>
                                    @else
                                        <input type="{{ $field['type'] ?? 'text' }}" name="{{ $field['name'] }}" value="{{ $field['value'] }}"
                                            class="block w-full rounded-lg border border-zinc-300 pl-10 pr-3 py-3 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all font-medium text-zinc-900 {{ ($field['readonly'] ?? false) ? 'bg-zinc-50 opacity-70 cursor-not-allowed' : '' }}"
                                            placeholder="{{ $field['placeholder'] ?? '#' }}"
                                            {{ ($field['readonly'] ?? false) ? 'readonly' : '' }}>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Agama -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">Agama</label>
                        <div class="md:col-span-3 relative">
                            <i data-lucide="heart" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                            <select name="religion" class="block w-full rounded-lg border border-zinc-300 pl-10 pr-10 py-3 text-sm font-medium text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all appearance-none cursor-pointer">
                                @foreach(['ISLAM', 'KRISTEN', 'KATOLIK', 'HINDU', 'BUDHA', 'KONGHUCU'] as $r)
                                    <option value="{{ $r }}" {{ $user->religion == $r ? 'selected' : '' }}>{{ $r }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-400">
                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Kelamin -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">Kelamin</label>
                        <div class="md:col-span-3 flex items-center gap-6">
                            @foreach(['Lelaki' => 'Pria', 'Perempuan' => 'Wanita'] as $val => $label)
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <div class="relative flex items-center justify-center">
                                        <input type="radio" name="gender" value="{{ $val }}" class="peer hidden" {{ $user->gender == $val ? 'checked' : '' }}>
                                        <div class="h-5 w-5 rounded-full border-2 border-zinc-300 peer-checked:border-zinc-900 transition-all"></div>
                                        <div class="h-2.5 w-2.5 rounded-full bg-zinc-900 scale-0 peer-checked:scale-100 transition-all absolute"></div>
                                    </div>
                                    <span class="text-sm font-bold text-zinc-600 group-hover:text-zinc-900">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Section 2: Informasi Identitas Tambahan -->
                <div class="space-y-8 pt-6 border-t border-zinc-50">
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-zinc-400 border-b pb-2">Status & Identitas</h3>
                    
                    <!-- Status Pernikahan -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">Status</label>
                        <div class="md:col-span-3 flex flex-wrap gap-6">
                            @foreach(['Belum Menikah', 'Menikah', 'Janda/Duda'] as $s)
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <div class="relative flex items-center justify-center">
                                        <input type="radio" name="marital_status" value="{{ $s }}" class="peer hidden" {{ $user->marital_status == $s ? 'checked' : '' }}>
                                        <div class="h-5 w-5 rounded-full border-2 border-zinc-300 peer-checked:border-zinc-900 transition-all"></div>
                                        <div class="h-2.5 w-2.5 rounded-full bg-zinc-900 scale-0 peer-checked:scale-100 transition-all absolute"></div>
                                    </div>
                                    <span class="text-sm font-bold text-zinc-600 group-hover:text-zinc-900">{{ $s }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Kewarganegaraan -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">Kewarganegaraan</label>
                        <div class="md:col-span-3">
                            <input type="text" name="nationality" value="{{ $user->nationality ?? 'Indonesia' }}" placeholder="Contoh: Indonesia"
                                class="block w-full rounded-lg border border-zinc-300 px-4 py-3 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all font-medium text-zinc-900">
                        </div>
                    </div>

                    <!-- NPWP -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">NPWP</label>
                        <div class="md:col-span-3">
                            <input type="text" name="npwp" value="{{ $user->npwp ?? '0' }}" placeholder="Masukkan Nomor NPWP"
                                class="block w-full rounded-lg border border-zinc-300 px-4 py-3 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all font-medium text-zinc-900">
                            <p class="text-[10px] text-zinc-400 mt-1 font-medium">Isi '0' jika belum memiliki NPWP</p>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Media Sosial -->
                <div class="space-y-8 pt-6 border-t border-zinc-50">
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-zinc-400 border-b pb-2">Media Sosial</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight pt-2">Link/Handle Sosial Media</label>
                        <div class="md:col-span-3 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach(['instagram', 'facebook', 'twitter', 'telegram', 'tiktok', 'linkedin', 'youtube'] as $sm)
                                <div class="relative group">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-zinc-300 uppercase tracking-widest group-focus-within:text-zinc-900 transition-colors">{{ substr($sm, 0, 3) }}</span>
                                    <input type="text" name="social_media[{{ $sm }}]" value="{{ $user->social_media[$sm] ?? '' }}" placeholder="{{ ucfirst($sm) }}"
                                        class="block w-full rounded-lg border border-zinc-300 pl-12 pr-3 py-2.5 text-xs font-medium text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all transition-all">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Section 4: Alamat Domisili -->
                <div class="space-y-8 pt-6 border-t border-zinc-50">
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-zinc-400 border-b pb-2">Alamat Domisili</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            $address_fields = [
                                ['label' => 'Provinsi', 'name' => 'province', 'value' => $user->province, 'placeholder' => 'JAWA TIMUR'],
                                ['label' => 'Kota / Kabupaten', 'name' => 'city', 'value' => $user->city, 'placeholder' => 'KOTA SURABAYA', 'searchable' => true],
                                ['label' => 'Kecamatan', 'name' => 'district', 'value' => $user->district, 'placeholder' => 'GUBENG'],
                                ['label' => 'Desa / Kelurahan', 'name' => 'village', 'value' => $user->village, 'placeholder' => 'AIRLANGGA'],
                            ];
                        @endphp

                        @foreach($address_fields as $af)
                            <div class="space-y-1.5 relative">
                                <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">{{ $af['label'] }}</label>
                                @if($af['searchable'] ?? false)
                                    <div class="relative" id="city-search-container">
                                        <input type="text" id="city-input" name="{{ $af['name'] }}" value="{{ $af['value'] }}" placeholder="{{ $af['placeholder'] }}" autocomplete="off"
                                            class="block w-full rounded-lg border border-zinc-300 px-4 py-3 text-sm font-medium text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all">
                                        <div id="city-suggestions" class="absolute z-[100] top-full left-0 right-0 mt-2 bg-white border border-zinc-200 rounded-xl shadow-2xl hidden max-h-[300px] overflow-y-auto overflow-hidden animate-fade-in">
                                            <!-- Suggestions will appear here -->
                                        </div>
                                    </div>
                                @else
                                    <input type="text" name="{{ $af['name'] }}" value="{{ $af['value'] }}" placeholder="{{ $af['placeholder'] }}"
                                        class="block w-full rounded-lg border border-zinc-300 px-4 py-3 text-sm font-medium text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all">
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">Alamat Lengkap</label>
                        <textarea name="address" rows="3" placeholder="Masukkan nama jalan, nomor rumah, RT/RW..."
                            class="block w-full rounded-lg border border-zinc-300 px-4 py-3 text-sm font-medium text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all resize-none">{{ $user->address }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-10 flex flex-col items-center border-t border-zinc-100">
                    <button type="submit" class="w-full sm:w-auto flex justify-center items-center gap-3 rounded-xl bg-zinc-900 px-16 py-4 text-sm font-bold text-white hover:bg-zinc-800 transition-all active:scale-[0.98] shadow-xl shadow-zinc-200 uppercase tracking-widest">
                        SIMPAN PEMBARUAN DATA
                        <i data-lucide="check-circle" class="h-5 w-5"></i>
                    </button>
                    <p class="text-[10px] text-zinc-400 mt-6 uppercase font-black tracking-[0.2em] italic text-center">DATA ANDA AKAN DIVERIFIKASI OLEH SISTEM REKRUTMEN PT KAI (PERSERO)</p>
                </div>
            </form>
        </div>

        <!-- Progress Footer -->
        <div class="bg-zinc-50 border-t border-zinc-100 py-6 px-10">
             <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-zinc-900 flex items-center justify-center text-white">
                        <i data-lucide="shield-check" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-zinc-900 uppercase tracking-widest">Status Akun</p>
                        <p class="text-xs font-bold text-emerald-600 uppercase tracking-tighter">Peserta Aktif / Terverifikasi</p>
                    </div>
                </div>
                <div class="text-center sm:text-right">
                    <p class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest">Pembaruan Terakhir</p>
                    <p class="text-xs font-bold text-zinc-500 italic">{{ now()->translatedFormat('d F Y H:i') }}</p>
                </div>
             </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const citySearchContainers = document.querySelectorAll('.city-search-container');
        let timeout = null;

        citySearchContainers.forEach(container => {
            const cityInput = container.querySelector('.city-input');
            const citySuggestions = container.querySelector('.city-suggestions');

            if (cityInput && citySuggestions) {
                cityInput.addEventListener('input', function() {
                    clearTimeout(timeout);
                    const query = this.value;

                    if (query.length < 2) {
                        citySuggestions.classList.add('hidden');
                        return;
                    }

                    timeout = setTimeout(() => {
                        fetch(`{{ route('cities.search') }}?q=${query}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                citySuggestions.innerHTML = '';
                                if (data.length > 0) {
                                    data.forEach(city => {
                                        const div = document.createElement('div');
                                        div.className = 'px-4 py-3 hover:bg-zinc-50 cursor-pointer border-b border-zinc-50 last:border-0 transition-colors';
                                        div.innerHTML = `
                                            <div class="text-sm font-bold text-zinc-900">${city.name}</div>
                                            <div class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest">${city.province_name}</div>
                                        `;
                                        div.addEventListener('click', () => {
                                            cityInput.value = city.name;
                                            citySuggestions.classList.add('hidden');
                                            
                                            // Auto-fill province ONLY for city input, not for birth place
                                            if (cityInput.name === 'city') {
                                                const provinceInput = document.querySelector('input[name="province"]');
                                                if (provinceInput && !provinceInput.value) {
                                                    provinceInput.value = city.province_name;
                                                }
                                            }
                                        });
                                        citySuggestions.appendChild(div);
                                    });
                                    citySuggestions.classList.remove('hidden');
                                } else {
                                    citySuggestions.classList.add('hidden');
                                }
                            });
                    }, 300);
                });

                // Close suggestions when clicking outside
                document.addEventListener('click', function(e) {
                    if (!cityInput.contains(e.target) && !citySuggestions.contains(e.target)) {
                        citySuggestions.classList.add('hidden');
                    }
                });
            }
        });

        // Photo Preview Script
        const photoInput = document.getElementById('photo_upload');
        const photoContainer = document.querySelector('.relative.inline-block.group .h-44.w-32');

        if (photoInput && photoContainer) {
            photoInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    console.log('File selected:', file.name, 'Size:', file.size, 'type:', file.type);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        photoContainer.innerHTML = '';
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Preview Foto';
                        img.className = 'w-full h-full object-cover';
                        photoContainer.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush
@endsection
