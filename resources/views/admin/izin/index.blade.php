@extends('layouts.app')
@section('title', 'Manajemen Izin Pegawai')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Manajemen Izin & Sakit</h2>
            <button onclick="openModal('addIzinModal')"
                class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors shadow-sm">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Input Izin (Admin)
            </button>
        </div>

        <!-- Filters -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-zinc-200">
            <form action="{{ route('admin.izin.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Cari Pegawai</label>
                        <div class="relative">
                            <i data-lucide="search"
                                class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nama atau NIP..."
                                class="h-10 w-full rounded-lg border border-zinc-200 pl-10 pr-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Jenis</label>
                        <select name="type"
                            class="h-10 w-full rounded-lg border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                            <option value="">Semua Jenis</option>
                            <option value="izin" {{ request('type') == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ request('type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="cuti" {{ request('type') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                            <option value="dispensasi" {{ request('type') == 'dispensasi' ? 'selected' : '' }}>Dispensasi
                            </option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Status</label>
                        <select name="status"
                            class="h-10 w-full rounded-lg border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 h-10 items-center justify-center rounded-lg bg-zinc-900 px-4 text-sm font-bold text-white hover:bg-zinc-800 transition-all">
                            Filter
                        </button>
                        @if (request()->anyFilled(['search', 'type', 'status']))
                            <a href="{{ route('admin.izin.index') }}"
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
                            <th class="px-6 py-4 font-medium">Jenis & Tanggal</th>
                            <th class="px-6 py-4 font-medium">Alasan</th>
                            <th class="px-6 py-4 font-medium">Lampiran</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse ($izins as $izin)
                            <tr class="group hover:bg-zinc-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-zinc-900 capitalize">{{ $izin->pegawai->nama_lengkap }}
                                    </div>
                                    <div class="text-[10px] text-zinc-500 font-medium">NIP: {{ $izin->pegawai->nip }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-0.5 text-[10px] font-bold uppercase text-zinc-600 mb-1">
                                        {{ $izin->type }}
                                    </span>
                                    <div class="text-xs text-zinc-600">
                                        {{ \Carbon\Carbon::parse($izin->start_date)->format('d M') }} -
                                        {{ \Carbon\Carbon::parse($izin->end_date)->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-zinc-600 line-clamp-2 max-w-[200px]">{{ $izin->reason }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($izin->attachment)
                                        <a href="{{ asset('storage/' . $izin->attachment) }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700">
                                            <i data-lucide="file-text" class="h-3 w-3"></i>
                                            Lihat File
                                        </a>
                                    @else
                                        <span class="text-xs text-zinc-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'approved' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                            'rejected' => 'bg-red-50 text-red-700 ring-red-600/20',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold uppercase ring-1 ring-inset {{ $statusColors[$izin->status] }}">
                                        {{ $izin->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        @if ($izin->status === 'pending')
                                            <button onclick="openApprovalModal('{{ $izin->id }}')"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-emerald-600 hover:border-emerald-200 transition-colors"
                                                title="Proses Approval">
                                                <i data-lucide="check-circle" class="h-4 w-4"></i>
                                            </button>
                                        @endif
                                        <button onclick="confirmDelete('{{ $izin->id }}')"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-red-600 hover:border-red-200 transition-colors"
                                            title="Hapus">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-zinc-500">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="p-4 rounded-full bg-zinc-50 border border-zinc-100">
                                            <i data-lucide="file-clock" class="h-8 w-8 text-zinc-300"></i>
                                        </div>
                                        <div class="text-center">
                                            <p class="font-medium text-zinc-900">Belum ada pengajuan izin</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($izins->hasPages())
                <div class="p-4 border-t border-zinc-200 bg-zinc-50/50">
                    {{ $izins->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Add Izin (Admin filing for Employee) -->
    <div id="addIzinModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm" onclick="closeModal('addIzinModal')">
        </div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform border border-zinc-100 overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <form action="{{ route('admin.izin.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-zinc-900">Input Izin Pegawai</h3>
                                <button type="button" onclick="closeModal('addIzinModal')"
                                    class="text-zinc-400 hover:text-zinc-600">
                                    <i data-lucide="x" class="h-5 w-5"></i>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-zinc-900 mb-1">Pilih Pegawai</label>
                                    <select name="pegawai_id" required
                                        class="block w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                                        <option value="">-- Pilih Pegawai --</option>
                                        @foreach ($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->nip }} |
                                                {{ $emp->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-zinc-900 mb-1">Jenis Izin</label>
                                        <select name="type" required
                                            class="block w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                                            <option value="izin">Izin</option>
                                            <option value="sakit">Sakit</option>
                                            <option value="cuti">Cuti</option>
                                            <option value="dispensasi">Dispensasi</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-zinc-900 mb-1">Lampiran
                                            (Opsional)</label>
                                        <input type="file" name="attachment"
                                            class="block w-full text-[10px] text-zinc-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-zinc-900 mb-1">Dari Tanggal</label>
                                        <input type="date" name="start_date" required
                                            class="block w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-zinc-900 mb-1">Sampai Tanggal</label>
                                        <input type="date" name="end_date" required
                                            class="block w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-zinc-900 mb-1">Alasan</label>
                                    <textarea name="reason" rows="3" required
                                        class="block w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all"
                                        placeholder="Tuliskan alasan izin..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-zinc-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-zinc-100">
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-lg bg-zinc-900 px-6 py-2 text-sm font-bold text-white shadow-sm hover:bg-zinc-800 sm:ml-3 sm:w-auto transition-all">Simpan
                                Izin</button>
                            <button type="button" onclick="closeModal('addIzinModal')"
                                class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-6 py-2 text-sm font-bold text-zinc-700 shadow-sm ring-1 ring-inset ring-zinc-200 hover:bg-zinc-50 sm:mt-0 sm:w-auto transition-all">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Modal -->
    <div id="approvalModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm"
            onclick="closeModal('approvalModal')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform border border-zinc-100 overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                    <form id="approvalForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold text-zinc-900 mb-4">Approval Pengajuan Izin</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-zinc-900 mb-1">Keputusan</label>
                                    <select name="status" required
                                        class="block w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                                        <option value="approved">Setujui</option>
                                        <option value="rejected">Tolak</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-zinc-900 mb-1">Catatan Admin</label>
                                    <textarea name="admin_note" rows="3"
                                        class="block w-full rounded-lg border border-zinc-200 px-3 py-2 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all"
                                        placeholder="Tuliskan alasan persetujuan/penolakan..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-zinc-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-zinc-100">
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-lg bg-zinc-900 px-6 py-2 text-sm font-bold text-white shadow-sm hover:bg-zinc-800 sm:ml-3 sm:w-auto transition-all">Konfirmasi</button>
                            <button type="button" onclick="closeModal('approvalModal')"
                                class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-6 py-2 text-sm font-bold text-zinc-700 shadow-sm ring-1 ring-inset ring-zinc-200 hover:bg-zinc-50 sm:mt-0 sm:w-auto transition-all">Tutup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm" onclick="closeModal('deleteModal')">
        </div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="alert-triangle" class="h-5 w-5 text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-bold text-zinc-900">Hapus Data Pengajuan</h3>
                                <p class="mt-2 text-sm text-zinc-500">Anda yakin ingin menghapus data pengajuan izin ini?
                                    Tindakan ini tidak dapat dibatalkan.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-zinc-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-lg bg-red-600 px-6 py-2 text-sm font-bold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-all">Hapus
                                Permanen</button>
                        </form>
                        <button type="button" onclick="closeModal('deleteModal')"
                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-6 py-2 text-sm font-bold text-zinc-700 shadow-sm ring-1 ring-inset ring-zinc-200 hover:bg-zinc-50 sm:mt-0 sm:w-auto transition-all">Batalkan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function openApprovalModal(id) {
            document.getElementById('approvalForm').action = "{{ url('admin/izin') }}/" + id + "/status";
            openModal('approvalModal');
        }

        function confirmDelete(id) {
            document.getElementById('deleteForm').action = "{{ url('admin/izin') }}/" + id;
            openModal('deleteModal');
        }
    </script>
@endsection
