<?php

namespace App\Http\Requests\V1;

use App\Support\Enums\UserRole;
use BenSampo\Enum\Rules\EnumKey;
use Illuminate\Foundation\Http\FormRequest;

class UserCredetialsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'role' => ['required', new EnumKey(UserRole::class)],
            'banned' => 'required|boolean',
        ];
    }
}
