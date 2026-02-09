<div class="id-card-container bg-white flex flex-col relative rounded-3xl border-4 border-gray-100 shadow-2xl">
    <!-- Frame Borders -->
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
    <div class="flex-1 flex flex-col items-center pt-24 pb-20 px-12 z-10">

        <!-- KAI Logo -->
        <div class="mb-20 flex flex-col items-center">
            <div class="relative">
                <img src="{{ asset('image/logo-kai.png') }}" class="h-16 object-contain" crossorigin="anonymous">
            </div>
        </div>

        <!-- Photo & QR Row -->
        <div class="flex flex-row items-center justify-between w-full px-4 mb-8">
            <div class="relative w-[220px] h-[280px]">
                @if ($employee->foto)
                    <img src="{{ asset('storage/' . $employee->foto) }}"
                        class="w-full h-full object-cover rounded-lg shadow-sm border border-gray-200"
                        crossorigin="anonymous">
                @else
                    <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                        <span class="text-sm">No Photo</span>
                    </div>
                @endif
            </div>

            <div class="w-[220px] h-[220px] flex items-center justify-center relative">
                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->errorCorrection('H')->generate($employee->nip) !!}
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <img src="{{ asset('image/logo-kai.png') }}"
                        class="w-[60px] h-auto object-contain bg-white/90 backdrop-blur-sm p-1 rounded-lg border border-gray-100 shadow-sm"
                        crossorigin="anonymous">
                </div>
            </div>
        </div>

        <!-- Name, NIP, and Details -->
        <div class="text-center mt-2 w-full">
            <h1
                class="text-2xl font-black text-[#404040] uppercase tracking-wide border-b-2 border-gray-300 pb-2 inline-block px-8 mb-2">
                {{ $employee->nama_lengkap }}
            </h1>
            <h2 class="text-xl font-bold text-[#505050] uppercase mt-1 mb-4">
                NIP : {{ $employee->nip }}
            </h2>

            <div class="flex flex-col gap-1.5 text-[#505050] text-lg font-bold uppercase">
                @if ($employee->jabatan)
                    <p>{{ $employee->jabatan->name }}</p>
                @endif
                @if ($employee->divisi)
                    <p>{{ $employee->divisi->name }}</p>
                @endif
                @if ($employee->kantor)
                    <p>{{ $employee->kantor->office_name }}</p>
                @endif
            </div>
        </div>

        <!-- Footer Info -->
        <div class="mt-auto flex items-center justify-start w-full gap-4 px-4">
            <img src="{{ asset('image/logo-kai.png') }}" class="h-12 w-auto object-contain" crossorigin="anonymous">
            <div class="text-left">
                <p class="text-gray-600 text-sm font-medium leading-tight">Subsidiary of</p>
                <p class="text-gray-800 text-lg font-bold leading-tight">PT Kereta Api Indonesia</p>
                <p class="text-gray-600 text-sm font-medium leading-tight">(Persero)</p>
            </div>
        </div>

    </div>
</div>
