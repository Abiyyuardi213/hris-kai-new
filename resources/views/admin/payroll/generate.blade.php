@extends('layouts.app')
@section('title', 'Generate Payroll')

@section('content')
    <div class="flex flex-col space-y-6 max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.payroll.index') }}"
                class="h-10 w-10 flex items-center justify-center rounded-full bg-white border border-zinc-100 text-zinc-400 hover:text-zinc-900 transition-all shadow-sm">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Generate Payroll Bulanan</h2>
                <p class="text-sm text-zinc-500">Sistem akan menghitung gaji berdasarkan kehadiran di bulan yang dipilih.</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-zinc-100 shadow-xl shadow-zinc-200/50 overflow-hidden">
            <div class="p-8 border-b border-zinc-50 bg-zinc-50/30">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl bg-zinc-900 text-white flex items-center justify-center shadow-lg">
                        <i data-lucide="calculator" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-zinc-900">Kalkulasi Gaji Otomatis</h4>
                        <p class="text-xs text-zinc-500 font-medium">Total Gaji = (Gaji/Hari * Jumlah Kehadiran) + Tunjangan
                            Tetap</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.payroll.process-generate') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest ml-1">Bulan Periode</label>
                        <div class="relative">
                            <i data-lucide="calendar"
                                class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                            <select name="month" required
                                class="block w-full rounded-2xl border border-zinc-100 bg-zinc-50/50 pl-11 pr-4 py-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none transition-all appearance-none">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest ml-1">Tahun Periode</label>
                        <div class="relative">
                            <i data-lucide="clock"
                                class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                            <select name="year" required
                                class="block w-full rounded-2xl border border-zinc-100 bg-zinc-50/50 pl-11 pr-4 py-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none transition-all appearance-none">
                                @for ($i = date('Y'); $i >= 2020; $i--)
                                    <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 flex gap-4">
                    <i data-lucide="info" class="h-5 w-5 text-amber-600 shrink-0"></i>
                    <p class="text-xs text-amber-800 leading-relaxed font-medium">
                        Sistem hanya akan men-generate data untuk pegawai yang <strong>memiliki Jabatan</strong> dan
                        <strong>belum memiliki record payroll</strong> di bulan/tahun tersebut. Pastikan data jabatan (gaji
                        harian & tunjangan) sudah benar sebelum memproses.
                    </p>
                </div>

                <div class="pt-4 flex gap-4">
                    <button type="submit"
                        class="flex-[2] px-6 py-4 rounded-2xl bg-zinc-900 text-sm font-bold text-white hover:bg-zinc-800 transition-all active:scale-[0.98] shadow-lg shadow-zinc-200">
                        Proses Generate Payroll
                    </button>
                    <a href="{{ route('admin.payroll.index') }}"
                        class="flex-1 px-6 py-4 rounded-2xl bg-white border border-zinc-200 text-sm font-bold text-zinc-700 hover:bg-zinc-100 transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
