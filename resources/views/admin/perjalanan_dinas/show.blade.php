@extends('layouts.app')
@section('title', 'Detail Perjalanan Dinas')

@section('content')
    <div class="flex flex-col space-y-6 max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.perjalanan_dinas.index') }}"
                    class="h-10 w-10 rounded-xl border border-zinc-200 bg-white flex items-center justify-center text-zinc-500 hover:text-zinc-900 transition-all hover:bg-zinc-50 shadow-sm">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Detail Perjalanan Dinas</h2>
                    <p class="text-zinc-500 text-sm">Informasi lengkap penugasan dan status perjalanan dinas.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $colors = [
                        'Pengajuan' => 'bg-amber-50 text-amber-600 border-amber-100',
                        'Disetujui' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'Ditolak' => 'bg-red-50 text-red-600 border-red-100',
                        'Sedang Berjalan' => 'bg-blue-50 text-blue-600 border-blue-100',
                        'Selesai' => 'bg-zinc-50 text-zinc-600 border-zinc-100',
                        'Dibatalkan' => 'bg-zinc-50 text-zinc-400 border-zinc-100',
                    ];
                    $class = $colors[$trip->status] ?? 'bg-zinc-50 text-zinc-600 border-zinc-100';
                @endphp
                <span
                    class="px-4 py-1.5 rounded-full border text-xs font-bold uppercase tracking-widest {{ $class }}">
                    {{ $trip->status }}
                </span>
            </div>
        </div>

        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center gap-3">
                <i data-lucide="check-circle" class="h-5 w-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50/50 flex items-center justify-between">
                        <h3 class="font-bold text-zinc-900 text-sm">Informasi Perjalanan</h3>
                        <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">NO. SURAT:
                            {{ $trip->no_surat_tugas ?? 'MENUNGGU NOMOR' }}</div>
                    </div>
                    <div class="p-8 space-y-8">
                        <div>
                            <h4 class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Tujuan &
                                Keperluan</h4>
                            <p class="text-2xl font-black text-zinc-900 italic tracking-tighter uppercase leading-tight">
                                {{ $trip->tujuan }}</p>
                            <p
                                class="text-zinc-600 mt-4 leading-relaxed text-sm bg-zinc-50 p-4 rounded-xl border border-zinc-100 italic">
                                "{{ $trip->keperluan }}"</p>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <h4 class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Tanggal Mulai
                                </h4>
                                <p class="text-sm font-bold text-zinc-900">{{ $trip->tanggal_mulai->format('d F Y') }}</p>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Tanggal
                                    Selesai</h4>
                                <p class="text-sm font-bold text-zinc-900">{{ $trip->tanggal_selesai->format('d F Y') }}</p>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Transportasi
                                </h4>
                                <p class="text-sm font-bold text-zinc-900">{{ $trip->jenis_transportasi ?? '-' }}</p>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Estimasi
                                    Biaya</h4>
                                <p class="text-sm font-bold text-emerald-600">Rp
                                    {{ number_format($trip->estimasi_biaya, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Peserta List -->
                <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50/50 flex items-center justify-between">
                        <h3 class="font-bold text-zinc-900 text-sm">Daftar Peserta Perjalanan</h3>
                        <span
                            class="text-[10px] bg-zinc-900 text-white px-2 py-0.5 rounded font-black tracking-widest uppercase">{{ $trip->pegawaiPeserta->count() }}
                            Orang</span>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-zinc-50">
                                @foreach ($trip->pegawaiPeserta as $peserta)
                                    <tr class="hover:bg-zinc-50/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="h-10 w-10 rounded-xl bg-zinc-100 overflow-hidden border border-zinc-200 shrink-0">
                                                    @if ($peserta->foto)
                                                        <img src="{{ asset('storage/' . $peserta->foto) }}"
                                                            class="h-full w-full object-cover">
                                                    @else
                                                        <div
                                                            class="h-full w-full flex items-center justify-center text-zinc-400">
                                                            <i data-lucide="user" class="h-5 w-5"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="font-bold text-zinc-900">{{ $peserta->nama_lengkap }}</div>
                                                    <div class="text-[11px] text-zinc-500 font-medium">NIP:
                                                        {{ $peserta->nip }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-[11px] font-bold text-zinc-600 uppercase">
                                                {{ $peserta->jabatan->name ?? '-' }}</div>
                                            <div class="text-[10px] text-zinc-400">{{ $peserta->divisi->name ?? '-' }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar / Action -->
            <div class="space-y-6">
                <div class="sticky top-20 space-y-6">
                    <!-- Status Update Form -->
                    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-900">
                            <h3 class="font-bold text-white text-sm lowercase tracking-tighter">update_status_perjalanan
                            </h3>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('admin.perjalanan_dinas.update-status', $trip->id) }}" method="POST"
                                class="space-y-5">
                                @csrf
                                @method('PATCH')

                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Status
                                        Pelaksanaan</label>
                                    <select name="status" required
                                        class="w-full px-4 py-2.5 rounded-xl border border-zinc-200 text-sm font-bold focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                                        @foreach (['Pengajuan', 'Disetujui', 'Ditolak', 'Sedang Berjalan', 'Selesai', 'Dibatalkan'] as $status)
                                            <option value="{{ $status }}"
                                                {{ $trip->status == $status ? 'selected' : '' }}>{{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">No. Surat
                                        Tugas</label>
                                    <input type="text" name="no_surat_tugas"
                                        value="{{ old('no_surat_tugas', $trip->no_surat_tugas) }}"
                                        class="w-full px-4 py-2.5 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all font-medium">
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Realisasi Biaya
                                        Akhir (Rp)</label>
                                    <input type="number" name="realisasi_biaya"
                                        value="{{ old('realisasi_biaya', $trip->realisasi_biaya ?? $trip->estimasi_biaya) }}"
                                        class="w-full px-4 py-2.5 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all font-black text-emerald-600">
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Catatan
                                        Admin</label>
                                    <textarea name="catatan_persetujuan" rows="3"
                                        class="w-full px-4 py-2.5 rounded-xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all"
                                        placeholder="Masukkan alasan jika disetujui/ditolak...">{{ old('catatan_persetujuan', $trip->catatan_persetujuan) }}</textarea>
                                </div>

                                <button type="submit"
                                    class="w-full py-3.5 rounded-2xl bg-zinc-900 text-white font-bold text-sm shadow-xl hover:bg-zinc-800 transition-all active:scale-95 flex items-center justify-center gap-2">
                                    <i data-lucide="check-circle" class="h-4 w-4"></i>
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Approval Info -->
                    @if ($trip->disetujui_oleh)
                        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6">
                            <h4 class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mb-3">Status
                                Persetujuan
                            </h4>
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-10 w-10 rounded-xl bg-white flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-100">
                                    <i data-lucide="shield-check" class="h-5 w-5"></i>
                                </div>
                                @if ($trip->pengetuju)
                                    <div>
                                        <p class="text-xs font-bold text-emerald-900">{{ $trip->pengetuju->name }}</p>
                                        <p class="text-[9px] text-emerald-600 font-bold uppercase">
                                            {{ $trip->updated_at->format('d M Y H:i') }}</p>
                                    </div>
                                @else
                                    <div>
                                        <p class="text-xs font-bold text-emerald-900">SYSTEM</p>
                                        <p class="text-[9px] text-emerald-600 font-bold uppercase">
                                            {{ $trip->updated_at->format('d M Y H:i') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
