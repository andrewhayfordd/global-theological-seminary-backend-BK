<?php

namespace App\Http\Requests;

use App\Models\Branch;
use App\Models\InventoryItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class InventoryBranchCreateRequest extends FormRequest
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
            "item_code"  => ['required', Rule::exists(InventoryItem::class)->where(fn ($q) => $q->where('deleted', 0)),],
            "school_code" => ['required'],
            "branch_code" => ['required', Rule::exists(Branch::class,)->where(fn ($q) => $q->where('deleted', 0))],
            "item_quantity" => ['required', "numeric"],
            "supply_date" => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            "item_code.required" => "inventory item is required",
            "item_code.exists" => "inventory item is invalid or couldnt be found",
            "school_code" => "School id missing",
            "item_quantity.required" => "item quantity is required",
            "item_quantity.numeric" => "item quantity is invalid",
            "supply_date.required" => "supply date is required",
            "supply_date.date" => "supply date  has invalid format",
            "branch_code.required" => "branch is required",
            "branch_code.exists" => "branch is invalid",
        ];
    }

    protected function failedValidation(Validator $validator): HttpResponseException
    {
        throw new HttpResponseException(response()->json([
            'ok'  => false,
            'msg' =>  "Creating branch inventory failed",
            'data' => [],
            'errors' => $validator->errors(),
            'errors_all' => $validator->errors()->all(),
        ], 422));
    }
}
