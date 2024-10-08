<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class LoginBrowserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string|array<int, string|\Closure|Rules\Enum>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required'],
            'password' => ['required', 'string'],
            'remember_me' => ['boolean'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
