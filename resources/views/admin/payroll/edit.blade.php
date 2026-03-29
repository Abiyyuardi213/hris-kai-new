@extends('layouts.app')
@section('title', 'Edit Payroll')

@section('content')
    <div class="flex flex-col space-y-6 max-w-4xl mx-auto pb-12">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.payroll.index', ['month' => $payroll->month, 'year' => $payroll->year]) }}"
                    class="h-10 w-10 flex items-center justify-center rounded-xl bg-white border border-zinc-200 text-zinc-400 hover:text-zinc-900 hover:border-zinc-300 transition-all shadow-sm">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <div>
                    <h2 class="text-3xl font-black tracking-tight text-zinc-900 uppercase italic">Edit Payroll</h2>
                    <p class="text-sm text-zinc-500 font-medium">Monitoring & Penyesuaian Komponen Gaji Pegawai</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Periode</p>
                <p class="text-lg font-black text-zinc-900">
                    {{ Carbon\Carbon::createFromDate($payroll->year, $payroll->month, 1)->translatedFormat('F') }}
                    {{ $payroll->year }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-zinc-200 shadow-2xl shadow-zinc-200/50 overflow-hidden">
            <!-- Employee Info Header -->
            <div class="p-8 border-b border-zinc-100 bg-zinc-50/50 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl bg-zinc-900 flex items-center justify-center text-white">
                        <i data-lucide="user" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-zinc-900 uppercase italic leading-tight">{{ $payroll->pegawai->nama_lengkap }}</h3>
                        <p class="text-xs font-bold text-zinc-500 uppercase tracking-widest">{{ $payroll->pegawai->jabatan->name ?? 'Staff' }} • {{ $payroll->pegawai->nip }}</p>
                    </div>
                </div>
                <div class="px-4 py-2 rounded-xl bg-emerald-50 border border-emerald-100">
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Status Payroll</span>
                    <p class="text-xs font-bold text-emerald-700 uppercase">{{ $payroll->status }}</p>
                </div>
            </div>

            <div class="p-0">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-zinc-50/50">
                        <tr>
                            <th class="px-8 py-4 text-left font-black text-zinc-400 uppercase tracking-widest text-[10px] border-b border-zinc-100">No</th>
                            <th class="px-8 py-4 text-left font-black text-zinc-400 uppercase tracking-widest text-[10px] border-b border-zinc-100">Komponen Transaksi</th>
                            <th class="px-8 py-4 text-right font-black text-zinc-400 uppercase tracking-widest text-[10px] border-b border-zinc-100 whitespace-nowrap">Nilai Komponen (IDR)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @php
                            $components = [
                                ['label' => 'Gaji Pokok', 'value' => $payroll->gaji_pokok],
                                ['label' => 'Tunjangan Jabatan', 'value' => $payroll->tunjangan_jabatan],
                                ['label' => 'Tunjangan Perumahan', 'value' => $payroll->tunjangan_perumahan],
                                ['label' => 'Tunjangan Admin Bank', 'value' => $payroll->tunjangan_admin_bank],
                                ['label' => 'Tunjangan JPK (4%)', 'value' => $payroll->tunjangan_jpk],
                                ['label' => 'Tunjangan Pajak', 'value' => $payroll->tunjangan_pajak],
                                ['label' => 'ER Jamsostek JKK (0.24%)', 'value' => $payroll->er_jamsostek_jkk],
                                ['label' => 'ER Jamsostek JHT (3.7%)', 'value' => $payroll->er_jamsostek_jht],
                                ['label' => 'ER Jamsostek JKM (0.3%)', 'value' => $payroll->er_jamsostek_jkm],
                                ['label' => 'Tunjangan JPK Pensiun (2%)', 'value' => $payroll->tunjangan_jpk_pensiun],
                                ['label' => 'Tunjangan JP BPJS (2%)', 'value' => $payroll->tunjangan_jp_bpjs],
                                ['label' => 'Potongan Mandiri Inhealth', 'value' => $payroll->potongan_mandiri_inhealth, 'is_deduction' => true],
                            ];
                        @endphp

                        @foreach($components as $index => $comp)
                        <tr class="hover:bg-zinc-50/30 transition-colors {{ isset($comp['is_deduction']) && $comp['is_deduction'] ? 'bg-red-50/30' : '' }}">
                            <td class="px-8 py-4 text-zinc-400 font-bold w-16 italic">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-8 py-4 font-bold text-zinc-900 group-hover:pl-10 transition-all font-mono">{{ $comp['label'] }}</td>
                            <td class="px-8 py-4 text-right font-black {{ isset($comp['is_deduction']) && $comp['is_deduction'] ? 'text-red-600' : 'text-zinc-700' }}">
                                {{ isset($comp['is_deduction']) && $comp['is_deduction'] ? '-' : '' }}Rp {{ number_format($comp['value'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-zinc-900 text-white">
                        <tr>
                            <td colspan="2" class="px-8 py-6 text-right">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 block mb-1">Subtotal Current Earnings</span>
                                <span class="text-sm font-bold opacity-80 uppercase italic">Gaji & Tunjangan Bulanan</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <span class="text-2xl font-black italic tracking-tighter">
                                    Rp {{ number_format($payroll->total_gaji - $payroll->thr - $payroll->bonus, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <form action="{{ route('admin.payroll.update', $payroll->id) }}" method="POST" class="p-8 bg-zinc-50/30">
                @csrf
                @method('PUT')

                <div class="flex items-center gap-3 mb-8">
                    <div class="h-1 w-8 bg-orange-500 rounded-full"></div>
                    <h4 class="text-xs font-black text-zinc-900 uppercase tracking-widest italic">Penyesuaian THR & Bonus</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] ml-1">Nominal Tunjangan Hari Raya (THR)</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-zinc-400 transition-colors group-focus-within:text-zinc-900">
                                    Rp
                                </div>
                                <input type="number" name="thr" id="thr"
                                    value="{{ old('thr', $payroll->thr) }}" required
                                    class="block w-full rounded-2xl border-2 border-zinc-100 bg-white pl-12 pr-4 py-4 text-sm font-black focus:border-zinc-900 focus:ring-4 focus:ring-zinc-900/5 outline-none transition-all placeholder:text-zinc-300">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] ml-1">Nominal Bonus Insentif</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-zinc-400 transition-colors group-focus-within:text-zinc-900">Rp</div>
                                <input type="number" name="bonus" value="{{ old('bonus', $payroll->bonus) }}" required
                                    class="block w-full rounded-2xl border-2 border-zinc-100 bg-white pl-12 pr-4 py-4 text-sm font-black focus:border-zinc-900 focus:ring-4 focus:ring-zinc-900/5 outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col h-full">
                        <div class="space-y-2 flex-1">
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] ml-1">Keterangan / Memo Bonus</label>
                            <textarea name="keterangan_bonus" rows="5"
                                class="block w-full h-[calc(100%-24px)] rounded-2xl border-2 border-zinc-100 bg-white px-4 py-4 text-sm font-bold focus:border-zinc-900 focus:ring-4 focus:ring-zinc-900/5 outline-none transition-all placeholder:text-zinc-300 resize-none"
                                placeholder="Tuliskan alasan pemberian bonus (misal: Insentif Tahunan, THR Idul Fitri, dll)">{{ old('keterangan_bonus', $payroll->keterangan_bonus) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex flex-col md:flex-row items-center gap-4">
                    <button type="submit"
                        class="w-full md:flex-[2] flex items-center justify-center gap-3 px-8 py-5 rounded-2xl bg-zinc-900 text-sm font-black text-white hover:bg-zinc-800 transition-all active:scale-[0.98] shadow-2xl shadow-zinc-900/20 group uppercase italic tracking-widest">
                        <span>Perbarui Data Payroll</span>
                        <i data-lucide="save" class="h-4 w-4 transition-transform group-hover:rotate-12"></i>
                    </button>
                    <a href="{{ route('admin.payroll.index', ['month' => $payroll->month, 'year' => $payroll->year]) }}"
                        class="w-full md:flex-1 px-8 py-5 rounded-2xl bg-white border-2 border-zinc-100 text-sm font-black text-zinc-500 hover:text-zinc-900 hover:border-zinc-900 transition-all text-center uppercase italic tracking-widest">
                        Batalkan
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

