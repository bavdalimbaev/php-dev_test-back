<?php

namespace App\Http\Requests\Product;

use App\Utils\Tables\Category\CategoryColumn;
use App\Utils\Tables\ETables;
use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryCreate extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_ids' => 'required|array',
            'category_ids.*' => 'required|numeric|exists:' . ETables::CATEGORY->value . ',' . CategoryColumn::ID,
        ];
    }
}
