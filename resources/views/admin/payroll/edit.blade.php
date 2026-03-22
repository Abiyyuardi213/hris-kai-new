@extends('layouts.app')
@section('title', 'Edit Payroll')

@section('content')
    <div class="flex flex-col space-y-6 max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.payroll.index', ['month' => $payroll->month, 'year' => $payroll->year]) }}"
                class="h-10 w-10 flex items-center justify-center rounded-full bg-white border border-zinc-100 text-zinc-400 hover:text-zinc-900 transition-all shadow-sm">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Edit Payroll:
                    {{ $payroll->pegawai->nama_lengkap }}</h2>
                <p class="text-sm text-zinc-500">Periode:
                    {{ Carbon\Carbon::createFromDate($payroll->year, $payroll->month, 1)->translatedFormat('F') }}
                    {{ $payroll->year }}</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-zinc-100 shadow-xl shadow-zinc-200/50 overflow-hidden">
            <div class="p-8 border-b border-zinc-50 bg-zinc-50/30">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Gaji Pokok</p>
                        <p class="font-bold text-zinc-900">Rp {{ number_format($payroll->gaji_pokok, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Tunj. Jabatan</p>
                        <p class="font-bold text-zinc-900">Rp {{ number_format($payroll->tunjangan_jabatan, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Tunj. Perumahan</p>
                        <p class="font-bold text-zinc-900">Rp {{ number_format($payroll->tunjangan_perumahan, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Tunj. Admin Bank</p>
                        <p class="font-bold text-zinc-900">Rp {{ number_format($payroll->tunjangan_admin_bank, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Tunj. JPK (4%)</p>
                        <p class="font-bold text-zinc-900">Rp {{ number_format($payroll->tunjangan_jpk, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Tunj. Pajak</p>
                        <p class="font-bold text-zinc-900">Rp {{ number_format($payroll->tunjangan_pajak, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">ER JKK (0.24%)</p>
                        <p class="font-bold text-zinc-900">Rp {{ number_format($payroll->er_jamsostek_jkk, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">ER JHT (3.7%)</p>
                        <p class="font-bold text-zinc-900">Rp {{ number_format($payroll->er_jamsostek_jht, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">ER JKM (0.3%)</p>
                        <p class="font-bold text-zinc-900">Rp {{ number_format($payroll->er_jamsostek_jkm, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">JPK Pensiun (2%)</p>
                        <p class="font-bold text-zinc-900">Rp {{ number_format($payroll->tunjangan_jpk_pensiun, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">JP BPJS (2%)</p>
                        <p class="font-bold text-zinc-900">Rp {{ number_format($payroll->tunjangan_jp_bpjs, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest text-emerald-600">Total Earnings</p>
                        <p class="font-bold text-emerald-600">Rp
                            {{ number_format($payroll->total_gaji - $payroll->thr - $payroll->bonus, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.payroll.update', $payroll->id) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest ml-1">Jumlah Hari
                                THR</label>
                            <div class="relative">
                                <i data-lucide="calendar-check"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 h-4 w-4"></i>
                                <input type="number" name="thr_days" id="thr_days"
                                    value="{{ old('thr_days', $payroll->thr_days) }}" required
                                    class="block w-full rounded-2xl border border-zinc-100 bg-zinc-50/50 pl-11 pr-4 py-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                            </div>
                            <p class="text-[10px] text-zinc-400 mt-1 ml-1" id="thr_preview">
                                Estimasi: Rp {{ number_format((($payroll->gaji_pokok / 30) + ($payroll->tunjangan_jabatan / 30)) * $payroll->thr_days, 0, ',', '.') }}
                                ((Gaji Pokok + Tunjangan)/30 x <span id="days_val">{{ $payroll->thr_days }}</span> Hari)
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest ml-1">Bonus</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 font-bold">Rp</span>
                                <input type="number" name="bonus" value="{{ old('bonus', $payroll->bonus) }}" required
                                    class="block w-full rounded-2xl border border-zinc-100 bg-zinc-50/50 pl-11 pr-4 py-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-zinc-400 uppercase tracking-widest ml-1">Keterangan
                            Bonus</label>
                        <textarea name="keterangan_bonus" rows="3"
                            class="block w-full rounded-2xl border border-zinc-100 bg-zinc-50/50 px-4 py-4 text-sm font-medium focus:ring-2 focus:ring-zinc-900 outline-none transition-all placeholder:text-zinc-400"
                            placeholder="Contoh: Bonus Kinerja Akhir Tahun atau THR Idul Fitri">{{ old('keterangan_bonus', $payroll->keterangan_bonus) }}</textarea>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="submit"
                            class="flex-[2] px-6 py-4 rounded-2xl bg-zinc-900 text-sm font-bold text-white hover:bg-zinc-800 transition-all active:scale-[0.98] shadow-lg shadow-zinc-200">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.payroll.index', ['month' => $payroll->month, 'year' => $payroll->year]) }}"
                            class="flex-1 px-6 py-4 rounded-2xl bg-white border border-zinc-200 text-sm font-bold text-zinc-700 hover:bg-zinc-100 transition-all text-center">
                            Batal
                        </a>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const thrDaysInput = document.getElementById('thr_days');
                            const thrPreview = document.getElementById('thr_preview');
                            const daysVal = document.getElementById('days_val');
                            const gajiPokok = {{ $payroll->gaji_pokok }};
                            const tunjangan = {{ $payroll->tunjangan_jabatan }};

                            thrDaysInput.addEventListener('input', function() {
                                const days = parseInt(this.value) || 0;
                                const estimate = days * ((gajiPokok / 30) + (tunjangan / 30));
                                daysVal.textContent = days;
                                thrPreview.innerHTML =
                                    `Estimasi: Rp ${new Intl.NumberFormat('id-ID').format(estimate)} ((Gaji Pokok + Tunjangan)/30 x ${days} Hari)`;
                            });
                        });
                    </script>
            </form>
        </div>
    </div>
@endsection
