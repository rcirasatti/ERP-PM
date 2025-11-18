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
            <!-- Paragraf pembuka -->
            <p class="para-opening">
                Pada hari ini, Kamis tanggal Enam belas bulan Oktober tahun Dua Ribu Dua Puluh Lima
                telah dilaksanakan Survey atas Jaringan Telekomunikasi langganan untuk Mendukung
                INTERNET CORPORATE Pelanggan PT INDOWIN ENGINEERING INDONESIA:
                (SO: ______ /TER)
            </p>

            <!-- PIHAK PERTAMA -->
            <div class="info-block">
                <div class="info-row">
                    <div class="info-label"><strong>PIHAK PERTAMA</strong></div>
                    <div class="info-colon">:</div>
                    <div class="info-value"><strong>{{ $penawaran->client->nama ?? 'N/A' }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Diwakili Oleh</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $penawaran->pic ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jabatan</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">Tim Teknis</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Alamat Kantor</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $penawaran->client->alamat ?? '' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nomer Telepon</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $penawaran->client->telepon ?? '' }}</div>
                </div>
            </div>



            <!-- PIHAK KEDUA -->
            <div class="info-block">
                <div class="info-row">
                    <div class="info-label">PIHAK KEDUA</div>
                    <div class="info-colon">:</div>
                    <div class="info-value"><strong>CV. GUNDARA SOLUSI BERSAMA</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Diwakili Oleh</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">ILHAM FARABI</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jabatan</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">Tim Teknis</div> 
                </div>
                <div class="info-row">
                    <div class="info-label">Alamat Kantor</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">
                        Jl. Sendang Gede No.14, Banyumanik, Kec. Banyumanik, Kota<br>
                        Semarang, Jawa Tengah 50264
                    </div>
                </div>
                               <div class="info-row">
                    <div class="info-label">Nomor Telepon</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">082-29-7777-169</div>
                </div>
            </div>

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

            <p class="sub-paragraph">Petugas Lapangan :</p>

            <!-- Tabel Petugas -->
            <table>
                <thead>
                    <tr>
                        <th>Perusahaan</th>
                        <th>Nama</th>
                        <th>No. Telp / HP</th>
                        <th>Lokasi Kerja</th>
                        <th>Paraf</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PT INDOWIN ENGINEERING INDONESIA</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>CV. GUNDARA SOLUSI BERSAMA</td>
                        <td>Ilham</td>
                        <td>082297777169</td>
                        <td>Semarang</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <p class="closing-text">
                Demikian Berita Acara ini dibuat untuk digunakan sebagaimana mestinya.
            </p>

            <!-- Tanggal -->
            <p class="date-right">
                Semarang, {{ $penawaran->tanggal->format('d F Y') }}
            </p>

            <!-- Tanda tangan kiri & kanan -->
            <div class="signature-row">
                <div class="signature-block">
                    <p>CV. GUNDARA SOLUSI BERSAMA</p>
                    <div class="signature-space"></div>
                    <p>( Ilham )</p>
                </div>
                <div class="signature-block">
                    <p>PT INDOWIN ENGINEERING INDONESIA</p>
                    <div class="signature-space"></div>
                    <p>(..............................)</p>
                </div>
            </div>

            <!-- Mengetahui / Menyetujui -->
            <div class="center-signature">
                <p>Mengetahui/Menyetujui,</p>
                <p>PT INDONESIA COMNETS PLUS</p>
                <p>SBUR JAWA BAGIAN TENGAH</p>
                <div class="signature-space-center"></div>
                <p>(Karisma Kusuma Ayu)</p>
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
