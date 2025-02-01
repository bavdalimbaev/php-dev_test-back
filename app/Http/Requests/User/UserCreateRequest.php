<?php

namespace App\Http\Requests\User;

use App\Utils\Tables\ETables;
use App\Utils\Tables\User\UserColumn;
use App\Utils\Tables\User\UserProfileColumn;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            UserColumn::NAME => 'required|string|max:255',
            UserColumn::EMAIL => 'required|string|email|max:255|unique:'. ETables::USER->value,
            UserColumn::PASSWORD => 'required|string',
            UserProfileColumn::BIO => 'nullable|string',
        ];
    }
}
