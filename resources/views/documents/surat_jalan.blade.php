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
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }

        .page {
            max-width: 210mm;
            margin: 0 auto;
            padding: 0;
        }

        .page-frame {
            border: 1px solid #000;
            padding: 20px 15px 25px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .page {
                max-width: 100%;
            }

            .page-frame {
                padding: 15px;
            }
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }

        .do-title {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 5px;
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

        /* TOP INFO */
        .top-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .company-details {
            font-size: 10px;
            line-height: 1.6;
        }

        .company-details p {
            margin: 2px 0;
        }

       .do-number {
    font-size: 10px;
    text-align: left;
}

.do-number p {
    margin: 2px 0;
}

.do-number .label {
    display: inline-block;
    width: 90px;   
    font-weight: bold;
}


        /* SHIP TO */
        .ship-to-wrapper {
            margin-bottom: 15px;
            font-size: 10px;
        }

        .ship-to-wrapper p {
            margin: 2px 0;
        }

        .ship-label {
            margin-bottom: 5px;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 20px;
        }

        thead {
            background: #f0f0f0;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 10px;
        }

        th {
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* SIGNATURE */
        .signature-row {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }

        .signature-block {
            width: 45%;
            text-align: center;
        }

        .signature-label {
            margin-bottom: 65px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 0 auto 5px;
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
    <div class="page">
        <div class="page-frame">
            <!-- HEADER -->
            <div class="header">
                <div class="do-title">DELIVERY ORDER</div>
                <div class="logo-section">
                    <img src="{{ asset('Picture1.png') }}" alt="GSB Logo">
                </div>
            </div>

            <!-- COMPANY & DO INFO -->
            <div class="top-info">
                <div class="company-details">
                    <p>Jl. Sendang Gede No.14, Banyumanik, Kec. Banyumanik, Kota</p>
                    <p>Semarang, Jawa Tengah 50264</p>
                    <p>Email : cgundarasolusibersama@gmail.com</p>
                    <p>Phone : 082-29-7777-169</p>
                </div>
                <div class="do-number">
                    <p>
                        <span class="label">DO No</span> :
                        <strong>{{ $penawaran->no_penawaran ?? '-' }}</strong>
                    </p>
                    <p>
                        <span class="label">Delivery Date</span> :
                        <strong>{{ $penawaran->tgl_kirim ?? '-' }}</strong>
                    </p>
                </div>

            </div>

            <!-- SHIP TO -->
            <div class="ship-to-wrapper">
                <p class="ship-label"><strong><u>Ship To :</u></strong></p>
                <p><strong>{{ $penawaran->client->nama ?? 'N/A' }}</strong></p>
                <p>{{ $penawaran->client->alamat ?? '' }}</p>
                <p>{{ $penawaran->client->telepon ?? '' }}</p>
            </div>

            <!-- ITEMS TABLE -->
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama Barang</th>
                        <th style="width: 12%;">Satuan</th>
                        <th style="width: 10%;">Jumlah</th>
                        <th style="width: 10%;">Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penawaran->items as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $item->material->nama ?? 'N/A' }}</td>
                            <td class="text-center">{{ $item->material->satuan ?? 'Unit' }}</td>
                            <td class="text-center">{{ number_format($item->jumlah, 0) }}</td>
                            <td class="text-center">-</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada item</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- SIGNATURE -->
            <div class="signature-row">
                <div class="signature-block">
                    <div class="signature-label" style="text-decoration: underline;"><strong>Tanggal Diterima :</strong>
                    </div>
                    <div class="signature-line"></div>
                </div>
                <div class="signature-block">
                    <div class="signature-label" style="text-decoration: underline;"><strong>Diterima Oleh :</strong>
                    </div>
                    <div class="signature-line"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="print-button">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; cursor: pointer;">
            Print / Save as PDF
        </button>
    </div>
</body>

</html>
