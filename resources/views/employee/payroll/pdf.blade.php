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
            margin: 0.5cm 0.8cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            line-height: 1.3;
            color: #333;
            padding: 0;
            margin: 0;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #002060;
            padding-bottom: 5px;
            margin-bottom: 10px;
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
            width: 50px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 8px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .info-label {
            width: 90px;
            color: #666;
            font-size: 7.5pt;
            text-transform: uppercase;
        }

        .info-value {
            font-weight: bold;
            color: #000;
            font-size: 8.5pt;
        }

        .section-title {
            font-size: 9.5pt;
            font-weight: bold;
            color: #002060;
            border-bottom: 1px solid #eee;
            padding-bottom: 4px;
            margin-bottom: 8px;
            margin-top: 5px;
            text-transform: uppercase;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .data-table td {
            padding: 3px 8px;
            border-bottom: 1px solid #f9f9f9;
        }

        .data-table .label {
            font-weight: bold;
            color: #333;
            font-size: 8pt;
        }

        .data-table .sub-label {
            font-size: 7pt;
            color: #888;
            display: block;
        }

        .data-table .amount {
            text-align: right;
            font-weight: bold;
            font-size: 8.5pt;
            font-family: 'Courier New', Courier, monospace;
        }

        .total-section {
            background-color: #f8fafc;
            padding: 6px 10px;
            border-radius: 8px;
            margin-top: 5px;
        }

        .total-label {
            font-size: 8pt;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }

        .total-amount {
            font-size: 12.5pt;
            font-weight: 800;
            color: #0f172a;
            margin: 1px 0;
        }

        .terbilang {
            font-style: italic;
            font-size: 8pt;
            color: #64748b;
        }

        .signature-section {
            margin-top: 5px;
            width: 100%;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 180px;
            float: right;
            text-align: center;
            page-break-inside: avoid;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 10pt;
        }

        .qr-code {
            margin: 5px 0;
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
                <span class="label">Upah Pokok</span>
                <span class="sub-label">Upah Bulanan Tetap</span>
            </td>
            <td class="amount">Rp {{ number_format($payroll->gaji_pokok, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>
                <span class="label">Tunjangan Jabatan</span>
                <span class="sub-label">Tunjangan Tetap Bulanan</span>
            </td>
            <td class="amount">Rp {{ number_format($payroll->tunjangan_jabatan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>
                <span class="label">Tunjangan Perumahan</span>
                <span class="sub-label">Fasilitas Tempat Tinggal</span>
            </td>
            <td class="amount">Rp {{ number_format($payroll->tunjangan_perumahan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>
                <span class="label">Tunjangan Admin Bank</span>
                <span class="sub-label">Administrasi Payroll</span>
            </td>
            <td class="amount">Rp {{ number_format($payroll->tunjangan_admin_bank, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>
                <span class="label">Tunjangan Iuran JPK</span>
                <span class="sub-label">BPJS Kesehatan (4%)</span>
            </td>
            <td class="amount">Rp {{ number_format($payroll->tunjangan_jpk, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>
                <span class="label">Tunjangan Pajak</span>
                <span class="sub-label">PPh 21 Ditanggung Perusahaan</span>
            </td>
            <td class="amount">Rp {{ number_format($payroll->tunjangan_pajak, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>
                <span class="label">ER Jamsostek (JKK, JHT, JKM)</span>
                <span class="sub-label">0.24% JKK, 3.7% JHT, 0.3% JKM</span>
            </td>
            <td class="amount">Rp {{ number_format($payroll->er_jamsostek_jkk + $payroll->er_jamsostek_jht + $payroll->er_jamsostek_jkm, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>
                <span class="label">Iuran Pensiun (JPK Pensiun & JP BPJS)</span>
                <span class="sub-label">2% JPK Pensiun, 2% JP BPJS</span>
            </td>
            <td class="amount">Rp {{ number_format($payroll->tunjangan_jpk_pensiun + $payroll->tunjangan_jp_bpjs, 0, ',', '.') }}</td>
        </tr>
        @if ($payroll->thr > 0)
            <tr>
                <td>
                    <span class="label">Tunjangan Hari Raya (THR)</span>
                    <span class="sub-label">Tunjangan Tahunan</span>
                </td>
                <td class="amount">Rp {{ number_format($payroll->thr, 0, ',', '.') }}</td>
            </tr>
        @endif
        @if ($payroll->bonus > 0)
            <tr>
                <td>
                    <span class="label">Bonus & Insentif</span>
                    <span class="sub-label">{{ $payroll->keterangan_bonus ?? 'Insentif Khusus' }}</span>
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
                    <img src="data:image/svg+xml;base64, {{ base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(55)->generate($mdFinance->nip . ';' . $mdFinance->nama_lengkap . ';TTD Elektronik Slip Gaji')) }}"
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
