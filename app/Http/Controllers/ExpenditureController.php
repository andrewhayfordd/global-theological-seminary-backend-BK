<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\ExpensesResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ExpenditureResource;
use App\Http\Resources\ExpenditureCategoryResource;

class ExpenditureController extends Controller
{
    public function fetchCategory($schoolCode)
    {
        $data = DB::table("tblexp_cat")
            ->where("school_code", $schoolCode)
            ->where("deleted", 0)
            ->get();

        return response()->json([
            "data" => ExpenditureCategoryResource::collection($data)
        ]);
    }

    public function index($schoolCode)
    {
        $data = DB::table("tblexpenses")->select(
            "tblexpenses.*",
            "tblexp_cat.name",
            "tblexp_cat.code",
            "tblbranch.branch_desc"
        )
            ->join("tblexp_cat", "tblexpenses.exp_cat", "tblexp_cat.code")
            ->join("tblbranch", "tblexpenses.branch", "tblbranch.branch_code",)
            ->when(request("acyear"), function ($query) {
                return $query->where("acyear",request("acyear"));
            })
            ->when(request("branch"), function ($query) {
                return $query->where("branch",request("branch"));
            })
            ->when(request("acterm"), function ($query) {
                return $query->where("acterm",request("acterm"));
            })
            ->when(request("trans_type"), function ($query) {
                return $query->where("trans_type", request("trans_type"));
            })
            ->where("tblexp_cat.school_code", $schoolCode)
            ->where("tblexpenses.school_code", $schoolCode)
            ->where("tblexp_cat.deleted", 0)
            ->where("tblexpenses.deleted", 0)
            ->get();

        return response()->json([
            "data" => ExpenditureResource::collection($data)
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "acterm" => "required",
                "acyear" => "required",
                "amount" => "required",
                "exp_type" => "required",
                "trans_type" => "required",
                "branch" => ["required"],
            ],
            [
                "acterm.required" => "Semester field is required",
                "acyear.required" => "Academic year field is required",
                "amount.required" => "Amount field is required",
                "exp_type.required" => "Expense type field is required",
                "trans_type.required" => "Transaction type field is required",
                "branch.required" => "Branch field is required",

            ]
        );


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "<h5>Failed to a add expense</h5>" . join("<br> ", $validator->errors()->all()),
                "error" => [
                    "msg" => "Some required fields are missing: " . join(" ", $validator->errors()->all()),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }


        // Make sure that the supplied school code exists and belongs to a
        // school in the system
        $school = School::where("school_code", $request->school_code)->where("deleted", "0")->first();
        if (empty($school)) {
            return response()->json([
                "ok" => false,
                "msg" => "<h5>Failed to add expense</h5> Could not determine the school this student belongs to",
                "error" => [
                    "msg" => "The supplied school code was not found in the system: {$request->school_code}",
                    "fix" => "Ensure that the supplied school code is correct"
                ]
            ]);
        }
        if (!in_array($request->trans_type, ['cash', 'bank', 'cheque'])) {
            return response()->json([
                "ok" => false,
                "msg" => "<h5>Failed to add expense</h5> Transaction type is invalid",
            ]);
        }
        try {


            switch ($request->trans_type) {
                case 'bank':
                    DB::table("tblexpenses")->insert([
                        "school_code" => $request->school_code,
                        "acyear" => $request->acyear,
                        "acterm" => $request->acterm,
                        "branch" => $request->branch,
                        "exp_cat" => $request->exp_type,
                        "trans_type" => $request->trans_type,
                        "trans_date" => date("Y-m-d"),
                        "bank" => $request->bank_name,
                        "cheque_no" => null,
                        "account_no" => $request->bank_account_number,
                        "amount" => $request->amount,
                        "notes" => $request->note,
                        "cheque_bank" => null,
                        "payer" => $request->payer_name,
                        "account_holder" => $request->account_holder,
                        "bank_branch" => $request->bank_branch,
                        "deleted" => "0",
                        "createdate" => date("Y-m-d"),
                        "createuser" => $request->createuser,
                    ]);
                    break;
                case "cheque":
                    DB::table("tblexpenses")->insert([
                        "school_code" => $request->school_code,
                        "acyear" => $request->acyear,
                        "acterm" => $request->acterm,
                        "branch" => $request->branch,
                        "exp_cat" => $request->exp_type,
                        "trans_type" => $request->trans_type,
                        "trans_date" => date("Y-m-d"),
                        "bank" => null,
                        "cheque_no" => $request->cheque_no,
                        "account_no" => null,
                        "amount" => $request->amount,
                        "notes" => $request->note,
                        "cheque_bank" => $request->cheque_bank,
                        "payer" => null,
                        "account_holder" => null,
                        "bank_branch" => null,
                        "deleted" => "0",
                        "createdate" => date("Y-m-d"),
                        "createuser" => $request->createuser,
                    ]);
                    break;
                default:
                    DB::table("tblexpenses")->insert([
                        "school_code" => $request->school_code,
                        "acyear" => $request->acyear,
                        "acterm" => $request->acterm,
                        "exp_cat" => $request->exp_type,
                        "branch" => $request->branch,
                        "trans_type" => $request->trans_type,
                        "trans_date" => date("Y-m-d"),
                        "bank" => null,
                        "cheque_no" => null,
                        "account_no" => null,
                        "amount" => $request->amount,
                        "notes" => $request->note,
                        "cheque_bank" => null,
                        "payer" => null,
                        "account_holder" => null,
                        "bank_branch" => null,
                        "deleted" => "0",
                        "createdate" => date("Y-m-d"),
                        "createuser" => $request->createuser,
                    ]);
                    break;
            }


            return response()->json([
                "ok" => true,
                "msg" => "New expense added successfully"
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Adding failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add expense. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "exp_id" => "required",
                "acterm" => "required",
                "acyear" => "required",
                "amount" => "required",
                "exp_type" => "required",
                "trans_type" => "required",
                "branch" => ["required"],
            ],
            [
                "acterm.required" => "Semester field is required",
                "acyear.required" => "Academic year field is required",
                "amount.required" => "Amount field is required",
                "exp_type.required" => "Expense type field is required",
                "trans_type.required" => "Transaction type field is required",
                "branch.required" => "Branch field is required",
                "exp_id.required" => "Expenditure id  is  required",
            ]
        );


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "<h5>Failed to a add expense</h5>" . join("<br> ", $validator->errors()->all()),
                "error" => [
                    "msg" => "Some required fields are missing: " . join(" ", $validator->errors()->all()),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }


        // Make sure that the supplied school code exists and belongs to a
        // school in the system
        $school = School::where("school_code", $request->school_code)->where("deleted", "0")->first();
        if (empty($school)) {
            return response()->json([
                "ok" => false,
                "msg" => "<h5>Failed to add expense</h5> Could not determine the school this student belongs to",
                "error" => [
                    "msg" => "The supplied school code was not found in the system: {$request->school_code}",
                    "fix" => "Ensure that the supplied school code is correct"
                ]
            ]);
        }
        if (!in_array($request->trans_type, ['cash', 'bank', 'cheque'])) {
            return response()->json([
                "ok" => false,
                "msg" => "<h5>Failed to add expense</h5> Transaction type is invalid",
            ]);
        }
        try {


            switch ($request->trans_type) {
                case 'bank':
                    DB::table("tblexpenses")->where("id", $request->exp_id)->update([
                        "acyear" => $request->acyear,
                        "acterm" => $request->acterm,
                        "branch" => $request->branch,
                        "exp_cat" => $request->exp_type,
                        "trans_type" => $request->trans_type,
                        "trans_date" => date("Y-m-d"),
                        "bank" => $request->bank_name,
                        "cheque_no" => null,
                        "account_no" => $request->bank_account_number,
                        "amount" => $request->amount,
                        "notes" => $request->note,
                        "cheque_bank" => null,
                        "payer" => $request->payer_name,
                        "account_holder" => $request->account_holder,
                        "bank_branch" => $request->bank_branch,
                        "modifydate" => date("Y-m-d"),
                        "modifyuser" => $request->createuser,
                    ]);
                    break;
                case "cheque":
                    DB::table("tblexpenses")->where("id", $request->exp_id)->update([
                        "school_code" => $request->school_code,
                        "acyear" => $request->acyear,
                        "acterm" => $request->acterm,
                        "branch" => $request->branch,
                        "exp_cat" => $request->exp_type,
                        "trans_type" => $request->trans_type,
                        "trans_date" => date("Y-m-d"),
                        "bank" => null,
                        "cheque_no" => $request->cheque_no,
                        "account_no" => null,
                        "amount" => $request->amount,
                        "notes" => $request->note,
                        "cheque_bank" => $request->cheque_bank,
                        "payer" => null,
                        "account_holder" => null,
                        "bank_branch" => null,
                        "modifydate" => date("Y-m-d"),
                        "modifyuser" => $request->createuser,
                    ]);
                    break;
                default:
                    DB::table("tblexpenses")->where("id", $request->exp_id)->update([
                        "acyear" => $request->acyear,
                        "acterm" => $request->acterm,
                        "exp_cat" => $request->exp_type,
                        "branch" => $request->branch,
                        "trans_type" => $request->trans_type,
                        "bank" => null,
                        "cheque_no" => null,
                        "account_no" => null,
                        "amount" => $request->amount,
                        "notes" => $request->note,
                        "cheque_bank" => null,
                        "payer" => null,
                        "account_holder" => null,
                        "bank_branch" => null,
                        "modifydate" => date("Y-m-d"),
                        "modifyuser" => $request->createuser,
                    ]);
                    break;
            }


            return response()->json([
                "ok" => true,
                "msg" => "New changes saved successfully"
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Updating failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add expense. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function addCategory(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "category" => ['required', 'string', Rule::unique("tblexp_cat", 'name')],
                'createuser' => ['required', Rule::exists('tbluser', "userid")->where(function ($query) {
                    return $query->where('deleted', 0);
                })],
                'school_code'  => ['required'],
            ],
            [
                "createuser.required" => "User Id not found",
                'createuser.exists' => "You are not authorized!",
                'school_code.required' => "School code not found",
                "category.unique" => "Category name already exists",

            ]
        );


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "<h5>Failed to a add new category</h5>" . join("<br> ", $validator->errors()->all()),
                "error" => [
                    "msg" => "Some required fields are missing: " . join(" ", $validator->errors()->all()),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }


        // Make sure that the supplied school code exists and belongs to a
        // school in the system
        $school = School::where("school_code", $request->school_code)->where("deleted", "0")->first();
        if (empty($school)) {
            return response()->json([
                "ok" => false,
                "msg" => "<h5>Failed to add new category</h5> Could not determine the school this student belongs to",
                "error" => [
                    "msg" => "The supplied school code was not found in the system: {$request->school_code}",
                    "fix" => "Ensure that the supplied school code is correct"
                ]
            ]);
        }

        try {
            DB::table("tblexp_cat")->insert([
                "school_code" => $request->school_code,
                "name" => $request->category,
                "code" => "EXP" . strtoupper(bin2hex(random_bytes(4))),
                "deleted" => "0",
                "createdate" => date("Y-m-d"),
                "createuser" => $request->createuser,
            ]);

            return response()->json([
                "ok" => true,
                "msg" => "New category added successfully"
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "<h5>Adding failed.</h5> An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add expenditure. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function updateCategory(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "category" => ['required', 'string', Rule::unique("tblexp_cat", 'name')],
                'createuser' => ['required', Rule::exists('tbluser', "userid")->where(function ($query) {
                    return $query->where('deleted', 0);
                })],
                "code" => ['required', Rule::exists('tblexp_cat', "code")->where(function ($query) {
                    return $query->where('deleted', 0);
                })]
            ],
            [
                "createuser.required" => "User Id not found",
                'createuser.exists' => "You are not authorized!",
                "category.unique" => "Category name already exists",
                "code.required" => "Category Id not found",
                'code.exists' => "Category Id is not valid!",

            ]
        );


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "<h5>Failed to update category</h5>" . join("<br> ", $validator->errors()->all()),
                "error" => [
                    "msg" => "Some required fields are missing: " . join(" ", $validator->errors()->all()),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }



        // Make sure that the supplied school code exists and belongs to a
        // school in the system
        $school = School::where("school_code", $request->school_code)->where("deleted", "0")->first();
        if (empty($school)) {
            return response()->json([
                "ok" => false,
                "msg" => "<h5>Failed to update new category</h5> Could not determine the school this student belongs to",
                "error" => [
                    "msg" => "The supplied school code was not found in the system: {$request->school_code}",
                    "fix" => "Ensure that the supplied school code is correct"
                ]
            ]);
        }

        try {
            DB::table("tblexp_cat")->where("code", $request->code)->update([
                "name" => $request->category,
                "deleted" => "0",
                "modifydate" => date("Y-m-d"),
                "modifyuser" => $request->createuser,
            ]);


            return response()->json([
                "ok" => true,
                "msg" => "Category updated successfully"
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "<h5>Updating failed.</h5> An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add expenditure. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function destroy($id, $schoolCode)
    {
        try {
            $expense = DB::table("tblexpenses")->where("school_code", $schoolCode)->where('id', $id);
            if ($expense->doesntExist()) {

                return response()->json([
                    "ok" => false,
                    "msg" => "Failed to delete, Could not find matching records",
                ]);
            }
            $expense->update([
                "deleted" => 1
            ]);

            return response()->json([
                "ok" => true,
                "msg" => "Expense has been deleted",
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Failed to delete, An internal error occurred",
            ]);
        }
    }



    public function DeleteCategory($code)
    {
        if (DB::table("tblexp_cat")->where('code', $code)->doesntExist()) {
            return response()->json([
                'ok' => false,
                'msg' => "<h5>Failed to delete category</5> Category record was not found"
            ]);
        }

        DB::table("tblexp_cat")->where('code', $code)
            ->update([
                "deleted" => 1
            ]);

        return response()->json([
            "ok" => true,
            'msg' => "<h5>Category deleted successfully</5>"
        ]);
    }
}
