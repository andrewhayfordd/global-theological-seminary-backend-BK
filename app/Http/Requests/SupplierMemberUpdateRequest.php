<?php

namespace App\Http\Requests;

use App\Models\Supplier;
use App\Models\SupplierContactPosition;
use App\Models\SupplierMember;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SupplierMemberUpdateRequest extends FormRequest
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
            'transid' => ['required', Rule::exists(SupplierMember::class)->where(fn ($query) => $query->where('deleted', 0))],
            'fname' => ['required', 'string',],
            'lname' => ['required', 'string'],
            'phone' => ['required', 'numeric'],
            'supplier_code' => ['required', Rule::exists(Supplier::class,)->where(fn ($query) => $query->where('deleted', 0))],
            'position_code' => ['nullable', Rule::exists(SupplierContactPosition::class)],
            'createuser' => ['sometimes'],
        ];
    }

    public function messages()
    {
        return [
            'transid.required' => 'supplier member id is missing',
            'transid.exists' => 'supplier member id is invalid',
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
                'msg' =>  "Updating supplier member details failed",
                'data' => [],
                'errors' => $validator->errors(),
                'errors_all' => $validator->errors()->all(),
            ], 422)
        );
    }
}
