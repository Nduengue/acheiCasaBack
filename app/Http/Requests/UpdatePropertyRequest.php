<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePropertyRequest extends FormRequest
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
            'category_id' => 'nullable|string|in:Praia,Armazem,Loja,Terreno,Residencial,Escritorios,Quartos',
            'title' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'type_of_business' => 'nullable|in:A,V',
            'furnished' => 'nullable|in:yes,no',
            'country' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'location' => 'nullable',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'room' => 'nullable|integer|min:0',
            'bathroom' => 'nullable|integer|min:0',
            'contact'=>'nullable',
            'photo'=> 'nullable|array',
            'photo.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'offer' => 'nullable',
            'time_unit' => 'nullable|in:second,minute,hours,day,week,month,year',
            'minimum_time' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
        ];
    }
    
    protected function prepareForValidation()
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
        $property = Property::where('id', $this->route('property'))->first();
        if ($property) {
            $this->merge([
                'category_id' =>$this->category_id??$property->category_id,
                'title' =>$this->title??$property->title,
                'type' => $this->type??$property->type,
                'status' => $this->status??$property->status,
                'type_of_business' => $this->type_of_business??$property->type_of_business,
                'furnished' => $this->furnished??$property->furnished,
                'country' => $this->country??$property->country,
                'address' => $this->address??$property->address,
                'city' => $this->city??$property->city,
                'province' =>$this->province??$property->province,
                'location' => json_encode($this->location??$property->location),
                'description' => $this->description??$property->description,
                'room' => $this->room??$property->room,
                'bathroom' => $this->bathroom??$property->bathroom,
                'useful_sand' => $this->useful_sand??$property->useful_sand,
                'contact'=>json_encode($this->contact??$property->contact),
                'photo'=>json_encode($this->photo??$property->photo),
                'offer'=>json_encode($this->offer??$property->offer),
                'price' =>$this->price??$property->price 
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
