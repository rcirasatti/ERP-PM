# Panduan & Catatan Analisis AI DSS (Decision Support System) ERP-PM

Dokumen ini berisi panduan teknis dan penjelasan praktis mengenai cara kerja sistem **AI DSS (Decision Support System)** pada modul Penawaran ERP-PM, khususnya terkait interpretasi visual pada pop-up analisis ML.

---

## 📊 1. Alur Logika Analisis DSS ML
Model Machine Learning (`predict_dss.py`) dilatih menggunakan data historis proyek yang telah selesai (*completed projects*). Model ini memprediksi **Biaya Aktual Lapangan (Estimated Actual Cost)** berdasarkan dua parameter input:
1. **Nilai Penawaran (Grand Total / Quoted Cost):** Nominal yang diajukan ke klien (diambil dari parse Excel BoQ).
2. **Wilayah Proyek (Region Dummies):** Lokasi pengerjaan proyek (misal: *Kota Semarang*).
3. **Jenis Pekerjaan:** Jenis proyek yang dipilih (misal: *Project / Purchase Order*).

Rumus dasar penentuan selisih biaya (*variance*):
$$\text{Selisih} = \text{Prediksi Biaya Aktual} - \text{Total Penawaran}$$

---

## 📉 2. Arti Tanda Minus (-) di Kolom Penghematan

Pada antarmuka pop-up analisis AI DSS, Anda akan melihat indikator selisih persentase dan nominal. Berikut cara membacanya:

| Nilai Selisih | Warna & Label Visual | Interpretasi Sistem | Arti bagi Pengguna |
| :--- | :--- | :--- | :--- |
| **Minus (-)** <br>*(Contoh: `-12.5%`)* | 🟢 **Potensi Penghematan (Aman)** | Prediksi Biaya Aktual <br>**LEBIH KECIL** <br>dari Nilai Penawaran. | **SANGAT AMAN / PROFIT**<br>Ada potensi penghematan anggaran atau margin keuntungan ekstra sebesar persentase tersebut. Proyek sangat layak disetujui. |
| **Plus (+)** <br>*(Contoh: `+8.2%`)* | 🔴 **Potensi Overrun (Rugi)** | Prediksi Biaya Aktual <br>**LEBIH BESAR** <br>dari Nilai Penawaran. | **BAHAYA / POTENSI RUGI (BONCOS)**<br>Biaya pengerjaan riil diprediksi membengkak melebihi nilai kontrak penawaran. Sangat disarankan untuk revisi harga atau margin. |

---

## 🛡️ 3. Kategori Penilaian Risiko

AI DSS membagi tingkat risiko penawaran menjadi tiga level utama:

### 🟢 Risiko Rendah (Low Risk)
* **Kondisi:** Estimasi Overrun $\le 0\%$ (Tanda Minus `-`).
* **Karakteristik:** Penawaran harga aman, berada di atas estimasi pengeluaran riil lapangan.
* **Rekomendasi AI:**
  > [!NOTE]
  > **✓ Risiko rendah.** Estimasi biaya terlihat sangat aman, diprediksi ada penghematan/margin ekstra sebesar X%. Proyek dapat langsung disetujui untuk masuk ke tahap pengerjaan.

### 🟡 Risiko Sedang (Medium Risk)
* **Kondisi:** Estimasi Overrun berada di antara $0\%$ sampai $10\%$ (`0% < Overrun <= 10%`).
* **Karakteristik:** Biaya aktual diprediksi sedikit melebihi penawaran, sehingga berpotensi memotong sebagian besar margin keuntungan bersih.
* **Rekomendasi AI:**
  > [!WARNING]
  > **⚠️ WARNING:** Model AI memprediksi kerugian/overrun sebesar X%. Ada potensi biaya membengkak melebihi penawaran. Review kembali harga material dan kuantitas sebelum melanjutkan.

### 🔴 Risiko Tinggi (High Risk)
* **Kondisi:** Estimasi Overrun melebihi $10\%$ (`Overrun > 10%`).
* **Karakteristik:** **Sangat Bahaya.** Pengeluaran riil di lapangan diprediksi jauh lebih besar daripada penawaran BoQ. Risiko kerugian finansial yang signifikan bagi perusahaan.
* **Rekomendasi AI:**
  > [!CAUTION]
  > **🔴 DANGER:** Model AI memprediksi potensi kerugian/overrun sebesar X%. Prediksi aktual biaya adalah Rp X berbanding penawaran Rp Y. Sangat disarankan untuk merevisi penawaran!

---

## ⚙️ 4. Faktor Penilai Tambahan pada Pop-up

Selain perhitungan persentase variance di atas, DSS juga mengukur tingkat kompleksitas berdasarkan beberapa variabel berikut:
* **Jumlah Item BoQ:** Semakin banyak jenis barang, semakin tinggi potensi deviasi pengeluaran di lapangan.
* **Kompleksitas Item (Skala 1-10):** Dihitung secara dinamis dari kombinasi jumlah item dan standar deviasi harga historis.
* **Overrun Historis (%):** Persentase riwayat proyek bermasalah (yang mengalami pembengkakan biaya) pada jenis pekerjaan/klien yang sama di masa lalu.

---

## 🛠️ 5. Rekomendasi Tindakan bagi Pengguna

Berdasarkan pop-up DSS, pengguna memiliki 3 pilihan aksi:
1. **Setujui Penawaran (Approve):** Digunakan jika risiko **Rendah (Hijau)**. Sistem otomatis akan mengubah status menjadi `disetujui` dan men-generate data material dari BoQ.
2. **Revisi (Revise):** Digunakan jika risiko **Sedang (Kuning)** atau **Tinggi (Merah)**. Mengembalikan penawaran ke status draft agar pengguna dapat merapikan komponen harga material/margin.
3. **Tolak (Reject):** Membatalkan draf penawaran BoQ jika dirasa proyek tersebut tidak menguntungkan bagi perusahaan.
