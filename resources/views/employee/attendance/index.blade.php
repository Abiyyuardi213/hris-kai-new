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
                            <!-- Camera Section -->
                            <div class="space-y-4">
                                <div class="relative mx-auto max-w-[400px] aspect-[4/3] rounded-2xl bg-zinc-900 overflow-hidden shadow-inner border-2 border-zinc-100"
                                    id="camera-container">
                                    <video id="webcam" autoplay playsinline class="w-full h-full object-cover"></video>
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

                            <form id="attendance-form"
                                action="{{ !$todayAttendance ? route('employee.attendance.clock-in') : route('employee.attendance.clock-out') }}"
                                method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="image" id="image-input">
                                <input type="hidden" name="location" id="location-input">

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
                        <p class="text-sm text-blue-700 mt-1">Anda diharapkan melakukan absensi masuk antara jam
                            {{ \Carbon\Carbon::parse($shift->start_time)->subMinutes(30)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($shift->start_time)->addMinutes(15)->format('H:i') }}.</p>
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
                                                class="text-[9px] uppercase font-bold text-zinc-400">{{ \Carbon\Carbon::parse($item->tanggal)->format('M') }}</span>
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
                // Webcam Logic
                const video = document.getElementById('webcam');
                const canvas = document.getElementById('canvas');
                const capturedImage = document.getElementById('captured-image');
                const captureBtn = document.getElementById('capture-btn');
                const retakeBtn = document.getElementById('retake-btn');
                const submitBtn = document.getElementById('submit-btn');
                const imageInput = document.getElementById('image-input');
                const locationInput = document.getElementById('location-input');
                const locationStatus = document.getElementById('location-status');

                let stream;

                async function initCamera() {
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

                initCamera();

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

                retakeBtn.onclick = () => {
                    video.classList.remove('hidden');
                    capturedImage.classList.add('hidden');
                    captureBtn.classList.remove('hidden');
                    retakeBtn.classList.add('hidden');
                    imageInput.value = '';
                    checkReady();
                };

                // Location Logic
                function initLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                const loc = position.coords.latitude + ',' + position.coords.longitude;
                                locationInput.value = loc;
                                locationStatus.textContent = "📍 Lokasi terdeteksi (" + position.coords.latitude.toFixed(
                                    4) + ")";
                                locationStatus.classList.replace('text-zinc-400', 'text-emerald-500');
                                checkReady();
                            },
                            (error) => {
                                locationStatus.textContent = "⚠️ Lokasi tidak dapat dideteksi.";
                                locationStatus.classList.replace('text-zinc-400', 'text-red-500');
                                // Fallback for demo if needed, but in real app usually required
                            }
                        );
                    } else {
                        locationStatus.textContent = "Browser tidak mendukung lokasi.";
                    }
                }

                initLocation();

                function checkReady() {
                    if (imageInput.value && locationInput.value) {
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
