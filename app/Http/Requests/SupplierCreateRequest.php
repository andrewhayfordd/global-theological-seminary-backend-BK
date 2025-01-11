<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class SupplierCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ["required","string"],
            'phone' =>["required","numeric"],
            'email' => ["nullable","email"],
            'address' => ["nullable","string"],
            'createuser' => ["sometimes", "string"],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "Name of the supplier is required",
            'phone.required' => "Phone of the supplier is required",
            'phone.numeric' => "Phone number is invalid",
            'email.email' => "The email provided is invalid",
            'createuser' => "Your id is missing",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
         throw new HttpResponseException(response()->json([ 
            'ok'  => false,
            'msg' =>  "Creating supplier failed",
            'data' => [],
            'errors' => $validator->errors(),
            'errors_all' => $validator->errors()->all(),
         ], 422));

        
    }
}
