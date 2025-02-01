<?php

namespace App\Http\Requests\Product;

use App\Utils\Tables\ETables;
use App\Utils\Tables\Product\ProductColumn;
use App\Utils\Tables\User\UserColumn;
use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ProductColumn::TITLE => 'required|string|max:255',
            ProductColumn::DESCRIPTION => 'nullable|string',
            ProductColumn::PRICE => 'required|numeric',
            ProductColumn::USER_ID => 'required|exists:'.ETables::USER->value.',' . UserColumn::ID,
        ];
    }
}
