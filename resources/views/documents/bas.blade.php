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
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.6;
        }

        .page {
            max-width: 210mm;
            margin: 0 auto;
            padding: 25px 25px 30px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .page {
                max-width: 100%;
                padding: 20px;
            }

            .print-button {
                display: none;
            }
        }

        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 15px;
        }

        .content {
            font-size: 11px;
        }

        .para-opening {
            text-align: justify;
            margin-bottom: 12px;
            font-size: 11px;
        }

        .line-fill {
            display: flex;
            align-items: flex-end;
        }

        .line-fill-text {
            white-space: nowrap;
        }

        .line-fill-blank {
            flex: 1;
            border-bottom: 1px solid #000;
            margin-left: 4px;
            line-height: 1.2;
        }


        .blank-inline {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 80px;
            line-height: 1.5;
        }

        .info-block-title {
            font-weight: bold;
            margin-top: 8px;
            margin-bottom: 3px;
        }

        .info-block {
            margin-left: 0;
            margin-bottom: 8px;
        }

        /* label : value dengan titik dua rata */
        .info-row {
            display: grid;
            grid-template-columns: 110px 10px 1fr;
            margin: 1px 0;
        }

        .info-colon {
            text-align: center;
        }

        .info-value {
            text-align: left;
        }

        /* teks di bawah blok pihak */
        .sub-paragraph {
            margin-top: 12px;
            margin-bottom: 8px;
        }

        .kondisi-list {
            margin-left: 20px;
            margin-bottom: 10px;
        }

        .kondisi-list li {
            margin: 2px 0;
        }

        .kondisi-list span {
            display: inline-block;
            width: 200px;
            border-bottom: 1px dotted #000;
        }

        /* tabel petugas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px 6px;
            font-size: 10px;
        }

        th {
            text-align: center;
            font-weight: bold;
            background: #f0f0f0;
        }

        /* bagian bawah */
        .closing-text {
            margin-top: 8px;
            margin-bottom: 18px;
        }

        .date-right {
            text-align: right;
            margin-bottom: 25px;
        }

        .signature-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: 40px;
            margin-bottom: 30px;
        }

        .signature-block {
            text-align: center;
            font-size: 11px;
        }

        .signature-block p {
            margin: 3px 0;
        }

        .signature-space {
            height: 45px;
            margin: 5px 0;
        }

        .center-signature {
            text-align: center;
            margin-top: 10px;
        }

        .center-signature p {
            margin: 3px 0;
        }

        .center-signature .signature-space-center {
            height: 45px;
            margin: 5px 0;
        }

        .print-button {
            text-align: right;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="page">
        <!-- Judul -->
        <div class="header-title">
            BERITA ACARA SURVEY
        </div>

        <div class="content">
            <p class="para-opening">
                <!-- Baris 1 -->
                <span class="line-fill">
                    <span class="line-fill-text">Pada hari ini,</span>
                    <span class="line-fill-blank"></span>
                </span>
            </p>

            <p class="para-opening">
                <!-- Baris 2 -->
                <span class="line-fill">
                    <span class="line-fill-text">
                        telah dilaksanakan Survey <span class="blank-inline" style="min-width:250px;"></span> untuk
                    </span>
                    <span class="line-fill-blank"></span>
                </span>
                <br>
                <!-- Baris 3 -->
                <span class="line-fill">
                    <span class="line-fill-text">Pelanggan:</span>

                    <!-- garis yang fleksibel -->
                    <span class="line-fill-blank"></span>

                    <!-- teks SO di ujung kanan -->
                    <span class="line-fill-text">
                        (SO: <span class="blank-inline" style="min-width:50px;"></span> /TER)
                    </span>
                </span>

            </p>
            <br>
            <!-- PIHAK PERTAMA -->
            <div class="info-block">
                <div class="info-row">
                    <div class="info-label"><strong>PIHAK PERTAMA</strong></div>
                    <div class="info-colon">:</div>
                    <div class="info-value"><strong>{{ $penawaran->client->nama ?? 'N/A' }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label" style="margin-left: 20%">Diwakili Oleh</div>
                    <div class="info-colon">:</div>
                    <div class="info-value"></div>
                </div>
                <div class="info-row">
                    <div class="info-label" style="margin-left: 20%">Jabatan</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">Tim Teknis</div>
                </div>
                <div class="info-row">
                    <div class="info-label" style="margin-left: 20%">Alamat Kantor</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $penawaran->client->alamat ?? '' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label" style="margin-left: 20%">Nomer Telepon</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $penawaran->client->telepon ?? '' }}</div>
                </div>
            </div><br>
            <!-- PIHAK KEDUA -->
            <div class="info-block">
                <div class="info-row">
                    <div class="info-label"><strong>PIHAK KEDUA</strong></div>
                    <div class="info-colon">:</div>
                    <div class="info-value"><strong>CV. GUNDARA SOLUSI BERSAMA</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label" style="margin-left: 20%">Diwakili Oleh</div>
                    <div class="info-colon">:</div>
                    <div class="info-value"></div>
                </div>
                <div class="info-row">
                    <div class="info-label" style="margin-left: 20%">Jabatan</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">Tim Teknis</div>
                </div>
                <div class="info-row">
                    <div class="info-label" style="margin-left: 20%">Alamat Kantor</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">
                        Jl. Sendang Gede No.14, Banyumanik, Kec. Banyumanik, Kota<br>
                        Semarang, Jawa Tengah 50264
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label" style="margin-left: 20%">Nomor Telepon</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">082-29-7777-169</div>
                </div>
            </div>
<br>
            <!-- Kendala -->
            <p class="sub-paragraph">
                Bahwa saat pekerjaan Survey ditemukan <strong>(Ada/Tidak Ada)</strong> kendala sbb:
            </p>
            <ol class="kondisi-list">
                <li><span></span></li>
                <li><span></span></li>
                <li><span></span></li>
                <li><span></span></li>
            </ol>
<br>
            <p class="sub-paragraph">Petugas Lapangan :</p>

            <!-- Tabel Petugas -->
            <table style="text-align: center;">
                <thead>
                    <tr>
                        <th style="width: 30%;">Perusahaan</th>
                        <th style="width: 27%;">Nama</th>
                        <th style="width: 15%;">No. Telp / HP</th>
                        <th style="width: 17%;">Lokasi Kerja</th>
                        <th style="width: 11%;">Paraf</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CV. GUNDARA SOLUSI BERSAMA</td>
                        <td></td>
                        <td>082297777169</td>
                        <td>Semarang</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <p class="closing-text">
                Demikian Berita Acara ini dibuat untuk digunakan sebagaimana mestinya.
            </p>
<br>
            <!-- Tanggal -->
            <p class="date-right">
                Semarang, ________________________
            </p>
<br>
            <!-- Tanda tangan kiri & kanan -->
            <div class="signature-row">
                <div class="signature-block">
                    <p>CV. GUNDARA SOLUSI BERSAMA</p>
                    <div class="signature-space"></div>
                    <p>(..............................)</p>
                </div>
                <div class="signature-block">
                    <p>Pelanggan</p>
                    <div class="signature-space"></div>
                    <p>(..............................)</p>
                </div>
            </div>

            <!-- Mengetahui / Menyetujui -->
            <div class="center-signature">
                <p>Mengetahui/Menyetujui,</p>
                <p style="text-transform: uppercase;">{{ $penawaran->client->nama ?? 'N/A' }}</p>
                <div class="signature-space-center"></div>
                <p>{{ $penawaran->client->kontak ?? 'N/A' }}</p>
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
