<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateListingRequest extends FormRequest
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
            'title' => 'required',
            'maker_id' => 'required|exists:makers,id',
            'model_id' => 'required|exists:car_models,id',
            'year' => 'required',
            'registration_number' => 'required',
            'transmission' => 'required',
            'price_per_day' => 'required',
            'phone_number' => 'required',
            'images' => 'required',
        ];
    }
}
