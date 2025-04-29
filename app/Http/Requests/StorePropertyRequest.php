<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StorePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
     
    public function authorize(): bool
    {
        return true;
    }
    /*  Modelo
        Table property {
            id serial [primary key]
            user_id int [ref: > user.id]
            category_id enum("Praia", "Reserva", "Loja", "Terreno", "Residencial", "Escritorio", "Quartos","Armazem")
            agency_id int [ref: > agency.id, null]
            title varchar
            type varchar [null, note: "Casa, Apartamento, Armazem, Loja, Terreno, ..."]
            status varchar [note: "usado,novo etc"]
            type_of_business enum("A","V") [note: "A - Alugar  V - Venda"]
            furnished  enum("yes","no") [note: "Mobilada? Não"]
            country varchar
            address varchar
            city varchar
            province varchar
            location array [null, note: "[latitude & longitude]"]
            length decimal [null, note: "comprimento"]  
            width decimal [null, note: "largura"]
            description text
            room int [null]
            bathroom int [null]
            useful_sand decimal
            announces bool [default: false]
            favorite bool [default: false]
            deleted bool [default: false]
            time_unit enum("second","minute","hours","day","week","month","year") [null]
            minimum_time decimal [null]
            price decimal
        }
    */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|string|in:Praia,Armazem,Loja,Terreno,Residencial,Escritorios,Quartos',
            'agency_id' => 'nullable|exists:agencies,id',
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'type_of_business' => 'required|in:A,V',
            'furnished' => 'nullable|in:yes,no',
            'country' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'location' => 'nullable',
            'length' => 'required_if:category_id,Terreno|numeric|min:0',
            'width' => 'required_if:category_id,Terreno|numeric|min:0',
            'description' => 'nullable|string',
            'room' => 'nullable|integer|min:0',
            'bathroom' => 'nullable|integer|min:0',
            'contact'=>'required',
            'photo'=> 'required|array',
            'photo.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'offer' => 'nullable',
            'time_unit' => 'required_if:type_of_business,A|in:second,minute,hours,day,week,month,year',
            'minimum_time' => 'required_if:type_of_business,A|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ];
    }
    protected function passedValidation()
    {
        if ($this->has('location') && is_string($this->location)) {
            $this->merge([
                'location' => json_decode($this->location, true),
            ]);
        }

        if ($this->has('offer') && is_string($this->offer)) {
            $this->merge([
                'offer' => json_decode($this->offer, true),
            ]);
        }
        if ($this->has('contact') && is_string($this->contact)) {
            $this->merge([
                'contact' => json_decode($this->contact, true),
            ]);
        }
    }
    
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => Auth::id()
        ]);
        if ($this->has('location') && is_string($this->location)) {
            $this->merge([
                'location' => json_decode($this->location, true),
            ]);
        }

        if ($this->has('offer') && is_string($this->offer)) {
            $this->merge([
                'offer' => json_decode($this->offer, true),
            ]);
        }
        if ($this->has('contact') && is_string($this->contact)) {
            $this->merge([
                'contact' => json_decode($this->contact, true),
            ]);
        }
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
