<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
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
        $uniqueName = Rule::unique('categories', 'name');
        $category = $this->route('category');

        if ($category instanceof Category) {
            $uniqueName->ignore($category->id);
        }

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                $uniqueName,
            ],
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama kategori',
            'color' => 'warna',
            'description' => 'deskripsi',
            'is_active' => 'status aktif',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'unique' => 'Kategori dengan nama ini sudah ada.',
            'regex' => 'Warna harus berupa kode hex, contoh #06B6D4.',
        ];
    }
}
