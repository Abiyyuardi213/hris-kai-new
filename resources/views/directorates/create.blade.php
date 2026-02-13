@extends('layouts.app')

@section('content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-bold tracking-tight">Tambah Direktorat</h2>
            <a href="{{ route('directorates.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-zinc-50 hover:text-zinc-900 transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="rounded-xl border bg-white shadow-sm p-6 max-w-2xl">
            <form action="{{ route('directorates.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-zinc-900">Nama Direktorat</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('name') border-red-500 @enderror"
                            placeholder="Contoh: Direktorat Keuangan" oninput="generateCode()">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-medium text-zinc-900">Kode Direktorat
                            (Otomatis)</label>
                        <input type="text" id="code" name="code" value="{{ old('code') }}" required readonly
                            class="mt-1 block w-full rounded-lg border border-zinc-100 bg-zinc-50 px-3 py-2 text-sm text-zinc-500 cursor-not-allowed @error('code') border-red-500 @enderror"
                            placeholder="Akan terisi otomatis...">
                        @error('code')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-zinc-900">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 @error('description') border-red-500 @enderror"
                            placeholder="Deskripsi singkat direktorat...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2">
                        Simpan Direktorat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function generateCode() {
            const name = document.getElementById('name').value;
            const nextNumber = {{ $nextNumber }};

            if (!name) {
                document.getElementById('code').value = '';
                return;
            }

            let text = name.trim().toUpperCase();

            // Simplified logic for Directorate code generation
            // Usually simpler than Division codes, maybe D[initials] or similar?
            // Let's stick to the same logic as Divisions for now but maybe prefix with D- if needed?
            // Actually user didn't specify format. Let's use similar logic.

            const phraseSubs = {
                'SUMBER DAYA MANUSIA': 'SDM',
                'TEKNOLOGI INFORMASI': 'TI',
                'SARANA PRASARANA': 'SARPRAS',
                'HUBUNGAN MASYARAKAT': 'HUMAS',
                'JALAN DAN JEMBATAN': 'JJ',
                'SINYAL DAN TELEKOMUNIKASI': 'SINTEL',
                'PENGAMBAHAN DAN PEMELIHARAAN': 'PP',
                'KESELAMATAN DAN KEAMANAN': 'K3',
                'NON ANGKUTAN': 'NONANG',
                'QUALITY ASSURANCE': 'QA',
                'INTERNAL AUDIT': 'SPI'
            };

            for (const [phrase, abbr] of Object.entries(phraseSubs)) {
                if (text.includes(phrase)) {
                    text = text.replace(phrase, abbr);
                }
            }

            let words = text.split(/\s+/);

            const wordSubs = {
                'UMUM': 'UM',
                'KEUANGAN': 'KEU',
                'OPERASI': 'OP',
                'KOMERSIAL': 'KOM',
                'TEKNIK': 'TEK',
                'ADMINISTRASI': 'ADM',
                'LOGISTIK': 'LOG',
                'PELAYANAN': 'YAN',
                'PEMASARAN': 'SAR',
                'ANGKUTAN': 'ANG',
                'FASILITAS': 'FAS',
                'KONSTRUKSI': 'KON',
                'JARINGAN': 'JAR',
                'PENGADAAN': 'ADA',
                'STRATEGIS': 'STRA',
                'BISNIS': 'BIS',
                'HUKUM': 'HK',
                'PERENCANAAN': 'REN',
                'PENGEMBANGAN': 'BANG',
                'SISTEM': 'SIS',
                'DATA': 'DAT',
                'PUSAT': 'PST',
                'DAERAH': 'DA',
                'DIREKTORAT': 'DIR', // Added for directorate
                'KERUMAHTANGGAAN': 'KRT',
                'PROTOKOLER': 'PRO'
            };

            const ignoreWords = ['DAN', '&', 'OF', 'THE', 'UNTUK', 'DARI', 'DI', 'KE'];

            let codeParts = [];

            words.forEach(word => {
                word = word.replace(/[^A-Z0-9]/g, '');

                if (!word || ignoreWords.includes(word)) return;

                if (wordSubs[word]) {
                    codeParts.push(wordSubs[word]);
                } else {
                    if (word.length <= 4) {
                        codeParts.push(word);
                    } else {
                        codeParts.push(word.substring(0, 3));
                    }
                }
            });

            const abbreviation = codeParts.join('-');

            const paddedNumber = nextNumber.toString().padStart(2, '0'); // 2 digits usually enough for directorates

            document.getElementById('code').value = abbreviation + '-' + paddedNumber;
        }
    </script>
@endsection
