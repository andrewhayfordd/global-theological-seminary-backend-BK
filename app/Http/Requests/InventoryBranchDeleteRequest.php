<?php

namespace App\Http\Requests;

use App\Models\InventoryBranch;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InventoryBranchDeleteRequest extends FormRequest
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
            "transid"  => ['required',Rule::exists(InventoryBranch::class)->where(fn($q) => $q->where('deleted',0)),],
        ];
    }

    public function messages(): array 
    {
        return [
            "transid.required" => "Branch inventory code is required" ,
            "transid.exists" => "Branch inventory  is invalid" ,
        ];
    }

    protected function failedValidation(Validator $validator) : HttpResponseException
    {
        throw new HttpResponseException(response()->json([ 
            'ok'  => false,
            'msg' =>  "Deletebranch inventory failed",
            'data' => [],
            'errors' => $validator->errors(),
            'errors_all' => $validator->errors()->all(),
         ], 422));

    }
}
