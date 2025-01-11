<?php

namespace App\Http\Requests;

use App\Models\InventoryStore;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InventoryStoreDeleteRequest extends FormRequest
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
            "transid" => ["required", Rule::exists(InventoryStore::class)->where(fn($q) => $q->where('deleted',0))],
        ];
    }

    public function messages()
    {
        return [
            'transid.required' => 'inventory id is required',
            'transid.exists' => 'inventory id is invalid or doesnt match the records',
        ];
    }

    protected function failedValidation(Validator $validator): HttpResponseException
    {
        throw new HttpResponseException(
            response()->json([
                'ok'  => false,
                'msg' =>  "Deleting inventory failed",
                'data' => [],
                'errors' => $validator->errors(),
                'errors_all' => $validator->errors()->all(),
            ], 422)
        );
    }
}
