<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentReceiptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    /*  Table payment_receipts {
        id serial [primary key]
        business_id int [ref: > businesses.id]
        user_id int [ref: > user.id, note: "quem enviou o comprovativo"] 
        path_receipt varchar [note: "caminho do comprovativo (imagem ou pdf)"]
        payment_method enum("bank_transfer", "cash", "other") [default: "bank_transfer"]
        sent_at timestamp 
        approved bool [default: false, note: "Aprovado manualmente?"]
        notes text [null]
    } */
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
            'business_id' => 'required|exists:businesses,id',
            'user_id' => 'required|exists:users,id',
            'path_receipt' => 'required|string|max:255',
            'payment_method' => 'required|in:bank_transfer,cash,other',
            'sent_at' => 'nullable|date',
            'approved' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ];
    }
    /**
     * Fail Validate the request.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'message' => 'Erro de validação!',
            'errors' => $validator->errors(),
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
