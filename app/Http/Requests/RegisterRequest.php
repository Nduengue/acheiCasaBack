<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
            "first_name" => "required|string|max:255",
            "last_name" => "required|string|max:255",
            "phone_number" => "required|string|max:255",
            "email" => "required|email|unique:users,email|max:255",
            "password" => "required|confirmed|string|min:8|max:255",
            "code" => "required|string",
            "birthdate" => "required|date",
            "gender" => "required|in:M,F",
            "front" => "required|image|mimes:jpeg,png,jpg,gif|max:2048",
            "back" => "required|image|mimes:jpeg,png,jpg,gif|max:2048",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [
                'success' => false,
                'message' => 'Erro de validação!',
                'errors' => $validator->errors()
            ]
        ));
    }
}
