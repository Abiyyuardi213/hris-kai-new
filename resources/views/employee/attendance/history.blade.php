@extends('layouts.employee')

@section('title', 'Riwayat Presensi')

@section('content')
    <div class="flex flex-col space-y-6 max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Riwayat Presensi</h2>
                <p class="text-zinc-500 text-sm">Lihat rekapitulasi kehadiran Anda setiap bulan.</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-zinc-500">Periode</p>
                <h3 class="text-lg font-bold text-zinc-900">
                    {{ date('F', mktime(0, 0, 0, $selectedMonth, 1)) }} {{ $selectedYear }}
                </h3>
            </div>
        </div>

        <!-- Monthly History Card -->
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
            <div
                class="px-6 py-5 border-b border-zinc-100 bg-zinc-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-zinc-900 text-sm">Rekap Absensi Bulanan</h3>
                </div>

                <form action="{{ route('employee.attendance.history') }}" method="GET" class="flex items-center gap-2">
                    <select name="month"
                        class="px-3 py-2 rounded-lg border border-zinc-200 text-xs font-bold text-zinc-700 focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ sprintf('%02d', $m) }}"
                                {{ $selectedMonth == sprintf('%02d', $m) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                    <select name="year"
                        class="px-3 py-2 rounded-lg border border-zinc-200 text-xs font-bold text-zinc-700 focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none">
                        @for ($y = date('Y'); $y >= date('Y') - 2; $y--)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                {{ $y }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="p-2 bg-zinc-900 text-white rounded-lg hover:bg-zinc-800 transition-all">
                        <i data-lucide="search" class="h-4 w-4"></i>
                    </button>
                </form>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-5 divide-x divide-zinc-100 border-b border-zinc-100 bg-white">
                <div class="p-4 text-center">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Hadir</p>
                    <p class="text-lg font-black text-emerald-600">{{ $stats['total_hadir'] }}</p>
                </div>
                <div class="p-4 text-center">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Terlambat</p>
                    <p class="text-lg font-black text-red-600">{{ $stats['total_late'] }}</p>
                </div>
                <div class="p-4 text-center">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Pulang Cepat</p>
                    <p class="text-lg font-black text-orange-600">{{ $stats['total_early'] }}</p>
                </div>
                <div class="p-4 text-center">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Izin</p>
                    <p class="text-lg font-black text-blue-600">{{ $stats['total_izin'] }}</p>
                </div>
                <div class="p-4 text-center">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Sakit</p>
                    <p class="text-lg font-black text-yellow-600">{{ $stats['total_sakit'] }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead
                        class="bg-zinc-50/50 border-b border-zinc-100 text-zinc-400 uppercase text-[10px] font-bold tracking-widest">
                        <tr>
                            <th class="px-6 py-4 text-center">Tanggal</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Jam Masuk</th>
                            <th class="px-6 py-4">Jam Pulang</th>
                            <th class="px-6 py-4">Interval</th>
                            <th class="px-6 py-4">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($monthlyHistory as $item)
                            <tr class="hover:bg-zinc-50/50 transition-colors">
                                <td class="px-6 py-4 text-center border-r border-zinc-50">
                                    <div class="font-black text-xl text-zinc-900">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}</div>
                                    <div class="text-[10px] text-zinc-400 font-bold uppercase">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('M Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = [
                                            'Hadir' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'Izin' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'Sakit' => 'bg-orange-50 text-orange-600 border-orange-100',
                                            'Alpa' => 'bg-red-50 text-red-600 border-red-100',
                                        ];
                                        $statusClass =
                                            $statusClasses[$item->status] ?? 'bg-zinc-50 text-zinc-600 border-zinc-100';
                                    @endphp
                                    <span
                                        class="px-2 py-0.5 rounded-lg border text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($item->jam_masuk)
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
                                            <span class="font-bold text-zinc-700">{{ $item->jam_masuk }}</span>
                                        </div>
                                    @else
                                        <span class="text-zinc-300">--:--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($item->jam_pulang)
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-2 rounded-full bg-orange-500"></div>
                                            <span class="font-bold text-zinc-700">{{ $item->jam_pulang }}</span>
                                        </div>
                                    @else
                                        <span class="text-zinc-300">--:--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($item->jam_masuk && $item->jam_pulang)
                                        @php
                                            $masuk = \Carbon\Carbon::parse($item->jam_masuk);
                                            $pulang = \Carbon\Carbon::parse($item->jam_pulang);
                                            $diff = $masuk->diff($pulang);
                                            $duration = '';
                                            if ($diff->h > 0) {
                                                $duration .= $diff->h . 'j ';
                                            }
                                            $duration .= $diff->i . 'm';
                                        @endphp
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-2 rounded-full bg-blue-500"></div>
                                            <span class="font-bold text-zinc-700">{{ $duration }}</span>
                                        </div>
                                    @else
                                        <span class="text-zinc-300">--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-zinc-500 font-medium">{{ $item->keterangan ?? '-' }}</div>
                                    @if ($item->terlambat > 0)
                                        <div
                                            class="text-[10px] text-red-500 font-bold mt-1 inline-flex items-center gap-1 bg-red-50 px-1.5 py-0.5 rounded">
                                            <i data-lucide="clock" class="h-3 w-3"></i>
                                            Terlambat {{ $item->terlambat }}m
                                        </div>
                                    @endif
                                    @if ($item->pulang_cepat > 0)
                                        <div
                                            class="text-[10px] text-orange-500 font-bold mt-1 inline-flex items-center gap-1 bg-orange-50 px-1.5 py-0.5 rounded">
                                            <i data-lucide="clock" class="h-3 w-3"></i>
                                            Pulang Cepat {{ $item->pulang_cepat }}m
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-zinc-500 italic">
                                    <i data-lucide="calendar-off" class="h-12 w-12 mx-auto mb-3 text-zinc-200"></i>
                                    <p class="font-medium">Tidak ada data absensi untuk periode ini.</p>
                                    <p class="text-xs text-zinc-400 mt-1">Silakan pilih bulan atau tahun lain.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
