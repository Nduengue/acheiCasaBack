<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusinessRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
     /* 
        Table businesses {
            id serial [primary key]
            property_id int [ref: > property.id]
            buyer_id int [ref: > user.id, note: "quem quer comprar/alugar"]
            seller_id int [ref: > user.id, note: "dono do imóvel (ou agência)"]
            intermediary_id int [ref: > intermediary.id, null]
            price decimal
            status enum("pending", "accepted", "rejected", "closed", "cancelled") [default: "pending"]
            type_of_business enum("A", "V")  [note: "aluguel ou venda"]
            started_at timestamp 
            closed_at timestamp [null]
            notes text [null]
            deleted bool [default: false]
        }
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
            "status" => "nullable|in:pending,accepted,rejected,closed,cancelled",
            "notes" => "nullable|string",
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
            'status' => $this->input('status') ?? 'pending',
            'notes' => $this->input('notes') ?? null,
        ]);
    }
}
