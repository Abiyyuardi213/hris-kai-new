<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sertifikat Mandiri Inhealth - {{ $employee->nama_lengkap }}</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 1cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #111827;
            width: 100%;
            height: 100%;
        }
        
        /* Fixed Border System - Using points for absolute precision in DomPDF */
        .master-border-outer {
            border: 6pt double #002D5C;
            padding: 2pt;
            height: 1000px; /* Safe A4 height at 96dpi within 1cm margin */
            width: 100%;
            box-sizing: border-box;
        }
        
        .master-border-inner {
            border: 1pt solid #002D5C;
            height: 100%;
            padding: 30pt;
            box-sizing: border-box;
            position: relative;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 70pt;
            color: rgba(0, 45, 92, 0.03);
            font-weight: 900;
            z-index: -100;
            white-space: nowrap;
        }

        .header-section { margin-bottom: 20pt; text-align: center; }
        .logos { margin-bottom: 15pt; }
        .logos img { height: 35pt; vertical-align: middle; }
        .separator { 
            display: inline-block; 
            width: 1pt; 
            height: 25pt; 
            background: #cbd5e1; 
            margin: 0 15pt; 
            vertical-align: middle; 
        }

        .title-section h1 {
            font-size: 20pt;
            color: #002D5C;
            margin: 0;
            letter-spacing: 1.5pt;
            text-transform: uppercase;
            font-weight: bold;
        }
        .title-section p {
            font-size: 9pt;
            color: #64748b;
            letter-spacing: 4pt;
            margin: 4pt 0 0;
            text-transform: uppercase;
        }

        .section-label {
            font-size: 8.5pt;
            font-weight: bold;
            color: #002D5C;
            border-bottom: 1pt solid #f1f5f9;
            padding-bottom: 4pt;
            margin: 20pt 0 10pt 0;
            text-transform: uppercase;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 4pt 0;
            font-size: 9.5pt;
        }
        .label { width: 140pt; color: #4b5563; }
        .value { color: #111827; font-weight: bold; }

        .benefit-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5pt;
        }
        .benefit-table th {
            text-align: left;
            background: #f8fafc;
            padding: 8pt;
            font-size: 7.5pt;
            color: #475569;
            border-bottom: 1pt solid #e2e8f0;
            text-transform: uppercase;
        }
        .benefit-table td {
            padding: 8pt;
            font-size: 8.5pt;
            border-bottom: 1px solid #f8fafc;
        }
        .status-badge {
            text-align: right;
            font-weight: bold;
            color: #10b981;
            font-size: 7pt;
            text-transform: uppercase;
        }

        /* Footer Container - Fixed at bottom of inner border */
        .footer-container {
            position: absolute;
            bottom: 60pt;
            left: 30pt;
            right: 30pt;
        }
        
        .signature-table { width: 100%; }
        .sig-block { width: 60%; font-size: 8.5pt; }
        .qr-block { width: 40%; text-align: center; font-size: 7pt; }
        .qr-img-box {
            border: 1pt solid #e2e8f0;
            padding: 5pt;
            border-radius: 6pt;
            margin-bottom: 4pt;
            display: inline-block;
            background: #fff;
        }
        
        .legal-notice {
            margin-top: 20pt;
            text-align: center;
            font-size: 7.5pt;
            color: #94a3b8;
            line-height: 1.4;
        }

    </style>
</head>
<body>
    <div class="master-border-outer">
        <div class="master-border-inner">
            <div class="watermark">MANDIRI INHEALTH</div>

            <div class="header-section">
                <div class="logos">
                    <img src="{{ public_path('image/logo-mandiri-inhealth.png') }}">
                    <div class="separator"></div>
                    <img src="{{ public_path('image/logo-kai.png') }}">
                </div>
                <div class="title-section">
                    <h1>Sertifikat Kepesertaan</h1>
                    <p>Jaminan Kesehatan Nasional</p>
                </div>
            </div>

            <div class="section-label">I. Informasi Peserta Terjamin</div>
            <table class="data-table">
                <tr>
                    <td class="label">Nama Lengkap Pegawai</td>
                    <td class="value">: {{ $employee->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td class="label">Nomor Induk Pegawai</td>
                    <td class="value">: {{ $employee->nip }}</td>
                </tr>
                <tr>
                    <td class="label">Jabatan Struktural</td>
                    <td class="value">: {{ $employee->jabatan->name }}</td>
                </tr>
                <tr>
                    <td class="label">Nomor Peserta Ins.</td>
                    <td class="value">: {{ $insuranceData['card_number'] }}</td>
                </tr>
            </table>

            <div class="section-label">II. Ringkasan Paket & Manfaat</div>
            <table class="data-table" style="margin-bottom: 5pt;">
                <tr>
                    <td class="label">Nama Paket Layanan</td>
                    <td class="value">: {{ $insuranceData['plan_name'] }} (Premium Care)</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Efektif Berlaku</td>
                    <td class="value">: {{ \Carbon\Carbon::parse($insuranceData['effective_date'])->translatedFormat('d F Y') }}</td>
                </tr>
            </table>

            <table class="benefit-table">
                <thead>
                    <tr>
                        <th style="width: 75%;">Komponen Layanan Kesehatan</th>
                        <th style="text-align: right;">Status Proteksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Rawat Inap Kelas I (VVIP Priority)</td>
                        <td class="status-badge">FULLY COVERED</td>
                    </tr>
                    <tr>
                        <td>Hospital Income & Cash Plan Benefits</td>
                        <td class="status-badge">ACTIVE MEMBER</td>
                    </tr>
                    <tr>
                        <td>Ambulans & Emergency Management</td>
                        <td class="status-badge">ACTIVE MEMBER</td>
                    </tr>
                    <tr>
                        <td>Evakuasi & Repatriasi Medis Nasional</td>
                        <td class="status-badge">ACTIVE MEMBER</td>
                    </tr>
                </tbody>
            </table>

            <div class="footer-container">
                <table class="signature-table">
                    <tr>
                        <td class="sig-block" valign="top">
                            <p style="color: #64748b; margin-bottom: 50pt;">Ditetapkan di Jakarta, {{ now()->translatedFormat('d F Y') }}</p>
                            <p style="font-weight: bold; text-decoration: underline; margin-bottom: 2pt;">MANDIRI INHEALTH SYSTEM</p>
                            <p style="color: #64748b; font-size: 8pt;">Verified Digital Document • HRIS PT KAI Integrated</p>
                        </td>
                        <td class="qr-block" align="right" valign="top">
                            <div class="qr-img-box">
                                <img src="data:image/svg+xml;base64, {{ base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(70)->generate('VAL-'.$employee->nip.'-'.now()->timestamp)) }}">
                            </div>
                            <p style="color: #64748b; font-weight: bold; text-transform: uppercase;">Validation QR Code</p>
                        </td>
                    </tr>
                </table>

                <div class="legal-notice">
                    Sertifikat ini diterbitkan secara otomatis oleh HRIS PT Kereta Api Indonesia (Persero) bekerjasama dengan Mandiri Inhealth. Penyalahgunaan dokumen ini akan dikenakan sanksi sesuai hukum yang berlaku di Indonesia.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
