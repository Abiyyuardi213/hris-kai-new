@extends('layouts.app')
@section('title', 'Detail Lowongan')

@section('content')
    <div class="flex flex-col space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.recruitment.index') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <h2 class="text-3xl font-bold tracking-tight">Detail Lowongan</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Info Card -->
            <div class="lg:col-span-1 space-y-6">
                <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm space-y-4">
                    <div class="pb-4 border-b">
                        <h3 class="font-bold text-lg text-zinc-900">{{ $recruitment->title }}</h3>
                        <p class="text-xs text-zinc-500 uppercase tracking-wider">{{ $recruitment->position->name ?? '-' }}</p>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">Status</span>
                            <span class="font-bold uppercase text-[10px]">{{ $recruitment->status }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">Kuota</span>
                            <span class="font-bold">{{ $recruitment->quantity }} Orang</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-zinc-500">Deadline</span>
                            <span class="font-bold">{{ $recruitment->deadline ? \Carbon\Carbon::parse($recruitment->deadline)->format('d M Y') : '-' }}</span>
                        </div>
                    </div>

                    <div class="pt-4 flex gap-2">
                        <a href="{{ route('admin.recruitment.edit', $recruitment->id) }}" class="flex-1 h-10 flex items-center justify-center rounded-lg bg-zinc-900 text-xs font-bold text-white hover:bg-zinc-800 transition-all">Edit</a>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                    <h4 class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-3">Persyaratan</h4>
                    <div class="text-sm text-zinc-600 whitespace-pre-line leading-relaxed">
                        {{ $recruitment->requirements }}
                    </div>
                </div>
            </div>

            <!-- Applicants Table -->
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                    <div class="p-6 border-b bg-zinc-50/50">
                        <h3 class="font-bold text-zinc-900">Daftar Pelamar ({{ $recruitment->applications->count() }})</h3>
                    </div>
                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-zinc-50/50 text-zinc-500 border-b">
                                <tr>
                                    <th class="px-6 py-4 font-medium">Pelamar</th>
                                    <th class="px-6 py-4 font-medium">Status</th>
                                    <th class="px-6 py-4 font-medium">Tanggal Lamar</th>
                                    <th class="px-6 py-4 font-medium text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @forelse ($recruitment->applications as $app)
                                    <tr class="hover:bg-zinc-50/50">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-zinc-900">{{ $app->candidate->name }}</div>
                                            <div class="text-[10px] text-zinc-500">{{ $app->candidate->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-0.5 text-[10px] font-bold uppercase text-zinc-600">
                                                {{ $app->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-zinc-600">
                                            {{ $app->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button onclick="openStatusModal({{ $app->id }}, '{{ $app->status }}', '{{ $app->admin_notes }}')" class="text-zinc-400 hover:text-zinc-900">
                                                <i data-lucide="more-vertical" class="h-4 w-4"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-zinc-500 italic">Belum ada pelamar untuk lowongan ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
                        <div class="bg-white px-6 py-6">
                            <h3 class="text-lg font-bold text-zinc-900 mb-4">Update Status Pelamar</h3>
                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-zinc-400 uppercase">Status Seleksi</label>
                                    <select name="status" id="modalStatusInput" class="h-10 w-full rounded-lg border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none">
                                        <option value="pending">Pending</option>
                                        <option value="reviewing">Reviewing</option>
                                        <option value="interview">Interview</option>
                                        <option value="test">Test / Technical</option>
                                        <option value="hired">Hired (Diterima)</option>
                                        <option value="rejected">Rejected (Ditolak)</option>
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-zinc-400 uppercase">Catatan Admin</label>
                                    <textarea name="admin_notes" id="modalNotesInput" rows="3" class="w-full rounded-lg border border-zinc-200 p-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-zinc-50 px-6 py-4 flex justify-end gap-2">
                            <button type="button" onclick="closeModal('statusModal')" class="px-4 py-2 text-sm font-bold text-zinc-700 hover:bg-zinc-100 rounded-lg">Batal</button>
                            <button type="submit" class="px-6 py-2 bg-zinc-900 text-white text-sm font-bold rounded-lg hover:bg-zinc-800 transition-all">Update Status</button>
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
