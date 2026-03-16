@extends('layouts.app')
@section('title', 'Manajemen Rekrutmen')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Manajemen Rekrutmen</h2>
                <p class="text-zinc-500">Kelola lowongan pekerjaan dan proses seleksi kandidat.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.recruitment.applications') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 transition-colors shadow-sm">
                    <i data-lucide="users" class="h-4 w-4"></i>
                    Semua Pelamar
                </a>
                <a href="{{ route('admin.recruitment.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors shadow-sm">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Buat Lowongan
                </a>
            </div>
        </div>

        <!-- Vacancy Cards/Table -->
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50/50 text-zinc-500 border-b border-zinc-200">
                        <tr>
                            <th class="px-6 py-4 font-medium">Lowongan / Posisi</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium text-center">Pelamar</th>
                            <th class="px-6 py-4 font-medium">Deadline</th>
                            <th class="px-6 py-4 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse ($vacancies as $item)
                            <tr class="group hover:bg-zinc-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-zinc-900">{{ $item->title }}</div>
                                    <div class="text-[10px] text-zinc-500 uppercase tracking-wider">
                                        {{ $item->position->name ?? 'Posisi Tidak Ditemukan' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statuses = [
                                            'draft' => 'bg-zinc-100 text-zinc-600 ring-zinc-500/20',
                                            'open' => 'bg-emerald-100 text-emerald-700 ring-emerald-600/20',
                                            'closed' => 'bg-red-100 text-red-700 ring-red-600/20',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold uppercase ring-1 ring-inset {{ $statuses[$item->status] }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $item->applications_count }} Orang
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-zinc-600">
                                    {{ $item->deadline ? \Carbon\Carbon::parse($item->deadline)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.recruitment.show', $item->id) }}"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-blue-600 hover:border-blue-200 transition-colors">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>
                                        <a href="{{ route('admin.recruitment.edit', $item->id) }}"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-amber-600 hover:border-amber-200 transition-colors">
                                            <i data-lucide="edit-3" class="h-4 w-4"></i>
                                        </a>
                                        <button onclick="confirmDelete('{{ $item->id }}')"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-red-600 hover:border-red-200 transition-colors">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-zinc-500">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="p-4 rounded-full bg-zinc-50 border border-zinc-100">
                                            <i data-lucide="briefcase" class="h-8 w-8 text-zinc-300"></i>
                                        </div>
                                        <p class="font-medium text-zinc-900">Belum ada lowongan pekerjaan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($vacancies->hasPages())
                <div class="p-4 border-t border-zinc-200">
                    {{ $vacancies->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm" onclick="closeModal('deleteModal')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="alert-triangle" class="h-5 w-5 text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-bold text-zinc-900">Hapus Lowongan</h3>
                                <p class="mt-2 text-sm text-zinc-500">Anda yakin ingin menghapus lowongan ini? Seluruh data lamaran terkait juga akan terhapus.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-zinc-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-6 py-2 text-sm font-bold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-all">Hapus</button>
                        </form>
                        <button type="button" onclick="closeModal('deleteModal')" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-6 py-2 text-sm font-bold text-zinc-700 shadow-sm ring-1 ring-inset ring-zinc-200 hover:bg-zinc-50 sm:mt-0 sm:w-auto transition-all">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
        function confirmDelete(id) {
            document.getElementById('deleteForm').action = "{{ url('admin/recruitment') }}/" + id;
            openModal('deleteModal');
        }
    </script>
@endsection
