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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
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
            'description' => 'nullable|string',
            'room' => 'nullable|integer|min:0',
            'bathroom' => 'nullable|integer|min:0',
            'contact'=>'required',
            'photo'=> 'required|array',
            'photo.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            /**
             * add validation for offer
             * offer is an array of objects
             * each object has an id that exists in the offer_options table
             */
            'offer' => 'nullable',
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
