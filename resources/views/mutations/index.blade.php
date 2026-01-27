@extends('layouts.app')
@section('title', 'Mutasi')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Riwayat Mutasi</h2>
            <a href="{{ route('mutations.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors shadow-sm">
                <i data-lucide="arrow-right-left" class="h-4 w-4"></i>
                Proses Mutasi
            </a>
        </div>

        <!-- Content -->
        <div class="space-y-4">
            <!-- Filters -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-zinc-200">
                <form action="{{ route('mutations.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Cari
                                Pegawai</label>
                            <div class="relative">
                                <i data-lucide="search"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Nama / NIP..."
                                    class="h-10 w-full rounded-lg border border-zinc-200 pl-10 pr-3 text-sm focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 outline-none transition-all">
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Jenis
                                Mutasi</label>
                            <select name="type"
                                class="h-10 w-full rounded-lg border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                                <option value="">Semua Jenis</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                        {{ strtoupper($type) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Division -->
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Divisi
                                (Tujuan)</label>
                            <select name="division"
                                class="h-10 w-full rounded-lg border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                                <option value="">Semua Divisi</option>
                                @foreach ($divisions as $div)
                                    <option value="{{ $div->id }}"
                                        {{ request('division') == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Office -->
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Unit Kerja
                                (Tujuan)</label>
                            <select name="office"
                                class="h-10 w-full rounded-lg border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                                <option value="">Semua Unit</option>
                                @foreach ($offices as $off)
                                    <option value="{{ $off->id }}"
                                        {{ request('office') == $off->id ? 'selected' : '' }}>{{ $off->office_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Start Date -->
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Mulai
                                Tanggal</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="h-10 w-full rounded-lg border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                        </div>

                        <!-- End Date -->
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Sampai
                                Tanggal</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="h-10 w-full rounded-lg border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                        </div>

                        <!-- Action Buttons -->
                        <div class="lg:col-span-2 flex items-end gap-2">
                            <button type="submit"
                                class="flex-1 h-10 items-center justify-center rounded-lg bg-zinc-900 px-4 text-sm font-bold text-white hover:bg-zinc-800 transition-all active:scale-[0.98]">
                                Terapkan Filter
                            </button>
                            @if (request()->anyFilled(['search', 'type', 'division', 'office', 'start_date', 'end_date']))
                                <a href="{{ route('mutations.index') }}"
                                    class="h-10 flex items-center justify-center rounded-lg border border-zinc-200 bg-white px-4 text-sm font-bold text-zinc-700 hover:bg-zinc-50 transition-all">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-zinc-50/50 text-zinc-500 border-b border-zinc-200">
                            <tr>
                                <th class="px-6 py-4 font-medium">Pegawai</th>
                                <th class="px-6 py-4 font-medium">Jenis</th>
                                <th class="px-6 py-4 font-medium">Dari</th>
                                <th class="px-6 py-4 font-medium">Ke</th>
                                <th class="px-6 py-4 font-medium">Tanggal Efektif</th>
                                <th class="px-6 py-4 font-medium text-right w-[100px]">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse ($mutations as $mutation)
                                <tr class="group hover:bg-zinc-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-zinc-900">{{ $mutation->employee->nama_lengkap }}
                                        </div>
                                        <div class="text-xs text-zinc-500">{{ $mutation->employee->nip }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium bg-blue-50 text-blue-700 uppercase ring-1 ring-inset ring-blue-700/10">
                                            {{ $mutation->type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <div class="text-zinc-500">Div: <span
                                                class="text-zinc-900">{{ $mutation->fromDivision->name ?? '-' }}</span>
                                        </div>
                                        <div class="text-zinc-500">Pos: <span
                                                class="text-zinc-900">{{ $mutation->fromPosition->name ?? '-' }}</span>
                                        </div>
                                        <div class="text-zinc-500">Off: <span
                                                class="text-zinc-900">{{ $mutation->fromOffice->office_name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <div class="text-zinc-500">Div: <span
                                                class="text-zinc-900">{{ $mutation->toDivision->name ?? '-' }}</span></div>
                                        <div class="text-zinc-500">Pos: <span
                                                class="text-zinc-900">{{ $mutation->toPosition->name ?? '-' }}</span></div>
                                        <div class="text-zinc-500">Off: <span
                                                class="text-zinc-900">{{ $mutation->toOffice->office_name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-zinc-600">
                                        {{ \Carbon\Carbon::parse($mutation->mutation_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button type="button"
                                            onclick="showMutationDetail({{ json_encode([
                                                'pegawai' => $mutation->employee->nama_lengkap,
                                                'nip' => $mutation->employee->nip,
                                                'type' => strtoupper($mutation->type),
                                                'date' => \Carbon\Carbon::parse($mutation->mutation_date)->format('d M Y'),
                                                'reason' => $mutation->reason,
                                                'from' => [
                                                    'div' => $mutation->fromDivision->name ?? '-',
                                                    'pos' => $mutation->fromPosition->name ?? '-',
                                                    'off' => $mutation->fromOffice->office_name ?? '-',
                                                ],
                                                'to' => [
                                                    'div' => $mutation->toDivision->name ?? '-',
                                                    'pos' => $mutation->toPosition->name ?? '-',
                                                    'off' => $mutation->toOffice->office_name ?? '-',
                                                ],
                                                'file' => $mutation->file_sk ? asset('storage/' . $mutation->file_sk) : null,
                                            ]) }})"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-zinc-900 transition-colors">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </button>
                                        <a href="{{ route('mutations.show', $mutation->id) }}"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-zinc-900 transition-colors ml-1">
                                            <i data-lucide="file-text" class="h-4 w-4"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-zinc-500">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <i data-lucide="history" class="h-8 w-8 text-zinc-300"></i>
                                            <div class="text-center">
                                                <p class="font-medium text-zinc-900">Belum ada riwayat mutasi</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($mutations->hasPages())
                    <div class="p-4 border-t border-zinc-200 bg-zinc-50/50">
                        {{ $mutations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Mutation Detail Modal -->
    <div id="mutationModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background Overlay -->
            <div class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="closeMutationModal()"></div>

            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

            <!-- Modal Content -->
            <div
                class="relative inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:align-middle">
                <div class="bg-white">
                    <!-- Header -->
                    <div class="flex items-center justify-between border-b border-zinc-100 px-6 py-4">
                        <div>
                            <h3 class="text-lg font-bold text-zinc-900" id="modal-title">Detail Mutasi Pegawai</h3>
                            <p class="text-xs text-zinc-500 mt-0.5" id="md-sub-title"></p>
                        </div>
                        <button type="button" onclick="closeMutationModal()"
                            class="rounded-lg p-2 text-zinc-400 hover:bg-zinc-50 hover:text-zinc-500 transition-colors">
                            <i data-lucide="x" class="h-5 w-5"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-6 overflow-y-auto max-h-[70vh]">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Basic Info -->
                            <div class="space-y-6">
                                <div>
                                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Status
                                        Perubahan</p>
                                    <span id="md-type"
                                        class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-bold bg-blue-50 text-blue-700 uppercase ring-1 ring-inset ring-blue-700/10"></span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Tanggal
                                        Efektif</p>
                                    <p id="md-date" class="text-sm font-bold text-zinc-900"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-1">Alasan /
                                        Catatan</p>
                                    <p id="md-reason" class="text-sm text-zinc-600 leading-relaxed"></p>
                                </div>
                                <div id="md-file-container" class="hidden">
                                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-2">Dokumen
                                        SK</p>
                                    <a id="md-file-link" href="#" target="_blank"
                                        class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-zinc-200 bg-zinc-50 text-sm font-medium text-zinc-700 hover:bg-zinc-100 transition-colors">
                                        <i data-lucide="file-text" class="h-4 w-4"></i>
                                        Lihat Dokumen SK
                                    </a>
                                </div>
                            </div>

                            <!-- Comparison -->
                            <div class="space-y-6">
                                <div class="rounded-xl border border-zinc-100 bg-zinc-50/50 p-4">
                                    <p
                                        class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                        <span class="h-1.5 w-1.5 rounded-full bg-zinc-300"></span>
                                        Posisi Lama
                                    </p>
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-[10px] text-zinc-500 uppercase">Divisi</p>
                                            <p id="md-from-div" class="text-sm font-bold text-zinc-900"></p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-zinc-500 uppercase">Jabatan</p>
                                            <p id="md-from-pos" class="text-sm font-bold text-zinc-900"></p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-zinc-500 uppercase">Unit Kerja</p>
                                            <p id="md-from-off" class="text-sm font-bold text-zinc-900"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-xl border border-blue-100 bg-blue-50/30 p-4">
                                    <p
                                        class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                        Posisi Baru
                                    </p>
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-[10px] text-blue-500/60 uppercase">Divisi</p>
                                            <p id="md-to-div" class="text-sm font-bold text-zinc-900"></p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-blue-500/60 uppercase">Jabatan</p>
                                            <p id="md-to-pos" class="text-sm font-bold text-zinc-900"></p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-blue-500/60 uppercase">Unit Kerja</p>
                                            <p id="md-to-off" class="text-sm font-bold text-zinc-900"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="border-t border-zinc-100 bg-zinc-50 px-6 py-4 flex justify-end">
                        <button type="button" onclick="closeMutationModal()"
                            class="rounded-lg border border-zinc-200 bg-white px-6 py-2 text-sm font-bold text-zinc-700 hover:bg-zinc-50 transition-colors">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showMutationDetail(data) {
                document.getElementById('md-sub-title').textContent = `${data.pegawai} • ${data.nip}`;
                document.getElementById('md-type').textContent = data.type;
                document.getElementById('md-date').textContent = data.date;
                document.getElementById('md-reason').textContent = data.reason;

                document.getElementById('md-from-div').textContent = data.from.div;
                document.getElementById('md-from-pos').textContent = data.from.pos;
                document.getElementById('md-from-off').textContent = data.from.off;

                document.getElementById('md-to-div').textContent = data.to.div;
                document.getElementById('md-to-pos').textContent = data.to.pos;
                document.getElementById('md-to-off').textContent = data.to.off;

                const fileContainer = document.getElementById('md-file-container');
                if (data.file) {
                    fileContainer.classList.remove('hidden');
                    document.getElementById('md-file-link').href = data.file;
                } else {
                    fileContainer.classList.add('hidden');
                }

                const modal = document.getElementById('mutationModal');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                lucide.createIcons();
            }

            function closeMutationModal() {
                const modal = document.getElementById('mutationModal');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        </script>
    @endpush
@endsection
