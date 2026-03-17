<header class="sticky top-0 z-50 w-full h-20 bg-white border-b flex items-center justify-between px-10 shadow-sm">
    <!-- Left: Title and Date -->
    <div class="flex items-center gap-8">
        <div class="flex items-center gap-4">
            <img src="{{ asset('image/logo-kai.png') }}" alt="Logo KAI" class="h-8 w-auto">
            <div class="h-8 w-[1.5px] bg-zinc-200"></div>
            <h1 class="text-[20px] font-bold text-zinc-900 tracking-tight">e-Recruitment</h1>
            <div class="h-8 w-[1.5px] bg-zinc-200"></div>
        </div>
        <div class="text-[14px] font-medium text-zinc-400 italic" id="liveClock">
            {{ now()->translatedFormat('d F Y H:i:s') }}
        </div>
    </div>

    <!-- Right: Notifikasi and Logout -->
    <div class="flex items-center gap-6">
        <!-- Notification -->
        <div class="flex items-center gap-3">
            <i data-lucide="bell" class="h-5 w-5 text-zinc-900"></i>
            <span class="text-[12px] font-bold text-zinc-900 uppercase tracking-widest">Notifikasi</span>
            <span
                class="bg-[#D946EF] text-white text-[11px] font-bold px-2 py-0.5 rounded flex items-center justify-center">2</span>
        </div>

        <!-- Logout Button -->
        <form action="{{ route('candidate.logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="bg-[#5570F1] hover:bg-[#4459c7] text-white px-5 py-2.5 rounded-lg flex items-center gap-2 transition-all shadow-sm">
                <i data-lucide="power" class="h-4 w-4"></i>
                <span class="text-[13px] font-bold uppercase tracking-wider">Logout</span>
            </button>
        </form>
    </div>
</header>

<script>
    function updateClock() {
        const now = new Date();
        const options = {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        document.getElementById('liveClock').innerText = now.toLocaleString('id-ID', options).replace(/ pukul/, '');
    }
    setInterval(updateClock, 1000);
</script>
