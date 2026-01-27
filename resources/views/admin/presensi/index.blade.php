@extends('layouts.app')

@section('title', 'Daftar Presensi Pegawai')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Monitoring Presensi</h2>
                <p class="text-zinc-500 text-sm">Pantau kehadiran seluruh pegawai secara real-time.</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-6">
            <form action="{{ route('admin.presensi.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Cari Pegawai</label>
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau NIP..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all appearance-none bg-no-repeat bg-[right_1rem_center] bg-[length:1em_1em]"
                        style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%239ca3af%22 stroke-width=%222%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M19 9l-7 7-7-7%22 /%3E%3C/svg%3E')">
                        <option value="">Semua Status</option>
                        <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Izin" {{ request('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Sakit" {{ request('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Alpa" {{ request('status') == 'Alpa' ? 'selected' : '' }}>Alpa</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full px-4 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 bg-zinc-900 text-white text-sm font-bold py-2 rounded-lg hover:bg-zinc-800 transition-all">Filter</button>
                    <a href="{{ route('admin.presensi.index') }}"
                        class="px-3 py-2 bg-zinc-100 text-zinc-600 rounded-lg hover:bg-zinc-200 transition-all">
                        <i data-lucide="rotate-ccw" class="h-4 w-4"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead
                        class="bg-zinc-50/50 border-b border-zinc-100 text-zinc-400 uppercase text-[11px] font-bold tracking-widest">
                        <tr>
                            <th class="px-6 py-4 w-12">No</th>
                            <th class="px-6 py-4">Pegawai</th>
                            <th class="px-6 py-4">Tanggal / Shift</th>
                            <th class="px-6 py-4">Masuk / Pulang</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($presensis as $presensi)
                            <tr class="hover:bg-zinc-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-zinc-400">
                                    {{ ($presensis->currentPage() - 1) * $presensis->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-10 w-10 rounded-full bg-zinc-100 overflow-hidden border border-zinc-200 shrink-0">
                                            @if ($presensi->pegawai->foto)
                                                <img src="{{ asset('storage/' . $presensi->pegawai->foto) }}"
                                                    class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center text-zinc-400">
                                                    <i data-lucide="user" class="h-5 w-5"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-zinc-900">{{ $presensi->pegawai->nama_lengkap }}
                                            </div>
                                            <div class="text-[11px] text-zinc-500 font-medium">NIP:
                                                {{ $presensi->pegawai->nip }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-zinc-900">
                                        {{ \Carbon\Carbon::parse($presensi->tanggal)->format('d M Y') }}</div>
                                    <div class="text-[11px] text-blue-600 font-bold uppercase tracking-tighter">
                                        {{ $presensi->shift->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-[10px] font-bold text-zinc-400 bg-zinc-100 px-1.5 rounded uppercase">In</span>
                                            <span
                                                class="font-bold {{ $presensi->terlambat > 0 ? 'text-red-600' : 'text-zinc-900' }}">{{ $presensi->jam_masuk ?? '--:--' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-[10px] font-bold text-zinc-400 bg-zinc-100 px-1.5 rounded uppercase">Out</span>
                                            <span
                                                class="font-bold {{ $presensi->pulang_cepat > 0 ? 'text-orange-600' : 'text-zinc-900' }}">{{ $presensi->jam_pulang ?? '--:--' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = [
                                            'Hadir' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'Izin' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'Sakit' => 'bg-orange-50 text-orange-600 border-orange-100',
                                            'Alpa' => 'bg-red-50 text-red-600 border-red-100',
                                        ];
                                        $class =
                                            $statusClasses[$presensi->status] ??
                                            'bg-zinc-50 text-zinc-600 border-zinc-100';
                                    @endphp
                                    <span
                                        class="px-2.5 py-1 rounded-lg border text-[11px] font-bold uppercase tracking-wider {{ $class }}">
                                        {{ $presensi->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-zinc-500 max-w-[150px] truncate">
                                        {{ $presensi->keterangan ?? '-' }}</div>
                                    @if ($presensi->status === 'Hadir')
                                        @if ($presensi->terlambat > 0)
                                            <div
                                                class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-red-50 text-red-600 text-[10px] font-bold uppercase mt-1">
                                                <i data-lucide="clock-alert" class="h-3 w-3"></i>
                                                Terlambat {{ $presensi->terlambat }}m
                                            </div>
                                        @else
                                            <div
                                                class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase mt-1">
                                                <i data-lucide="check-circle-2" class="h-3 w-3"></i>
                                                Tepat Waktu
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button"
                                            onclick="openEditModal('{{ $presensi->id }}', '{{ $presensi->status }}', '{{ $presensi->jam_masuk }}', '{{ $presensi->jam_pulang }}', '{{ $presensi->keterangan }}')"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                                            <i data-lucide="edit" class="h-4 w-4"></i>
                                        </button>
                                        <a href="{{ route('admin.presensi.show', $presensi->id) }}"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-zinc-500">
                                    <i data-lucide="inbox" class="h-12 w-12 mx-auto mb-4 text-zinc-200"></i>
                                    <p>Tidak ada data presensi yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($presensis->hasPages())
                <div class="px-6 py-4 border-t border-zinc-100 bg-zinc-50/50">
                    {{ $presensis->links() }}
                </div>
            @endif
        </div>
    </div>
    <!-- Edit Presence Modal -->
    <div id="edit-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div id="edit-modal-overlay"
                class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm transition-opacity opacity-0" aria-hidden="true"
                onclick="closeEditModal()"></div>

            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

            <!-- Modal Content -->
            <div id="edit-modal-content"
                class="relative inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md sm:align-middle opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <form id="edit-form" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-zinc-900" id="modal-title">Edit Presensi</h3>
                            <button type="button" onclick="closeEditModal()" class="text-zinc-400 hover:text-zinc-500">
                                <i data-lucide="x" class="h-5 w-5"></i>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-zinc-700 mb-1">Status Kehadiran</label>
                                <select name="status" id="edit-status" required
                                    class="w-full px-3 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900">
                                    <option value="Hadir">Hadir</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Alpa">Alpa</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-zinc-700 mb-1">Jam Masuk</label>
                                    <input type="time" name="jam_masuk" id="edit-jam-masuk"
                                        class="w-full px-3 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-zinc-700 mb-1">Jam Pulang</label>
                                    <input type="time" name="jam_pulang" id="edit-jam-pulang"
                                        class="w-full px-3 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-zinc-700 mb-1">Keterangan</label>
                                <textarea name="keterangan" id="edit-keterangan" rows="3"
                                    class="w-full px-3 py-2 rounded-lg border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900"
                                    placeholder="Opsional..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-zinc-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-zinc-800 transition-all sm:w-auto">
                            Simpan Perubahan
                        </button>
                        <button type="button" onclick="closeEditModal()"
                            class="inline-flex w-full justify-center rounded-xl bg-white border border-zinc-200 px-4 py-2.5 text-sm font-bold text-zinc-700 hover:bg-zinc-50 transition-all sm:w-auto">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, status, jamMasuk, jamPulang, keterangan) {
            const modal = document.getElementById('edit-modal');
            const form = document.getElementById('edit-form');
            const overlay = document.getElementById('edit-modal-overlay');
            const content = document.getElementById('edit-modal-content');

            // Set values
            form.action = `/admin/presensi/${id}`;
            document.getElementById('edit-status').value = status;
            document.getElementById('edit-jam-masuk').value = jamMasuk ? jamMasuk.substring(0, 5) : '';
            document.getElementById('edit-jam-pulang').value = jamPulang ? jamPulang.substring(0, 5) : '';
            document.getElementById('edit-keterangan').value = keterangan || '';

            // Show modal
            modal.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                content.classList.remove('opacity-0', 'translate-y-4', 'sm:scale-95');
                content.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);

            lucide.createIcons();
        }

        function closeEditModal() {
            const modal = document.getElementById('edit-modal');
            const overlay = document.getElementById('edit-modal-overlay');
            const content = document.getElementById('edit-modal-content');

            overlay.classList.add('opacity-0');
            content.classList.add('opacity-0', 'translate-y-4', 'sm:scale-95');
            content.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Close on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeEditModal();
            }
        });
    </script>
@endsection
