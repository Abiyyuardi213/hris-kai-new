<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Slip Gaji - {{ $payroll->pegawai->nama_lengkap }} -
        {{ \Carbon\Carbon::create()->month($payroll->month)->translatedFormat('F') }} {{ $payroll->year }}</title>
    <style>
        @page {
            size: A5;
            margin: 0.8cm 1cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #333;
            padding: 0;
            margin: 0;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #002060;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 14pt;
            font-weight: bold;
            color: #002060;
            text-transform: uppercase;
            margin: 0;
        }

        .header-subtitle {
            font-size: 10pt;
            font-weight: bold;
            color: #002060;
            margin: 0;
        }

        .logo {
            width: 60px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .info-label {
            width: 100px;
            color: #666;
            font-size: 8pt;
            text-transform: uppercase;
        }

        .info-value {
            font-weight: bold;
            color: #000;
        }

        .section-title {
            font-size: 11pt;
            font-weight: bold;
            color: #002060;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
            margin-top: 20px;
            text-transform: uppercase;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #f9f9f9;
        }

        .data-table .label {
            font-weight: bold;
            color: #333;
        }

        .data-table .sub-label {
            font-size: 8pt;
            color: #888;
            display: block;
        }

        .data-table .amount {
            text-align: right;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
        }

        .total-section {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .total-label {
            font-size: 9pt;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }

        .total-amount {
            font-size: 16pt;
            font-weight: 800;
            color: #0f172a;
            margin: 3px 0;
        }

        .terbilang {
            font-style: italic;
            font-size: 9pt;
            color: #64748b;
        }

        .signature-section {
            margin-top: 40px;
            width: 100%;
        }

        .signature-box {
            width: 220px;
            float: right;
            text-align: center;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 10pt;
        }

        .qr-code {
            margin: 10px 0;
        }

        .signer-name {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 2px;
        }

        .signer-nip {
            font-size: 9pt;
            color: #666;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 8pt;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <div class="header-title">SLIP GAJI PEGAWAI</div>
                <div class="header-subtitle">PT KERETA API INDONESIA (Persero)</div>
            </td>
            <td style="width: 30%; text-align: right;">
                <img src="{{ public_path('image/logo-kai.png') }}" alt="KAI Logo" class="logo">
            </td>
        </tr>
    </table>

    <!-- Employee Info -->
    <table class="info-table">
        <tr>
            <td style="width: 50%;">
                <table>
                    <tr>
                        <td class="info-label">Nama Pegawai</td>
                        <td style="width: 10px;">:</td>
                        <td class="info-value">{{ $payroll->pegawai->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">NIP</td>
                        <td style="width: 10px;">:</td>
                        <td class="info-value">{{ $payroll->pegawai->nip }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Jabatan</td>
                        <td style="width: 10px;">:</td>
                        <td class="info-value">{{ $payroll->pegawai->jabatan->name }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%;">
                <table>
                    <tr>
                        <td class="info-label">Periode</td>
                        <td style="width: 10px;">:</td>
                        <td class="info-value">
                            {{ \Carbon\Carbon::create()->month($payroll->month)->translatedFormat('F') }}
                            {{ $payroll->year }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Referensi</td>
                        <td style="width: 10px;">:</td>
                        <td class="info-value">#PAY-{{ strtoupper(substr($payroll->id, 0, 8)) }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Status</td>
                        <td style="width: 10px;">:</td>
                        <td class="info-value"
                            style="color: {{ $payroll->status === 'paid' ? '#059669' : '#d97706' }}">
                            {{ strtoupper($payroll->status) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Earnings Detail -->
    <div class="section-title">Rincian Penghasilan</div>
    <table class="data-table">
        <tr>
            <td>
                <span class="label">Gaji Pokok (Harian)</span>
                <span class="sub-label">Rp {{ number_format($payroll->gaji_harian, 0, ',', '.') }} x
                    {{ $payroll->jumlah_hadir }} Hari Hadir</span>
            </td>
            <td class="amount">Rp {{ number_format($payroll->gaji_harian * $payroll->jumlah_hadir, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>
                <span class="label">Tunjangan Jabatan</span>
                <span class="sub-label">Tunjangan Tetap Bulanan</span>
            </td>
            <td class="amount">Rp {{ number_format($payroll->tunjangan_jabatan, 0, ',', '.') }}</td>
        </tr>
        @if ($payroll->thr > 0)
            <tr>
                <td>
                    <span class="label">Tunjangan Hari Raya (THR)</span>
                    <span class="sub-label">Tunjangan Khusus Hari Raya</span>
                </td>
                <td class="amount">Rp {{ number_format($payroll->thr, 0, ',', '.') }}</td>
            </tr>
        @endif
        @if ($payroll->bonus > 0)
            <tr>
                <td>
                    <span class="label">Bonus & Insentif</span>
                    <span class="sub-label">{{ $payroll->keterangan_bonus ?? 'Bonus Tambahan' }}</span>
                </td>
                <td class="amount">Rp {{ number_format($payroll->bonus, 0, ',', '.') }}</td>
            </tr>
        @endif
    </table>

    <!-- Total Netto -->
    <div class="total-section">
        <div class="total-label">Total Diterima (Netto)</div>
        <div class="total-amount">Rp {{ number_format($payroll->total_gaji, 0, ',', '.') }}</div>
        <div class="terbilang">Terbilang: # {{ \App\Services\Terbilang::make($payroll->total_gaji) }} rupiah #</div>
    </div>

    <!-- Signature -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-title">Managing Director of Finance,</div>

            @if ($mdFinance)
                <div class="qr-code">
                    <img src="data:image/svg+xml;base64, {{ base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(70)->generate($mdFinance->nip . ';' . $mdFinance->nama_lengkap . ';TTD Elektronik Slip Gaji')) }}"
                        alt="QR Code">
                </div>
                <div class="signer-name">{{ $mdFinance->nama_lengkap }}</div>
                <div class="signer-nip">NIPP. {{ $mdFinance->nip }}</div>
            @else
                <div style="height: 100px;"></div>
                <div class="signer-name">INDARTO PAMOENGKAS</div>
                <div class="signer-nip">NIPP. 654324</div>
            @endif
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        <table width="100%">
            <tr>
                <td align="left">
                    Sesuai dengan ketentuan yang berlaku, dokumen ini telah ditandatangani secara elektronik.
                </td>
                <td align="right">
                    KAI-HRIS | {{ date('d/m/Y H:i') }}
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
