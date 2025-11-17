<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAST - {{ $penawaran->no_penawaran }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.6;
        }
        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
            background: white;
            color: #000;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .container {
                padding: 0;
                max-width: 100%;
            }
        }
        .logo-section {
            text-align: center;
            margin-bottom: 15px;
        }
        .logo-section img {
            height: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 13px;
        }
        .subheader {
            text-align: center;
            margin-bottom: 15px;
            font-size: 10px;
        }
        .content {
            font-size: 11px;
            line-height: 1.8;
        }
        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        .section-content {
            margin: 10px 0;
            padding: 0 10px;
        }
        .section-content p {
            margin: 5px 0;
        }
        .info-row {
            display: grid;
            grid-template-columns: 180px 1fr;
            margin: 5px 0;
        }
        .info-label {
            font-weight: bold;
        }
        .info-value {
            margin-left: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th {
            border: 1px solid #000;
            padding: 8px;
            font-weight: bold;
            background: #f0f0f0;
            font-size: 10px;
        }
        td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
        .signature-section {
            margin-top: 30px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        .signature-item {
            text-align: left;
        }
        .signature-label {
            font-weight: bold;
            margin-bottom: 50px;
            font-size: 11px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-bottom: 5px;
            height: 30px;
        }
        .signature-name {
            font-size: 10px;
            margin-top: 3px;
        }
        .date-section {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
        }
        .date-section p {
            margin: 3px 0;
        }
        .print-button {
            text-align: right;
            margin-top: 20px;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo -->
        <div class="logo-section">
            <p style="font-weight: bold; color: #0066cc;">PLN</p>
            <p style="font-size: 9px;">Icon Plus</p>
        </div>

        <!-- Header -->
        <div class="header">
            BERITA ACARA SERAH TERIMA
        </div>

        <!-- Subheader -->
        <div class="subheader">
            <p>Pada Hari ini, Rabu Tanggal Lima bulan Februari Tahun Dua Ribu Dua Puluh Lima kami yang bertanda tangan dibawah ini :</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Pihak I -->
            <div class="section-title">I. Nama</div>
            <div class="section-content">
                <div class="info-row">
                    <div class="info-label">Nama</div>
                    <div class="info-value">: Radintha Anka Arsyianiya</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jabatan</div>
                    <div class="info-value">: Koordinator</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nama Mitra</div>
                    <div class="info-value">: CV Gundara Solusi Bersama</div>
                </div>
            </div>

            <!-- Pihak II -->
            <div class="section-title">II. Telah melakukan serah terima material FOC dengan rincian sebagai berikut :</div>
            <div class="section-content">
                <p style="font-weight: bold;">Nomor PA : A131401003667/TER</p>
                <p style="font-weight: bold;">Nama User : DINAS KOMUNIKASI DAN INFORMATIKA PROVINSI JAWA TENGAH(MURALHARIO KUNDURAN BLORA)</p>
            </div>

            <!-- Material Table -->
            <div class="section-title">Lampiran Material</div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Panjang Reservasi (m)</th>
                        <th>Panjang pada Hasil OTDR (m)</th>
                        <th>Sisa (m)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penawaran->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-right">{{ $item->jumlah }}</td>
                        <td class="text-right">{{ $item->jumlah }}</td>
                        <td class="text-right">0</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-right">Tidak ada item</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Footer Content -->
            <p style="margin-top: 20px;">Dengan ini dinyatakan bahwa material yang diserahkan dalam kondisi baik dan telah diterima oleh kedua belah pihak.</p>

            <!-- Signature Section -->
            <div class="signature-section">
                <div class="signature-item">
                    <div class="signature-label">Tim Gudang,</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">(...........................)</div>
                </div>
                <div class="signature-item">
                    <div class="signature-label">Tim Mitra,</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">(Radintha Anka)</div>
                </div>
            </div>

            <!-- Date Section -->
            <div class="date-section">
                <p style="margin-top: 30px;"><strong>Semarang, {{ $penawaran->tanggal->format('d F Y') }}</strong></p>
                <p style="margin-top: 10px;"><strong>CV. GUNDARA SOLUSI BERSAMA</strong></p>
                <p><strong>PT INDOWIN ENGINEERING INDONESIA</strong></p>
                <p>SBUR JAWA BAGIAN TENGAH</p>
            </div>
        </div>
    </div>

    <!-- Print Button -->
    <div class="print-button">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; cursor: pointer;">
            Print / Save as PDF
        </button>
    </div>
</body>
</html>
