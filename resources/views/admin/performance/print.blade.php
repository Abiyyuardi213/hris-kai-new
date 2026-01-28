<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPI Report - {{ $appraisal->pegawai->nama_lengkap }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 2.5cm;
            font-size: 11pt;
            line-height: 1.4;
        }

        .header {
            position: relative;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            min-height: 60px;
        }

        .logo {
            position: absolute;
            top: 0;
            left: 0;
            height: 35px;
        }

        .title {
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        .subtitle {
            font-size: 10pt;
            margin: 5px 0 0;
        }

        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }

        .info-table td {
            vertical-align: top;
            padding: 4px 0;
        }

        .label {
            width: 150px;
            font-weight: bold;
        }

        .kpi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .kpi-table th,
        .kpi-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .kpi-table th {
            background: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .footer {
            margin-top: 50px;
        }

        .signature-grid {
            width: 100%;
            margin-top: 40px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .score-box {
            float: right;
            border: 2px solid #000;
            padding: 15px;
            text-align: center;
            width: 120px;
        }

        .score-box .score {
            font-size: 24pt;
            font-weight: bold;
            margin: 0;
        }

        .score-box .rating {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
        }

        .catatan {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 10px;
            min-height: 80px;
            font-style: italic;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <img src="{{ asset('image/logo-kai.png') }}" class="logo">
        <h1 class="title">LAPORAN PENILAIAN KINERJA PEGAWAI</h1>
        <p class="subtitle">PT KERETA API INDONESIA (PERSERO)</p>
        <p class="subtitle" style="font-weight: bold;">Tahun Anggaran {{ $appraisal->tahun }}</p>
    </div>

    <div class="score-box">
        <p style="font-size: 8pt; margin: 0; font-weight: bold; text-transform: uppercase;">Final Score</p>
        <p class="score">{{ number_format($appraisal->total_score, 1) }}</p>
        <p class="rating">Rating: {{ $appraisal->rating }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Pegawai</td>
            <td>: {{ $appraisal->pegawai->nama_lengkap }}</td>
        </tr>
        <tr>
            <td class="label">NIP</td>
            <td>: {{ $appraisal->pegawai->nip }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td>: {{ $appraisal->pegawai->jabatan->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Unit Kerja / Divisi</td>
            <td>: {{ $appraisal->pegawai->divisi->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Periode Penilaian</td>
            <td>: {{ $appraisal->periode_mulai->format('d M Y') }} s/d
                {{ $appraisal->periode_selesai->format('d M Y') }}</td>
        </tr>
    </table>

    <h3 style="font-size: 11pt; border-bottom: 1px solid #000; padding-bottom: 5px;">I. PENCAPAIAN INDIKATOR KINERJA
        UTAMA (KPI)</h3>
    <table class="kpi-table">
        <thead>
            <tr>
                <th style="width: 40px;" class="text-center">No</th>
                <th>Elemen Penilaian</th>
                <th style="width: 100px;" class="text-center">Kategori</th>
                <th style="width: 60px;" class="text-center">Bobot</th>
                <th style="width: 60px;" class="text-center">Skor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($appraisal->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->indicator->name }}</strong><br>
                        <span style="font-size: 8pt; color: #444;">{{ $item->indicator->description }}</span>
                        @if ($item->comment)
                            <div style="margin-top: 5px; font-size: 8pt; font-style: italic;">Ket: {{ $item->comment }}
                            </div>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->indicator->category }}</td>
                    <td class="text-center">{{ $item->indicator->weight }}%</td>
                    <td class="text-center" style="font-weight: bold;">{{ $item->score }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f9f9f9;">
                <td colspan="4" class="text-right" style="font-weight: bold;">SKOR AKHIR TERTIMBANG</td>
                <td class="text-center" style="font-weight: bold; font-size: 12pt;">
                    {{ number_format($appraisal->total_score, 1) }}</td>
            </tr>
        </tfoot>
    </table>

    <h3 style="font-size: 11pt; border-bottom: 1px solid #000; padding-bottom: 5px;">II. CATATAN DAN HASIL REVIEW</h3>
    <div class="catatan">
        {{ $appraisal->catatan_reviewer ?? 'Tidak ada catatan tambahan dari penilai.' }}
    </div>

    <table class="signature-grid">
        <tr>
            <td class="signature-box">
                <p>Pejabat Penilai,</p>
                <br><br><br><br>
                <p><strong>{{ $appraisal->appraiser->name }}</strong></p>
                <p style="font-size: 9pt;">Admin HRIS</p>
            </td>
            <td style="width: 10%;"></td>
            <td class="signature-box">
                <p>Pegawai Ditilai,</p>
                <br><br><br><br>
                <p><strong>{{ $appraisal->pegawai->nama_lengkap }}</strong></p>
                <p style="font-size: 9pt;">NIP: {{ $appraisal->pegawai->nip }}</p>
            </td>
        </tr>
    </table>

    <div style="margin-top: 30px; font-size: 8pt; color: #888; text-align: center;">
        Dokumen ini dibuat secara digital melalui Sistem HRIS PT KAI pada tanggal {{ date('d/m/Y H:i') }}
    </div>
</body>

</html>
