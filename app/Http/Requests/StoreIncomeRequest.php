<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIncomeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(fn ($query) => $query->where('is_active', true)),
            ],
            'amount' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
            'received_at' => ['required', 'date_format:Y-m-d'],
            'source' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'judul',
            'category_id' => 'kategori',
            'amount' => 'jumlah',
            'received_at' => 'tanggal',
            'source' => 'sumber',
            'note' => 'catatan',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'numeric' => 'Kolom :attribute harus berupa angka.',
            'min' => 'Kolom :attribute tidak boleh negatif.',
            'max' => 'Kolom :attribute terlalu besar.',
            'date_format' => 'Kolom :attribute harus berformat YYYY-MM-DD.',
            'exists' => 'Kategori yang dipilih tidak tersedia.',
        ];
    }
}
