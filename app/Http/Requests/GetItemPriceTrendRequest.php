<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetItemPriceTrendRequest extends FormRequest
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
        // return auth()->check() && in_array(auth()->user()->role, ['admin', 'manager']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'material_id' => 'required|exists:materials,id',
            'limit' => 'integer|min:5|max:50',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'material_id.required' => 'Material harus dipilih',
            'material_id.exists' => 'Material tidak ditemukan',
            'limit.integer' => 'Limit harus berupa angka',
            'limit.min' => 'Minimum limit adalah 5 record',
            'limit.max' => 'Maksimum limit adalah 50 record',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'material_id' => 'material',
            'limit' => 'batas jumlah data',
        ];
    }
}
