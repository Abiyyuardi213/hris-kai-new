@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Dashboard Overview</h2>
                <p class="text-zinc-500 text-sm">Monitoring performa dan aktivitas sistem HRIS PT KAI.</p>
            </div>
            <div class="flex items-center gap-3">
                <span
                    class="px-4 py-2 bg-zinc-100 rounded-xl text-zinc-600 font-bold text-xs uppercase tracking-wider border border-zinc-200">
                    {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Pegawai -->
            @if (Auth::user()->hasPermission('view-employees') || Auth::user()->hasPermission('manage-employees'))
                <div
                    class="group relative overflow-hidden rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center justify-between space-y-0 pb-2">
                        <h3 class="text-sm font-bold text-zinc-500 uppercase tracking-wider">Total Pegawai</h3>
                        <div
                            class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center transition-transform group-hover:scale-110">
                            <i data-lucide="users" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-3xl font-black text-zinc-900">{{ $stats['total_pegawai'] }}</div>
                        <p class="text-[10px] font-bold text-zinc-400 mt-1">PEGAWAI AKTIF TERDAFTAR</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-zinc-50 text-[11px]">
                        <a href="{{ route('employees.index') }}"
                            class="font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                            Kelola Data <i data-lucide="chevron-right" class="h-3 w-3"></i>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Presensi Hari Ini -->
            @if (Auth::user()->hasPermission('view-attendance') || Auth::user()->hasPermission('manage-attendance'))
                <div
                    class="group relative overflow-hidden rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center justify-between space-y-0 pb-2">
                        <h3 class="text-sm font-bold text-zinc-500 uppercase tracking-wider">Presensi Hari Ini</h3>
                        <div
                            class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center transition-transform group-hover:scale-110">
                            <i data-lucide="list-check" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-3xl font-black text-zinc-900">{{ $stats['presensi_hari_ini'] }}</div>
                        <p class="text-[10px] font-bold text-zinc-400 mt-1">PEGAWAI SUDAH ABSEN</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-zinc-50 text-[11px]">
                        <a href="{{ route('admin.presensi.index') }}"
                            class="font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                            Monitor Kehadiran <i data-lucide="chevron-right" class="h-3 w-3"></i>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Izin Pending -->
            @if (Auth::user()->hasPermission('manage-izin'))
                <div
                    class="group relative overflow-hidden rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center justify-between space-y-0 pb-2">
                        <h3 class="text-sm font-bold text-zinc-500 uppercase tracking-wider">Izin Pending</h3>
                        <div
                            class="h-10 w-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center transition-transform group-hover:scale-110">
                            <i data-lucide="file-text" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-3xl font-black text-zinc-900">{{ $stats['izin_pending'] }}</div>
                        <p class="text-[10px] font-bold text-zinc-400 mt-1">PENGAJUAN MENUNGGU KONFIRMASI</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-zinc-50 text-[11px]">
                        <a href="{{ route('admin.izin.index') }}"
                            class="font-bold text-orange-600 hover:text-orange-700 flex items-center gap-1">
                            Tinjau Pengajuan <i data-lucide="chevron-right" class="h-3 w-3"></i>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Lembur Pending -->
            @if (Auth::user()->hasPermission('manage-overtime'))
                <div
                    class="group relative overflow-hidden rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center justify-between space-y-0 pb-2">
                        <h3 class="text-sm font-bold text-zinc-500 uppercase tracking-wider">Lembur Pending</h3>
                        <div
                            class="h-10 w-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center transition-transform group-hover:scale-110">
                            <i data-lucide="timer" class="h-5 w-5"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-3xl font-black text-zinc-900">{{ $stats['lembur_pending'] }}</div>
                        <p class="text-[10px] font-bold text-zinc-400 mt-1">PERMINTAAN LEMBUR BARU</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-zinc-50 text-[11px]">
                        <a href="{{ route('admin.overtime.index') }}"
                            class="font-bold text-purple-600 hover:text-purple-700 flex items-center gap-1">
                            Proses Lembur <i data-lucide="chevron-right" class="h-3 w-3"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Attendance -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50/50 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-zinc-900 text-sm">Presensi Terbaru Hari Ini</h3>
                        <p class="text-[10px] text-zinc-500 font-medium">Real-time update dari terminal presensi</p>
                    </div>
                    <i data-lucide="activity" class="h-4 w-4 text-emerald-500"></i>
                </div>
                <div class="p-0 flex-1">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-zinc-50/50 text-zinc-400 font-bold uppercase tracking-widest text-[10px]">
                                <tr>
                                    <th class="px-6 py-3">Pegawai</th>
                                    <th class="px-6 py-3">Jam Masuk</th>
                                    <th class="px-6 py-3">Lokasi</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-50">
                                @forelse($recent_presensi as $presensi)
                                    <tr class="hover:bg-zinc-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="h-7 w-7 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-400 shrink-0">
                                                    @if ($presensi->pegawai->foto)
                                                        <img src="{{ asset('storage/' . $presensi->pegawai->foto) }}"
                                                            class="h-full w-full rounded-full object-cover">
                                                    @else
                                                        <i data-lucide="user" class="h-3 w-3"></i>
                                                    @endif
                                                </div>
                                                <span
                                                    class="font-bold text-zinc-900 truncate max-w-[120px]">{{ $presensi->pegawai->nama_lengkap }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-black text-zinc-700">{{ $presensi->jam_masuk }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-1 text-zinc-500 truncate max-w-[150px]">
                                                <i data-lucide="map-pin" class="h-3 w-3 shrink-0"></i>
                                                {{ $presensi->lokasi_masuk ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if ($presensi->terlambat > 0)
                                                <span
                                                    class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full font-bold text-[9px] uppercase">Terlambat</span>
                                            @else
                                                <span
                                                    class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full font-bold text-[9px] uppercase">On
                                                    Time</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-zinc-400">
                                            <p class="italic text-[11px]">Belum ada data presensi hari ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Requests -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-zinc-900 text-sm">Pengajuan Terbaru</h3>
                    <i data-lucide="bell" class="h-4 w-4 text-orange-400"></i>
                </div>
                <div class="divide-y divide-zinc-50 p-2">
                    @forelse($recent_izin as $izin)
                        <div class="p-4 hover:bg-zinc-50 rounded-xl transition-colors">
                            <div class="flex items-start gap-3">
                                <div
                                    class="h-8 w-8 rounded-lg flex items-center justify-center shrink-0 {{ $izin->type == 'izin' ? 'bg-blue-50 text-blue-600' : 'bg-red-50 text-red-600' }}">
                                    <i data-lucide="{{ $izin->type == 'izin' ? 'file-text' : 'medical-box' }}"
                                        class="h-4 w-4"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-bold text-zinc-900 truncate">
                                        {{ $izin->pegawai->nama_lengkap }}</p>
                                    <p class="text-[10px] text-zinc-500 mt-0.5">{{ $izin->reason }}</p>
                                    <div class="flex items-center justify-between mt-2">
                                        <span
                                            class="text-[9px] font-bold text-zinc-400 uppercase">{{ $izin->created_at->diffForHumans() }}</span>
                                        <span
                                            class="px-1.5 py-0.5 rounded bg-zinc-100 text-[8px] font-bold uppercase tracking-wider text-zinc-500">{{ $izin->status }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-zinc-400 italic text-[11px]">
                            Tidak ada pengajuan terbaru
                        </div>
                    @endforelse
                </div>
                <div class="p-3 border-t border-zinc-50 bg-zinc-50/30">
                    <a href="{{ route('admin.izin.index') }}"
                        class="block w-full text-center py-2 text-[10px] font-bold text-zinc-400 hover:text-zinc-900 transition-colors uppercase tracking-widest">
                        Lihat Semua Pengajuan
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-zinc-900 rounded-3xl p-8 text-white relative overflow-hidden">
            <div class="absolute right-0 top-0 h-full w-1/3 bg-white/5 skew-x-[-20deg] translate-x-12"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div>
                    <h3 class="text-2xl font-black mb-2 italic tracking-tighter uppercase text-orange-500">KAI SYSTEM
                        ADMINISTRATION</h3>
                    <p class="text-zinc-400 text-sm max-w-md">Gunakan menu manajemen untuk mengatur operasional HRIS.
                        Pastikan data pegawai selalu diperbarui untuk keakuratan payroll.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    @if (Auth::user()->hasPermission('manage-users'))
                        <div
                            class="flex flex-col items-center px-6 py-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md">
                            <span class="text-2xl font-black text-white">{{ $stats['total_users'] }}</span>
                            <span class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest mt-1">Users</span>
                        </div>
                    @endif

                    @if (Auth::user()->hasPermission('manage-offices'))
                        <div
                            class="flex flex-col items-center px-6 py-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md">
                            <span class="text-2xl font-black text-white">{{ $stats['total_kantor'] }}</span>
                            <span class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest mt-1">Kantor</span>
                        </div>
                    @endif

                    @if (Auth::user()->hasPermission('manage-roles'))
                        <div
                            class="flex flex-col items-center px-6 py-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md">
                            <span class="text-2xl font-black text-white">{{ $stats['total_peran'] }}</span>
                            <span class="text-[9px] font-bold text-zinc-500 uppercase tracking-widest mt-1">Roles</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
