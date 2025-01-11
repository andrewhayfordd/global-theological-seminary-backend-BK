<?php

namespace App\Http\Requests;

use App\Models\SupplierMember;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SupplierMemberDeleteRequest extends FormRequest
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
            'createuser' => ['sometimes'],
        ];
    }

    public function messages(): array
    {
        return [
            'transid.required' => 'supplier member id is missing',
            'transid.exists' => 'supplier member id is invalid',
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
