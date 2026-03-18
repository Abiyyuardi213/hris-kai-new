<div id="logoutModalCandidate" class="fixed inset-0 z-[10000] flex items-center justify-center bg-zinc-900/60 backdrop-blur-sm hidden animate-fade-in">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[400px] mx-4 overflow-hidden animate-zoom-in">
        <div class="p-8 text-center">
            <div class="h-20 w-20 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center mx-auto mb-6">
                <i data-lucide="log-out" class="h-10 w-10"></i>
            </div>
            <h3 class="text-xl font-black text-zinc-900 uppercase tracking-tight mb-2">Konfirmasi Keluar</h3>
            <p class="text-sm font-medium text-zinc-500">Apakah Anda yakin ingin mengakhiri sesi pendaftaran Anda sekarang?</p>
        </div>
        <div class="flex border-t border-zinc-100 h-16">
            <button type="button" onclick="document.getElementById('logoutModalCandidate').classList.add('hidden')"
                class="flex-1 text-sm font-bold text-zinc-400 hover:text-zinc-900 hover:bg-zinc-50 transition-all uppercase tracking-widest border-r border-zinc-100">
                Batal
            </button>
            <form action="{{ route('candidate.logout') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit"
                    class="h-full w-full text-sm font-extrabold text-rose-600 hover:bg-rose-50 transition-all uppercase tracking-widest">
                    Ya, Keluar
                </button>
            </form>
        </div>
    </div>
</div>
