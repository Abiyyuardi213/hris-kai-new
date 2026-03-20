@extends('layouts.app')
@section('title', 'Daftar Pelamar - ' . $recruitment->judul_lowongan)

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.recruitment.show', $recruitment->id) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-zinc-900 transition-colors">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Daftar Pelamar</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-sm text-zinc-500 font-medium">{{ $recruitment->judul_lowongan }}</span>
                        <span class="w-1 h-1 rounded-full bg-zinc-300"></span>
                        <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest">{{ $applications->total() }} Total Pelamar</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
            <div class="p-6 border-b bg-zinc-50/50 flex items-center justify-between">
                <h3 class="font-bold text-zinc-900 uppercase text-xs tracking-widest italic">Data Pelamar Masuk</h3>
            </div>
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50/50 text-zinc-500 border-b uppercase text-[10px] font-black tracking-widest">
                        <tr>
                            <th class="px-6 py-4 font-medium">Data Pelamar</th>
                            <th class="px-6 py-4 font-medium">Pendidikan Terakhir</th>
                            <th class="px-6 py-4 font-medium">Status Saat Ini</th>
                            <th class="px-6 py-4 font-medium">Tanggal Lamar</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 italic">
                        @forelse ($applications as $app)
                            <tr class="hover:bg-zinc-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-zinc-900 group-hover:text-blue-600 transition-colors uppercase">{{ $app->candidate->name }}</div>
                                    <div class="text-[10px] text-zinc-400 font-bold tracking-tighter">{{ $app->candidate->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-zinc-700 uppercase">{{ $app->candidate->last_education ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $appStatuses = [
                                            'pending' => 'bg-zinc-100 text-zinc-600',
                                            'reviewing' => 'bg-blue-100 text-blue-700',
                                            'interview' => 'bg-amber-100 text-amber-700',
                                            'test' => 'bg-purple-100 text-purple-700',
                                            'hired' => 'bg-emerald-100 text-emerald-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[9px] font-black uppercase {{ $appStatuses[$app->status] ?? 'bg-zinc-100 text-zinc-600' }}">
                                        {{ $app->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-zinc-500">
                                    {{ $app->created_at->format('d F Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button onclick="openStatusModal({{ $app->id }}, '{{ $app->status }}', '{{ $app->admin_notes }}')" class="p-2 rounded-lg bg-zinc-50 border border-zinc-200 text-zinc-400 hover:text-zinc-900 hover:border-zinc-300 transition-all">
                                        <i data-lucide="more-vertical" class="h-4 w-4"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-zinc-400 italic uppercase tracking-widest text-xs font-bold">Belum ada pelamar yang mendaftar ke lowongan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($applications->hasPages())
                <div class="p-4 border-t bg-zinc-50/50">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm" onclick="closeModal('statusModal')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                    <form id="statusForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="bg-white px-8 py-8">
                            <h3 class="text-lg font-black text-zinc-900 uppercase tracking-widest italic mb-6 border-b pb-4">Update Status Seleksi Pelamar</h3>
                            <div class="space-y-6">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Status Seleksi</label>
                                    <select name="status" id="modalStatusInput" class="h-11 w-full rounded-lg border border-zinc-300 px-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none">
                                        <option value="pending">PENDING</option>
                                        <option value="reviewing">REVIEWING</option>
                                        <option value="interview">INTERVIEW</option>
                                        <option value="test">TEST / TECHNICAL</option>
                                        <option value="hired">HIRED (DITERIMA)</option>
                                        <option value="rejected">REJECTED (DITOLAK)</option>
                                    </select>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Catatan Tim Rekrutmen</label>
                                    <textarea name="admin_notes" id="modalNotesInput" rows="3" placeholder="Masukkan catatan hasil review atau alasan seleksi..." class="w-full rounded-lg border border-zinc-300 p-4 text-sm font-medium focus:ring-2 focus:ring-zinc-900 outline-none"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-zinc-50 px-8 py-5 flex justify-end gap-3 rounded-b-xl border-t">
                            <button type="button" onclick="closeModal('statusModal')" class="px-6 py-2.5 text-xs font-black uppercase tracking-widest text-zinc-400 hover:text-zinc-600">Batal</button>
                            <button type="submit" class="px-8 py-2.5 bg-zinc-900 text-white text-xs font-black uppercase tracking-widest rounded shadow-xl hover:bg-zinc-800 transition-all">Simpan Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openStatusModal(appId, currentStatus, currentNotes) {
            document.getElementById('statusForm').action = "/admin/recruitment/applications/" + appId + "/status";
            document.getElementById('modalStatusInput').value = currentStatus;
            document.getElementById('modalNotesInput').value = currentNotes || '';
            document.getElementById('statusModal').classList.remove('hidden');
        }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
    </script>
@endsection
