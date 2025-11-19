<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Corporate - {{ $penawaran->no_penawaran }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        .page {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px 15px;
        }

        .invoice-frame {
            border: 1px solid #000;
            padding: 15px 20px 25px;
        }

        .header {
            text-align: center;
            font-weight: bold;
            padding: 14px 8px;
            margin-bottom: 10px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            border: 1px solid #000;
            padding: 6px;
            margin-bottom: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .info-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            font-size: 10px;
        }

        .info-label {
            width: 75px;
            background: #f0f0f0;
            font-weight: bold;
        }

        /* ITEM TABLE */
        .item-table th,
        .item-table td {
            border: 1px solid #000;
            font-size: 10px;
            padding: 4px 6px;
        }

        .item-table th {
            background: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }

        .item-col-item {
            width: 18%;
        }

        .item-col-uraian {
            width: 60%;
        }

        .item-col-jumlah {
            width: 22%;
        }

        /* Lebar kolom tabel item */
        .item-table col.col-item {
            width: 15%;
        }

        /* kolom "Item" */
        .item-table col.col-uraian-left {
            width: 20%;
        }

        /* Uraian kiri (kecil) */
        .item-table col.col-uraian-right {
            width: 45%;
        }

        /* Uraian kanan (besar) */
        .item-table col.col-jumlah {
            width: 20%;
        }

        /* kolom "Jumlah" */

        .right {
            text-align: right;
        }

        /* BANK & TTD */
        .bottom-section {
            margin-top: 18px;
        }

        .bottom-grid {
            display: flex;
            justify-content: space-between;
            margin-top: 18px;
            align-items: center;
        }

        .bank-table {
            border-collapse: collapse;
            font-size: 10px;
            width: auto;
            line-height: 1.5;
        }

        .bank-table td {
            padding: 0 4px;
        }

        .bank-label {
            white-space: nowrap;
            padding-right: 4px;
        }

        .bank-colon {
            width: 8px;
            text-align: center;
        }

        .bank-value {
            white-space: nowrap;
            /* biar nama panjang nggak turun baris */
        }

        .ttd-box,
        .ttd-2-box,
        .ttd-name {
            border: 1px solid #000;
            padding: 8px;
            font-size: 10px;
            text-align: center;
            align-content: center;
            width: 180px;
        }

        @media print {
            .print-button {
                display: none;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .page {
                padding: 0;
            }

                      * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            .info-label {
                background: #f0f0f0 !important;
            }

            .item-table th {
                background: #f0f0f0 !important;
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="invoice-frame">
            <!-- HEADER -->
            <div class="header">
                <img src="{{ asset('Picture1.png') }}" alt="GSB Logo"
                    style="height:65px; width:auto; object-fit:contain; display:block;">

            </div>

            <div class="title">INVOICE</div>
            <table class="info-table">
                <!-- Row 1 -->
                <tr>
                    <td class="info-label">Kepada :</td>
                    <td class="info">{{ $penawaran->client->nama ?? 'N/A' }}</td>
                    <td class="info-label">No. Invoice</td>
                    <td class="info">{{ $penawaran->no_penawaran }}</td>
                </tr>

                <!-- Row 2: -->
                <tr>
                    <td class="info-label" rowspan="4">Alamat :</td>
                    <td class="info" rowspan="4">
                        {{ $penawaran->client->alamat ?? '' }} <br> {{ $penawaran->client->telepon ?? '' }}
                    </td>
                    <td class="info-label">No Faktur Pajak</td>
                    <td class="info"></td>
                </tr>
                <!-- Row 3 -->
                <tr>
                    <td class="info-label">Tgl Invoice</td>
                    <td class="info">{{ $penawaran->tanggal->format('d/m/Y') }}</td>
                </tr>

                <!-- Row 4 -->
                <tr>
                    <td class="info-label">Tgl Jatuh Tempo</td>
                    <td class="info" style="vertical-align: middle;">45 hari sejak selesai validasi</td>
                </tr>

                <!-- Row 5 -->
                <tr>
                    <td class="info-label">No. Perjanjian</td>
                    <td class="info"></td>
                </tr>

                <!-- Row 6 -->
                <tr>
                    <td class="info-label">NPWP :</td>
                    <td colspan="3"></td>
                </tr>
            </table>

            <!-- ITEM TABLE (3 kolom: Item / Uraian / Jumlah) -->
            @php
                // Pemisahan harga material (BARANG) dan non-material (JASA, TOL, LAINNYA)
                $hargaMaterial = 0;
                $hargaNonMaterial = 0;

                foreach ($penawaran->items ?? [] as $item) {
                    $totalItem = $item->harga_jual * $item->jumlah;
                    if ($item->material->type === 'BARANG') {
                        $hargaMaterial += $totalItem;
                    } else {
                        $hargaNonMaterial += $totalItem;
                    }
                }
            @endphp
            <table class="item-table" style="margin-top: 12px;">
                <colgroup>
                    <col class="col-item">
                    <col class="col-uraian-left">
                    <col class="col-uraian-right">
                    <col class="col-jumlah">
                </colgroup>
                <tr>
                    <th class="item-col-item">Item</th>
                    <th class="item-col-uraian" colspan="2">Uraian</th>
                    <th class="item-col-jumlah">Jumlah</th>
                </tr>

                <tr>
                    <td rowspan="9"></td>
                    <td class="item-label-inside">PEKERJAAN :</td>
                    <td></td>
                    <td rowspan="3"></td>
                </tr>

                <tr>
                    <td class="item-label-inside">PO No. :</td>
                    <td></td>

                </tr>

                <tr>
                    <td class="item-label-inside">GR No. :</td>
                    <td></td>

                </tr>

                <tr>
                    <td colspan="2">JASA :</td>
                    <td style="text-align: center;">Rp{{ number_format($hargaNonMaterial, 0, ',', '.') }}
                    </td>
                </tr>

                <tr>
                    <td colspan="2">MATERIAL :</td>
                    <td style="text-align: center;">Rp{{ number_format($hargaMaterial, 0, ',', '.') }}</td>
                </tr>

                <tr>
                    <td colspan="2">HARGA JUAL (JASA + MATERIAL) :</td>
                    <td style="text-align: center;">
                        Rp{{ number_format($penawaran->grand_total, 0, ',', '.') }}
                    </td>
                </tr>

                <tr>
                    <td colspan="2">PPN :</td>
                    <td style="text-align: center;">
                        Rp{{ number_format($penawaran->ppn ?? $penawaran->grand_total * 0.11, 0, ',', '.') }}
                    </td>
                </tr>

                <tr>
                    <td colspan="2">NILAI TAGIHAN :</td>
                    <td style="text-align: center;">
                        Rp{{ number_format($penawaran->grand_total_with_ppn ?? $penawaran->grand_total * 1.11, 0, ',', '.') }}
                    </td>
                </tr>

                <tr>
                    <td class="item-label-inside">TERBILANG</td>
                    <td colspan="3" style="text-align: center; height: 50px;"> @php
                        $finalTotal = $penawaran->grand_total_with_ppn ?? $penawaran->grand_total * 1.11;
                        // Function to convert number to words (Indonesian)
                        $terbilang = \App\Helpers\FormatHelper::angkaKeHuruf($finalTotal);
                    @endphp
                        {{ $terbilang }}</td>
                </tr>
            </table>
            <!-- BOTTOM: BANK & TTD -->
            <div class="bottom-section">
                <div class="bottom-grid">
                    <!-- Bank -->
                    <table class="bank-table">
                        <tr>
                            <td class="bank-label">Nama Bank</td>
                            <td class="bank-colon">:</td>
                            <td class="bank-value">Bank BNI</td>
                        </tr>
                        <tr>
                            <td class="bank-label">No Rekening</td>
                            <td class="bank-colon">:</td>
                            <td class="bank-value">1439152652</td>
                        </tr>
                        <tr>
                            <td class="bank-label">Nama</td>
                            <td class="bank-colon">:</td>
                            <td class="bank-value">CV Gundara Solusi Bersama</td>
                        </tr>
                    </table>

                    <!-- TTD -->
                    <div>
                        <div class="ttd-box" style="border-bottom: 0px">
                            <p>Direktur</p>
                        </div>
                        <div class="ttd-2-box" style="height: 80px; border-bottom: 0px;">
                        </div>
                        <div class="ttd-name">HAYU MARYANTI</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="print-button" style="text-align: right; margin: 15px 15px 0;">
        <button onclick="window.print()" style="padding: 8px 18px; font-size: 13px; cursor: pointer;">
            Print / Save as PDF
        </button>
    </div>
</body>

</html>
