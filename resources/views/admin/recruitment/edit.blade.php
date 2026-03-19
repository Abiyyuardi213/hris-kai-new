@extends('layouts.app')
@section('title', 'Edit Lowongan')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.recruitment.index') }}" class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-zinc-200 bg-white text-zinc-400 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Edit Lowongan</h2>
                <p class="text-zinc-500 text-sm">Perbarui detail lowongan pekerjaan.</p>
            </div>
        </div>

        <form action="{{ route('admin.recruitment.update', $recruitment->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="md:col-span-2 space-y-6">
                    <div class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm space-y-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-zinc-900">Judul Lowongan</label>
                            <input type="text" name="judul_lowongan" value="{{ old('judul_lowongan', $recruitment->judul_lowongan) }}" required
                                class="h-10 w-full rounded-md border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-zinc-900">Deskripsi Pekerjaan</label>
                                <div id="description_editor_wrapper" class="rounded-md border border-zinc-200 overflow-hidden focus-within:ring-2 focus-within:ring-zinc-900 transition-all">
                                    <div id="description_editor" class="min-h-[200px]">
                                        {!! $recruitment->detail->description ?? '' !!}
                                    </div>
                                </div>
                                <input type="hidden" name="description" id="description">
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-zinc-900">Persyaratan</label>
                                <div id="requirements_editor_wrapper" class="rounded-md border border-zinc-200 overflow-hidden focus-within:ring-2 focus-within:ring-zinc-900 transition-all">
                                    <div id="requirements_editor" class="min-h-[200px]">
                                        {!! $recruitment->detail->requirements ?? '' !!}
                                    </div>
                                </div>
                                <input type="hidden" name="requirements" id="requirements">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Settings -->
                <div class="space-y-6">
                    <div class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm space-y-4">
                        <h3 class="text-sm font-bold text-zinc-900 flex items-center gap-2 mb-2">
                            <i data-lucide="settings" class="h-4 w-4 text-zinc-400"></i>
                            Pengaturan
                        </h3>
                        
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ old('start_date', $recruitment->start_date) }}" required
                                class="h-9 w-full rounded-md border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Tanggal Berakhir</label>
                            <input type="date" name="end_date" value="{{ old('end_date', $recruitment->end_date) }}" required
                                class="h-9 w-full rounded-md border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Status</label>
                            <select name="status" required
                                class="h-9 w-full rounded-md border border-zinc-200 px-3 text-sm focus:ring-2 focus:ring-zinc-900 outline-none transition-all">
                                <option value="open" {{ old('status', $recruitment->status) == 'open' ? 'selected' : '' }}>Published</option>
                                <option value="closed" {{ old('status', $recruitment->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div class="pt-4 border-t border-zinc-100">
                            <button type="submit" onclick="syncQuillToInput()"
                                class="w-full h-10 bg-zinc-900 text-white text-sm font-medium rounded-md hover:bg-zinc-800 transition-all active:scale-95 flex items-center justify-center gap-2">
                                <i data-lucide="save" class="h-4 w-4"></i>
                                Perbarui
                            </button>
                            <a href="{{ route('admin.recruitment.index') }}"
                                class="w-full mt-2 h-9 flex items-center justify-center text-sm text-zinc-500 hover:text-zinc-900 transition-colors font-medium">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border: none !important;
        border-bottom: 1px solid #e4e4e7 !important;
        background: #fafafa;
        padding: 8px 12px !important;
    }
    .ql-container.ql-snow {
        border: none !important;
        font-family: inherit;
        font-size: 0.875rem;
    }
    .ql-editor {
        padding: 16px 12px !important;
        min-height: 200px;
        line-height: 1.5;
        color: #18181b;
    }
    .ql-editor.ql-blank::before {
        left: 12px !important;
        color: #a1a1aa;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var toolbarOptions = [
        ['bold', 'italic', 'underline'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        ['link', 'clean']
    ];

    var quillDesc = new Quill('#description_editor', {
        theme: 'snow',
        placeholder: 'Tulis deskripsi...',
        modules: { toolbar: toolbarOptions }
    });

    var quillReq = new Quill('#requirements_editor', {
        theme: 'snow',
        placeholder: 'Tulis persyaratan...',
        modules: { toolbar: toolbarOptions }
    });

    function syncQuillToInput() {
        document.getElementById('description').value = quillDesc.root.innerHTML;
        document.getElementById('requirements').value = quillReq.root.innerHTML;
    }
</script>
@endpush
