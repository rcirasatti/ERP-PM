<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAS - {{ $penawaran->no_penawaran }}</title>
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
        .header {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 12px;
        }
        .content {
            font-size: 11px;
            line-height: 1.8;
        }
        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        .section-content {
            margin-left: 20px;
            margin-bottom: 10px;
        }
        .section-content p {
            margin: 5px 0;
        }
        .info-row {
            display: grid;
            grid-template-columns: 200px 1fr;
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
            margin-bottom: 40px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-bottom: 5px;
        }
        .signature-name {
            font-size: 10px;
            margin-top: 5px;
        }
        .centered-signature {
            text-align: center;
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
        <!-- Header -->
        <div class="header">
            BERITA ACARA SURVEY
        </div>

        <!-- Content -->
        <div class="content">
            <p>Pada hari ini, Kamis tanggal Enam belas bulan Oktober tahun Dua Ribu Dua Puluh Lima kami yang ditandatangani Survey atas Jaringan Telekomunikasi langganan untuk Mendukung INTERNET CORPORATE Pelanggian PT INDOWIN ENGINEERING INDONESIA (GO - IDOWIN ENGINEERING INDONESIA):</p>

            <!-- Pihak Pertama -->
            <div class="section-title">PIHAK PERTAMA</div>
            <div class="section-content">
                <div class="info-row">
                    <div class="info-label">Diwakili Oleh</div>
                    <div class="info-value">:</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jabatan</div>
                    <div class="info-value">: PTL Aktivasi / Survey</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Alamat Kantor</div>
                    <div class="info-value">: Jln Rayon Semarang Selatan, Jl Setia Budi No 96, Srondol Banyumanik, Semarang, Jawa Tengah</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nomer Telpon</div>
                    <div class="info-value">: 024-7620576</div>
                </div>
            </div>

            <!-- Pihak Kedua -->
            <div class="section-title">PIHAK KEDUA</div>
            <div class="section-content">
                <div class="info-row">
                    <div class="info-label">Diwakili Oleh</div>
                    <div class="info-value">: {{ $penawaran->client->nama ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jabatan</div>
                    <div class="info-value">: Tim Teknis</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Alamat Kantor</div>
                    <div class="info-value">: {{ $penawaran->client->alamat ?? '' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nomer Telpon</div>
                    <div class="info-value">: {{ $penawaran->client->telepon ?? '' }}</div>
                </div>
            </div>

            <!-- Hasil Survey -->
            <div class="section-title">HASIL SURVEY</div>
            <div class="section-content">
                <p>Dari hasil survey yang telah kami lakukan pada tanggal {{ $penawaran->tanggal->format('d F Y') }}, kami menemukan kondisi:</p>
                
                <table>
                    <thead>
                        <tr>
                            <th>Aspek</th>
                            <th>Kondisi/Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Lokasi</td>
                            <td>{{ $penawaran->client->alamat ?? 'Lokasi client' }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Layanan</td>
                            <td>Internet Corporate</td>
                        </tr>
                        <tr>
                            <td>Total Item Penawaran</td>
                            <td>{{ $penawaran->items->count() }} item</td>
                        </tr>
                        <tr>
                            <td>Tanggal Survey</td>
                            <td>{{ $penawaran->tanggal->format('d/m/Y') }}</td>
                        </tr>
                    </tbody>
                </table>

                <p style="margin-top: 10px;"><strong>Rekomendasi:</strong></p>
                <p>Kondisi lokasi memungkinkan untuk dilakukan installasi layanan sesuai yang ditawarkan dalam penawaran ini.</p>
            </div>

            <!-- Kesimpulan -->
            <div class="section-title">KESIMPULAN</div>
            <div class="section-content">
                <p>Berdasarkan hasil survey tersebut di atas, kami nyatakan bahwa lokasi tersebut memungkinkan untuk dilakukan pelaksanaan pekerjaan sesuai dengan penawaran yang telah diberikan.</p>
            </div>

            <!-- Signature Section -->
            <div class="signature-section">
                <div class="signature-item">
                    <div class="signature-label">Tim Gudang,</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">(................................)</div>
                </div>
                <div class="signature-item">
                    <div class="signature-label">Tim Mitra,</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">({{ $penawaran->client->nama ?? 'Client' }})</div>
                </div>
            </div>

            <p style="text-align: center; margin-top: 30px; font-weight: bold;">
                Semarang, {{ $penawaran->tanggal->format('d F Y') }}
            </p>
            <p style="text-align: center; margin-top: 5px;">CV. GUNDARA SOLUSI BERSAMA</p>
            <p style="text-align: center;">PT INDOWIN ENGINEERING INDONESIA</p>
            <p style="text-align: center;">SBUR JAWA BAGIAN TENGAH</p>
            <p style="text-align: center; margin-top: 20px; font-weight: bold;">(Karisma Kusuma Ayu)</p>
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
