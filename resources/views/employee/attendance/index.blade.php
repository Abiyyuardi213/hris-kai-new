@extends('layouts.employee')

@section('title', 'Absensi Pegawai')

@section('content')
    <div class="flex flex-col space-y-6 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Presensi Harian</h2>
                <p class="text-zinc-500 text-sm">Silakan lakukan absensi masuk dan pulang tepat waktu.</p>
            </div>
            <div class="text-right">
                <h3 class="text-xl font-bold text-zinc-900" id="current-time">00:00:00</h3>
                <p class="text-xs text-zinc-500">{{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            </div>
        </div>

        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center gap-3">
                <i data-lucide="check-circle" class="h-5 w-5"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl flex items-center gap-3">
                <i data-lucide="alert-circle" class="h-5 w-5"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Main Presensi Card -->
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                    <div class="p-6 md:p-8 space-y-6 text-center">
                        @if (!$todayAttendance || !$todayAttendance->jam_pulang)
                            <!-- Helper for Show QR (Identity) -->
                            @if ($shift->require_qr ?? false)
                                <div
                                    class="mb-6 flex flex-col items-center justify-center bg-white p-6 rounded-2xl border border-dashed border-zinc-300">
                                    <p class="text-sm font-bold text-zinc-900 mb-4">QR Code Identitas (NIP)</p>
                                    <div
                                        class="bg-white p-2 rounded-xl shadow-sm border border-zinc-100 relative flex items-center justify-center">
                                        {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->margin(1)->errorCorrection('H')->generate($employee->nip) !!}
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                            <img src="{{ asset('image/logo-kai.png') }}"
                                                class="w-[40px] h-auto object-contain bg-white/90 backdrop-blur-sm p-0.5 rounded-lg border border-gray-100 shadow-sm"
                                                crossorigin="anonymous">
                                        </div>
                                    </div>
                                    <p class="mt-3 text-sm font-mono font-bold text-zinc-700 tracking-wider">
                                        {{ $employee->nip }}</p>
                                    <p class="text-xs text-zinc-400 mt-1 text-center max-w-[200px]">Tunjukkan QR Code ini
                                        kepada petugas atau perangkat jika diperlukan.</p>
                                </div>
                            @endif

                            <!-- QR Scanner Section -->
                            @if (($shift->require_qr ?? false) && (!$todayAttendance || !$todayAttendance->jam_pulang))
                                <div class="space-y-2 mb-6">
                                    <p class="text-sm font-bold text-zinc-900">Scan QR Code Absensi</p>
                                    <div id="reader" class="rounded-2xl overflow-hidden border-2 border-zinc-200"></div>
                                    <p id="qr-status" class="text-xs text-zinc-500 text-center">Arahkan kamera ke QR Code.
                                    </p>
                                </div>
                            @endif

                            <!-- Camera Section (Selfie) - ONLY if Not Non-Remote -->
                            @if (!($shift->require_qr ?? false))
                                <div class="space-y-4">
                                    <div class="relative mx-auto max-w-[400px] aspect-[4/3] rounded-2xl bg-zinc-900 overflow-hidden shadow-inner border-2 border-zinc-100"
                                        id="camera-container">
                                        <video id="webcam" autoplay playsinline
                                            class="w-full h-full object-cover"></video>
                                        <canvas id="canvas" class="hidden"></canvas>
                                        <img id="captured-image" class="hidden w-full h-full object-cover">

                                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2">
                                            <button type="button" id="capture-btn"
                                                class="h-14 w-14 rounded-full bg-white/20 backdrop-blur-md border-4 border-white flex items-center justify-center hover:scale-110 transition-transform">
                                                <div class="h-10 w-10 rounded-full bg-white"></div>
                                            </button>
                                            <button type="button" id="retake-btn"
                                                class="hidden h-10 px-4 rounded-full bg-zinc-900/80 backdrop-blur-md text-white text-xs font-bold border border-white/20">Ulangi</button>
                                        </div>
                                    </div>
                                    <p class="text-xs text-zinc-400 italic" id="location-status">Mendeteksi lokasi...</p>
                                </div>
                            @else
                                <div class="text-center py-4 bg-zinc-50 rounded-xl border border-zinc-100 mb-4">
                                    <p class="text-xs text-zinc-400 italic" id="location-status">Mendeteksi lokasi...</p>
                                </div>
                            @endif

                            <form id="attendance-form"
                                action="{{ !$todayAttendance ? route('employee.attendance.clock-in') : route('employee.attendance.clock-out') }}"
                                method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="image" id="image-input">
                                <input type="hidden" name="location" id="location-input">
                                <input type="hidden" name="qr_content" id="qr-input">

                                @if (!$todayAttendance)
                                    <button type="submit" id="submit-btn" disabled
                                        class="w-full py-4 rounded-2xl bg-zinc-900 text-white font-bold opacity-50 cursor-not-allowed transition-all flex items-center justify-center gap-2">
                                        <i data-lucide="log-in" class="h-5 w-5"></i>
                                        Absen Masuk
                                    </button>
                                    <p class="text-xs text-zinc-500">Jadwal Masuk: <span
                                            class="font-bold">{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}</span>
                                    </p>
                                @else
                                    <button type="submit" id="submit-btn" disabled
                                        class="w-full py-4 rounded-2xl bg-orange-600 text-white font-bold opacity-50 cursor-not-allowed transition-all flex items-center justify-center gap-2">
                                        <i data-lucide="log-out" class="h-5 w-5"></i>
                                        Absen Pulang
                                    </button>
                                    <p class="text-xs text-zinc-500">Jadwal Pulang: <span
                                            class="font-bold">{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</span>
                                    </p>
                                @endif
                            </form>
                        @else
                            <!-- Already Finished -->
                            <div class="py-12 flex flex-col items-center space-y-4">
                                <div
                                    class="h-24 w-24 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center border-2 border-emerald-100">
                                    <i data-lucide="party-popper" class="h-12 w-12"></i>
                                </div>
                                <h3 class="text-xl font-bold text-zinc-900">Presensi Hari Ini Selesai</h3>
                                <p class="text-sm text-zinc-500 max-w-xs mx-auto">Terima kasih atas dedikasi Anda hari ini.
                                    Selamat beristirahat dan hati-hati di jalan.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Shift Info Card -->
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 flex items-start gap-4">
                    <div
                        class="h-12 w-12 rounded-xl bg-white flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                        <i data-lucide="clock" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-blue-900">Jadwal Shift: {{ $shift->name }}</h4>
                        <p class="text-sm text-blue-700 mt-1">
                            Jam Kerja: {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}.
                        </p>
                    </div>
                </div>
            </div>

            <!-- History Content -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-zinc-100 bg-zinc-50/50 flex items-center justify-between">
                        <h3 class="font-bold text-zinc-900 text-sm">Riwayat Terakhir</h3>
                        <i data-lucide="history" class="h-4 w-4 text-zinc-400"></i>
                    </div>
                    <div class="p-2">
                        <div class="space-y-1">
                            @forelse($history as $item)
                                <div
                                    class="p-4 rounded-xl hover:bg-zinc-50 transition-colors flex items-center justify-between border border-transparent hover:border-zinc-100">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-10 w-10 rounded-lg bg-zinc-100 flex flex-col items-center justify-center">
                                            <span
                                                class="text-[9px] uppercase font-bold text-zinc-400">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('M') }}</span>
                                            <span
                                                class="text-sm font-bold text-zinc-700 leading-none">{{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-zinc-900">
                                                @if ($item->status == 'Hadir')
                                                    <span class="text-emerald-600">Hadir</span>
                                                @else
                                                    <span class="text-red-600">{{ $item->status }}</span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-zinc-500">{{ $item->jam_masuk ?? '--:--' }} -
                                                {{ $item->jam_pulang ?? '--:--' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if ($item->terlambat > 0)
                                            <div
                                                class="text-[10px] bg-red-50 text-red-600 px-2 py-0.5 rounded font-bold uppercase tracking-tighter">
                                                Terlambat {{ $item->terlambat }}m</div>
                                        @endif
                                        @if ($item->pulang_cepat > 0)
                                            <div
                                                class="text-[10px] bg-orange-50 text-orange-600 px-2 py-0.5 rounded font-bold uppercase tracking-tighter mt-1">
                                                Pulang Cepat {{ $item->pulang_cepat }}m</div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-center text-zinc-500 py-8">Belum ada riwayat presensi.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script>
            // Time Display
            function updateTime() {
                const now = new Date();
                const timeStr = now.getHours().toString().padStart(2, '0') + ':' +
                    now.getMinutes().toString().padStart(2, '0') + ':' +
                    now.getSeconds().toString().padStart(2, '0');
                document.getElementById('current-time').textContent = timeStr;
            }
            setInterval(updateTime, 1000);
            updateTime();

            @if (!$todayAttendance || !$todayAttendance->jam_pulang)
                // Webcam Logic (only if elements exist)
                const video = document.getElementById('webcam');
                const canvas = document.getElementById('canvas');
                const capturedImage = document.getElementById('captured-image');
                const captureBtn = document.getElementById('capture-btn');
                const retakeBtn = document.getElementById('retake-btn');

                const submitBtn = document.getElementById('submit-btn');
                const imageInput = document.getElementById('image-input');
                const locationInput = document.getElementById('location-input');
                const locationStatus = document.getElementById('location-status');

                // QR Vars
                const requireQr = {{ $shift->require_qr ?? false ? 'true' : 'false' }};
                const qrInput = document.getElementById('qr-input');
                const qrStatus = document.getElementById('qr-status');
                let html5QrcodeScanner = null;

                let stream;

                async function initCamera() {
                    if (!video) return; // Skip if no camera element (Non-Remote)
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({
                            video: {
                                facingMode: 'user'
                            },
                            audio: false
                        });
                        video.srcObject = stream;
                    } catch (err) {
                        console.error("Error accessing webcam: ", err);
                        alert("Mohon izinkan akses kamera untuk melakukan presensi.");
                    }
                }

                // Only init camera if not requiring QR (meaning Remote shift)
                // Wait, logic is: Remote -> Selfie Required, No QR check.
                // Non-Remote -> QR Required (Location), Selfie Not Required.
                // The elements for Selfie are hidden if requireQr is true.
                if (!requireQr) {
                    initCamera();
                }

                if (captureBtn) {
                    captureBtn.onclick = () => {
                        const context = canvas.getContext('2d');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);

                        const dataUrl = canvas.toDataURL('image/jpeg');
                        capturedImage.src = dataUrl;
                        imageInput.value = dataUrl;

                        video.classList.add('hidden');
                        capturedImage.classList.remove('hidden');
                        captureBtn.classList.add('hidden');
                        retakeBtn.classList.remove('hidden');

                        checkReady();
                    };
                }

                if (retakeBtn) {
                    retakeBtn.onclick = () => {
                        video.classList.remove('hidden');
                        capturedImage.classList.add('hidden');
                        captureBtn.classList.remove('hidden');
                        retakeBtn.classList.add('hidden');
                        imageInput.value = '';
                        checkReady();
                    };
                }

                // ... QR Logic (unchanged) ...
                if (requireQr && document.getElementById('reader')) {
                    function onScanSuccess(decodedText, decodedResult) {
                        console.log(`Code matched = ${decodedText}`, decodedResult);
                        if (qrInput) qrInput.value = decodedText;
                        if (qrStatus) {
                            qrStatus.textContent = "✅ QR Code Terdeteksi: " + decodedText;
                            qrStatus.className = "text-xs text-emerald-600 font-bold text-center mt-2";
                        }
                        checkReady();
                    }

                    function onScanFailure(error) {}

                    html5QrcodeScanner = new Html5QrcodeScanner(
                        "reader", {
                            fps: 10,
                            qrbox: {
                                width: 250,
                                height: 250
                            }
                        }, false);
                    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                }

                // ... Location Logic (unchanged) ...
                function initLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            async (position) => {
                                    const lat = position.coords.latitude;
                                    const lon = position.coords.longitude;
                                    if (locationStatus) locationStatus.textContent = "🔍 Mencari alamat...";

                                    try {
                                        const response = await fetch(
                                            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`
                                        );
                                        const data = await response.json();
                                        const address = data.display_name;
                                        const shortAddress = data.address.road || data.address.suburb || data.address
                                            .city || data.display_name.split(',')[0];

                                        locationInput.value = address;
                                        if (locationStatus) {
                                            locationStatus.textContent = "📍 " + shortAddress;
                                            locationStatus.classList.replace('text-zinc-400', 'text-emerald-500');
                                            locationStatus.classList.replace('text-red-500', 'text-emerald-500');
                                        }
                                    } catch (error) {
                                        const loc = lat + ',' + lon;
                                        locationInput.value = loc;
                                        if (locationStatus) {
                                            locationStatus.textContent = "📍 Lokasi terdeteksi (" + lat.toFixed(4) + ")";
                                            locationStatus.classList.replace('text-zinc-400', 'text-emerald-500');
                                        }
                                    }
                                    checkReady();
                                },
                                (error) => {
                                    if (locationStatus) {
                                        locationStatus.textContent = "⚠️ Lokasi tidak dapat dideteksi.";
                                        locationStatus.classList.replace('text-zinc-400', 'text-red-500');
                                    }
                                }
                        );
                    } else {
                        if (locationStatus) locationStatus.textContent = "Browser tidak mendukung lokasi.";
                    }
                }
                initLocation();

                function checkReady() {
                    let ready = true;
                    // Image required ONLY if NOT requireQr (Remote)
                    if (!requireQr && !imageInput.value) ready = false;

                    if (!locationInput.value) ready = false;

                    // QR required if requireQr (Non-Remote)
                    if (requireQr && (!qrInput || !qrInput.value)) ready = false;

                    if (ready) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                }
            @endif
        </script>
    @endpush
@endsection
