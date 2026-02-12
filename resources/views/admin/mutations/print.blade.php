<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Dinas Mutasi - {{ $mutation->employee->nama_lengkap }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm 2cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
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
            font-weight: bold;
            color: #002060;
            text-transform: uppercase;
            margin: 0;
            line-height: 1.2;
        }

        .header-subtitle {
            font-size: 13pt;
            font-weight: bold;
            color: #002060;
            margin: 0;
            line-height: 1.2;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .meta-info {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
            font-size: 10pt;
        }

        .meta-info td {
            vertical-align: top;
            padding: 1px 0;
        }

        .yth-section {
            margin-bottom: 15px;
        }

        .content-body {
            text-align: justify;
            margin-bottom: 15px;
        }

        .content-body p {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .disclaimer {
            font-size: 9pt;
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
            text-align: center;
        }

        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 280px;
            text-align: center;
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
            <div class="header-title">NOTA DINAS MUTASI</div>
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
                        <td>{{ $sequence }}/SK/{{ $signerCode }}/{{ $officeCode }}/{{ $monthRoman }}/{{ date('Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td>Sifat</td>
                        <td>:</td>
                        <td>Terbatas</td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td>1 (satu) File</td>
                    </tr>
                    <tr>
                        <td>Perihal</td>
                        <td>:</td>
                        <td><strong>Keputusan Mutasi Pegawai</strong></td>
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
        Kepala Operasional<br>
        {{ $mutation->toOffice->office_name ?? 'Kantor Tujuan' }}<br>
        Di Tempat
    </div>

    <!-- Content -->
    <div class="content-body">
        <div class="disclaimer">
            <strong>DISCLAIMER !!!</strong><br>
            Dokumen ini bersifat SANGAT TERBATAS dan RAHASIA. Penyalahgunaan terkait penyebaran dan/atau penggunaan
            dokumen (termasuk isi dan semua isi surat yang tertuang dalam dokumen ini) tanpa seizin pemilik dokumen
            dan/atau penerima surat yang tertuang di nota dinas ini, akan dikenakan sanksi sesuai ketentuan yang
            berlaku di perusahaan.
        </div>

        <p>1. Dalam rangka pemenuhan pekerja di lingkungan kerja
            {{ $mutation->toOffice->office_name ?? 'Kantor Tujuan' }}
            {{ $mutation->toDivision ? 'Unit ' . $mutation->toDivision->name : '' }},
            maka akan segera dilaksanakan tindakan prosedural yang bersifat sementara dan telah di setujui
            secara elektronik oleh Direktur Utama PT. Kereta Api Indonesia (Persero). Maka dengan ini kami
            memberikan perintah kepada salah satu pegawai PT. Kereta Api Indonesia (Persero) yang berasal
            dari {{ $mutation->fromOffice->office_name ?? 'Kantor Asal' }} atas nama:</p>

        <!-- Employee Data Table -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>NAMA PEGAWAI</th>
                    <th>NIPP</th>
                    <th>ASAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $mutation->employee->nama_lengkap }}</td>
                    <td>{{ $mutation->employee->nip }}</td>
                    <td>{{ $mutation->fromOffice->office_name ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <p>2. Agar segera menempati posisi dan jabatan baru di
            {{ $mutation->toOffice->office_name ?? 'Kantor Tujuan' }}
            sebagai <strong>{{ $mutation->toPosition->name ?? 'Jabatan Baru' }}</strong>.</p>

        <p>3. Kepada SM/Manager SDM dan Umum Daop/Divre/Balai Yasa agar menginformasikan kepada
            Anak Perusahaan/Mitra Perusahaan atau kepada tenaga alih daya yang bekerja di Lingkungan PT
            Kereta Api Indonesia (Persero).</p>

        <p>4. Demikian disampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih.</p>
    </div>

    <!-- Signature -->
    <div class="signature-section">
        <div class="signature-box" style="text-align: center; width: 320px;">
            <p style="margin-bottom: 2px; font-weight: bold;">
                @if ($officeCode === 'KP' || $officeCode === 'DZ')
                    Corporate Deputy Director of Personnel Care, Control and Development,
                @elseif(substr($officeCode, 0, 2) === 'DV')
                    Vice President {{ $officeName ?? 'Divre' }},
                @else
                    Vice President {{ $officeName ?? 'Daerah Operasi' }},
                @endif
            </p>

            @if (isset($vp) && $vp)
                <div style="display: flex; justify-content: center; margin: 15px 0; position: relative;">
                    {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate(
                        $vp->nip . ';' . $vp->nama_lengkap . ';TTD Elektronik',
                    ) !!}
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        <img src="{{ asset('image/logo-kai.png') }}"
                            style="width: 20px; background: white; padding: 2px;">
                    </div>
                </div>

                <div style="font-weight: bold; text-decoration: underline;">
                    {{ $vp->nama_lengkap }}
                </div>
                <p style="margin-top: 2px; font-size: 10pt;">NIPP {{ $vp->nip }}</p>
            @else
                <div style="height: 100px;"></div>

                <div style="font-weight: bold; text-decoration: underline;">
                    ( ..................................... )
                </div>
                <p style="margin-top: 5px; font-size: 10pt;">NIPP ...........................</p>
            @endif
        </div>
    </div>

    <div class="page-break"></div>

    <div class="attachment-content" style="font-size: 10pt;">
        <p>Penerima:</p>
        <ol>
            <li>Executive Vice President Daerah Operasi 1 Jakarta | SURYAWAN PUTRA HIA | 40985</li>
            <li>Executive Vice President Daerah Operasi 2 Bandung | JOKO WIDAGDO | 46872</li>
            <li>Executive Vice President Daerah Operasi 4 Semarang | WISNU PRAMUDYO | 44342</li>
            <li>Executive Vice President Daerah Operasi 6 Yogyakarta | IWAN EKA PUTRA | 46930</li>
            <li>Executive Vice President Daerah Operasi 8 Surabaya | HERI SISWANTO | 40866</li>
            <li>Executive Vice President UPT Balai Yasa Manggarai | IRWANSYAH | 40971</li>
            <li>Executive Vice President UPT Balai Yasa Yogyakarta | EKO WINDU WIDIO PURNOMO | 46914</li>
            <li>Executive Vice President UPT Balai Yasa Lahat | DEDDY HENDRADY | 46909</li>
            <li>Kepala Divisi Regional III Palembang | JUNAIDI NASUTION | 46870</li>
            <li>Kepala Divisi Regional IV Tanjungkarang | MUH. SAIFUL ALAM | 46895</li>
            <li>Vice President Daerah Operasi 3 Cirebon | TAKDIR SANTOSO | 46892</li>
            <li>Vice President Daerah Operasi 5 Purwokerto | DANIEL JOHANNES HUTABARAT | 40994</li>
            <li>Vice President Daerah Operasi 7 Madiun | HENDRA WAHYONO | 41124</li>
            <li>Vice President Daerah Operasi 9 Jember | BROER RIZAL | 42353</li>
            <li>Vice President Divisi Regional I Sumatera Utara | YUSKAL SETIAWAN | 42350</li>
            <li>Vice President Divisi Regional II Sumatera Barat | MOHAMAD ARIE FATHURROCHMAN | 40975</li>
            <li>General Manager UPT Balai Yasa Surabaya Gubeng | DOMINICUS AGUNG WAWAN PURNAWAN | 40962</li>
            <li>General Manager UPT Balai Yasa Tegal | AGUS NADI | 40963</li>
            <li>PLT General Manager UPT Balai Yasa Pulubrayan | DARWIN NAPITUPULU | 40954</li>
        </ol>

        <p>Tembusan Internal:</p>
        <ol>
            <li>Senior Manager Sumber Daya Manusia dan Umum RONY BIMA YUDIANTO</li>
            <li>Manager Sumber Daya Manusia Dan Umum MAYA</li>
            <li>Manager Sumber Daya Manusia Dan Umum IWAN DUDUNG APRIANTONI</li>
            <li>Manager Sumber Daya Manusia dan Umum AGUS JUNAEDI</li>
            <li>Manager Sumber Daya Manusia dan Umum FEBRIAN DWI PRASOJO</li>
            <li>Manager Sumber Daya Manusia Dan Umum DARMUJI</li>
            <li>Manager Sumber Daya Manusia dan Umum TIVEN RONIKKO</li>
            <li>Manager Sumber Daya Manusia Dan Umum WAWIK SUHARJONO</li>
            <li>Manager Sumber Daya Manusia dan Umum MOCH ARIF HARI MARWANTO</li>
            <li>Manager Sumber Daya Manusia dan Umum AHMADI FAISOL</li>
            <li>Manager Keuangan dan Sumber Daya Manusia ADRIYAN YUDISTIARA</li>
            <li>Kepala Bagian Sumber Daya Manusia dan Umum IRVAN BUDI IRAWAN</li>
            <li>Kepala Bagian Sumber Daya Manusia dan Umum ANDRITIYO NUGROHO</li>
            <li>Manager Keuangan, SDM dan Teknologi Informasi FRI ANISTUTI</li>
            <li>Manager Keuangan, SDM dan Teknologi Informasi SUPRIYANTO</li>
            <li>Manager Keuangan, SDM dan Teknologi Informasi NUR ELLY</li>
            <li>Manager Keuangan, SDM dan Teknologi Informasi ISMAIL FIKRI</li>
            <li>Manager Keuangan, SDM dan Teknologi Informasi ANARITA</li>
            <li>Manager Keuangan, Sumber Daya Manusia dan Teknologi Informasi DIKI CHARMEIN</li>
            <li>Junior Manager Sumber Daya Manusia AGANIA AGROVIGNASINENSIA MUSTIKA AYU</li>
            <li>Junior Manager Sumber Daya Manusia SHANTY MARIA IKAWATI</li>
            <li>Junior Manager Sumber Daya Manusia AGUS WIDODO</li>
            <li>Junior Manager Sumber Daya Manusia RANI MARTINI</li>
            <li>Assistant Manager Sumber Daya Manusia ANDIKA KHOLIFAH GILAR PRATIWI</li>
            <li>PLT Assistant Manager Sumber Daya Manusia ANDIKA GAUTAMA</li>
            <li>Assistant Manager Sumber Daya Manusia IQBAL AHMAD DHUHA</li>
            <li>Assistant Manager Sumber Daya Manusia EMI YATUN</li>
            <li>Assistant Manager Sumber Daya Manusia SOFWAN FAHRI</li>
            <li>Assistant Manager Sumber Daya Manusia NATALIA PANDIANGAN</li>
            <li>Assistant Manager Sumber Daya Manusia, Kerumahtanggaan Dan Protokoler, dan Dokumen REZKI SATRIA</li>
            <li>Kepala Unit Sumber Daya Manusia ALGA WINATHA PUTRA SHAZALI</li>
            <li>Kepala Unit Sumber Daya Manusia FEBRIADI</li>
            <li>Assistant Manager Sumber Daya Manusia, Kerumahtanggaan dan Protokoler BUDI PRASETYONO</li>
            <li>Assistant Manager Sumber Daya Manusia, Kerumahtanggaan dan Protokoler ICUK SUHARNO</li>
            <li>Assistant Manager Sumber Daya Manusia, Kerumahtanggaan dan Protokoler EKO PURWADI</li>
            <li>Assistant Manager Sumber Daya Manusia, Kerumahtanggaan dan Protokoler FURQON PRATIKNO WIBOWO</li>
            <li>Assistant Manager Sumber Daya Manusia, Kerumahtanggaan dan Protokoler JAZIROH</li>
            <li>Assistant Manager Sumber Daya Manusia, Kerumahtanggaan dan Protokoler YAN FIRMANSYAH</li>
        </ol>

        <p>Lampiran :</p>
        <ol style="list-style-type: decimal;">
            <li>Form Surat Rekomendasi dari Kepala Bagian SDM Anak Perusahaan_Mitra Perusahaan + Contoh Pengisian
                (Send).pdf</li>
        </ol>
    </div>

    <div class="footer-note"
        style="margin-top: 50px; font-size: 8pt; color: #555; border-top: 1px solid #ccc; padding-top: 5px;">
        <div style="float: left; width: 80%;">
            Sesuai dengan ketentuan peraturan perundang-undangan yang berlaku, surat ini telah ditandatangani secara
            elektronik sehingga tidak diperlukan tanda tangan dan stempel basah.
        </div>
        <div style="float: right; text-align: right;">
            www.kai.id &nbsp;&nbsp;&nbsp; Hlm. 2 | 2
        </div>
        <div style="clear: both;"></div>
    </div>

</body>

</html>
