<div class="id-card-container bg-white flex flex-col relative rounded-3xl border-4 border-gray-100 shadow-2xl">
    <!-- Frame Borders (Same as Front) -->
    <div class="absolute top-[30px] left-[30px] right-[30px] h-[8px] bg-[#001D85] rounded-t-full"></div>
    <div
        class="absolute top-[30px] left-[30px] bottom-[30px] w-[8px] bg-gradient-to-b from-[#001D85] from-50% to-[#F37021] to-50% rounded-l-full">
    </div>
    <div class="absolute top-[30px] right-[30px] bottom-[30px] w-[8px] bg-[#F37021] rounded-r-full"></div>
    <div class="absolute bottom-[30px] left-[30px] right-[30px] h-[8px] bg-[#2D3339] rounded-b-full"></div>

    <!-- Decorative Shapes -->
    <div class="absolute top-[30px] left-[30px] w-16 h-16 border-t-[8px] border-l-[8px] border-[#001D85] rounded-tl-3xl">
    </div>
    <div class="absolute top-[30px] right-[30px] w-16 h-16 border-t-[8px] border-r-[8px] border-[#001D85] rounded-tr-3xl"
        style="border-right-color: #F37021;"></div>
    <div class="absolute bottom-[30px] left-[30px] w-16 h-16 border-b-[8px] border-l-[8px] border-[#2D3339] rounded-bl-3xl"
        style="border-left-color: #F37021"></div>
    <div class="absolute bottom-[30px] right-[30px] w-16 h-16 border-b-[8px] border-r-[8px] border-[#2D3339] rounded-br-3xl"
        style="border-right-color: #F37021;"></div>

    <!-- Content Wrapper -->
    <div class="flex-1 flex flex-col items-center pt-24 pb-20 px-16 z-10 text-center">

        <!-- KAI Logo -->
        <div class="mb-16 flex flex-col items-center">
            <div class="relative">
                <img src="{{ asset('image/logo-kai.png') }}" class="h-20 object-contain" crossorigin="anonymous">
            </div>
        </div>

        <!-- Rules List -->
        <div class="w-full text-left space-y-4 mb-auto">
            <ol class="list-decimal list-outside pl-5 space-y-4 text-[#404040] font-medium text-lg leading-relaxed">
                <li>Kartu ini adalah tanda pengenal karyawan yang harus dikenakan pada saat jam kerja/dinas</li>
                <li>Karyawan diharapkan menjaga kartu ini dari kerusakan dan kehilangan</li>
                <li>Jika sudah tidak tercatat sebagai karyawan PT. KAI, Kartu ini wajib dikembalikan ke bagian Corporate
                </li>
                <li>Bila menemukan kartu ini harap dikembalikan ke :</li>
            </ol>
        </div>

        <!-- Address Info -->
        <div class="w-full mt-12 bg-zinc-50 rounded-2xl p-6 border border-zinc-100">
            <h3 class="font-bold text-xl text-[#001D85] mb-2">PT. KAI</h3>
            <p class="text-[#505050] font-bold text-lg mb-4">Branch Office Daerah Operasi 8</p>

            <div class="space-y-1 text-[#606060] text-base leading-relaxed">
                <p>Jl. Gubeng Masjid No.39, Pacar Keling,</p>
                <p>Kec. Tambaksari, Surabaya, Jawa Timur 60131, Indonesia</p>
                <p class="font-medium mt-3">62 (31) 503 6575</p>
                <p class="text-[#F37021] font-medium">humasda8@kai.id</p>
            </div>
        </div>

    </div>
</div>
