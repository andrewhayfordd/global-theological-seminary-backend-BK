<?php

namespace App\Http\Requests;

use App\Models\Supplier;
use App\Models\SupplierContactPosition;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class SupplierMemberCreateRequest extends FormRequest
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
            'fname' => ['required', 'string',],
            'lname' => ['required', 'string'],
            'phone' => ['required', 'numeric'],
            'supplier_code' => ['required', Rule::exists(Supplier::class)],
            'position_code' => ['nullable', Rule::exists(SupplierContactPosition::class)],
            'createuser' => ['sometimes']
        ];
    }

    public function messages(): array
    {
        return [
            'fname.required' => 'first name is required',
            'lname.required' => 'last name is required',
            'phone.required' => 'phone number is required',
            'phone.numeric' => "phone number is invalid",
            'supplier_code.required' => 'supplier is required',
            'supplier.exists' => 'supplier is invalid',
            'position_code.exists' => 'position is invalid',
            'createuser.sometimes' => 'You are not allowed to perform this operation, id is missing',
        ];
    }

    protected function failedValidation(Validator $validator): HttpResponseException
    {
        throw new HttpResponseException(
            response()->json([
                'ok'  => false,
                'msg' =>  "Creating member failed",
                'data' => [],
                'errors_all' => $validator->errors()->all(),
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
