<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Slip Project - {{ $projectPayroll->pegawai->nama_lengkap }}</title>
    <style>
        @page {
            size: A5 portrait;
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
            margin-bottom: 15px;
        }

        .header-title {
            font-size: 13pt;
            font-weight: bold;
            color: #002060;
            text-transform: uppercase;
            margin: 0;
        }

        .header-subtitle {
            font-size: 9pt;
            font-weight: bold;
            color: #002060;
            margin: 0;
        }

        .logo {
            width: 60px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .info-label {
            width: 90px;
            color: #666;
            font-size: 8pt;
            text-transform: uppercase;
        }

        .info-value {
            font-weight: bold;
            color: #000;
        }

        .section-title {
            font-size: 10pt;
            font-weight: bold;
            color: #002060;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .project-section {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .project-title-label {
            font-size: 7pt;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .project-name {
            font-size: 11pt;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .project-description {
            font-size: 8.5pt;
            color: #475569;
            line-height: 1.4;
            border-top: 1px dashed #cbd5e1;
            padding-top: 8px;
            margin-top: 8px;
        }

        .total-amount-box {
            text-align: right;
            padding-top: 10px;
        }

        .total-label {
            font-size: 8pt;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }

        .total-amount {
            font-size: 18pt;
            font-weight: 900;
            color: #059669;
            margin: 5px 0;
        }

        .terbilang {
            font-style: italic;
            font-size: 8pt;
            color: #64748b;
            margin-top: 5px;
        }

        .signature-section {
            margin-top: 15px;
            width: 100%;
        }

        .signature-box {
            width: 180px;
            float: right;
            text-align: center;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 4px;
            font-size: 8pt;
        }

        .qr-code {
            margin: 4px 0;
        }

        .signer-name {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 1px;
            font-size: 9pt;
        }

        .signer-nip {
            font-size: 7.5pt;
            color: #666;
        }

        .footer {
            position: fixed;
            bottom: 0px;
            width: 100%;
            font-size: 7pt;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 75%;">
                <div class="header-title">SLIP PEMBAYARAN PROJECT</div>
                <div class="header-subtitle">PT KERETA API INDONESIA (Persero)</div>
            </td>
            <td style="width: 25%; text-align: right;">
                <img src="{{ public_path('image/logo-kai.png') }}" alt="KAI Logo" class="logo">
            </td>
        </tr>
    </table>

    <!-- Employee Info -->
    <table class="info-table">
        <tr>
            <td>
                <table>
                    <tr>
                        <td class="info-label">Nama Pegawai</td>
                        <td style="width: 8px;">:</td>
                        <td class="info-value">{{ $projectPayroll->pegawai->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">NIP / NIPP</td>
                        <td style="width: 8px;">:</td>
                        <td class="info-value">{{ $projectPayroll->pegawai->nip }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Unit Kerja</td>
                        <td style="width: 8px;">:</td>
                        <td class="info-value">{{ $projectPayroll->pegawai->divisi->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Referensi</td>
                        <td style="width: 8px;">:</td>
                        <td class="info-value">#PROJ-{{ strtoupper(substr($projectPayroll->id, 0, 8)) }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 40%;">
                <table style="width: 100%;">
                    <tr>
                        <td class="info-label">Periode</td>
                        <td style="width: 8px;">:</td>
                        <td class="info-value">
                            {{ \Carbon\Carbon::create()->month($projectPayroll->month)->translatedFormat('F') }}
                            {{ $projectPayroll->year }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Status</td>
                        <td style="width: 8px;">:</td>
                        <td class="info-value">
                            <span class="status-badge" style="background: {{ $projectPayroll->status === 'paid' ? '#ecfdf5' : '#fffbeb' }}; color: {{ $projectPayroll->status === 'paid' ? '#059669' : '#d97706' }};">
                                {{ strtoupper($projectPayroll->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="section-title">Informasi Pekerjaan & Pembayaran</div>

    <!-- Project Section -->
    <div class="project-section">
        <div class="project-title-label">Nama Project / Tugas Khusus</div>
        <div class="project-name">{{ $projectPayroll->project_name }}</div>
        
        <div class="project-description">
            <div class="project-title-label">Catatan Kinerja</div>
            <div style="font-size: 8.5pt; color: #475569;">
                {{ $projectPayroll->keterangan ?? 'Pembayaran upah tambahan berdasarkan pengerjaan project sesuai kebijakan manajemen PT KAI.' }}
            </div>
        </div>

        <div class="total-amount-box">
            <div class="total-label">Gaji Project Diterima (Netto)</div>
            <div class="total-amount">Rp {{ number_format($projectPayroll->total_pay, 0, ',', '.') }}</div>
            <div class="terbilang"># {{ \App\Services\Terbilang::make($projectPayroll->total_pay) }} rupiah #</div>
        </div>
    </div>

    <!-- Signature -->
    <div class="signature-section">
        <div style="float: left; margin-top: 40px;">
            <p style="font-size: 7pt; color: #94a3b8; margin: 0;">Dokumen HRIS KAI - Slip Gaji Elektronik.</p>
            <p style="font-size: 7pt; color: #94a3b8; margin: 2px 0;">ID: {{ $projectPayroll->id }}</p>
        </div>
        <div class="signature-box">
            <div class="signature-title">Managing Director of Finance,</div>
            <div class="qr-code">
                <img src="data:image/svg+xml;base64, {{ base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(55)->generate($mdFinance->nip . ';' . $mdFinance->nama_lengkap . ';TTD Elektronik Slip Project')) }}" alt="QR Code">
            </div>
            <div class="signer-name">{{ $mdFinance->nama_lengkap }}</div>
            <div class="signer-nip">NIPP. {{ $mdFinance->nip }}</div>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d/m/Y H:i:s') }} oleh Sistem Otomatis HRIS KAI.
    </div>
</body>
</html>
