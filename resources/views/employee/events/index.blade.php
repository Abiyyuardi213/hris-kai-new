@extends('layouts.employee')
@section('title', 'Kalender & Agenda')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-zinc-900">Agenda & Kegiatan</h2>
            <p class="text-zinc-500 mt-1">Jadwal kegiatan, rapat, dan hari libur perusahaan.</p>
        </div>

        <!-- Filter -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-zinc-200">
            <form action="{{ route('employee.events.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="relative flex-1 min-w-[240px]">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari agenda atau deskripsi..."
                        class="flex h-10 w-full rounded-lg border border-zinc-300 pl-10 pr-3 py-2 text-sm placeholder:text-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                </div>

                <div class="w-full sm:w-48">
                    <select name="type" onchange="this.form.submit()"
                        class="flex h-10 w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all">
                        <option value="">Semua Tipe</option>
                        <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>Event</option>
                        <option value="meeting" {{ request('type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                        <option value="holiday" {{ request('type') == 'holiday' ? 'selected' : '' }}>Hari Libur</option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <button type="submit"
                    class="inline-flex h-10 items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors">
                    Filter
                </button>
            </form>
        </div>

        <!-- Event List Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($events as $event)
                <div class="group bg-white rounded-2xl border border-zinc-100 shadow-sm hover:shadow-lg transition-all overflow-hidden flex flex-col h-full cursor-pointer"
                    onclick='showEvent(@json($event))'>
                    <!-- Date Header -->
                    <div class="px-6 py-4 bg-zinc-50 border-b border-zinc-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex flex-col items-center justify-center bg-white border border-zinc-200 rounded-lg w-10 h-10 shadow-sm">
                                <span
                                    class="text-[10px] uppercase font-bold text-zinc-400">{{ $event->start_date->format('M') }}</span>
                                <span class="text-sm font-bold text-zinc-900">{{ $event->start_date->format('d') }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-zinc-900">{{ $event->start_date->format('l') }}</div>
                                <div class="text-xs text-zinc-500">{{ $event->start_date->format('Y') }}</div>
                            </div>
                        </div>

                        @php
                            $colors = [
                                'event' => 'bg-blue-100 text-blue-700 ring-blue-700/10',
                                'meeting' => 'bg-purple-100 text-purple-700 ring-purple-700/10',
                                'holiday' => 'bg-red-100 text-red-700 ring-red-700/10',
                                'other' => 'bg-gray-100 text-gray-700 ring-gray-700/10',
                            ];
                            $labels = [
                                'event' => 'Event',
                                'meeting' => 'Meeting',
                                'holiday' => 'Libur',
                                'other' => 'Lainnya',
                            ];
                        @endphp
                        <span
                            class="inline-flex items-center rounded-md px-2 py-1 text-[10px] font-bold uppercase tracking-wider ring-1 ring-inset {{ $colors[$event->type] ?? $colors['other'] }}">
                            {{ $labels[$event->type] ?? ucfirst($event->type) }}
                        </span>
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex-1 flex flex-col">
                        <h3
                            class="text-lg font-bold text-zinc-900 group-hover:text-blue-600 transition-colors line-clamp-2 mb-2">
                            {{ $event->title }}
                        </h3>

                        <div class="space-y-2 mt-auto">
                            <div class="flex items-center gap-2 text-sm text-zinc-500">
                                <i data-lucide="clock" class="h-4 w-4 shrink-0"></i>
                                <span>{{ $event->start_date->format('H:i') }} -
                                    {{ $event->end_date->format('H:i') }}</span>
                            </div>
                            @if ($event->location)
                                <div class="flex items-center gap-2 text-sm text-zinc-500">
                                    <i data-lucide="map-pin" class="h-4 w-4 shrink-0"></i>
                                    <span class="truncate">{{ $event->location }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-3 bg-zinc-50 border-t border-zinc-100 flex items-center justify-between">
                        <span class="text-xs font-medium text-zinc-400">Klik untuk detail</span>
                        <i data-lucide="arrow-right"
                            class="h-4 w-4 text-zinc-300 group-hover:text-zinc-900 transition-colors"></i>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                    <div class="p-4 rounded-full bg-zinc-50 border border-zinc-100 mb-4">
                        <i data-lucide="calendar-off" class="h-8 w-8 text-zinc-300"></i>
                    </div>
                    <h3 class="text-lg font-medium text-zinc-900">Belum ada agenda</h3>
                    <p class="text-zinc-500 mt-1">Belum ada agenda publik yang ditampilkan.</p>
                </div>
            @endforelse
        </div>

        @if ($events->hasPages())
            <div class="mt-6">
                {{ $events->links() }}
            </div>
        @endif
    </div>

    <!-- Detail Modal -->
    <div id="showModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm" onclick="closeModal('showModal')">
        </div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-zinc-100">
                    <!-- Close Button -->
                    <button type="button" onclick="closeModal('showModal')"
                        class="absolute top-4 right-4 z-10 p-2 text-zinc-400 hover:text-zinc-600 bg-white/50 hover:bg-white rounded-full transition-all">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>

                    <!-- Modal Content -->
                    <div class="bg-white">
                        <!-- Header -->
                        <div class="px-6 pt-8 pb-6 border-b border-zinc-100">
                            <div class="flex items-center gap-3 mb-4">
                                <span id="showType"
                                    class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-bold uppercase tracking-wider ring-1 ring-inset"></span>
                            </div>
                            <h3 class="text-2xl font-bold text-zinc-900 leading-tight" id="showTitle"></h3>
                        </div>

                        <!-- Body -->
                        <div class="p-6 space-y-6">
                            <!-- Time & Location -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-start gap-3 p-4 bg-zinc-50 rounded-xl border border-zinc-100">
                                    <div class="p-2 bg-white rounded-lg shadow-sm text-zinc-900">
                                        <i data-lucide="calendar" class="h-5 w-5"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Waktu</p>
                                        <p id="showDate" class="text-sm font-semibold text-zinc-900 mt-1"></p>
                                        <p id="showTime" class="text-sm text-zinc-500"></p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 p-4 bg-zinc-50 rounded-xl border border-zinc-100">
                                    <div class="p-2 bg-white rounded-lg shadow-sm text-zinc-900">
                                        <i data-lucide="map-pin" class="h-5 w-5"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Lokasi</p>
                                        <p id="showLocation" class="text-sm font-semibold text-zinc-900 mt-1"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <p class="text-sm font-bold text-zinc-900 mb-2 flex items-center gap-2">
                                    <i data-lucide="align-left" class="h-4 w-4 text-zinc-400"></i>
                                    Deskripsi Agenda
                                </p>
                                <div id="showDescription"
                                    class="text-sm text-zinc-600 leading-relaxed whitespace-pre-line bg-zinc-50 p-4 rounded-xl border border-zinc-100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openModal(id) {
                document.getElementById(id).classList.remove('hidden');
            }

            function closeModal(id) {
                document.getElementById(id).classList.add('hidden');
            }

            function showEvent(event) {
                // Set Text
                document.getElementById('showTitle').textContent = event.title;
                document.getElementById('showLocation').textContent = event.location || 'Online / Tidak ada lokasi';
                document.getElementById('showDescription').textContent = event.description || 'Tidak ada deskripsi.';

                // Format Dates
                const startDate = new Date(event.start_date);
                const endDate = new Date(event.end_date);

                const dateOptions = {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                };
                const timeOptions = {
                    hour: '2-digit',
                    minute: '2-digit'
                };

                document.getElementById('showDate').textContent = startDate.toLocaleDateString('id-ID', dateOptions);
                document.getElementById('showTime').textContent =
                    `${startDate.toLocaleTimeString('id-ID', timeOptions)} - ${endDate.toLocaleTimeString('id-ID', timeOptions)} WIB`;

                // Type Badge
                const typeEl = document.getElementById('showType');
                const typeColors = {
                    'event': 'bg-blue-50 text-blue-700 ring-blue-700/10',
                    'meeting': 'bg-purple-50 text-purple-700 ring-purple-700/10',
                    'holiday': 'bg-red-50 text-red-700 ring-red-700/10',
                    'other': 'bg-gray-50 text-gray-700 ring-gray-700/10',
                };
                const typeLabels = {
                    'event': 'Event',
                    'meeting': 'Meeting',
                    'holiday': 'Libur',
                    'other': 'Lainnya',
                };

                typeEl.className =
                    `inline-flex items-center rounded-md px-2.5 py-1 text-xs font-bold uppercase tracking-wider ring-1 ring-inset ${typeColors[event.type] || typeColors['other']}`;
                typeEl.textContent = typeLabels[event.type] || event.type;

                openModal('showModal');
            }

            document.addEventListener('keydown', function(event) {
                if (event.key === "Escape") {
                    closeModal('showModal');
                }
            });
        </script>
    @endpush
@endsection
