@php
    $user = Auth::guard('candidate')->user();
    $age = $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->diff(\Carbon\Carbon::now()) : null;
    $ageString = $age ? "{$age->y} Th {$age->m} Bln {$age->d} Hr" : '-';
@endphp

<aside class="w-full">
    <!-- Main Sidebar Card -->
    <div
        class="bg-white rounded-[24px] shadow-[0_4px_25px_rgb(0,0,0,0.03)] border border-zinc-100 p-8 space-y-8 relative">
        <!-- More Option -->
        <div class="absolute top-6 right-6">
            <button class="text-zinc-200 hover:text-zinc-400 transition-colors">
                <i data-lucide="more-horizontal" class="h-6 w-6"></i>
            </button>
        </div>

        <!-- Profile Section -->
        <div class="flex items-start gap-6">
            <div class="shrink-0">
                @if ($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Avatar"
                        class="h-32 w-[100px] rounded-2xl object-cover shadow-sm">
                @else
                    <div
                        class="h-32 w-[100px] rounded-2xl bg-zinc-100 flex flex-col items-center justify-center border-2 border-dashed border-zinc-200 text-zinc-400">
                        <i data-lucide="user" class="h-8 w-8 mb-1 opacity-20"></i>
                        <span class="text-[9px] uppercase font-bold tracking-tighter">No Photo</span>
                    </div>
                @endif
            </div>
            <div class="pt-3 overflow-hidden">
                <h3 class="font-bold text-[20px] text-zinc-900 flex items-center gap-2 leading-tight">
                    <span class="truncate">{{ $user->name }}</span>
                    <span class="inline-flex items-center justify-center bg-[#2DD4BF] rounded-full p-0.5 shrink-0">
                        <i data-lucide="check" class="h-3 w-3 text-white stroke-[4]"></i>
                    </span>
                </h3>
                <p class="text-zinc-400 text-sm mt-1">Selamat Datang</p>
            </div>
        </div>

        <!-- Info List -->
        <div class="space-y-4 pt-2">
            <div class="flex items-center justify-between">
                <span class="text-[14px] font-medium text-zinc-500">NIK:</span>
                <span
                    class="text-[14px] font-medium text-zinc-400 font-mono tracking-tight">{{ $user->identity_number }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-[14px] font-medium text-zinc-500">Telepon:</span>
                <span class="text-[14px] font-medium text-zinc-400 font-mono tracking-tight">{{ $user->phone }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-[14px] font-medium text-zinc-500">Usia:</span>
                <span class="text-[14px] font-medium text-zinc-400">{{ $ageString }}</span>
            </div>
        </div>

        <!-- Nav Menu -->
        <div class="space-y-2 pt-4">
            <!-- Biodata (Active) -->
            <a href="#"
                class="flex items-center gap-4 px-5 py-3.5 rounded-xl bg-[#F1F5FE] text-[#5570F1] transition-all group">
                <i data-lucide="user-cog" class="h-5 w-5"></i>
                <span class="text-[16px] font-bold">Biodata</span>
            </a>

            <!-- Pendidikan -->
            <a href="#"
                class="flex items-center gap-4 px-5 py-3.5 rounded-xl text-zinc-400 hover:bg-zinc-50 transition-all group">
                <i data-lucide="book" class="h-5 w-5"></i>
                <span class="text-[16px] font-bold">Pendidikan</span>
            </a>

            <!-- File Dokumen -->
            <a href="#"
                class="flex items-center gap-4 px-5 py-3.5 rounded-xl text-zinc-400 hover:bg-zinc-50 transition-all group">
                <i data-lucide="file-text" class="h-5 w-5"></i>
                <span class="text-[16px] font-bold">File Dokumen</span>
            </a>

            <!-- Lowongan -->
            <a href="#"
                class="flex items-center gap-4 px-5 py-3.5 rounded-xl text-zinc-400 hover:bg-zinc-50 transition-all group">
                <i data-lucide="monitor" class="h-5 w-5"></i>
                <span class="text-[16px] font-bold">Lowongan</span>
            </a>
        </div>
    </div>
</aside>
