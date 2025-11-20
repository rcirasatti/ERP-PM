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
            margin-bottom: 5px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-section img {
            height: 65px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 16px;
        }

        .subheader {
            text-align: left;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .subheader-line {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 4px;
        }

        .subheader-text {
            white-space: nowrap;
            margin-right: 6px;
        }

        .subheader-blank {
            flex: 1;
            border-bottom: 1px solid #000;
            line-height: 1.5;
        }


        .content {
            font-size: 11px;
            line-height: 1.8;
        }

        .section-title {
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
            grid-template-columns: 25px 150px 10px 1fr;
            margin: 3px 0;
        }

        
        .info-colon {
            text-align: left;
        }

        .info-value {}


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
            text-align: center;
        }

        .signature-label {
            font-weight: bold;
            margin-bottom: 90px;
            font-size: 11px;
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
            <div class="logo-section">
                <img src="{{ asset('Picture1.png') }}" alt="GSB Logo">
            </div>
        </div><br>

        <!-- Header -->
        <div class="header">
            BERITA ACARA SERAH TERIMA
        </div><br>

        <!-- Subheader -->
        <div class="subheader">
            <div class="subheader-line">
                <span class="subheader-text">Pada Hari ini</span>
                <span class="subheader-blank"></span>
            </div>
            <p>kami yang bertanda tangan dibawah ini :</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Pihak I -->
            <div class="section-content">
                <div class="info-row">
                    <div class="info-no">I.</div>
                    <div class="info-label">Nama</div>
                    <div class="info-colon">:</div>
                    <div class="info-value"></div>
                    {{-- atau {{ $penawaran->nama_pic ?? '' }} --}}
                </div>
                <div class="info-row">
                    <div class="info-no"></div>
                    <div class="info-label">Jabatan</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">Koordinator</div>
                </div>
                <div class="info-row">
                    <div class="info-no"></div>
                    <div class="info-label">Nama Mitra</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">CV Gundara Solusi Bersama</div>
                </div>
            </div>


            <!-- Pihak II -->
            <div class="section-title">telah melakukan serah terima material FOC dengan rincian sebagai berikut :</div>
            <div class="section-content">
                <div class="info-row">
                    <div class="info-no"></div>
                    <div class="info-label">1. Nomor PA</div>
                    <div class="info-colon">:</div>
                    <div class="info-value"></div>
                    {{-- atau {{ $penawaran->nomor_pa ?? '' }} --}}
                </div>
                <div class="info-row">
                    <div class="info-no"></div>
                    <div class="info-label">2. Nama User</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">
                        {{ $penawaran->client->nama ?? 'N/A' }}
                    </div>
                </div>
            </div><br>

            <!-- Material Table -->
            <div class="section-title" style="text-align: center;"><strong>Lampiran Material</strong></div>
               <table style="text-align: center">
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
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-center">0</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-right">Tidak ada item</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- Signature Section -->
            <div class="signature-section">
                <div class="signature-item">
                    <div class="signature-label">Tim Gudang,</div>
                    <div class="signature-name">(................................................................)</div>
                </div>
                <div class="signature-item">
                    <div class="signature-label">Tim Mitra,</div>
                    <div class="signature-name">(................................................................)</div>
                </div>
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
