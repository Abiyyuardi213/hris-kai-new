@extends('layouts.candidate')
@section('title', 'File Dokumen')

@section('content')
<div class="max-w-[1000px] mx-auto space-y-8 pb-20">
    <!-- Header Title -->
    <div class="flex flex-col mb-4">
        <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Upload File Dokumen</h2>
        <p class="text-sm text-zinc-500 mt-2">Silahkan unggah dokumen pendukung pendaftaran anda (KTP, Ijazah, Transkrip, dll)</p>
    </div>

    @if($errors->any())
    <div class="bg-rose-50 border border-rose-100 rounded-2xl p-6 mb-6 animate-in fade-in slide-in-from-top-4 duration-500">
        <div class="flex items-center gap-3 mb-3">
            <div class="h-8 w-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                <i data-lucide="alert-circle" class="h-5 w-5"></i>
            </div>
            <h3 class="text-sm font-bold text-rose-900 uppercase tracking-widest">Terdapat Kesalahan Input</h3>
        </div>
        <ul class="space-y-1 ml-11 list-disc">
            @foreach($errors->all() as $error)
                <li class="text-xs font-medium text-rose-600 italic">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="p-10">
            <form action="{{ route('candidate.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="space-y-8 max-w-[800px] mx-auto">
                    <!-- Jenis Berkas -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">Jenis Berkas</label>
                        <div class="md:col-span-2 relative">
                            <select name="document_type" class="block w-full rounded-lg border border-zinc-300 px-4 py-3 text-sm font-medium text-zinc-900 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 transition-all appearance-none cursor-pointer">
                                <option value="" disabled selected>Pilih Jenis Berkas</option>
                                @foreach($documentTypes as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-400">
                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Pilih File -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <label class="text-sm font-bold text-zinc-500 uppercase tracking-tight">Pilih Dokumen</label>
                        <div class="md:col-span-2">
                            <div class="relative group">
                                <label for="document_file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-zinc-200 rounded-2xl bg-zinc-50/50 hover:bg-zinc-50 hover:border-zinc-300 transition-all cursor-pointer group">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <div class="h-10 w-10 rounded-full bg-white shadow-sm flex items-center justify-center text-zinc-400 group-hover:text-zinc-900 transition-colors mb-2">
                                            <i data-lucide="upload-cloud" class="h-5 w-5"></i>
                                        </div>
                                        <p class="text-[11px] font-bold text-zinc-500 uppercase tracking-widest" id="file-name-label">Klik untuk unggah file</p>
                                        <p class="text-[9px] text-zinc-400 mt-1 font-bold italic">Format: PDF, JPG, PNG (Maks 2MB)</p>
                                    </div>
                                    <input id="document_file" name="document_file" type="file" class="hidden" accept=".pdf,image/*" />
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="flex items-center gap-2 rounded-lg bg-emerald-500 px-8 py-3 text-xs font-bold text-white hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100 uppercase tracking-widest">
                            <i data-lucide="check-circle" class="h-4 w-4"></i>
                            UNGGAH SEKARANG
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table List -->
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-50/50 border-b border-zinc-100">
                        <th class="px-6 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900">Jenis Dokumen</th>
                        <th class="px-6 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900">Nama File</th>
                        <th class="px-6 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 text-center">Ukuran / Tipe</th>
                        <th class="px-6 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 text-center">Status</th>
                        <th class="px-6 py-5 text-[11px] font-black uppercase tracking-[0.2em] text-zinc-900 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-50">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-zinc-50/30 transition-colors">
                            <td class="px-6 py-5">
                                <div class="text-xs font-bold text-zinc-900 uppercase tracking-tight">
                                    {{ $documentTypes[$doc->document_type] ?? $doc->document_type }}
                                </div>
                            </td>
                            <td class="px-6 py-5 text-xs font-bold text-zinc-500 italic">
                                {{ $doc->file_name }}
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="text-[10px] font-black bg-zinc-100 text-zinc-500 px-2 py-1 rounded uppercase tracking-tighter">
                                    {{ strtoupper(pathinfo($doc->file_path, PATHINFO_EXTENSION)) }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex items-center justify-center gap-1.5 text-emerald-600">
                                    <i data-lucide="check-circle-2" class="h-3.5 w-3.5"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Tersimpan</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" 
                                        class="h-8 w-8 rounded-lg bg-zinc-900 flex items-center justify-center text-white hover:bg-zinc-800 transition-all shadow-md shadow-zinc-100">
                                        <i data-lucide="eye" class="h-4 w-4"></i>
                                    </a>
                                    <form action="{{ route('candidate.documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="h-8 w-8 rounded-lg bg-rose-500 flex items-center justify-center text-white hover:bg-rose-600 transition-all shadow-md shadow-rose-100">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i data-lucide="file-warning" class="h-12 w-12 mb-4 text-zinc-900"></i>
                                    <p class="text-xs font-black uppercase tracking-widest text-zinc-900">Belum ada dokumen yang diunggah</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('document_file').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : "Klik untuk unggah file";
        document.getElementById('file-name-label').textContent = fileName;
        if(e.target.files[0]) {
            document.getElementById('file-name-label').className = "text-[11px] font-black text-emerald-600 uppercase tracking-widest";
        }
    });
</script>
@endpush
@endsection
