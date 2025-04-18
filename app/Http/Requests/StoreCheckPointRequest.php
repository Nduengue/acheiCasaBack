<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckPointRequest extends FormRequest
{
    /* Table checkpoints {
        id serial [primary key]
        user_id int [ref: > user.id]
        property_id int [ref: > property.id]
        check_in datatime 
        check_out datetime [not null, note: "opicional"]
        status enum("check_in","check_out","cancelled") [default: "check_in"]
    } */
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
            'check_in' => 'required|date',
            'check_out' => 'nullable|date|after:check_in',
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
    protected function prepareForValidation()
    {
        $this->merge([
            'check_in' => $this->input('check_in') ?? date("Y-m-d H:i:s"),
            'check_out' => $this->input('check_out') ?? null,
        ]);
    }
}
