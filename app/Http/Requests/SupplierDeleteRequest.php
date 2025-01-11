<?php

namespace App\Http\Requests;

use App\Models\Supplier;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class SupplierDeleteRequest extends FormRequest
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
            'transid' => ['required', Rule::exists(Supplier::class)->where(fn ($query) => $query->where('deleted', 0))],
            'createuser' => ['sometimes', 'string'],
        ];
    }


    public function messages(): array
    {
        return [
            'transid.required' => "Id of the supplier is required",
            'transid.exists' => "Id of the supplier is invalid",
            'createuser' => "Your id is missing",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'ok' => false,
                'msg' => "Deleting supplier details failed",
                'errors_all' => $validator->errors()->all(),
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
