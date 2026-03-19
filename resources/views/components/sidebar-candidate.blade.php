@php
    $user = Auth::guard('candidate')->user();
    $age = $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->diff(\Carbon\Carbon::now()) : null;
    $ageString = $age ? "{$age->y} Th {$age->m} Bln {$age->d} Hr" : '-';
@endphp

<aside class="w-full">
    <!-- Main Sidebar Card -->
    <div
        class="bg-white rounded-2xl shadow-sm border border-zinc-200 p-8 space-y-8 relative">
        <!-- More Option -->
        <div class="absolute top-6 right-6">
            <button class="text-zinc-200 hover:text-zinc-400 transition-colors">
                <i data-lucide="more-horizontal" class="h-5 w-5"></i>
            </button>
        </div>

        <!-- Profile Section -->
        <div class="flex items-start gap-6">
            <div class="shrink-0">
                @if ($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Avatar"
                        class="h-28 w-[85px] rounded-xl object-cover shadow-sm border-2 border-white shadow-zinc-100">
                @else
                    <div
                        class="h-28 w-[85px] rounded-xl bg-zinc-50 flex flex-col items-center justify-center border-2 border-dashed border-zinc-200 text-zinc-400">
                        <i data-lucide="user" class="h-8 w-8 mb-1 opacity-20"></i>
                        <span class="text-[9px] uppercase font-bold tracking-tighter">No Photo</span>
                    </div>
                @endif
            </div>
            <div class="pt-2">
                <h3 class="font-bold text-[18px] text-zinc-900 flex items-center flex-wrap gap-2 leading-tight">
                    <span>{{ $user->name }}</span>
                    <span class="inline-flex items-center justify-center bg-emerald-500 rounded-full p-0.5 shrink-0">
                        <i data-lucide="check" class="h-3 w-3 text-white stroke-[4]"></i>
                    </span>
                </h3>
                <p class="text-zinc-400 text-xs font-bold uppercase tracking-widest mt-2">Selamat Datang</p>
            </div>
        </div>

        <!-- Info List -->
        <div class="space-y-4 pt-2">
            <div class="flex items-center justify-between">
                <span class="text-[13px] font-bold text-zinc-500 uppercase tracking-tight">NIK</span>
                <span
                    class="text-[13px] font-bold text-zinc-900 font-mono tracking-tight">{{ $user->identity_number }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-[13px] font-bold text-zinc-500 uppercase tracking-tight">Telepon</span>
                <span class="text-[13px] font-bold text-zinc-900 font-mono tracking-tight">{{ $user->phone }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-[13px] font-bold text-zinc-500 uppercase tracking-tight">Usia</span>
                <span class="text-[13px] font-bold text-zinc-900">{{ $ageString }}</span>
            </div>
        </div>

        <!-- Nav Menu -->
        <div class="space-y-1 pt-4">
            <!-- Biodata -->
            <a href="{{ route('candidate.dashboard') }}"
                class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('candidate.dashboard') ? 'bg-zinc-100 text-zinc-900 shadow-sm shadow-zinc-100' : 'text-zinc-400 hover:bg-zinc-50 hover:text-zinc-900' }}">
                <i data-lucide="user-cog" class="h-5 w-5 {{ request()->routeIs('candidate.dashboard') ? 'text-zinc-900' : 'text-zinc-400 group-hover:text-zinc-900' }}"></i>
                <span class="text-[15px] font-bold">Biodata</span>
            </a>

            <!-- Pendidikan -->
            <a href="{{ route('candidate.education') }}"
                class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('candidate.education') ? 'bg-zinc-100 text-zinc-900 shadow-sm shadow-zinc-100' : 'text-zinc-400 hover:bg-zinc-50 hover:text-zinc-900' }}">
                <i data-lucide="book" class="h-5 w-5 {{ request()->routeIs('candidate.education') ? 'text-zinc-900' : 'text-zinc-400 group-hover:text-zinc-900' }}"></i>
                <span class="text-[15px] font-bold">Pendidikan</span>
            </a>

            <!-- File Dokumen -->
            <a href="{{ route('candidate.documents') }}"
                class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('candidate.documents') ? 'bg-zinc-100 text-zinc-900 shadow-sm shadow-zinc-100' : 'text-zinc-400 hover:bg-zinc-50 hover:text-zinc-900' }}">
                <i data-lucide="file-text" class="h-5 w-5 {{ request()->routeIs('candidate.documents') ? 'text-zinc-900' : 'text-zinc-400 group-hover:text-zinc-900' }}"></i>
                <span class="text-[15px] font-bold">File Dokumen</span>
            </a>

            <!-- Lowongan -->
            <a href="{{ route('candidate.vacancies') }}"
                class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('candidate.vacancies*') ? 'bg-zinc-100 text-zinc-900 shadow-sm shadow-zinc-100' : 'text-zinc-400 hover:bg-zinc-50 hover:text-zinc-900' }}">
                <i data-lucide="briefcase" class="h-5 w-5 {{ request()->routeIs('candidate.vacancies*') ? 'text-zinc-900' : 'text-zinc-400 group-hover:text-zinc-900' }}"></i>
                <span class="text-[15px] font-bold">Lowongan</span>
            </a>
        </div>
    </div>
</aside>
