<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Izin - {{ $izin->pegawai->nama_lengkap }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm 2cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 16pt;
            font-weight: bold;
            color: #002060;
            text-transform: uppercase;
            margin: 0;
            text-align: center;
        }

        .header-subtitle {
            font-size: 13pt;
            font-weight: bold;
            color: #002060;
            margin: 0;
            text-align: center;
        }

        .logo {
            width: 80px;
        }

        .meta-info {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }

        .meta-info td {
            vertical-align: top;
            padding: 2px 0;
        }

        .content-body {
            text-align: justify;
            margin-bottom: 20px;
        }

        .content-body p {
            margin-bottom: 15px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .data-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .label {
            width: 150px;
        }

        .separator {
            width: 20px;
            text-align: center;
        }

        .signature-section {
            margin-top: 50px;
            width: 100%;
        }

        .signature-box {
            width: 280px;
            float: right;
            text-align: center;
        }

        .footer-note {
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 8pt;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 15%;"></td>
            <td style="width: 70%;">
                <div class="header-title">SURAT IZIN / CUTI PEGAWAI</div>
                <div class="header-subtitle">PT KERETA API INDONESIA (Persero)</div>
            </td>
            <td style="width: 15%; text-align: right;">
                <img src="{{ public_path('image/logo-kai.png') }}" alt="KAI Logo" class="logo">
            </td>
        </tr>
    </table>

    <!-- Meta Info -->
    <table class="meta-info">
        <tr>
            <td style="width: 60%;">
                <table>
                    <tr>
                        <td width="70">Nomor</td>
                        <td width="10">:</td>
                        <td>{{ str_pad($izin->id, 3, '0', STR_PAD_LEFT) }}/IZN/DF/{{ date('Y') }}</td>
                    </tr>
                    <tr>
                        <td>Sifat</td>
                        <td>:</td>
                        <td>Biasa</td>
                    </tr>
                    <tr>
                        <td>Perihal</td>
                        <td>:</td>
                        <td><strong>Permohonan {{ ucfirst($izin->type) }}</strong></td>
                    </tr>
                </table>
            </td>
            <td style="width: 40%; text-align: right;">
                Bandung, {{ \Carbon\Carbon::parse($izin->created_at)->translatedFormat('d F Y') }}
            </td>
        </tr>
    </table>

    <div class="content-body">
        <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>

        <table class="data-table">
            <tr>
                <td class="label">Nama Pegawai</td>
                <td class="separator">:</td>
                <td style="font-weight: bold;">{{ $izin->pegawai->nama_lengkap }}</td>
            </tr>
            <tr>
                <td class="label">NIPP</td>
                <td class="separator">:</td>
                <td>{{ $izin->pegawai->nip }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="separator">:</td>
                <td>{{ $izin->pegawai->jabatan->name }}</td>
            </tr>
            <tr>
                <td class="label">Unit Kerja</td>
                <td class="separator">:</td>
                <td>{{ $izin->pegawai->divisi->name ?? '-' }}</td>
            </tr>
        </table>

        <p>Telah mengajukan permohonan <strong>{{ strtoupper($izin->type) }}</strong> dengan rincian sebagai berikut:
        </p>

        <table class="data-table">
            <tr>
                <td class="label">Tanggal Mulai</td>
                <td class="separator">:</td>
                <td>{{ \Carbon\Carbon::parse($izin->start_date)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Selesai</td>
                <td class="separator">:</td>
                <td>{{ \Carbon\Carbon::parse($izin->end_date)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Lama Izin</td>
                <td class="separator">:</td>
                <td>
                    @php
                        $start = \Carbon\Carbon::parse($izin->start_date);
                        $end = \Carbon\Carbon::parse($izin->end_date);
                        $days = $start->diffInDays($end) + 1;
                    @endphp
                    {{ $days }} Hari
                </td>
            </tr>
            <tr>
                <td class="label">Alasan</td>
                <td class="separator">:</td>
                <td>{{ $izin->reason }}</td>
            </tr>
        </table>

        <p>Demikian surat permohonan ini disampaikan, atas perhatiannya diucapkan terima kasih.</p>
    </div>

    <!-- Signature -->
    <div class="signature-section">
        <div class="signature-box">
            <p style="margin-bottom: 2px; font-weight: bold;">Managing Director of Finance,</p>

            @if ($mdFinance)
                <div style="margin: 15px 0;">
                    <img src="data:image/svg+xml;base64, {{ base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->generate($mdFinance->nip . ';' . $mdFinance->nama_lengkap . ';TTD Elektronik Surat Izin')) }}"
                        alt="QR Code">
                </div>
                <div style="font-weight: bold; text-decoration: underline;">
                    {{ $mdFinance->nama_lengkap }}
                </div>
                <p style="margin-top: 2px; font-size: 10pt;">NIPP {{ $mdFinance->nip }}</p>
            @else
                <div style="height: 100px;"></div>
                <div style="font-weight: bold; text-decoration: underline;">
                    INDARTO PAMOENGKAS
                </div>
                <p style="margin-top: 2px; font-size: 10pt;">NIPP 654324</p>
            @endif
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer-note">
        <table width="100%">
            <tr>
                <td width="80%" align="left">
                    Dokumen ini telah ditandatangani secara elektronik sesuai dengan ketentuan yang berlaku di PT Kereta
                    Api Indonesia (Persero).
                </td>
                <td width="20%" align="right">
                    www.kai.id
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
