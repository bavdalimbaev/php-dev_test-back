<?php

namespace App\Http\Requests\Category;

use App\Utils\Tables\ETables;
use App\Utils\Tables\Product\ProductColumn;
use Illuminate\Foundation\Http\FormRequest;

class CategoryProductCreate extends FormRequest
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
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:' . ETables::PRODUCT->value . ',' . ProductColumn::ID,
        ];
    }
}
