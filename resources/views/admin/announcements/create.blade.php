@extends('layouts.app')
@section('title', 'Buat Pengumuman')

@section('content')
    <div class="max-w-4xl mx-auto flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.announcements.index') }}"
                class="h-10 w-10 rounded-xl border border-zinc-200 bg-white flex items-center justify-center text-zinc-500 hover:text-zinc-900 shadow-sm transition-all">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Buat Pengumuman Baru</h2>
                <p class="text-zinc-500 text-sm">Tulis informasi yang ingin dibagikan ke seluruh portal pegawai.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl flex flex-col gap-1">
                @foreach ($errors->all() as $error)
                    <p class="text-xs font-bold">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data"
            class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            <!-- Left Column: Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-8 space-y-6">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Judul
                            Pengumuman</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            placeholder="Contoh: Pemberitahuan Libur Bersama Idul Fitri..."
                            class="w-full px-5 py-4 rounded-2xl border border-zinc-200 text-lg font-bold tracking-tight focus:ring-2 focus:ring-zinc-900 transition-all">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Isi Pengumuman</label>
                        <textarea name="content" id="editor" rows="15" required
                            class="w-full px-5 py-4 rounded-2xl border border-zinc-200 text-sm focus:ring-2 focus:ring-zinc-900 transition-all"
                            placeholder="Tulis detail pengumuman di sini...">{{ old('content') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 space-y-6">
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Kategori</label>
                            <select name="category" required
                                class="w-full px-4 py-3 rounded-xl border border-zinc-200 text-sm font-bold focus:ring-2 focus:ring-zinc-900 transition-all">
                                <option value="Umum">Umum</option>
                                <option value="Penting">Penting (Urgent)</option>
                                <option value="HR">HR & Kebijakan</option>
                                <option value="Acara">Acara / Event</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Waktu
                                Publish</label>
                            <input type="datetime-local" name="published_at"
                                value="{{ old('published_at', date('Y-m-d\TH:i')) }}"
                                class="w-full px-4 py-3 rounded-xl border border-zinc-200 text-sm font-medium focus:ring-2 focus:ring-zinc-900 transition-all">
                            <p class="text-[10px] text-zinc-400 italic font-medium mt-1">Biarkan default untuk publish
                                instan.</p>
                        </div>

                        <div class="pt-4 border-t border-zinc-50">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1"
                                        {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                                    <div
                                        class="h-6 w-11 rounded-full bg-zinc-200 peer-checked:bg-emerald-500 transition-all after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full">
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-zinc-900 select-none">Aktifkan Sekarang</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 space-y-6">
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Gambar Cover
                                (Opsional)</label>
                            <input type="file" name="image_file" accept="image/*"
                                class="w-full text-xs text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-zinc-900 file:text-white file:cursor-pointer hover:file:bg-zinc-800 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">File Lampiran
                                (PDF/Lainnya)</label>
                            <input type="file" name="attachment_file"
                                class="w-full text-xs text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-zinc-100 file:text-zinc-600 file:cursor-pointer hover:file:bg-zinc-200 transition-all">
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-4 rounded-2xl bg-zinc-900 text-white font-bold text-sm shadow-xl shadow-zinc-200 hover:bg-zinc-800 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i data-lucide="send" class="h-4 w-4"></i>
                    Publikasikan Pengumuman
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <!-- You can add CKEditor or TinyMCE if needed -->
    <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote']
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <style>
        .ck-editor__editable {
            min-height: 400px;
            border-radius: 0 0 1.5rem 1.5rem !important;
            font-size: 0.875rem;
        }

        .ck-toolbar {
            border-radius: 1.5rem 1.5rem 0 0 !important;
            border-bottom: 0 !important;
        }
    </style>
@endpush
