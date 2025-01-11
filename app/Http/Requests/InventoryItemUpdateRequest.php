<?php

namespace App\Http\Requests;

use App\Models\InventoryItem;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InventoryItemUpdateRequest extends FormRequest
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
            'item_code' => ['required',Rule::exists(InventoryItem::class)->where(fn($q) => $q->where('deleted',0))],
            'item_desc' => ['required','string']
        ];
    }

    public function messages(): array 
    {
        return [
            "item_desc.required" => "Item description is required" ,
            "item_desc.string" => "Item description has invalid format or value" ,
            "item_code.required" => "Item code is required",
            "item_code.exists" => 'Item code is invalid',
        ];
    }

    protected function failedValidation(Validator $validator) : HttpResponseException
    {
        throw new HttpResponseException(response()->json([ 
            'ok'  => false,
            'msg' =>  "Updating item failed",
            'data' => [],
            'errors' => $validator->errors(),
            'errors_all' => $validator->errors()->all(),
         ], 422));

    }
}
