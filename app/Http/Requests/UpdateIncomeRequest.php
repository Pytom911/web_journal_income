<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateIncomeRequest extends StoreIncomeRequest
{
    /**
     * Keep a category that is already attached to the income being edited.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $income = $this->route('income');

        $rules['category_id'] = [
            'required',
            Rule::exists('categories', 'id')->where(fn ($query) => $query
                ->where('is_active', true)
                ->orWhere('id', $income?->category_id)),
        ];

        return $rules;
    }
}
