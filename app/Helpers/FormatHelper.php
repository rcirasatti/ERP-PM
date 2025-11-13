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
}
