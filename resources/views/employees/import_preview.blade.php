@extends('layouts.app')
@section('title', 'Pratinjau Impor Pegawai')

@section('content')
    <div class="flex flex-col space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight">Pratinjau Impor</h2>
                <p class="text-zinc-500 mt-1">Periksa data di bawah ini sebelum disimpan ke database.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('employees.index') }}" 
                   class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 transition-colors">
                    <i data-lucide="x" class="h-4 w-4"></i>
                    Batal
                </a>
                <form action="{{ route('employees.import') }}" method="POST">
                    @csrf
                    <input type="hidden" name="employees_data" value="{{ json_encode($previewData) }}">
                    <button type="submit" 
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-500 transition-colors shadow-sm shadow-emerald-200">
                        <i data-lucide="check" class="h-4 w-4"></i>
                        Impor Data
                    </button>
                </form>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50/50 text-zinc-500 border-b border-zinc-200">
                        <tr>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium whitespace-nowrap">NIP</th>
                            <th class="px-6 py-4 font-medium whitespace-nowrap">Nama Lengkap</th>
                            <th class="px-6 py-4 font-medium whitespace-nowrap">Divisi</th>
                            <th class="px-6 py-4 font-medium whitespace-nowrap">Jabatan</th>
                            <th class="px-6 py-4 font-medium whitespace-nowrap">Kantor</th>
                            <th class="px-6 py-4 font-medium whitespace-nowrap">Lokasi Lahir</th>
                            <th class="px-6 py-4 font-medium whitespace-nowrap text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach ($previewData as $index => $row)
                            <tr class="group hover:bg-zinc-50/50 transition-colors {{ $row['exists'] ? 'bg-red-50/50' : '' }}">
                                <td class="px-6 py-4">
                                    @if ($row['exists'])
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700">
                                            <i data-lucide="alert-circle" class="h-3 w-3"></i>
                                            Sudah Ada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700">
                                            <i data-lucide="plus-circle" class="h-3 w-3"></i>
                                            Baru
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium text-zinc-900">{{ $row['nip'] }}</td>
                                <td class="px-6 py-4 text-zinc-700">{{ $row['nama_lengkap'] }}</td>
                                <td class="px-6 py-4 text-zinc-600">{{ $row['divisi_label'] }}</td>
                                <td class="px-6 py-4 text-zinc-600">{{ $row['jabatan_label'] }}</td>
                                <td class="px-6 py-4 text-zinc-600">{{ $row['kantor_label'] }}</td>
                                <td class="px-6 py-4 text-zinc-600">{{ $row['tempat_lahir'] }}</td>
                                <td class="px-6 py-4 text-zinc-600">{{ $row['tanggal_lahir'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" 
                                            onclick="showDetail({{ json_encode($row) }})"
                                            class="inline-flex items-center gap-1.5 text-zinc-400 hover:text-zinc-600 transition-colors">
                                        <i data-lucide="eye" class="h-4 w-4"></i>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(count($previewData) > 0 && array_reduce($previewData, fn($carry, $item) => $carry || $item['exists'], false))
                <div class="bg-red-50 border-t border-red-100 px-6 py-4">
                    <p class="text-sm text-red-600 flex items-center gap-2 font-medium">
                        <i data-lucide="info" class="h-4 w-4"></i>
                        Data yang ditandai merah (Sudah Ada) tidak akan diimpor kembali untuk menghindari duplikasi.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-zinc-900/75 transition-opacity" onclick="closeModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-xl bg-white rounded-xl shadow-2xl border border-zinc-200 overflow-hidden transform transition-all">
                    <!-- Header -->
                    <div class="bg-white px-6 py-4 border-b border-zinc-100 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-zinc-900">Detail Calon Pegawai</h3>
                        <button type="button" onclick="closeModal()" class="text-zinc-400 hover:text-zinc-600 transition-colors">
                            <i data-lucide="x" class="h-5 w-5"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-0 max-h-[70vh] overflow-y-auto">
                        <table class="w-full text-sm">
                            <tbody class="divide-y divide-zinc-100" id="detailContent">
                                <!-- Content will be injected here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="bg-zinc-50 px-6 py-4 border-t border-zinc-100 flex justify-end">
                        <button type="button" onclick="closeModal()" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetail(data) {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('detailContent');
            
            const fields = [
                { label: 'NIP', value: data.nip },
                { label: 'NIK', value: data.nik },
                { label: 'Nama Lengkap', value: data.nama_lengkap },
                { label: 'Divisi', value: data.divisi_label },
                { label: 'Jabatan', value: data.jabatan_label },
                { label: 'Kantor', value: data.kantor_label },
                { label: 'Tempat Lahir', value: data.tempat_lahir },
                { label: 'Tanggal Lahir', value: data.tanggal_lahir },
                { label: 'Jenis Kelamin', value: data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' },
                { label: 'Agama', value: data.agama },
                { label: 'Status Pernikahan', value: data.status_pernikahan },
                { label: 'No HP', value: data.no_hp },
                { label: 'Email', value: data.email_pribadi },
                { label: 'Tgl Masuk', value: data.tanggal_masuk },
                { label: 'Status Pegawai', value: data.status_label },
                { label: 'Shift', value: data.shift_label },
                { label: 'Sisa Cuti', value: data.sisa_cuti },
                { label: 'Alamat KTP', value: data.alamat_ktp },
                { label: 'Alamat Domisili', value: data.alamat_domisili }
            ];

            content.innerHTML = fields.map(f => `
                <tr class="hover:bg-zinc-50/50 transition-colors">
                    <td class="px-6 py-3 font-semibold text-zinc-500 w-1/3">${f.label}</td>
                    <td class="px-6 py-3 text-zinc-900">${f.value || '-'}</td>
                </tr>
            `).join('');

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection
