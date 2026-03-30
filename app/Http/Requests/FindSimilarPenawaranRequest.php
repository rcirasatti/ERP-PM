<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FindSimilarPenawaranRequest extends FormRequest
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
            'client_id' => 'required|exists:clients,id',
            'limit' => 'integer|min:1|max:20',
            'exclude_penawaran_id' => 'nullable|exists:penawaran,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'client_id.required' => 'Client harus dipilih',
            'client_id.exists' => 'Client tidak ditemukan',
            'limit.integer' => 'Limit harus berupa angka',
            'limit.min' => 'Minimum limit adalah 1 penawaran',
            'limit.max' => 'Maksimum limit adalah 20 penawaran',
            'exclude_penawaran_id.exists' => 'Penawaran yang akan dikecualikan tidak ditemukan',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'client_id' => 'client',
            'limit' => 'batas jumlah penawaran',
            'exclude_penawaran_id' => 'penawaran yang dikecualikan',
        ];
    }
}
