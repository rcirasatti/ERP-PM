<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan - {{ $penawaran->no_penawaran }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
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
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .logo-section {
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 14px;
            font-weight: bold;
        }
        .company-tagline {
            font-size: 10px;
            color: #666;
        }
        .company-details {
            font-size: 10px;
            margin-top: 10px;
            line-height: 1.6;
        }
        .company-details p {
            margin: 2px 0;
        }
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 15px 0;
        }
        .info-group {
            font-size: 10px;
        }
        .info-group p {
            margin: 3px 0;
        }
        .info-group strong {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .do-number {
            text-align: right;
            margin: 15px 0;
            font-size: 11px;
        }
        .do-number p {
            margin: 3px 0;
        }
        .do-number strong {
            font-weight: bold;
        }
        .ship-to {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #000;
            background: #f9f9f9;
        }
        .ship-to-label {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 11px;
        }
        .ship-to p {
            margin: 2px 0;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        thead {
            background: #f0f0f0;
        }
        th {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-weight: bold;
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
        .delivery-location {
            margin: 20px 0;
            padding: 10px;
            border: 2px solid #228B22;
            background: #f0fff0;
        }
        .delivery-location p {
            margin: 3px 0;
            font-size: 10px;
        }
        .signature-section {
            margin-top: 30px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        .signature {
            text-align: center;
            font-size: 10px;
        }
        .signature p {
            margin: 5px 0;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin: 15px 0;
            height: 40px;
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
            <div class="logo-section">
                <div class="company-name">CV. Gundara Solusi Bersama</div>
                <div class="company-tagline">Discover The Difference</div>
            </div>
            <h1>DELIVERY ORDER</h1>
        </div>

        <!-- Company Details -->
        <div class="company-details">
            <p>Jl. Sendang Gede No.14, Banyumanik, Kec. Banyumanik, Kota</p>
            <p>Semarang, Jawa Tengah 50264</p>
            <p>Email: cgundarasolusibersama@gmail.com</p>
            <p>Phone: 082-29-7777-169</p>
        </div>

        <!-- DO Number and Date -->
        <div class="do-number">
            <p><strong>DO No</strong> : 04/V/GSB/2025</p>
            <p><strong>Delivery Date</strong> : 13/05/2025</p>
        </div>

        <!-- Ship To -->
        <div class="ship-to">
            <div class="ship-to-label">Ship To :</div>
            <p><strong>{{ $penawaran->client->nama ?? 'N/A' }}</strong></p>
            <p>{{ $penawaran->client->alamat ?? '' }}</p>
            <p>Contact: {{ $penawaran->client->telepon ?? '' }}</p>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penawaran->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->material->nama ?? 'N/A' }}</td>
                    <td>{{ $item->material->satuan ?? 'N/A' }}</td>
                    <td class="text-right">{{ number_format($item->jumlah, 0) }}</td>
                    <td>-</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-right">Tidak ada item</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Delivery Location -->
        <div class="delivery-location">
            <p><strong>Lokasi Pengiriman / Lokasi Pekerjaan:</strong></p>
            <p>{{ $penawaran->client->alamat ?? 'Alamat client' }}</p>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature">
                <p><strong>Tanggal Diterima :</strong></p>
                <div class="signature-line"></div>
                <p>Penerima / Penandatangan</p>
            </div>
            <div class="signature">
                <p><strong>Diterima Oleh :</strong></p>
                <div class="signature-line"></div>
                <p>Nama Jelas</p>
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
