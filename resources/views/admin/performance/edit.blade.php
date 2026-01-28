@extends('layouts.app')
@section('title', 'Evaluasi KPI - ' . $appraisal->pegawai->nama_lengkap)

@section('content')
    <div class="max-w-5xl mx-auto flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.performance.index') }}"
                    class="h-10 w-10 rounded-xl border border-zinc-200 bg-white flex items-center justify-center text-zinc-500">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Evaluasi KPI Pegawai</h2>
                    <p class="text-zinc-500 text-sm">Berikan skor 0-100 untuk setiap indikator di bawah ini.</p>
                </div>
            </div>
            <div class="px-4 py-2 bg-zinc-900 rounded-xl text-white text-xs font-bold uppercase tracking-widest">
                TAHUN {{ $appraisal->tahun }}
            </div>
        </div>

        <div
            class="bg-zinc-900 rounded-3xl p-6 text-white shadow-xl shadow-zinc-200 flex items-center gap-6 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 h-40 w-40 bg-white/5 rounded-full blur-3xl"></div>
            @if ($appraisal->pegawai->foto)
                <img src="{{ asset('storage/' . $appraisal->pegawai->foto) }}"
                    class="h-20 w-20 rounded-2xl object-cover border-2 border-white/20">
            @else
                <div class="h-20 w-20 rounded-2xl bg-white/10 flex items-center justify-center border-2 border-white/20">
                    <i data-lucide="user" class="h-10 w-10 text-white/50"></i>
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <h3 class="text-2xl font-black italic tracking-tighter uppercase">{{ $appraisal->pegawai->nama_lengkap }}
                </h3>
                <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest">
                    {{ $appraisal->pegawai->jabatan->name ?? '-' }} • {{ $appraisal->pegawai->divisi->name ?? '-' }}</p>
                <div
                    class="mt-2 text-[10px] font-black bg-white/10 inline-block px-2 py-0.5 rounded uppercase tracking-tighter">
                    NIP: {{ $appraisal->pegawai->nip }}</div>
            </div>
        </div>

        <form action="{{ route('admin.performance.update', $appraisal->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- KPI Indicators -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach ($appraisal->items as $item)
                        <div
                            class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-6 hover:border-zinc-300 transition-all">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest 
                                            {{ $item->indicator->category == 'Kinerja' ? 'bg-blue-100 text-blue-600' : ($item->indicator->category == 'Perilaku' ? 'bg-amber-100 text-amber-600' : 'bg-purple-100 text-purple-600') }}">
                                            {{ $item->indicator->category }}
                                        </span>
                                        <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest">Weight:
                                            {{ $item->indicator->weight }}%</span>
                                    </div>
                                    <h4 class="font-black text-zinc-900 leading-tight">{{ $item->indicator->name }}</h4>
                                    <p class="text-xs text-zinc-500 mt-1 italic">"{{ $item->indicator->description }}"</p>
                                </div>
                                <div class="w-24 shrink-0">
                                    <label
                                        class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1">Skor
                                        (0-100)</label>
                                    <input type="number" name="scores[{{ $item->id }}]"
                                        value="{{ old('scores.' . $item->id, $item->score) }}" required min="0"
                                        max="100"
                                        class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-center font-black text-lg focus:ring-2 focus:ring-zinc-900 transition-all">
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-zinc-50">
                                <label
                                    class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5">Komentar
                                    Khusus</label>
                                <textarea name="comments[{{ $item->id }}]" rows="2"
                                    placeholder="Contoh: Sangat baik dalam pengerjaan teknis namun perlu diperbaiki konsistensinya..."
                                    class="w-full px-4 py-2 rounded-xl border border-zinc-200 text-xs focus:ring-2 focus:ring-zinc-900 transition-all italic">{{ old('comments.' . $item->id, $item->comment) }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Submit Card -->
                <div class="space-y-6">
                    <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-8 sticky top-20">
                        <h3
                            class="text-sm font-black text-zinc-900 uppercase tracking-widest mb-6 border-b border-zinc-100 pb-4">
                            Penyelesaian</h3>

                        <div class="space-y-4 mb-8">
                            <div class="p-4 bg-zinc-50 rounded-2xl border border-zinc-100 italic">
                                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-2">Instruksi Skor
                                </p>
                                <ul class="text-[10px] text-zinc-500 space-y-1">
                                    <li>• <strong>90-100</strong>: Sangat Istimewa</li>
                                    <li>• <strong>80-89</strong>: Baik/Ekspektasi</li>
                                    <li>• <strong>70-79</strong>: Cukup</li>
                                    <li>• <strong>
                                            < 70</strong>: Perlu Perbaikan</li>
                                </ul>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Catatan
                                    Reviewer Akhir</label>
                                <textarea name="catatan_reviewer" rows="4"
                                    class="w-full px-4 py-3 rounded-2xl border border-zinc-200 text-xs focus:ring-2 focus:ring-zinc-900 transition-all font-medium"
                                    placeholder="Simpulan akhir mengenai kinerja pegawai tahun ini...">{{ old('catatan_reviewer', $appraisal->catatan_reviewer) }}</textarea>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Status
                                    Data</label>
                                <select name="status"
                                    class="w-full px-4 py-3 rounded-2xl border border-zinc-200 text-sm font-bold focus:ring-2 focus:ring-zinc-900 transition-all">
                                    <option value="Draft" {{ $appraisal->status == 'Draft' ? 'selected' : '' }}>Simpan
                                        Sebagai Draft</option>
                                    <option value="Selesai" {{ $appraisal->status == 'Selesai' ? 'selected' : '' }}>Selesai
                                        & Publish ke Pegawai</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-4 rounded-2xl bg-zinc-900 text-white font-bold text-sm shadow-xl hover:bg-zinc-800 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <i data-lucide="check-circle" class="h-4 w-4"></i>
                            Update Penilaian
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
