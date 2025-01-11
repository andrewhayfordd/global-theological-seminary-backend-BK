<?php

namespace App\Http\Requests;

use App\Models\Supplier;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class SupplierUpdateRequest extends FormRequest
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
            'transid'=> ["required",Rule::exists(Supplier::class)->where(fn($query) => $query->where('deleted',0))],
            'name' => ["required","string"],
            'phone' =>["required","numeric"],
            'email' => ["nullable","email"],
            'address' => ["nullable","string"],
            'createuser' => ["sometimes", "string"],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => "Name of the supplier is required",
            'transid.required' => "Id of the supplier is required",
            'transid.exists' => "Id of the supplier is invalid",
            'phone.required' => "Phone of the supplier is required",
            'phone.numeric' => "Phone number is invalid",
            'email.email' => "The email provided is invalid",
            'createuser' => "Your id is missing",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
      throw new HttpResponseException(
        response()->json([
            'ok'  => false,
            'msg' =>  "Updating supplier details failed",
            'data' => [],
            'errors_all' => $validator->errors()->all(),
            'errors' => $validator->errors(),
        ],422)
      );   
    }
}
