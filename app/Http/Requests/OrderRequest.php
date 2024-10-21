<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\UpperCase;

class OrderRequest extends FormRequest
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
            'id' => 'required|string|size:8',
            'name' => ['required', 'regex:/^[A-Za-z\s]+$/', new UpperCase()],
            'address.city' => 'required|string|max:255',
            'address.district' => 'required|string|max:255',
            'address.street' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:2000',
            'currency' => 'required|in:TWD,USD',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Id is required.',
            'id.string' => 'Id must be a valid string.',
            'id.size' => 'Id must be exactly 8 characters long.',
            'name.required' => 'Name is required.',
            'name.regex' => 'Name contains non-English characters.',
            'address.city.required' => 'Address City is required.',
            'address.district.required' => 'Address District is required.',
            'address.street.required' => 'Address Street is required.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price must be greater than 0.',
            'price.max' => 'Price is over 2000.',
            'currency.required' => 'Currency is required.',
            'currency.in' => 'Currency format is wrong.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
            ], 400)
        );
    }
}
