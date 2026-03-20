@extends('layouts.app')
@section('title', 'Detail Pelamar - ' . $candidate->name)

@section('content')
<div class="flex flex-col space-y-8">
    <div class="flex flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.candidates.index') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-400 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-5 w-5"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Detail Lengkap Pelamar</h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-sm text-zinc-500 font-medium">{{ $candidate->name }}</span>
                    <span class="w-1 h-1 rounded-full bg-zinc-300"></span>
                    <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest italic">Peserta Terverifikasi</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('admin.candidates.destroy', $candidate->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pelamar ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-6 py-2.5 text-xs font-black text-red-600 hover:bg-red-100 transition-all italic uppercase tracking-widest shadow-lg shadow-red-100">
                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                    Hapus Seluruh Data Pelamar
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Profile -->
        <div class="flex flex-col space-y-8">
            <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm flex flex-col items-center text-center">
                <div class="relative group">
                    <div class="h-64 w-48 rounded-2xl bg-zinc-900 overflow-hidden shadow-2xl shadow-zinc-200 border-4 border-white mb-6">
                        @if($candidate->photo)
                            <img src="{{ asset('storage/' . $candidate->photo) }}" alt="Foto Profile" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-white/20">
                                <i data-lucide="user-square" class="h-16 w-16 mb-2"></i>
                                <span class="text-[9px] font-black uppercase tracking-widest italic font-mono">No Profile Photo</span>
                            </div>
                        @endif
                    </div>
                </div>
                <h3 class="text-lg font-black text-zinc-900 uppercase italic tracking-tighter leading-none">{{ $candidate->name }}</h3>
                <p class="text-xs font-bold text-zinc-400 mt-2 uppercase tracking-widest">{{ $candidate->email }}</p>
                
                <div class="w-full h-px bg-zinc-100 my-6"></div>
                
                <div class="w-full space-y-4 text-left">
                    <div class="flex items-center flex-col justify-start">
                        <span class="text-[10px] font-black text-zinc-300 uppercase tracking-[0.2em] mb-1 italic block w-full">Nomor Identitas (NIK)</span>
                        <span class="text-sm font-black text-zinc-900 font-mono tracking-tighter italic block w-full">{{ $candidate->identity_number }}</span>
                    </div>
                    <div class="flex items-center flex-col justify-start">
                        <span class="text-[10px] font-black text-zinc-300 uppercase tracking-[0.2em] mb-1 italic block w-full">Kontak Telepon</span>
                        <span class="text-sm font-black text-zinc-900 font-mono tracking-tighter italic block w-full">{{ $candidate->phone ?? '-' }}</span>
                    </div>
                    @if($candidate->place_of_birth)
                        <div class="flex items-center flex-col justify-start">
                            <span class="text-[10px] font-black text-zinc-300 uppercase tracking-[0.2em] mb-1 italic block w-full">Tempat / Tgl Lahir</span>
                            <span class="text-sm font-black text-zinc-900 uppercase italic block w-full leading-none">{{ $candidate->place_of_birth }}, {{ \Carbon\Carbon::parse($candidate->date_of_birth)->format('d F Y') }}</span>
                            <span class="text-[10px] font-black text-zinc-400 mt-1 uppercase italic block w-full">({{ \Carbon\Carbon::parse($candidate->date_of_birth)->age }} Tahun)</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Social Media -->
            <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm">
                <h4 class="text-xs font-black uppercase tracking-[0.2em] text-zinc-900 mb-6 italic flex items-center gap-3">
                    <i data-lucide="share-2" class="h-4 w-4 text-blue-600"></i>
                    Media Sosial Pelamar
                </h4>
                <div class="grid grid-cols-2 gap-4">
                    @foreach(['instagram', 'facebook', 'twitter', 'linkedin'] as $sm)
                        <div class="bg-zinc-50 rounded-xl p-3 flex flex-col items-center justify-center border border-zinc-100 group hover:border-zinc-200 transition-all">
                            <i data-lucide="{{ $sm == 'facebook' ? 'facebook' : ($sm == 'twitter' ? 'twitter' : ($sm == 'instagram' ? 'instagram' : 'linkedin')) }}" class="h-5 w-5 text-zinc-300 group-hover:text-zinc-900 transition-colors mb-2"></i>
                            <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest group-hover:text-zinc-900 transition-all truncate w-full text-center">
                                @if($candidate->social_media && isset($candidate->social_media[$sm]))
                                    {{ $candidate->social_media[$sm] }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Identity -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden p-8">
                 <div class="flex items-center gap-3 border-b pb-6 mb-8">
                    <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-zinc-900 text-white">
                        <i data-lucide="user-pen" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-zinc-900 uppercase italic tracking-tighter">Biodata & Alamat Lengkap</h3>
                        <p class="text-xs font-bold text-zinc-400 italic">Data resmi kandidat sebagaimana terdaftar</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] italic">Agama</span>
                        <p class="text-sm font-black text-zinc-900 uppercase">{{ $candidate->religion ?? '-' }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] italic">Jenis Kelamin</span>
                        <p class="text-sm font-black text-zinc-900 uppercase">{{ $candidate->gender ?? '-' }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] italic">Status Perkawinan</span>
                        <p class="text-sm font-black text-zinc-900 uppercase">{{ $candidate->marital_status ?? '-' }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] italic">Kewarganegaraan</span>
                        <p class="text-sm font-black text-zinc-900 uppercase">{{ $candidate->nationality ?? 'INDONESIA' }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] italic">NPWP</span>
                        <p class="text-sm font-black text-zinc-900 font-mono italic">{{ $candidate->npwp ?? '-' }}</p>
                    </div>
                     <div class="space-y-1 md:col-span-2">
                        <span class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] italic">Alamat Domisili</span>
                        <p class="text-sm font-black text-zinc-900 uppercase italic leading-relaxed">
                            {{ $candidate->address ?? 'Alamat belum diatur.' }}<br>
                            {{ $candidate->village }}, {{ $candidate->district }}, {{ $candidate->city }}, {{ $candidate->province }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Education History -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden p-8">
                 <div class="flex items-center gap-3 border-b pb-6 mb-8">
                    <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-100">
                        <i data-lucide="graduation-cap" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-zinc-900 uppercase italic tracking-tighter">Riwayat Pendidikan</h3>
                        <p class="text-xs font-bold text-zinc-400 italic">Riwayat akademis kandidat dari jenjang tertinggi</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse ($candidate->educations as $edu)
                        <div class="rounded-xl border border-zinc-100 p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 group hover:bg-zinc-50 transition-all">
                            <div class="flex gap-4">
                                <div class="shrink-0 h-12 w-12 rounded-xl bg-zinc-100 flex items-center justify-center text-zinc-400 font-black italic text-lg shadow-inner uppercase">{{ $edu->degree_level }}</div>
                                <div>
                                    <h4 class="text-sm font-black text-zinc-900 uppercase italic leading-none">{{ $edu->institution }}</h4>
                                    <p class="text-xs font-bold text-zinc-500 mt-2 uppercase tracking-tight">{{ $edu->major }}</p>
                                    <p class="text-[10px] text-zinc-400 mt-1 uppercase font-bold">{{ $edu->city }} • Lulus pada: {{ \Carbon\Carbon::parse($edu->graduation_date)->format('F Y') }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="text-[10px] font-black text-zinc-300 uppercase tracking-widest mb-1 italic leading-none">Nilai / IPK</span>
                                <span class="text-xl font-black text-zinc-900 font-mono tracking-tighter italic shadow-sm">{{ $edu->score }}</span>
                                <span class="text-[9px] font-black text-zinc-400 mt-1 uppercase tracking-tighter opacity-70 italic shadow-sm">Akreditasi: {{ $edu->accreditation }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-zinc-400 italic border-2 border-dashed rounded-2xl uppercase tracking-widest text-xs font-bold">Data pendidikan belum dilengkapi oleh kandidat</div>
                    @endforelse
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden p-8">
                 <div class="flex items-center gap-3 border-b pb-6 mb-8">
                    <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-emerald-600 text-white shadow-lg shadow-emerald-100">
                        <i data-lucide="file-check-2" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-zinc-900 uppercase italic tracking-tighter">Dokumen & Berkas Pendukung</h3>
                        <p class="text-xs font-bold text-zinc-400 italic">Seluruh berkas administrasi kandidat</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse ($candidate->documents as $doc)
                        <div class="rounded-xl border border-zinc-100 p-5 flex items-center justify-between group hover:border-emerald-200 hover:bg-emerald-50/20 transition-all">
                             <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center text-zinc-400 group-hover:text-emerald-600 group-hover:bg-white transition-all">
                                    <i data-lucide="file-text" class="h-5 w-5"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="text-xs font-black text-zinc-900 uppercase italic truncate max-w-[150px] leading-none">{{ $doc->document_name }}</h4>
                                    <p class="text-[10px] font-bold text-zinc-400 mt-1.5 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($doc->created_at)->format('d/m/Y') }}</p>
                                </div>
                             </div>
                             <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="h-9 w-9 flex items-center justify-center rounded-lg bg-white border border-zinc-200 text-zinc-400 hover:text-emerald-600 hover:border-emerald-200 transition-all shadow-sm">
                                <i data-lucide="download" class="h-4 w-4"></i>
                             </a>
                        </div>
                    @empty
                         <div class="md:col-span-2 py-12 text-center text-zinc-400 italic border-2 border-dashed rounded-2xl uppercase tracking-widest text-xs font-bold">Kandidat belum mengunggah dokumen apapun</div>
                    @endforelse
                </div>
            </div>

            <!-- Application History -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden p-8">
                <div class="flex items-center gap-3 border-b pb-6 mb-8">
                   <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-orange-600 text-white shadow-lg shadow-orange-100">
                       <i data-lucide="history" class="h-5 w-5"></i>
                   </div>
                   <div>
                       <h3 class="text-lg font-black text-zinc-900 uppercase italic tracking-tighter">Riwayat Lamaran</h3>
                       <p class="text-xs font-bold text-zinc-400 italic">Daftar lowongan yang pernah dilamar kandidat ini</p>
                   </div>
               </div>

               <div class="space-y-4">
                   @forelse ($candidate->applications as $app)
                       <div class="rounded-xl border border-zinc-100 px-6 py-4 flex items-center justify-between group hover:bg-zinc-50 transition-all">
                           <div>
                               <h4 class="text-sm font-black text-zinc-900 uppercase italic">{{ $app->jobVacancy->judul_lowongan }}</h4>
                               <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-tighter mt-1">Daftar pada: {{ $app->created_at->format('d F Y') }}</p>
                           </div>
                           <div class="flex items-center gap-4">
                               @php
                                   $statusConfig = [
                                       'pending' => ['bg' => 'bg-zinc-100', 'text' => 'text-zinc-600'],
                                       'reviewing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                                       'interview' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                                       'test' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
                                       'hired' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
                                       'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
                                   ];
                                   $cfg = $statusConfig[$app->status] ?? ['bg' => 'bg-zinc-100', 'text' => 'text-zinc-600'];
                               @endphp
                               <span class="inline-flex items-center rounded-md px-2.5 py-1 text-[9px] font-black {{ $cfg['bg'] }} {{ $cfg['text'] }} uppercase tracking-widest italic shadow-sm">
                                   {{ $app->status }}
                               </span>
                               <a href="{{ route('admin.recruitment.show', $app->job_vacancy_id) }}" class="p-2 rounded-lg bg-zinc-50 border border-zinc-200 text-zinc-400 hover:text-zinc-900 transition-all">
                                   <i data-lucide="external-link" class="h-4 w-4"></i>
                               </a>
                           </div>
                       </div>
                   @empty
                        <div class="py-12 text-center text-zinc-400 italic border-2 border-dashed rounded-2xl uppercase tracking-widest text-xs font-bold">Kandidat belum melamar ke lowongan manapun</div>
                   @endforelse
               </div>
           </div>
        </div>
    </div>
</div>
@endsection
