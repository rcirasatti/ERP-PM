<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CopyItemsFromPenawaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: Re-enable auth check after testing
        // Temporarily disabled for API testing
        return true;
        
        // Original check (commented for testing):
        // return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'source_penawaran_id' => 'required|exists:penawaran,id',
            'target_penawaran_id' => 'required|exists:penawaran,id',
            'price_strategy' => 'required|in:keep,latest,average,override',
            'override_prices' => 'array',
            'override_prices.*.item_id' => 'required_if:price_strategy,override|exists:item_penawaran,id',
            'override_prices.*.harga_asli' => 'required_if:price_strategy,override|numeric|min:0',
            'override_prices.*.persentase_margin' => 'required_if:price_strategy,override|numeric|min:0|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'source_penawaran_id.required' => 'Penawaran sumber harus dipilih',
            'source_penawaran_id.exists' => 'Penawaran sumber tidak ditemukan',
            'target_penawaran_id.required' => 'Penawaran target harus dipilih',
            'target_penawaran_id.exists' => 'Penawaran target tidak ditemukan',
            'price_strategy.required' => 'Strategi harga harus dipilih',
            'price_strategy.in' => 'Strategi harga tidak valid. Pilih: keep, latest, average, atau override',
            'override_prices.*.item_id.required_if' => 'Item ID harus diisi ketika menggunakan strategi override',
            'override_prices.*.item_id.exists' => 'Salah satu item tidak ditemukan',
            'override_prices.*.harga_asli.required_if' => 'Harga asli harus diisi ketika menggunakan strategi override',
            'override_prices.*.harga_asli.numeric' => 'Harga asli harus berupa angka',
            'override_prices.*.harga_asli.min' => 'Harga asli tidak boleh negatif',
            'override_prices.*.persentase_margin.required_if' => 'Persentase margin harus diisi ketika menggunakan strategi override',
            'override_prices.*.persentase_margin.numeric' => 'Persentase margin harus berupa angka',
            'override_prices.*.persentase_margin.min' => 'Persentase margin tidak boleh negatif',
            'override_prices.*.persentase_margin.max' => 'Persentase margin tidak boleh lebih dari 100%',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'source_penawaran_id' => 'penawaran sumber',
            'target_penawaran_id' => 'penawaran target',
            'price_strategy' => 'strategi harga',
        ];
    }
}
