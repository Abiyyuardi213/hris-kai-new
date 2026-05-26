@extends('layouts.app')

@section('title', 'Kalender Presensi Pegawai')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Kalender Presensi</h2>
                <p class="text-zinc-500 text-sm">Menampilkan riwayat presensi bulan {{ $date->translatedFormat('F Y') }}.</p>
            </div>
            <a href="{{ route('admin.presensi-pegawai.index') }}"
                class="bg-white border border-zinc-200 text-zinc-600 text-sm font-bold py-2 px-4 rounded-lg hover:bg-zinc-50 transition-all inline-flex items-center gap-2">
                <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali
            </a>
        </div>

        <!-- Employee Info & Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Employee Profile -->
            <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-6 flex items-center gap-4">
                <div class="h-16 w-16 rounded-full bg-zinc-100 overflow-hidden border border-zinc-200 shrink-0">
                    @if ($pegawai->foto)
                        <img src="{{ asset('storage/' . $pegawai->foto) }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center text-zinc-400">
                            <i data-lucide="user" class="h-8 w-8"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-bold text-zinc-900">{{ $pegawai->nama_lengkap }}</h3>
                    <p class="text-sm text-zinc-500">NIP: {{ $pegawai->nip }}</p>
                    <p class="text-xs font-medium text-zinc-400 mt-1">{{ $pegawai->jabatan->name ?? '-' }}</p>
                </div>
            </div>

            <!-- Stats -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-zinc-200 shadow-sm p-6 grid grid-cols-4 gap-4">
                <div class="bg-emerald-50 rounded-lg p-4 text-center border border-emerald-100">
                    <span class="block text-3xl font-bold text-emerald-600 mb-1">{{ $totalHadir }}</span>
                    <span class="block text-xs font-bold uppercase tracking-wider text-emerald-700">Hadir</span>
                </div>
                <div class="bg-blue-50 rounded-lg p-4 text-center border border-blue-100">
                    <span class="block text-3xl font-bold text-blue-600 mb-1">{{ $totalIzin }}</span>
                    <span class="block text-xs font-bold uppercase tracking-wider text-blue-700">Izin</span>
                </div>
                <div class="bg-orange-50 rounded-lg p-4 text-center border border-orange-100">
                    <span class="block text-3xl font-bold text-orange-600 mb-1">{{ $totalSakit }}</span>
                    <span class="block text-xs font-bold uppercase tracking-wider text-orange-700">Sakit</span>
                </div>
                <div class="bg-red-50 rounded-lg p-4 text-center border border-red-100">
                    <span class="block text-3xl font-bold text-red-600 mb-1">{{ $totalAlpa }}</span>
                    <span class="block text-xs font-bold uppercase tracking-wider text-red-700">Alpa</span>
                </div>
            </div>
        </div>

        <!-- Month Navigation -->
        <div class="bg-white rounded-xl border border-zinc-200 shadow-sm p-4 flex items-center justify-between">
            <a href="{{ route('admin.presensi-pegawai.show', ['id' => $pegawai->id, 'month' => $date->copy()->subMonth()->format('m'), 'year' => $date->copy()->subMonth()->format('Y')]) }}" 
                class="p-2 bg-zinc-100 rounded-lg text-zinc-600 hover:bg-zinc-200 transition-colors">
                <i data-lucide="chevron-left" class="h-5 w-5"></i>
            </a>
            <h3 class="text-xl font-bold text-zinc-900">{{ $date->translatedFormat('F Y') }}</h3>
            <a href="{{ route('admin.presensi-pegawai.show', ['id' => $pegawai->id, 'month' => $date->copy()->addMonth()->format('m'), 'year' => $date->copy()->addMonth()->format('Y')]) }}" 
                class="p-2 bg-zinc-100 rounded-lg text-zinc-600 hover:bg-zinc-200 transition-colors">
                <i data-lucide="chevron-right" class="h-5 w-5"></i>
            </a>
        </div>

        <!-- Calendar Grid -->
        <div class="bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
            @php
                $startOfMonth = $date->copy()->startOfMonth();
                $daysInMonth = $date->daysInMonth;
                // 1 (Monday) to 7 (Sunday)
                $startDayOfWeek = $startOfMonth->dayOfWeekIso;
            @endphp
            <div class="grid grid-cols-7 bg-zinc-50 border-b border-zinc-200 text-center text-xs font-bold text-zinc-400 uppercase tracking-widest">
                <div class="py-3">Senin</div>
                <div class="py-3">Selasa</div>
                <div class="py-3">Rabu</div>
                <div class="py-3">Kamis</div>
                <div class="py-3">Jumat</div>
                <div class="py-3 text-red-400">Sabtu</div>
                <div class="py-3 text-red-400">Minggu</div>
            </div>
            <div class="grid grid-cols-7">
                @for ($i = 1; $i < $startDayOfWeek; $i++)
                    <div class="min-h-[100px] border-b border-r border-zinc-100 bg-zinc-50/30"></div>
                @endfor

                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $currentDateStr = $date->copy()->setDay($day)->format('Y-m-d');
                        $presensi = $presensis->get($currentDateStr);
                        $isWeekend = in_array($date->copy()->setDay($day)->dayOfWeekIso, [6, 7]);
                    @endphp
                    <div class="min-h-[100px] border-b border-r border-zinc-100 p-2 relative cursor-pointer hover:bg-zinc-100 transition-colors {{ $isWeekend ? 'bg-red-50/20' : '' }}"
                         onclick="openCalendarEditModal('{{ $currentDateStr }}', '{{ $presensi ? $presensi->id : '' }}', '{{ $presensi ? $presensi->status : 'Hadir' }}', '{{ $presensi ? $presensi->jam_masuk : '' }}', '{{ $presensi ? $presensi->jam_pulang : '' }}', '{{ $presensi ? addslashes($presensi->keterangan) : '' }}')">
                        <span class="text-sm font-bold {{ $isWeekend ? 'text-red-400' : 'text-zinc-700' }}">{{ $day }}</span>
                        
                        @if ($presensi)
                            <div class="mt-2 space-y-1">
                                @php
                                    $statusClasses = [
                                        'Hadir' => 'bg-emerald-100 text-emerald-700',
                                        'Izin' => 'bg-blue-100 text-blue-700',
                                        'Sakit' => 'bg-orange-100 text-orange-700',
                                        'Alpa' => 'bg-red-100 text-red-700',
                                    ];
                                    $bgClass = $statusClasses[$presensi->status] ?? 'bg-zinc-100 text-zinc-700';
                                @endphp
                                <div class="px-2 py-1 rounded text-[10px] font-bold uppercase text-center {{ $bgClass }}">
                                    {{ $presensi->status }}
                                </div>
                                @if($presensi->status === 'Hadir')
                                    <div class="text-[10px] text-center font-medium text-zinc-500">
                                        <span class="{{ $presensi->terlambat > 0 ? 'text-red-500' : '' }}">{{ $presensi->jam_masuk ? substr($presensi->jam_masuk, 0, 5) : '-' }}</span>
                                        -
                                        <span class="{{ $presensi->pulang_cepat > 0 ? 'text-orange-500' : '' }}">{{ $presensi->jam_pulang ? substr($presensi->jam_pulang, 0, 5) : '-' }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endfor

                {{-- Fill remaining cells to complete the grid --}}
                @php
                    $totalCells = ($startDayOfWeek - 1) + $daysInMonth;
                    $remainingCells = 7 - ($totalCells % 7);
                    if ($remainingCells === 7) $remainingCells = 0;
                @endphp
                @for ($i = 0; $i < $remainingCells; $i++)
                    <div class="min-h-[100px] border-b border-r border-zinc-100 bg-zinc-50/30"></div>
                @endfor
            </div>
        </div>
    </div>
    </div>

    <!-- Edit Presence Modal for Calendar -->
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
                <form id="edit-form" method="POST" action="/admin/presensi">
                    @csrf
                    <!-- We will use a hidden input for _method to switch between PUT and POST -->
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    
                    <!-- Hidden inputs for Store (POST) -->
                    <input type="hidden" name="pegawai_id" value="{{ $pegawai->id }}">
                    <input type="hidden" name="tanggal" id="edit-tanggal" value="">

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
                            Simpan
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
@endsection

@push('scripts')
<script>
    function openCalendarEditModal(tanggal, presensiId, status, jamMasuk, jamPulang, keterangan) {
        const modal = document.getElementById('edit-modal');
        const form = document.getElementById('edit-form');
        const overlay = document.getElementById('edit-modal-overlay');
        const content = document.getElementById('edit-modal-content');
        const title = document.getElementById('modal-title');
        const methodInput = document.getElementById('form-method');

        // Set values
        document.getElementById('edit-tanggal').value = tanggal;
        document.getElementById('edit-status').value = status || 'Hadir';
        document.getElementById('edit-jam-masuk').value = jamMasuk ? jamMasuk.substring(0, 5) : '';
        document.getElementById('edit-jam-pulang').value = jamPulang ? jamPulang.substring(0, 5) : '';
        document.getElementById('edit-keterangan').value = keterangan || '';

        // Check if updating or creating
        if (presensiId) {
            form.action = `/admin/presensi/${presensiId}`;
            methodInput.value = 'PUT';
            title.textContent = `Edit Presensi (${tanggal})`;
        } else {
            form.action = `/admin/presensi`;
            methodInput.value = 'POST';
            title.textContent = `Tambah Presensi (${tanggal})`;
        }

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
@endpush
