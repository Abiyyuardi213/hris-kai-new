@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-control {
            border-radius: 0.5rem !important;
            padding: 0.5rem 0.75rem !important;
            border-color: #d4d4d8 !important;
            font-size: 0.875rem !important;
        }

        .ts-wrapper.focus .ts-control {
            box-shadow: 0 0 0 1px #18181b !important;
            border-color: #18181b !important;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Tambah Jabatan</h2>
            <a href="{{ route('positions.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="rounded-xl border bg-white shadow-sm p-6 max-w-2xl">
            <form action="{{ route('positions.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-zinc-900">Nama Jabatan</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('name') border-red-500 @enderror"
                            placeholder="Contoh: Staff IT" oninput="generateCode()">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-medium text-zinc-900">Kode Jabatan
                            (Otomatis)</label>
                        <input type="text" id="code" name="code" value="{{ old('code') }}" required readonly
                            class="mt-1 block w-full rounded-lg border border-zinc-100 bg-zinc-50 px-3 py-2 text-sm text-zinc-500 cursor-not-allowed @error('code') border-red-500 @enderror"
                            placeholder="Akan terisi otomatis...">
                        @error('code')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="gaji_pokok" class="block text-sm font-medium text-zinc-900">Gaji Pokok (Bulanan)</label>
                            <div class="relative mt-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-zinc-500 sm:text-sm text-xs font-bold uppercase">Rp</span>
                                </div>
                                <input type="number" id="gaji_pokok" name="gaji_pokok"
                                    value="{{ old('gaji_pokok', 0) }}" required
                                    class="block w-full rounded-lg border border-zinc-300 pl-10 pr-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('gaji_pokok') border-red-500 @enderror"
                                    placeholder="0">
                            </div>
                            @error('gaji_pokok')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tunjangan" class="block text-sm font-medium text-zinc-900">Tunjangan Jabatan</label>
                            <div class="relative mt-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-zinc-500 sm:text-sm text-xs font-bold uppercase">Rp</span>
                                </div>
                                <input type="number" id="tunjangan" name="tunjangan" value="{{ old('tunjangan', 0) }}"
                                    required
                                    class="block w-full rounded-lg border border-zinc-300 pl-10 pr-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('tunjangan') border-red-500 @enderror"
                                    placeholder="0">
                            </div>
                            @error('tunjangan')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tunjangan_perumahan" class="block text-sm font-medium text-zinc-900">Tunjangan Perumahan</label>
                            <div class="relative mt-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-zinc-500 sm:text-sm text-xs font-bold uppercase">Rp</span>
                                </div>
                                <input type="number" id="tunjangan_perumahan" name="tunjangan_perumahan"
                                    value="{{ old('tunjangan_perumahan', 0) }}" required
                                    class="block w-full rounded-lg border border-zinc-300 pl-10 pr-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('tunjangan_perumahan') border-red-500 @enderror"
                                    placeholder="0">
                            </div>
                            @error('tunjangan_perumahan')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tunjangan_pajak" class="block text-sm font-medium text-zinc-900">Tunjangan Pajak (PPh 21)</label>
                            <div class="relative mt-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-zinc-500 sm:text-sm text-xs font-bold uppercase">Rp</span>
                                </div>
                                <input type="number" id="tunjangan_pajak" name="tunjangan_pajak"
                                    value="{{ old('tunjangan_pajak', 0) }}" required
                                    class="block w-full rounded-lg border border-zinc-300 pl-10 pr-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('tunjangan_pajak') border-red-500 @enderror"
                                    placeholder="0">
                            </div>
                            @error('tunjangan_pajak')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="potongan_mandiri_inhealth" class="block text-sm font-medium text-red-600">Potongan Mandiri Inhealth</label>
                            <div class="relative mt-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-zinc-500 sm:text-sm text-xs font-bold uppercase">Rp</span>
                                </div>
                                <input type="number" id="potongan_mandiri_inhealth" name="potongan_mandiri_inhealth"
                                    value="{{ old('potongan_mandiri_inhealth', 0) }}" required
                                    class="block w-full rounded-lg border border-red-300 pl-10 pr-3 py-2 text-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600 @error('potongan_mandiri_inhealth') border-red-500 @enderror"
                                    placeholder="0">
                            </div>
                            @error('potongan_mandiri_inhealth')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Simulasi Mandiri Inhealth -->
                    <div class="rounded-2xl border-2 border-dashed border-blue-100 bg-blue-50/50 p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-200">
                                    <i data-lucide="shield-check" class="h-5 w-5"></i>
                                </div>
                                <div class="leading-none">
                                    <h4 class="text-sm font-black text-blue-900 uppercase italic">Simulation: MyCare Ultimate</h4>
                                    <p class="text-[10px] font-bold text-blue-500 uppercase tracking-widest mt-1">Mandiri Inhealth Premium</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[9px] font-black text-blue-400 uppercase tracking-widest block">Premi Per Tahun</span>
                                <span class="text-lg font-black text-blue-900 italic">Rp 1.593.000</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-[10px] font-bold uppercase tracking-tight text-blue-600/70 border-t border-blue-100 pt-4">
                            <div class="flex items-center gap-2">
                                <i data-lucide="check" class="h-3 w-3"></i>
                                <span>Manfaat Utama (Rawat Inap)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="check" class="h-3 w-3"></i>
                                <span>Hospital Income Rp 500rb</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="check" class="h-3 w-3"></i>
                                <span>Hospital Cash Plan Rp 500rb</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="check" class="h-3 w-3"></i>
                                <span>Ambulans Rp 1jt</span>
                            </div>
                        </div>

                        <button type="button" onclick="applySimulation(1593000)" 
                            class="w-full py-3 rounded-xl bg-blue-600 text-xs font-black text-white hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 uppercase italic tracking-widest">
                            Terapkan Potongan Bulanan (Rp 132.750)
                        </button>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-zinc-900">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('description') border-red-500 @enderror"
                            placeholder="Deskripsi tugas jabatan...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <a href="{{ route('positions.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-zinc-white hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2 text-white">
                        Simpan Jabatan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function applySimulation(yearlyAmount) {
            const monthlyAmount = Math.ceil(yearlyAmount / 12);
            document.getElementById('potongan_mandiri_inhealth').value = monthlyAmount;
            
            // Highlight effect
            const input = document.getElementById('potongan_mandiri_inhealth');
            input.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
            setTimeout(() => {
                input.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
            }, 1000);
        }

        function generateCode() {
            const name = document.getElementById('name').value;
            const nextNumber = {{ $nextNumber }};

            if (!name) {
                document.getElementById('code').value = '';
                return;
            }

            // Split name into words, remove empty strings
            const words = name.trim().toUpperCase().split(/\s+/);
            let abbreviation = 'JOB-';

            words.forEach((word) => {
                if (word.length > 0) {
                    // Take first 3 letters
                    abbreviation += word.substring(0, 3) + '-';
                }
            });

            // Format number to 001, 002, etc.
            const paddedNumber = nextNumber.toString().padStart(3, '0');

            document.getElementById('code').value = abbreviation + paddedNumber;
        }
    </script>
@endpush
