@extends('layouts.app')
@section('title', 'Kalender & Agenda')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Agenda & Kegiatan</h2>
                <p class="text-zinc-500 mt-1">Kelola jadwal kegiatan, rapat, dan hari libur perusahaan.</p>
            </div>
            <div>
                <a href="{{ route('admin.events.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors shadow-sm">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Tambah Agenda
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="space-y-4">
            <!-- Search and Filter -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-zinc-200">
                <form action="{{ route('admin.events.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
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

                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="inline-flex h-10 items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('admin.events.index') }}"
                            class="inline-flex h-10 items-center justify-center rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 transition-colors">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-zinc-50/50 text-zinc-500 border-b border-zinc-200">
                            <tr>
                                <th class="px-6 py-4 font-medium">Agenda</th>
                                <th class="px-6 py-4 font-medium">Tipe</th>
                                <th class="px-6 py-4 font-medium">Waktu</th>
                                <th class="px-6 py-4 font-medium">Lokasi</th>
                                <th class="px-6 py-4 font-medium">Publik</th>
                                <th class="px-6 py-4 font-medium text-right w-[120px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse ($events as $event)
                                <tr class="group hover:bg-zinc-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-zinc-900">{{ $event->title }}</div>
                                        @if ($event->description)
                                            <div class="text-xs text-zinc-500 mt-1 line-clamp-1">{{ $event->description }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
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
                                            class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $colors[$event->type] ?? $colors['other'] }}">
                                            {{ $labels[$event->type] ?? ucfirst($event->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-zinc-900">{{ $event->start_date->format('d M Y H:i') }}</div>
                                        <div class="text-xs text-zinc-500">s/d {{ $event->end_date->format('d M Y H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-zinc-500">
                                        {{ $event->location ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($event->is_public)
                                            <span
                                                class="inline-flex items-center text-xs font-medium text-green-700 bg-green-50 px-2 py-1 rounded-full ring-1 ring-inset ring-green-600/20">
                                                Publik
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center text-xs font-medium text-zinc-600 bg-zinc-100 px-2 py-1 rounded-full ring-1 ring-inset ring-zinc-500/10">
                                                Internal
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button onclick='showEvent(@json($event))'
                                                class="p-2 text-zinc-400 hover:text-zinc-900 hover:bg-zinc-100 rounded-lg transition-colors"
                                                title="Lihat Detail">
                                                <i data-lucide="eye" class="h-4 w-4"></i>
                                            </button>
                                            <a href="{{ route('admin.events.edit', ['event' => $event->id] + request()->only('page', 'sort', 'search')) }}"
                                                class="p-2 text-zinc-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                                title="Edit">
                                                <i data-lucide="edit-2" class="h-4 w-4"></i>
                                            </a>
                                            <button onclick="confirmDelete('{{ $event->id }}', '{{ $event->title }}')"
                                                class="p-2 text-zinc-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-zinc-500">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <div class="p-4 rounded-full bg-zinc-50 border border-zinc-100">
                                                <i data-lucide="calendar-off" class="h-8 w-8 text-zinc-300"></i>
                                            </div>
                                            <div class="text-center">
                                                <p class="font-medium text-zinc-900">Belum ada agenda</p>
                                                <p class="text-sm mt-1">Mulai dengan menambahkan agenda baru.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($events->hasPages())
                    <div class="p-4 border-t border-zinc-200 bg-zinc-50/50">
                        {{ $events->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Show Modal -->
    <div id="showModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm" onclick="closeModal('showModal')">
        </div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-zinc-100">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6">
                        <div class="flex items-start justify-between">
                            <h3 class="text-xl font-bold leading-6 text-zinc-900" id="showTitle"></h3>
                            <button type="button" onclick="closeModal('showModal')"
                                class="text-zinc-400 hover:text-zinc-500">
                                <i data-lucide="x" class="h-5 w-5"></i>
                            </button>
                        </div>

                        <div class="mt-6 space-y-4">
                            <!-- Type & Visibility -->
                            <div class="flex items-center gap-3">
                                <span id="showType"
                                    class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset"></span>
                                <span id="showVisibility"
                                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset"></span>
                            </div>

                            <!-- Date Time -->
                            <div
                                class="flex items-start gap-3 text-sm text-zinc-600 bg-zinc-50 p-3 rounded-lg border border-zinc-100">
                                <i data-lucide="calendar" class="h-5 w-5 text-zinc-400 mt-0.5 shrink-0"></i>
                                <div>
                                    <div class="font-medium text-zinc-900">Waktu Pelaksanaan</div>
                                    <div id="showDate" class="mt-1"></div>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="flex items-start gap-3 text-sm text-zinc-600">
                                <i data-lucide="map-pin" class="h-5 w-5 text-zinc-400 mt-0.5 shrink-0"></i>
                                <div>
                                    <div class="font-medium text-zinc-900">Lokasi</div>
                                    <div id="showLocation" class="mt-1"></div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="border-t border-zinc-100 pt-4">
                                <div class="text-sm font-medium text-zinc-900 mb-2">Deskripsi</div>
                                <div id="showDescription" class="text-sm text-zinc-600 whitespace-pre-line"></div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-zinc-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-zinc-100">
                        <button type="button" onclick="closeModal('showModal')"
                            class="inline-flex w-full justify-center rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 sm:w-auto">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity backdrop-blur-sm" onclick="closeModal('deleteModal')">
        </div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-zinc-100">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="alert-triangle" class="h-5 w-5 text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-zinc-900">Hapus Agenda</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-zinc-500">
                                        Apakah Anda yakin ingin menghapus agenda <span id="deleteName"
                                            class="font-medium text-zinc-900"></span>?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-zinc-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-zinc-100">
                        <form id="deleteForm" method="POST" class="inline-block w-full sm:w-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Hapus</button>
                        </form>
                        <button type="button" onclick="closeModal('deleteModal')"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 sm:mt-0 sm:w-auto">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
            document.getElementById('showLocation').textContent = event.location || '-';
            document.getElementById('showDescription').textContent = event.description || 'Tidak ada deskripsi.';

            // Format Dates
            const startDate = new Date(event.start_date);
            const endDate = new Date(event.end_date);

            const options = {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            document.getElementById('showDate').textContent =
                `${startDate.toLocaleDateString('id-ID', options)} s/d ${endDate.toLocaleDateString('id-ID', options)}`;

            // Type Badge
            const typeEl = document.getElementById('showType');
            const typeColors = {
                'event': 'bg-blue-100 text-blue-700 ring-blue-700/10',
                'meeting': 'bg-purple-100 text-purple-700 ring-purple-700/10',
                'holiday': 'bg-red-100 text-red-700 ring-red-700/10',
                'other': 'bg-gray-100 text-gray-700 ring-gray-700/10',
            };
            const typeLabels = {
                'event': 'Event',
                'meeting': 'Meeting',
                'holiday': 'Libur',
                'other': 'Lainnya',
            };

            typeEl.className =
                `inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold ring-1 ring-inset ${typeColors[event.type] || typeColors['other']}`;
            typeEl.textContent = typeLabels[event.type] || event.type;

            // Visibility Badge
            const visEl = document.getElementById('showVisibility');
            if (event.is_public) {
                visEl.className =
                    "inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset text-green-700 bg-green-50 ring-green-600/20";
                visEl.textContent = "Publik";
            } else {
                visEl.className =
                    "inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset text-zinc-600 bg-zinc-100 ring-zinc-500/10";
                visEl.textContent = "Internal";
            }

            openModal('showModal');
        }

        function confirmDelete(id, name) {
            document.getElementById('deleteName').textContent = name;
            // Append current query string to retain pagination/filters
            document.getElementById('deleteForm').action = "{{ url('admin/events') }}/" + id + window.location.search;
            openModal('deleteModal');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal('deleteModal');
                closeModal('showModal');
            }
        });

        // Preserve scroll position (reusing generic logic if available, or simpler inline here)
        const deleteForm = document.getElementById('deleteForm');
        if (deleteForm) {
            deleteForm.addEventListener('submit', function() {
                sessionStorage.setItem('events_scroll_pos', window.scrollY);
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            const savedScrollPos = sessionStorage.getItem('events_scroll_pos');
            if (savedScrollPos) {
                window.scrollTo(0, parseInt(savedScrollPos));
                sessionStorage.removeItem('events_scroll_pos');
            }
        });
    </script>
@endsection
