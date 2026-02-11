<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Peringatan - {{ $sanction->employee->nama_lengkap }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm 2cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            /* Reduced from 11pt */
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 3px solid #000;
        }

        .header-text {
            text-align: center;
            flex-grow: 1;
        }

        .header-title {
            font-size: 16pt;
            /* Reduced from 18pt */
            font-weight: bold;
            color: #002060;
            text-transform: uppercase;
            margin: 0;
            line-height: 1.2;
        }

        .header-subtitle {
            font-size: 13pt;
            /* Reduced from 14pt */
            font-weight: bold;
            color: #002060;
            margin: 0;
            line-height: 1.2;
        }

        .logo {
            width: 80px;
            /* Reduced from 100px */
            height: auto;
        }

        .meta-info {
            width: 100%;
            margin-bottom: 15px;
            /* Reduced spacing */
            border-collapse: collapse;
            font-size: 10pt;
        }

        .meta-info td {
            vertical-align: top;
            padding: 1px 0;
        }

        .yth-section {
            margin-bottom: 15px;
            /* Reduced spacing */
        }

        .content-body {
            text-align: justify;
            margin-bottom: 15px;
        }

        .content-body p {
            margin-top: 0;
            margin-bottom: 10px;
            /* Tighter paragraph spacing */
        }

        .disclaimer {
            font-size: 9pt;
            /* Smaller font for disclaimer */
            margin-bottom: 15px;
            font-style: italic;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 15px 0;
            font-size: 9.5pt;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 4px 8px;
            /* Compact padding */
            text-align: center;
        }

        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .violation-box {
            border: 1px solid #000;
            padding: 8px;
            margin: 5px 0 15px 0;
            min-height: 50px;
        }

        .signature-section {
            margin-top: 30px;
            /* Reduced from 40px */
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
            /* Prevent partial cut-off */
        }

        .signature-box {
            width: 240px;
            text-align: left;
        }

        .signature-name {
            margin-top: 70px;
            /* Adjusted space for signing */
            font-weight: bold;
            text-decoration: underline;
        }

        /* Utility for 2-column meta */
        .meta-left {
            width: 60%;
        }

        .meta-right {
            width: 40%;
            text-align: right;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .header-container {
                border-bottom: 3px solid #000 !important;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <!-- Header -->
    <div class="header-container">
        <div style="width: 80px;"></div> <!-- Spacer -->
        <div class="header-text">
            <div class="header-title">SURAT PERINGATAN ({{ $sanction->type }})</div>
            <div class="header-subtitle">PT KERETA API INDONESIA (Persero)</div>
        </div>
        <img src="{{ asset('image/logo-kai.png') }}" alt="KAI Logo" class="logo">
    </div>

    <!-- Meta Info -->
    <table class="meta-info">
        <tr>
            <td class="meta-left">
                <table>
                    <tr>
                        <td width="70">Nomor</td>
                        <td width="10">:</td>
                        <td>....../SP/HRD/{{ date('m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>Sifat</td>
                        <td>:</td>
                        <td>Rahasia</td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td>1 (satu) Berkas</td>
                    </tr>
                    <tr>
                        <td>Perihal</td>
                        <td>:</td>
                        <td><strong>Sanksi Disiplin</strong></td>
                    </tr>
                </table>
            </td>
            <td class="meta-right">
                Surabaya, {{ date('d F Y') }}
            </td>
        </tr>
    </table>

    <!-- Address / Yth -->
    <div class="yth-section">
        Yth.<br>
        Sdr. <strong>{{ $sanction->employee->nama_lengkap }}</strong><br>
        {{ $sanction->employee->jabatan->name ?? 'Staff' }}<br>
        {{ $sanction->employee->divisi->name ?? 'Kantor Pusat' }}<br>
        Di Tempat
    </div>

    <!-- Content -->
    <div class="content-body">
        <div class="disclaimer">
            <strong>DISCLAIMER !!!</strong><br>
            Dokumen ini bersifat SANGAT TERBATAS dan RAHASIA. Penyalahgunaan terkait penyebaran dan/atau penggunaan
            dokumen ini tanpa seizin pejabat berwenang akan dikenakan sanksi sesuai ketentuan yang berlaku.
        </div>

        <p>1. Berdasarkan hasil evaluasi kinerja dan kedisiplinan serta bukti-bukti pelanggaran yang telah ditemukan,
            maka Manajemen PT. Kereta Api Indonesia (Persero) Daerah Operasi 8 Surabaya memberikan Surat Peringatan
            kepada pegawai dengan rincian sebagai berikut:</p>

        <!-- Employee Data Table -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>NAMA PEGAWAI</th>
                    <th>NIPP</th>
                    <th>JABATAN / UNIT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $sanction->employee->nama_lengkap }}</td>
                    <td>{{ $sanction->employee->nip }}</td>
                    <td>{{ $sanction->employee->jabatan->name ?? '-' }} /
                        {{ $sanction->employee->divisi->name ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Violation Details -->
        <p>2. Adapun pelanggaran yang dilakukan oleh pegawai yang bersangkutan adalah sebagai berikut:</p>
        <div class="violation-box">
            {{ $sanction->description }}
        </div>

        <p>3. Sanksi ini berlaku efektif mulai tanggal
            <strong>{{ \Carbon\Carbon::parse($sanction->start_date)->translatedFormat('d F Y') }}</strong>
            @if ($sanction->end_date)
                sampai dengan tanggal
                <strong>{{ \Carbon\Carbon::parse($sanction->end_date)->translatedFormat('d F Y') }}</strong>
            @endif.
        </p>

        <p>4. Kami berharap Saudara dapat memperbaiki kinerja dan kedisiplinan serta tidak mengulangi pelanggaran
            tersebut dikemudian hari. Demikian Surat Peringatan ini dibuat untuk menjadi perhatian dan dilaksanakan
            sebagaimana mestinya.</p>
    </div>

    <!-- Signature -->
    <!-- Signature -->
    <div class="signature-section">
        <div class="signature-box" style="text-align: center; width: 280px;">
            @if (isset($vp) && $vp)
                <p style="margin-bottom: 2px; font-weight: bold;">Vice President</p>
                <p style="margin-top: 0; margin-bottom: 15px;">
                    {{ $vp->kantor->office_name ?? 'Daerah Operasi 8 Surabaya' }}</p>

                <div style="display: flex; justify-content: center; margin-bottom: 10px; position: relative;">
                    {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate(
                        $vp->nip . ';' . $vp->nama_lengkap . ';TTD Elektronik',
                    ) !!}
                </div>

                <div style="margin-top: 5px; font-weight: bold; text-decoration: underline;">
                    {{ $vp->nama_lengkap }}
                </div>
                <p style="margin-top: 2px; font-size: 9pt;">NIP. {{ $vp->nip }}</p>
            @else
                <p style="margin-bottom: 0;">Vice President</p>
                <p style="margin-top: 0;">Daerah Operasi 8 Surabaya</p>

                <div class="signature-name" style="margin-top: 80px;">
                    ( ..................................... )
                </div>
                <p style="margin-top: 5px; font-size: 10pt;">NIP. ...........................</p>
            @endif
        </div>
    </div>

</body>

</html>
