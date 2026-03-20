@extends('layouts.app')
@section('title', 'Detail Lowongan')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.recruitment.index') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-zinc-900 transition-colors">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900">{{ $recruitment->judul_lowongan }}</h2>
                    <div class="flex items-center gap-3 mt-1 text-xs text-zinc-500 font-bold uppercase tracking-wider">
                        <span class="flex items-center gap-1"><i data-lucide="calendar" class="h-3 w-3"></i> {{ \Carbon\Carbon::parse($recruitment->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($recruitment->end_date)->format('d M Y') }}</span>
                        <span class="w-1 h-1 rounded-full bg-zinc-300"></span>
                        <span class="px-2 py-0.5 rounded bg-zinc-100 {{ $recruitment->status == 'open' ? 'text-emerald-600 bg-emerald-50' : 'text-red-600 bg-red-50' }}">{{ $recruitment->status }}</span>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.recruitment.edit', $recruitment->id) }}" class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 transition-colors shadow-sm">
                    <i data-lucide="edit-3" class="h-4 w-4"></i>
                    Edit Lowongan
                </a>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div x-data="{ activeTab: 'description' }">
            <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-zinc-200 gap-4 mb-6">
                <div class="flex items-center gap-2">
                    <button @click="activeTab = 'description'" :class="activeTab === 'description' ? 'border-zinc-900 text-zinc-900' : 'border-transparent text-zinc-400 hover:text-zinc-600'" class="px-4 py-3 text-xs font-black uppercase tracking-widest border-b-2 transition-all">
                        <div class="flex items-center gap-2">
                            <i data-lucide="file-text" class="h-4 w-4"></i>
                            Deskripsi
                        </div>
                    </button>
                    <button @click="activeTab = 'requirements'" :class="activeTab === 'requirements' ? 'border-zinc-900 text-zinc-900' : 'border-transparent text-zinc-400 hover:text-zinc-600'" class="px-4 py-3 text-xs font-black uppercase tracking-widest border-b-2 transition-all">
                        <div class="flex items-center gap-2">
                            <i data-lucide="check-square" class="h-4 w-4"></i>
                            Persyaratan
                        </div>
                    </button>
                    <button @click="activeTab = 'formations'" :class="activeTab === 'formations' ? 'border-zinc-900 text-zinc-900' : 'border-transparent text-zinc-400 hover:text-zinc-600'" class="px-4 py-3 text-xs font-black uppercase tracking-widest border-b-2 transition-all">
                        <div class="flex items-center gap-2">
                            <i data-lucide="layout-grid" class="h-4 w-4"></i>
                            Formasi
                        </div>
                    </button>
                </div>

                <div class="flex pb-4 md:pb-0">
                    <a href="{{ route('admin.recruitment.applicants.show', $recruitment->id) }}" class="inline-flex items-center gap-2 px-6 py-2 bg-emerald-600 text-white text-xs font-black uppercase tracking-[0.2em] rounded-lg hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100 italic">
                        <i data-lucide="users" class="h-4 w-4"></i>
                        Lihat Pelamar ({{ $recruitment->applications->count() }})
                    </a>
                </div>
            </div>

            <!-- Tab Contents -->
            <div class="w-full" x-show="activeTab === 'description'">
                <div class="rounded-xl border border-zinc-200 bg-white p-8 shadow-sm">
                    <h3 class="text-sm font-black uppercase tracking-[0.2em] text-zinc-400 border-b pb-4 mb-6 italic">Informasi Deskripsi Lowongan</h3>
                    <div class="prose prose-sm max-w-none text-zinc-600 leading-relaxed ql-editor border-none p-0">
                        {!! $recruitment->detail->description ?? 'Deskripsi belum diatur.' !!}
                    </div>
                </div>
            </div>

            <div class="w-full" x-show="activeTab === 'requirements'" style="display: none;">
                <div class="rounded-xl border border-zinc-200 bg-white p-8 shadow-sm">
                    <h3 class="text-sm font-black uppercase tracking-[0.2em] text-zinc-400 border-b pb-4 mb-6 italic">Persyaratan Umum Pelamar</h3>
                    <div class="prose prose-sm max-w-none text-zinc-600 leading-relaxed ql-editor border-none p-0">
                        {!! $recruitment->detail->requirements ?? 'Persyaratan belum diatur.' !!}
                    </div>
                </div>
            </div>

            <div class="w-full" x-show="activeTab === 'formations'" style="display: none;">
                <div class="flex flex-col space-y-6">
                    <!-- Formasi Table -->
                    <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-4 border-b bg-zinc-50 flex items-center justify-between">
                            <h4 class="font-bold text-zinc-900 uppercase text-xs tracking-widest">Daftar Formasi Tersedia</h4>
                            <button onclick="document.getElementById('addFormationModal').classList.remove('hidden')" class="px-3 py-1.5 bg-zinc-900 text-white text-[10px] font-bold rounded uppercase tracking-wider hover:bg-zinc-800 transition-all flex items-center gap-2">
                                <i data-lucide="plus" class="h-3 w-3"></i> Tambah Formasi
                            </button>
                        </div>
                        <div class="w-full overflow-x-auto">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-zinc-900 text-white uppercase tracking-tighter">
                                    <tr>
                                        <th class="px-6 py-3 font-bold">Formasi</th>
                                        <th class="px-6 py-3 font-bold">Pendidikan</th>
                                        <th class="px-6 py-3 font-bold">Jurusan</th>
                                        <th class="px-6 py-3 font-bold">Kelamin</th>
                                        <th class="px-6 py-3 font-bold">Syarat Dokumen</th>
                                        <th class="px-6 py-3 font-bold text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100">
                                    @forelse ($recruitment->formations as $formation)
                                        <tr class="hover:bg-zinc-50 transition-colors">
                                            <td class="px-6 py-4 font-bold text-zinc-900">{{ $formation->formation_name }}</td>
                                            <td class="px-6 py-4 text-zinc-600 uppercase font-bold">{{ $formation->education }}</td>
                                            <td class="px-6 py-4 text-zinc-500">
                                                <ul class="list-disc ml-4 space-y-0.5">
                                                    @if($formation->major)
                                                        @foreach(explode("\n", $formation->major) as $m)
                                                            <li>{{ $m }}</li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </td>
                                            <td class="px-6 py-4 text-zinc-600 font-bold uppercase">{{ $formation->gender }}</td>
                                            <td class="px-6 py-4 text-zinc-500">
                                                <ul class="list-disc ml-4 space-y-0.5">
                                                    @if($formation->document_requirements)
                                                        @foreach(explode("\n", $formation->document_requirements) as $doc)
                                                            <li>{{ $doc }}</li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <button onclick="openEditFormationModal({{ $formation }})" class="text-blue-400 hover:text-blue-600 transition-colors">
                                                        <i data-lucide="edit-2" class="h-4 w-4"></i>
                                                    </button>
                                                    <button onclick="confirmDeleteFormation({{ $formation->id }})" class="text-red-400 hover:text-red-600 transition-colors">
                                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center text-zinc-400 italic font-medium uppercase tracking-[0.2em]">Belum ada formasi yang ditambahkan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Formation Modal -->
    <div id="editFormationModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                    <form id="editFormationForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="bg-white px-8 py-8">
                            <h3 class="text-lg font-black text-zinc-900 uppercase tracking-widest italic mb-6 border-b pb-4">Edit Formasi Pekerjaan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2 space-y-1.5">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Nama Formasi</label>
                                    <input type="text" name="formation_name" id="edit_formation_name" required class="h-11 w-full rounded-lg border border-zinc-300 px-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Tingkat Pendidikan</label>
                                    <input type="text" name="education" id="edit_education" required class="h-11 w-full rounded-lg border border-zinc-300 px-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Jenis Kelamin</label>
                                    <select name="gender" id="edit_gender" required class="h-11 w-full rounded-lg border border-zinc-300 px-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none">
                                        <option value="PRIA & WANITA">PRIA & WANITA</option>
                                        <option value="PRIA ONLY">PRIA ONLY</option>
                                        <option value="WANITA ONLY">WANITA ONLY</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2 space-y-1.5">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Jurusan (Pisahkan dengan baris baru)</label>
                                    <textarea name="major" id="edit_major" rows="3" required class="w-full rounded-lg border border-zinc-300 p-4 text-sm font-medium focus:ring-2 focus:ring-zinc-900 outline-none"></textarea>
                                </div>
                                <div class="md:col-span-2 space-y-1.5">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Syarat Dokumen (Pisahkan dengan baris baru)</label>
                                    <textarea name="document_requirements" id="edit_document_requirements" rows="3" required class="w-full rounded-lg border border-zinc-300 p-4 text-sm font-medium focus:ring-2 focus:ring-zinc-900 outline-none"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-zinc-50 px-8 py-5 flex justify-end gap-3 rounded-b-xl border-t">
                            <button type="button" onclick="this.closest('#editFormationModal').classList.add('hidden')" class="px-6 py-2.5 text-xs font-black uppercase tracking-widest text-zinc-400 hover:text-zinc-600">Batal</button>
                            <button type="submit" class="px-8 py-2.5 bg-zinc-900 text-white text-xs font-black uppercase tracking-widest rounded shadow-xl hover:bg-zinc-800 transition-all">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Add Formation Modal -->
    <div id="addFormationModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                    <form action="{{ route('admin.recruitment.formations.add', $recruitment->id) }}" method="POST">
                        @csrf
                        <div class="bg-white px-8 py-8">
                            <h3 class="text-lg font-black text-zinc-900 uppercase tracking-widest italic mb-6 border-b pb-4">Tambah Formasi Pekerjaan Baru</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2 space-y-1.5">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Nama Formasi</label>
                                    <input type="text" name="formation_name" required placeholder="Contoh: D3 - OPERASIONAL DAN PEMELIHARAAN SARANA PRASARANA" class="h-11 w-full rounded-lg border border-zinc-300 px-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Tingkat Pendidikan</label>
                                    <input type="text" name="education" required placeholder="Contoh: D3" class="h-11 w-full rounded-lg border border-zinc-300 px-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Jenis Kelamin</label>
                                    <select name="gender" required class="h-11 w-full rounded-lg border border-zinc-300 px-4 text-sm font-bold focus:ring-2 focus:ring-zinc-900 outline-none">
                                        <option value="PRIA & WANITA">PRIA & WANITA</option>
                                        <option value="PRIA ONLY">PRIA ONLY</option>
                                        <option value="WANITA ONLY">WANITA ONLY</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2 space-y-1.5">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Jurusan (Pisahkan dengan baris baru)</label>
                                    <textarea name="major" rows="3" required placeholder="Contoh: Teknologi Bangunan dan Jalur Perkeretaapian\nTeknologi Elektro Perkeretaapian" class="w-full rounded-lg border border-zinc-300 p-4 text-sm font-medium focus:ring-2 focus:ring-zinc-900 outline-none"></textarea>
                                </div>
                                <div class="md:col-span-2 space-y-1.5">
                                    <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Syarat Dokumen (Pisahkan dengan baris baru)</label>
                                    <textarea name="document_requirements" rows="3" required placeholder="Contoh: CV\nIJAZAH D3\nIJAZAH SLTA" class="w-full rounded-lg border border-zinc-300 p-4 text-sm font-medium focus:ring-2 focus:ring-zinc-900 outline-none"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-zinc-50 px-8 py-5 flex justify-end gap-3 rounded-b-xl border-t">
                            <button type="button" onclick="this.closest('#addFormationModal').classList.add('hidden')" class="px-6 py-2.5 text-xs font-black uppercase tracking-widest text-zinc-400 hover:text-zinc-600">Batal</button>
                            <button type="submit" class="px-8 py-2.5 bg-zinc-900 text-white text-xs font-black uppercase tracking-widest rounded shadow-xl hover:bg-zinc-800 transition-all">Simpan Formasi</button>
                        </div>
                    </form>
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

    <!-- Delete Formation Form -->
    <form id="deleteFormationForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function openEditFormationModal(formation) {
            const form = document.getElementById('editFormationForm');
            form.action = `/admin/recruitment/formations/${formation.id}`;
            
            document.getElementById('edit_formation_name').value = formation.formation_name;
            document.getElementById('edit_education').value = formation.education;
            document.getElementById('edit_gender').value = formation.gender;
            document.getElementById('edit_major').value = formation.major;
            document.getElementById('edit_document_requirements').value = formation.document_requirements;
            
            document.getElementById('editFormationModal').classList.remove('hidden');
        }

        function openStatusModal(appId, currentStatus, currentNotes) {
            document.getElementById('statusForm').action = "/admin/recruitment/applications/" + appId + "/status";
            document.getElementById('modalStatusInput').value = currentStatus;
            document.getElementById('modalNotesInput').value = currentNotes || '';
            document.getElementById('statusModal').classList.remove('hidden');
        }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
        
        function confirmDeleteFormation(formationId) {
            if (confirm('Anda yakin ingin menghapus formasi ini?')) {
                const form = document.getElementById('deleteFormationForm');
                form.action = "/admin/recruitment/formations/" + formationId;
                form.submit();
            }
        }
    </script>
@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush
@endsection
