<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice GSB - {{ $penawaran->no_penawaran }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.3;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 12px;
            background: white;
            color: #000;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                padding: 10px;
                max-width: 100%;
            }

            .print-button {
                display: none !important;
            }
        }

        /* Header */
        .header-top {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }

        .logo-section {
            margin-bottom: 5px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .invoice-title {
            height: 30px;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
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
            margin-left: 37%;
            display: flex;
            gap: 8px;
        }

        .company-info {
            margin-bottom: 8px;
            font-size: 10px;
        }

        .company-info p {
            margin: 1px 0;
        }

        .bill-to {
            background: #f9f9f9;
            border: 1px solid #ccc;
            padding: 6px;
            font-size: 10px;
            margin-bottom: 8px;
        }

        .bill-to-label {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .bill-to p {
            margin: 1px 0;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
            font-size: 10px;
        }

        thead {
            background: #e8e8e8;
        }

        th {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            font-weight: bold;
        }

        td {
            border: 1px solid #000;
            padding: 5px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
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

        /* Payment */
        .payment-info {
            margin: 8px 0;
            padding: 6px;
            background: #fafafa;
            border: 1px solid #ccc;
            font-size: 10px;
        }

        .payment-info p {
            margin: 2px 0;
        }

        .payment-info strong {
            font-weight: bold;
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

        /* Print Button */
        .print-button {
            text-align: right;
            margin-top: 15px;
            padding: 10px;
        }

        .print-button button {
            padding: 8px 15px;
            font-size: 12px;
            cursor: pointer;
            background: #0066cc;
            color: white;
            border: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header-top">
            <div class="invoice-title">INVOICE</div>
            <div class="logo-section">
                <!-- GSB Logo -->
                <img src="{{ asset('Picture1.png') }}" alt="GSB Logo"
                    style="height:65px; width:auto; object-fit:contain; display:block;">
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-wrapper">

            <div class="left-top">
                <p>Jl. Sendang Gede No.14, Banyumanik,</p>
                <p>Kec. Banyumanik, Kota Semarang,</p>
                <p>Jawa Tengah 50264</p>
                <p>Phone: 082-29-7777-169</p>
            </div>

            <div class="right-top">
                <p><strong>Invoice No</strong> : <strong>{{ $penawaran->no_penawaran }}</strong></p>
                <p><strong>Invoice Date</strong> : <strong>{{ $penawaran->tanggal->format('d/m/Y') }}</strong></p>
            </div>

            <div class="left-bottom">
                <p style="text-decoration: underline;"><strong>Bill To :</strong></p>
                <p>{{ $penawaran->client->nama ?? 'N/A' }}</p>
                <p>{{ $penawaran->client->alamat ?? '' }}</p>
                <p>Phone: {{ $penawaran->client->telepon ?? '' }}</p>
            </div>

            <div class="right-bottom">
                <p style="text-decoration: underline;"><strong>For :</strong></p>
                <p>No. BAPS: [BAPS NUMBER]</p>
                <p>No. Perjanjian: [AGREEMENT NUMBER]</p>
            </div>

        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr text>
                    <th style="width: 5%;text-align: center;">No</th>
                    <th style="width: 35%;text-align: center;">Deskripsi</th>
                    <th style="width: 12%;text-align: center;">Satuan</th>
                    <th style="width: 12%;text-align: center;">Jumlah</th>
                    <th style="width: 18%;text-align: center;">Harga Satuan</th>
                    <th style="width: 18%;text-align: center;">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penawaran->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->material->nama ?? 'N/A' }}</td>
                        <td class="text-center">{{ $item->material->satuan ?? 'N/A' }}</td>
                        <td class="text-center">{{ number_format($item->jumlah, 0) }}</td>
                        <td class="text-center">Rp{{ number_format($item->harga_asli, 0, ',', '.') }}</td>
                        <td class="text-center">Rp{{ number_format($item->total_biaya_asli, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada item</td>
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
                    <td style="width: 82%;text-align: center;"><strong>PPN 11%</strong></td>
                    <td style="width: 18%;text-align: center;">
                        Rp{{ number_format($penawaran->ppn ?? ($penawaran->grand_total * 0.11), 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td style="width: 82%;text-align: center;border-right: 1px solid #999;"><strong>TOTAL
                            TAGIHAN</strong></td>
                    <td style="width: 18%;text-align: center;">
                        <strong>Rp{{ number_format($penawaran->grand_total_with_ppn ?? ($penawaran->grand_total * 1.11), 0, ',', '.') }}</strong>
                    </td>
                </tr>
                <tr class="terbilang">
                    <td style="text-align: center;" colspan="2"><strong> @php
                        $finalTotal = $penawaran->grand_total_with_ppn ?? ($penawaran->grand_total * 1.11);
                        // Function to convert number to words (Indonesian)
                        $terbilang = \App\Helpers\FormatHelper::angkaKeHuruf($finalTotal);
                    @endphp
                            Terbilang: {{ $terbilang }}</strong></td>
                </tr>
            </table>
        </div>

        {{-- <!-- Terbilang (Dynamic calculation) -->
        <div class="terbilang">
            @php
                $finalTotal = $penawaran->grand_total * 1.11;
                // Function to convert number to words (Indonesian)
                $terbilang = \App\Helpers\FormatHelper::angkaKeHuruf($finalTotal);
            @endphp
            Terbilang: {{ $terbilang }}
        </div> --}}

        <!-- Payment Info -->
        <div class="payment-info">
            <p><strong>Bank BNI</strong></p>
            <p>No. Rekening: 1439152652</p>
            <p>Nama Rekening: CV Gundara Solusi Bersama</p>
        </div>

        <!-- Signature -->
        <div class="signature-section">
            <div class="signature-item">
                <div class="signature-label" style="text-decoration: underline;">Direktur</div>
                <div class="signature-name">HAYU MARYANTI</div>
            </div>
        </div>
    </div>

    <!-- Print Button -->
    <div class="print-button">
        <button onclick="window.print()">Print / Save as PDF</button>
    </div>
</body>

</html>
