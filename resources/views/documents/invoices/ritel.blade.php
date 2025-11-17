<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Ritel - {{ $penawaran->no_penawaran }}</title>
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
            position: relative;
            /* penting untuk anchor posisi absolute */
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 20px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #000;
        }

        .header-left {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .header-right {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .header-right h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }


        .company-info {
            font-size: 10px;
            margin-bottom: 15px;
        }

        .company-info p {
            margin: 2px 0;
        }

        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-areas:
                "leftTop rightTop"
                "leftBottom rightBottom";
            gap: 10px;
            font-size: 10px;
        }

        /* Map area */
        .left-top {
            grid-area: leftTop;
        }

        .right-top {
            grid-area: rightTop;
        }

        .left-bottom {
            grid-area: leftBottom;
        }

        .right-bottom {
            grid-area: rightBottom;
        }

        .left-top p,
        .right-top p,
        .left-bottom p,
        .right-bottom p {
            margin: 2px 0;
        }


        .right-top p,
        .right-bottom p {
            display: grid;
            grid-template-columns: 70px auto;
            column-gap: 8px;
            margin: 2px 0;
            width: fit-content;
            margin-left: 40%;
        }

        .right-top span,
        .right-bottom span {
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        thead {
            background: #000;
            color: white;
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

        /* Summary */
        .summary-wrapper {
            display: flex;
            justify-content: flex-end;
            margin: 10px 0;
        }

        .summary-table {
            width: 280px;
            border-collapse: collapse;
            font-size: 10px;
        }

        .summary-table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .summary-table td:first-child {
            font-weight: bold;
            width: 55%;
            text-align: left;
        }

        .summary-table td:last-child {
            text-align: right;
            font-weight: bold;
            width: 45%;
        }

        .total-row td {
            background: #000;
            color: white;
        }

        /* Terbilang */
        .terbilang td {
            margin: 8px 0;
            padding: 6px;
            background: #f5f5f5;
            border: 1px solid #999;
            text-align: center;
        }

        .terbilang .terbilang-value {
            display: block;
            margin-top: 4px;
            font-weight: normal;
            text-transform: capitalize;
        }

        .payment-info {
            margin: 15px 0;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            font-size: 10px;
        }

        .payment-info p {
            margin: 2px 0;
        }

        /* Signature */
        .signature-section {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            /* tengah */
        }

        .signature-item {
            text-align: center;
            width: 140px;
        }

        .signature-label {
            font-weight: bold;
            margin-bottom: 70px;
            font-size: 10px;
        }

        .signature-name {
            font-size: 10px;
            font-weight: bold;
            margin-top: 2px;
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
            <div class="header-left">
                <img src={{ asset('Picture2.png') }} alt="Logo" class="logo"
                    style="height:60px; width:auto; object-fit:contain; display:block;">
            </div>
            <div class="header-right" style="text-align: center;">
                <h1>INVOICE</h1>
            </div>
        </div>
        <div class="content-wrapper">

            <div class="left-top">
                <p><strong>Perum Polri Durenan Indah Sadewa 1</strong></p>
                <p>Kl. Mangunjharjo, Kec. Tembalang, Semarang</p>
                <p>anugerahsejaiterawibowo@gmail.com</p>
                <p>0851-5650-5618 | 0895-3796-5370</p>
            </div>

            <div class="right-top">
                <p><strong>Invoice No</strong> <span>: {{ $penawaran->no_penawaran }}</span></p>
                <p><strong>Invoice Date</strong> <span>: {{ $penawaran->tanggal->format('d/m/Y') }}</span></p>
            </div>
            <div class="left-bottom">
                <p style="text-decoration: underline;"><strong>Bill To :</strong></p>
                <p>{{ $penawaran->client->nama ?? 'N/A' }}</p>
                <p>{{ $penawaran->client->alamat ?? '' }}</p>
                <p>Phone: {{ $penawaran->client->telepon ?? '' }}</p>
            </div>

            <div class="right-bottom">
                <p style="text-decoration: underline;"><strong>For :</strong></p>
                <p>No. BAPS <span>: [BAPS NUMBER]</span></p>
                <p>No. Perjanjian <span>: [AGREEMENT NUMBER]</span></p>
                <p>No. Faktur <span>: [FAKTUR NUMBER]</span></p>
            </div>

        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="text-align: center;">No</th>
                    <th style="text-align: center;">Deskripsi</th>
                    <th style="text-align: center;">Satuan</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: center;">Harga Satuan</th>
                    <th style="text-align: center;">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penawaran->items as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="text-align: center;">{{ $item->material->nama ?? 'N/A' }}</td>
                        <td style="text-align: center;">{{ $item->material->satuan ?? 'N/A' }}</td>
                        <td style="text-align: center;">{{ number_format($item->jumlah, 0) }}</td>
                        <td style="text-align: center;">Rp{{ number_format($item->harga_asli, 0, ',', '.') }}</td>
                        <td style="text-align: center;">Rp{{ number_format($item->total_biaya_asli, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-right">Tidak ada item</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary-wrapper">
            <table>
                <tr>
                    <td style="width: 82%;text-align: center;"><strong>SUBTOTAL</strong></td>
                    <td style="width: 18%;text-align: center;">
                        Rp{{ number_format($penawaran->grand_total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="width: 82%;text-align: center;"><strong>PPN 12%</strong></td>
                    <td style="width: 18%;text-align: center;">
                        Rp{{ number_format($penawaran->grand_total * 0.12, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td style="width: 82%;text-align: center;border-right: 1px solid #999;"><strong>TOTAL
                            TAGIHAN</strong></td>
                    <td style="width: 18%;text-align: center;">
                        <strong>Rp{{ number_format($penawaran->grand_total * 1.12, 0, ',', '.') }}</strong>
                    </td>
                </tr>
                <tr class="terbilang">
                    <td style="text-align: center;" colspan="2"><strong> @php
                        $finalTotal = $penawaran->grand_total * 1.12;
                        // Function to convert number to words (Indonesian)
                        $terbilang = \App\Helpers\FormatHelper::angkaKeHuruf($finalTotal);
                    @endphp
                            Terbilang: {{ $terbilang }}</strong></td>
                </tr>
            </table>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <p><strong>Pembayaran mohon di transfer ke rekening :</strong></p>
            <p>Bank BCA</p>
            <p>No. Rekening: 8915074801</p>
            <p>Nama Rekening: Lisa Theresia</p>
        </div>
        <!-- Signature -->
        <div class="signature-section">
            <div class="signature-item">
                <div class="signature-label" style="text-decoration: underline;">Direktur</div>
                <div class="signature-name">LISA THERESIA</div>
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
