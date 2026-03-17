@extends('layouts.candidate')
@section('title', 'Riwayat Pendidikan')

@section('content')
<div class="max-w-[1000px] mx-auto space-y-8 pb-20">
    <!-- Header Title -->
    <div class="flex flex-col mb-4">
        <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Informasi Riwayat Pendidikan</h2>
        <p class="text-sm text-zinc-500 mt-2">Silahkan lengkapi riwayat pendidikan anda dengan benar</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="p-10">
            <form action="{{ route('candidate.education.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-6 max-w-[800px] mx-auto">
                    <!-- Tingkat -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">Tingkat</label>
                        <div class="md:col-span-2 relative">
                            <select name="degree_level" class="block w-full rounded-lg border border-zinc-300 px-4 py-3 text-sm font-medium text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all appearance-none cursor-pointer">
                                <option value="" disabled selected>Pilih Tingkat</option>
                                @foreach(['SLTA', 'D3', 'D4', 'S1', 'S2', 'S3'] as $lvl)
                                    <option value="{{ $lvl }}">{{ $lvl }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-400">
                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Jurusan -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">Jurusan</label>
                        <div class="md:col-span-2">
                            <input type="text" name="major" placeholder="Masukkan Jurusan"
                                class="block w-full rounded-lg border border-zinc-300 px-4 py-3 text-sm font-medium text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all">
                        </div>
                    </div>

                    <!-- Kota -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">Kota</label>
                        <div class="md:col-span-2 relative city-search-container">
                            <input type="text" name="city" placeholder="Masukkan Kota" autocomplete="off"
                                class="city-input block w-full rounded-lg border border-zinc-300 px-4 py-3 text-sm font-medium text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all">
                            <div class="city-suggestions absolute z-[100] top-full left-0 right-0 mt-2 bg-white border border-zinc-200 rounded-xl shadow-2xl hidden max-h-[300px] overflow-y-auto overflow-hidden animate-fade-in"></div>
                        </div>
                    </div>

                    <!-- Institusi -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">Institusi (Sekolah)</label>
                        <div class="md:col-span-2">
                            <input type="text" name="institution" placeholder="Masukkan Institusi (Sekolah)"
                                class="block w-full rounded-lg border border-zinc-300 px-4 py-3 text-sm font-medium text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all">
                        </div>
                    </div>

                    <!-- Tanggal Lulus -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">Tanggal Lulus</label>
                        <div class="md:col-span-2">
                            <input type="date" name="graduation_date"
                                class="block w-full rounded-lg border border-zinc-300 px-4 py-3 text-sm font-medium text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all">
                        </div>
                    </div>

                    <!-- Nilai -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight pt-3">Nilai</label>
                        <div class="md:col-span-2">
                            <input type="number" step="0.01" name="score" placeholder="Masukkan Nilai"
                                class="block w-full rounded-lg border border-zinc-300 px-4 py-3 text-sm font-medium text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all">
                            <p class="text-[10px] text-zinc-400 mt-1 font-bold italic uppercase tracking-widest">Format : 0.00</p>
                        </div>
                    </div>

                    <!-- Akreditasi -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">Akreditasi</label>
                        <div class="md:col-span-2 relative">
                            <select name="accreditation" class="block w-full rounded-lg border border-zinc-300 px-4 py-3 text-sm font-medium text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all appearance-none cursor-pointer">
                                <option value="" disabled selected>Pilih Akreditasi</option>
                                @foreach(['A', 'B', 'C', 'Unggul', 'Baik Sekali', 'Baik'] as $acr)
                                    <option value="{{ $acr }}">{{ $acr }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-400">
                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="flex items-center gap-2 rounded-lg bg-emerald-500 px-8 py-3 text-xs font-bold text-white hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100 uppercase tracking-widest">
                            <i data-lucide="plus-square" class="h-4 w-4"></i>
                            TAMBAH
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-50/50 border-b border-zinc-100">
                        <th class="px-6 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 text-center">Tingkat</th>
                        <th class="px-6 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 text-center">Sekolah (Institusi)</th>
                        <th class="px-6 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 text-center">Jurusan</th>
                        <th class="px-6 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 text-center">Kota</th>
                        <th class="px-6 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 text-center">Tanggal Lulus</th>
                        <th class="px-6 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 text-center">Nilai</th>
                        <th class="px-6 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 text-center">Akreditasi</th>
                        <th class="px-6 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 text-center">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    @forelse($educations as $edu)
                        <tr class="hover:bg-zinc-50/30 transition-colors">
                            <td class="px-6 py-5 text-xs font-bold text-zinc-600 text-center">{{ $edu->degree_level }}</td>
                            <td class="px-6 py-5 text-xs font-bold text-zinc-900 text-center uppercase tracking-tight">{{ $edu->institution }}</td>
                            <td class="px-6 py-5 text-xs font-bold text-zinc-600 text-center">{{ $edu->major }}</td>
                            <td class="px-6 py-5 text-xs font-bold text-zinc-500 text-center uppercase">{{ $edu->city }}</td>
                            <td class="px-6 py-5 text-xs font-bold text-zinc-600 text-center">{{ \Carbon\Carbon::parse($edu->graduation_date)->format('d-M-Y') }}</td>
                            <td class="px-6 py-5 text-xs font-bold text-zinc-900 text-center">{{ number_format($edu->score, 2) }}</td>
                            <td class="px-6 py-5 text-xs font-bold text-zinc-900 text-center">{{ $edu->accreditation }}</td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('candidate.education.destroy', $edu->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat pendidikan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="h-8 w-8 rounded-lg bg-rose-500 flex items-center justify-center text-white hover:bg-rose-600 transition-all shadow-md shadow-rose-100">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                    <button class="h-8 w-8 rounded-lg bg-blue-500 flex items-center justify-center text-white hover:bg-blue-600 transition-all shadow-md shadow-blue-100">
                                        <i data-lucide="edit-3" class="h-4 w-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i data-lucide="graduation-cap" class="h-12 w-12 mb-4"></i>
                                    <p class="text-xs font-black uppercase tracking-widest text-zinc-900">Belum ada riwayat pendidikan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // City Search
        const citySearchContainers = document.querySelectorAll('.city-search-container');
        let cityTimeout = null;

        citySearchContainers.forEach(container => {
            const cityInput = container.querySelector('.city-input');
            const citySuggestions = container.querySelector('.city-suggestions');

            if (cityInput && citySuggestions) {
                cityInput.addEventListener('input', function() {
                    clearTimeout(cityTimeout);
                    const query = this.value;

                    if (query.length < 2) {
                        citySuggestions.classList.add('hidden');
                        return;
                    }

                    cityTimeout = setTimeout(() => {
                        fetch(`{{ route('cities.search') }}?q=${query}`, {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
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
            }
        });

        // Close suggestions when clicking outside
        document.addEventListener('click', function(e) {
            document.querySelectorAll('.city-suggestions').forEach(el => {
                if (!el.parentNode.contains(e.target)) {
                    el.classList.add('hidden');
                }
            });
        });
    });
</script>
@endpush
@endsection
