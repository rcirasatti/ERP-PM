<?php

namespace App\Helpers;

class FormatHelper
{
    /**
     * Format harga dengan intelligent conversion
     * - Jika < 1 juta: tampilkan dengan 0 decimal (e.g., "Rp 500.000")
     * - Jika >= 1 juta: tampilkan dalam juta dengan 2 decimal (e.g., "Rp 2,50 Juta")
     * - Jika >= 1 miliar: tampilkan dalam miliar dengan 2 decimal (e.g., "Rp 1,50 Miliar")
     * 
     * @param float|int $amount
     * @return string
     */
    public static function formatCurrency($amount)
    {
        $absAmount = abs($amount);
        
        if ($absAmount >= 1000000000) {
            // Miliar
            $value = $amount / 1000000000;
            return 'Rp ' . number_format($value, 2, ',', '.') . ' Miliar';
        } elseif ($absAmount >= 1000000) {
            // Juta
            $value = $amount / 1000000;
            return 'Rp ' . number_format($value, 2, ',', '.') . ' Juta';
        } else {
            // Rupiah biasa
            return 'Rp ' . number_format($amount, 0, ',', '.');
        }
    }

    /**
     * Format harga untuk card budget (compact format)
     * - Jika < 1 juta: tampilkan dengan 0 decimal (e.g., "Rp 500.000")
     * - Jika >= 1 juta && < 1 miliar: tampilkan dalam juta:
     *   - Dibawah 100 juta: dengan 1 decimal (e.g., "2,5jt")
     *   - 100 juta keatas: tanpa decimal (e.g., "150jt")
     * - Jika >= 1 miliar: tampilkan dalam miliar dengan 2 decimal (e.g., "1,50M")
     * 
     * @param float|int $amount
     * @return string
     */
    public static function formatCurrencyCompact($amount)
    {
        $absAmount = abs($amount);
        
        if ($absAmount >= 1000000000) {
            // Miliar
            $value = $amount / 1000000000;
            return 'Rp ' . number_format($value, 2, ',', '') . 'M';
        } elseif ($absAmount >= 1000000) {
            // Juta
            $value = $amount / 1000000;
            if ($absAmount >= 100000000) {
                // 100 juta keatas, tampilkan tanpa decimal
                return 'Rp ' . number_format($value, 0) . 'jt';
            } else {
                // Dibawah 100 juta, tampilkan dengan 1 decimal
                return 'Rp ' . number_format($value, 1, ',', '') . 'jt';
            }
        } else {
            // Kurang dari 1 juta, tampilkan full
            return 'Rp ' . number_format($amount, 0, ',', '.');
        }
    }

    /**
     * Format persentase dengan 1 decimal
     * 
     * @param float $value
     * @return string
     */
    public static function formatPercentage($value)
    {
        return number_format($value, 1, ',', '.') . '%';
    }

    /**
     * Convert angka ke huruf (Indonesian Terbilang)
     * 
     * @param float|int $number
     * @return string
     */
    public static function angkaKeHuruf($number)
    {
        $number = (int) $number;
        
        $kata_kecil = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan');
        $kata_besar = array('', 'ribu', 'juta', 'miliar', 'triliun');
        
        if ($number == 0) {
            return 'nol';
        }
        
        if ($number < 0) {
            return 'minus ' . self::angkaKeHuruf(-1 * $number);
        }
        
        $string = '';
        $angka_satuan = 0;
        $posisi = 0;
        
        while ($number > 0) {
            $angka_satuan = $number % 1000;
            if ($angka_satuan !== 0) {
                $string = self::_bentukKata($angka_satuan) . ' ' . $kata_besar[$posisi] . ' ' . $string;
            }
            $number = (int) ($number / 1000);
            $posisi++;
        }
        
        $string = trim($string);
        return ucfirst($string) . ' Rupiah';
    }
    
    /**
     * Helper untuk membentuk kata dari angka
     */
    private static function _bentukKata($number)
    {
        $kata_kecil = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan');
        $kata_puluh = array('', 'sepuluh', 'dua puluh', 'tiga puluh', 'empat puluh', 'lima puluh', 'enam puluh', 'tujuh puluh', 'delapan puluh', 'sembilan puluh');
        $kata_ratus = array('', 'seratus', 'dua ratus', 'tiga ratus', 'empat ratus', 'lima ratus', 'enam ratus', 'tujuh ratus', 'delapan ratus', 'sembilan ratus');
        
        $result = '';
        
        $ratusan = (int) ($number / 100);
        if ($ratusan > 0) {
            $result .= $kata_ratus[$ratusan] . ' ';
        }
        
        $puluhan = (int) (($number % 100) / 10);
        $satuan = $number % 10;
        
        if ($puluhan == 1) {
            $result .= self::_sebelas($satuan) . ' ';
        } else {
            if ($puluhan > 0) {
                $result .= $kata_puluh[$puluhan] . ' ';
            }
            if ($satuan > 0) {
                $result .= $kata_kecil[$satuan] . ' ';
            }
        }
        
        return trim($result);
    }
    
    /**
     * Helper untuk angka 10-19
     */
    private static function _sebelas($satuan)
    {
        $kata_sebelas = array('sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas', 'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas');
        return $kata_sebelas[$satuan];
    }
}
