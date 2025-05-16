<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        $except_email = auth()->user()->email;
        $except_phone = auth()->user()->phone_number;
        $id = auth()->user()->id; 
        return [
            "first_name" => "required|string|max:255",
            "last_name" => "required|string|max:255",
            "phone_number" => "required|string|max:255",
            "email" => "required|email|max:255",
            "birthdate" => "required|date",
            "gender" => "required|in:M,F",
            "biography" => "required|string|max:255",
            /* address */
            "province" => "nullable|string|max:255",
            "country" => "nullable|string|max:255",
            "municipality" => "nullable|string|max:255",
            "postal_code" => "nullable|string|max:255",
        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json(
            [
                'success' => false,
                'message' => 'Erro de validação!',
                'errors' => $validator->errors()
            ]
        ));
    }
}
