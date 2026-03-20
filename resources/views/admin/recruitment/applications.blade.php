@extends('layouts.app')
@section('title', 'Semua Lamaran Kerja')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Semua Lamaran</h2>
                <p class="text-sm text-zinc-500 mt-1">Pantau semua berkas lamaran yang masuk dari seluruh lowongan.</p>
            </div>
            <div class="flex items-center gap-2">
                 <!-- Add any global actions here if needed -->
            </div>
        </div>

        <!-- Filter Section -->
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
            <form action="{{ route('admin.recruitment.applications') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[240px]">
                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest block mb-1">Filter Lowongan</label>
                    <select name="vacancy_id" onchange="this.form.submit()" class="h-10 w-full rounded-lg border border-zinc-300 px-3 text-xs font-bold focus:ring-2 focus:ring-zinc-900 outline-none">
                        <option value="">Semua Lowongan</option>
                        @foreach(\App\Models\JobVacancy::orderBy('judul_lowongan')->get() as $v)
                            <option value="{{ $v->id }}" {{ request('vacancy_id') == $v->id ? 'selected' : '' }}>{{ $v->judul_lowongan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('admin.recruitment.applications') }}" class="h-10 px-4 inline-flex items-center text-xs font-bold text-zinc-400 hover:text-zinc-900 transition-colors uppercase italic underline">Reset</a>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50/50 text-zinc-500 border-b uppercase text-[10px] font-black tracking-widest">
                        <tr>
                            <th class="px-6 py-4 font-medium">Pelamar</th>
                            <th class="px-6 py-4 font-medium">Lowongan Diambil</th>
                            <th class="px-6 py-4 font-medium">Status Saat Ini</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 italic font-medium">
                        @forelse ($applications as $app)
                            <tr class="hover:bg-zinc-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-zinc-900 uppercase">{{ $app->candidate->name }}</div>
                                    <div class="text-[10px] text-zinc-400 tracking-tighter italic">{{ $app->candidate->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-zinc-600 block truncate max-w-[200px]">{{ $app->jobVacancy->judul_lowongan }}</span>
                                    <span class="text-[10px] text-zinc-400 font-bold uppercase">{{ $app->created_at->format('d/m/Y') }}</span>
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
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.recruitment.show', $app->job_vacancy_id) }}" class="p-2 rounded-lg bg-zinc-50 border border-zinc-200 text-zinc-400 hover:text-zinc-900 hover:border-zinc-300 transition-all" title="Lihat Lowongan">
                                            <i data-lucide="external-link" class="h-4 w-4"></i>
                                        </a>
                                        <button onclick="openStatusModal({{ $app->id }}, '{{ $app->status }}', '{{ $app->admin_notes }}')" class="p-2 rounded-lg bg-zinc-50 border border-zinc-200 text-zinc-400 hover:text-zinc-900 hover:border-zinc-300 transition-all">
                                            <i data-lucide="edit-2" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-zinc-400 italic uppercase tracking-widest text-xs font-bold">Belum ada lamaran masuk.</td>
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
