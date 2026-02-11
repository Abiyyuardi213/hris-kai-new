<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - {{ $employee->nama_lengkap }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f3f4f6;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .id-card-container {
            width: 638px;
            height: 1011px;
            background: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform-origin: top left;
            /* Changed to top left for easier JS sizing */
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white;
            }

            .no-print {
                display: none !important;
            }

            .id-card-container {
                box-shadow: none;
                margin: 0;
                page-break-after: always;
                transform: none !important;
                /* Reset transform for print */
            }

            @page {
                size: 638px 1011px;
                margin: 0;
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen p-4 md:p-8">

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Left Column: Employee Details -->
        <div class="lg:col-span-3 no-print order-2 lg:order-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-8">
                <div class="bg-zinc-900 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Detail Pegawai
                    </h2>
                </div>

                <div class="p-6">
                    <div class="flex flex-col items-center mb-6">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-100 shadow-inner mb-4">
                            @if ($employee->foto)
                                <img src="{{ asset('storage/' . $employee->foto) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 text-center">{{ $employee->nama_lengkap }}</h3>
                        <p class="text-sm text-gray-500 font-mono">{{ $employee->nip }}</p>
                        <span
                            class="mt-2 text-xs font-semibold px-2 py-1 bg-green-100 text-green-700 rounded-full">Aktif</span>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4 text-sm border-b border-gray-100 pb-3">
                            <div class="text-gray-500">Jabatan</div>
                            <div class="font-medium text-gray-900 text-right">{{ $employee->jabatan->name ?? '-' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm border-b border-gray-100 pb-3">
                            <div class="text-gray-500">Divisi</div>
                            <div class="font-medium text-gray-900 text-right">{{ $employee->divisi->name ?? '-' }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm border-b border-gray-100 pb-3">
                            <div class="text-gray-500">Kantor</div>
                            <div class="font-medium text-gray-900 text-right">
                                {{ $employee->kantor->office_name ?? '-' }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm border-b border-gray-100 pb-3">
                            <div class="text-gray-500">NIK</div>
                            <div class="font-medium text-gray-900 text-right font-mono">{{ $employee->nik }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm border-b border-gray-100 pb-3">
                            <div class="text-gray-500">Tanggal Masuk</div>
                            <div class="font-medium text-gray-900 text-right">
                                {{ \Carbon\Carbon::parse($employee->tanggal_masuk)->translatedFormat('d F Y') }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm pb-1">
                            <div class="text-gray-500">Email</div>
                            <div class="font-medium text-gray-900 text-right truncate">
                                {{ $employee->email_pribadi ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button onclick="window.close()"
                            class="flex-1 flex items-center justify-center gap-2 bg-white text-zinc-700 border border-zinc-300 px-4 py-2 rounded-lg font-medium hover:bg-zinc-50 transition-colors text-sm">
                            Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: ID Card Preview -->
        <div class="lg:col-span-9 flex flex-col items-center order-1 lg:order-2" id="cards-column">

            <!-- Controls -->
            <div
                class="no-print w-full mb-6 flex flex-col md:flex-row items-start md:items-center justify-between bg-white p-4 rounded-xl shadow-sm border border-zinc-200 gap-4">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-50 p-2 rounded-lg text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <rect x="7" y="7" width="3" height="9"></rect>
                            <rect x="14" y="7" width="3" height="5"></rect>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Pratinjau ID Card</h3>
                        <p class="text-xs text-gray-500">Depan & Belakang siap dicetak</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 w-full md:w-auto">
                    <button onclick="downloadCard('front')"
                        class="flex-1 md:flex-none justify-center items-center gap-2 bg-white text-emerald-600 border border-emerald-200 px-4 py-2 rounded-lg font-medium hover:bg-emerald-50 transition-colors text-xs md:text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span class="whitespace-nowrap">Simpan Depan</span>
                    </button>
                    <button onclick="downloadCard('back')"
                        class="flex-1 md:flex-none justify-center items-center gap-2 bg-white text-emerald-600 border border-emerald-200 px-4 py-2 rounded-lg font-medium hover:bg-emerald-50 transition-colors text-xs md:text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span class="whitespace-nowrap">Simpan Belakang</span>
                    </button> //
                    <button onclick="window.print()"
                        class="flex-1 md:flex-none justify-center items-center gap-2 bg-zinc-900 text-white px-5 py-2 rounded-lg font-medium hover:bg-zinc-800 transition-colors text-xs md:text-sm shadow-lg shadow-zinc-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        Cetak
                    </button>
                </div>
            </div>

            <!-- ID Card Container Wrapper to handle centering -->
            <div class="relative w-full flex flex-wrap justify-center gap-8 overflow-visible">
                <!-- Wrapper for Front Card -->
                <div id="card-front-wrapper" class="relative">
                    <div id="card-front" class="relative">
                        @include('employees.id-card-front')
                    </div>
                </div>
                <!-- Wrapper for Back Card -->
                <div id="card-back-wrapper" class="relative">
                    <div id="card-back" class="relative">
                        @include('employees.id-card-back')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function adjustCardScale() {
            const column = document.getElementById('cards-column');
            if (!column) return;

            const availableWidth = column.clientWidth - 32;
            const cardBaseWidth = 638;
            const cardBaseHeight = 1011;

            let scale = availableWidth / cardBaseWidth;

            if (scale > 1) scale = 1; // Limit max size to original 100%

            const cardWrappers = ['card-front-wrapper', 'card-back-wrapper'];

            cardWrappers.forEach(id => {
                const wrapper = document.getElementById(id);
                if (wrapper) {
                    wrapper.style.width = (cardBaseWidth * scale) + "px";
                    wrapper.style.height = (cardBaseHeight * scale) + "px";

                    const innerDiv = wrapper.querySelector('.id-card-container');
                    if (innerDiv) {
                        innerDiv.style.transform = `scale(${scale})`;
                    }
                }
            });
        }

        window.addEventListener('resize', adjustCardScale);
        window.addEventListener('DOMContentLoaded', adjustCardScale);
        setTimeout(adjustCardScale, 100);


        async function downloadCard(side) {
            const elementWrapperId = side === 'front' ? '#card-front-wrapper' : '#card-back-wrapper';

            const elementId = side === 'front' ? '#card-front .id-card-container' : '#card-back .id-card-container';
            const element = document.querySelector(elementId);
            const fileName = `ID-Card-${side}-{{ $employee->nip }}.png`;

            if (!element) return;

            Swal.fire({
                title: 'Sedang memproses...',
                text: 'Mohon tunggu sebentar, gambar sedang dibuat.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const pixelCanvas = document.createElement('canvas');
            pixelCanvas.width = 1;
            pixelCanvas.height = 1;
            const pixelCtx = pixelCanvas.getContext('2d', {
                willReadFrequently: true
            });

            function colorToRgb(cssColor) {
                if (!cssColor) return 'transparent';

                pixelCtx.clearRect(0, 0, 1, 1);
                pixelCtx.fillStyle = cssColor;
                pixelCtx.fillRect(0, 0, 1, 1);

                const [r, g, b, a] = pixelCtx.getImageData(0, 0, 1, 1).data;
                const alpha = a / 255;

                if (alpha === 0 && cssColor !== 'transparent' && !cssColor.includes('rgba(0,0,0,0)')) {
                    return cssColor;
                }

                if (alpha < 1) {
                    return `rgba(${r}, ${g}, ${b}, ${alpha.toFixed(2)})`;
                }
                return `rgb(${r}, ${g}, ${b})`;
            }

            function sanitizeStyleString(str) {
                if (!str) return str;
                return str.replace(/(oklch|oklab)\((?:[^)(]+|\((?:[^)(]+|\([^)(]*\))*\))*\)/g, (match) => {
                    return colorToRgb(match);
                });
            }

            const processPromise = new Promise(async (resolve, reject) => {
                const originalStyles = new Map();
                const originalSrcs = new Map();

                try {
                    const allElements = [element, ...element.querySelectorAll('*')];
                    const propsToCheck = [
                        'color', 'backgroundColor', 'borderColor', 'outlineColor',
                        'textDecorationColor',
                        'fill', 'stroke', 'stopColor', 'floodColor',
                        'boxShadow', 'backgroundImage', 'borderImage'
                    ];

                    allElements.forEach(el => {
                        const computed = window.getComputedStyle(el);
                        const styleToSave = {};
                        let hasChange = false;

                        propsToCheck.forEach(prop => {
                            const val = computed[prop];
                            if (val && (val.includes('oklch') || val.includes('oklab'))) {
                                styleToSave[prop] = el.style[prop];
                                const safeVal = sanitizeStyleString(val);

                                if (safeVal !== val) {
                                    el.style[prop] = safeVal;
                                }
                            }
                        });

                        if (hasChange) {
                            originalStyles.set(el, styleToSave);
                        }
                    });

                    const images = element.querySelectorAll('img');
                    const conversionTasks = Array.from(images).map(async (img) => {
                        originalSrcs.set(img, img.src);
                        if (img.src.startsWith('data:')) return;

                        try {
                            const controller = new AbortController();
                            const timeoutId = setTimeout(() => controller.abort(),
                                5000);

                            const response = await fetch(img.src, {
                                signal: controller.signal
                            });
                            clearTimeout(timeoutId);

                            const blob = await response.blob();
                            await new Promise((res, rej) => {
                                const reader = new FileReader();
                                reader.onloadend = () => {
                                    img.src = reader.result;
                                    res();
                                };
                                reader.onerror = rej;
                                reader.readAsDataURL(blob);
                            });
                        } catch (e) {
                            console.warn('Image conversion failed/timed out:', img.src);
                        }
                    });

                    await Promise.all(conversionTasks);

                    await new Promise(r => setTimeout(r, 100));

                    const canvas = await html2canvas(element, {
                        scale: 3,
                        useCORS: true,
                        allowTaint: false,
                        backgroundColor: null,
                        logging: false,
                        onclone: (clonedDoc) => {
                            const clonedElement = clonedDoc.querySelector(elementId);
                            if (clonedElement) {
                                clonedElement.style.zoom = '1';
                                clonedElement.style.transform = 'none';
                                clonedElement.style.margin = '0';
                                clonedElement.style.boxShadow =
                                    'none';
                            }
                        }
                    });

                    resolve({
                        canvas,
                        originalStyles,
                        originalSrcs
                    });

                } catch (e) {
                    reject({
                        error: e,
                        originalStyles,
                        originalSrcs
                    });
                }
            });

            const timeoutLimit = new Promise((_, reject) =>
                setTimeout(() => reject({
                    isTimeout: true
                }), 15000)
            );

            try {
                const result = await Promise.race([processPromise, timeoutLimit]);
                const {
                    canvas,
                    originalStyles,
                    originalSrcs
                } = result;

                const link = document.createElement('a');
                link.download = fileName;
                link.href = canvas.toDataURL('image/png');
                link.click();

                originalStyles.forEach((styles, el) => {
                    Object.keys(styles).forEach(p => el.style[p] = styles[p]);
                });
                originalSrcs.forEach((src, img) => {
                    if (originalSrcs.has(img)) img.src = src;
                });

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Gambar ID Card berhasil disimpan.',
                    timer: 2000,
                    showConfirmButton: false
                });

            } catch (err) {
                console.error(err);

                let msg = 'Terjadi kesalahan saat membuat gambar.';
                if (err.isTimeout) msg = 'Proses terlalu lama (waktu habis). Cek koneksi internet Anda.';
                else if (err.error) msg += ' ' + err.error.message;

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan',
                    text: msg,
                    footer: '<span class="text-xs text-red-500">Silakan refresh halaman jika tampilan berantakan.</span>'
                });
            }
        }
    </script>
</body>

</html>
