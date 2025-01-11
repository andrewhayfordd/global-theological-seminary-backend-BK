<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmentListResource;
use App\Http\Resources\departmentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Exception;
use Illuminate\Support\Facades\Log;

class departmentcontroller extends Controller
{
    //
    public function index($school_code)
    {
        $department = departmentResource::collection(
            DB::table('tbldept')
                ->where('school_code', $school_code)
                ->where('deleted', '0')
                ->get()
        );
        return response()->json(['data' => $department]);
    }
    //adding department
    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'departmentname' => "required",
            ],
            [
                'departmentname.required' => 'No department name provided ',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding department failed. " . join(". ", $validator->errors()->all()),


            ]);
        }
        try {
            //code...
            $transactionResult = DB::transaction(function () use ($request) {

                $deptnum = DB::table("tbldept")
                    ->where('school_code', $request->school_code)
                    ->get();
                $tableCount = $deptnum->count();
                $tableCount++;
                $prefix = 'DEPT';
                $dept_code = null;

                switch (strlen($tableCount)) {
                    case 1:
                        $dept_code = $prefix . '000' . $tableCount;
                        break;
                    case 2:
                        $dept_code = $prefix . '00' . $tableCount;
                        break;
                    case 3:
                        $dept_code = $prefix . '0' . $tableCount;
                        break;
                    case 4:
                    default:
                        $dept_code = $prefix . '' . $tableCount;
                        break;
                }
                //inserting department into database
                DB::table('tbldept')->insert([
                    'transid' => strtoupper(bin2hex(random_bytes(5))),
                    'school_code' => $request->school_code,
                    'branch_code' => null,
                    'dept_code' => $dept_code,
                    'dept_desc' => $request->departmentname,
                    'dept_type' => 'AC',
                    'source' => null,
                    'import' => null,
                    'export' => null,
                    'deleted' => '0',
                    'createuser' =>  $request->school_code,
                    'createdate' => date('Y-m-d'),
                    'modifyuser' => $request->school_code,
                    'modifydate' => date('Y-m-d'),
                ]);
            });
            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }
            return response()->json([
                "ok" => true,


            ]);
        } catch (\Throwable $e) {
            Log::error("Failed adding department: " . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Adding department failed!",
                "error" => [
                    "msg" => $e->__toString(),
                    "err_msg" => $e->getMessage(),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }
    }
    //deleting department
    public function destroy($departmentcode)
    {

        $dept = DB::table('tbldept')
            ->where('dept_code', $departmentcode);
        if (empty($dept)) {
            return response()->json([
                "ok" => false,
                "msg" => "Unknown code supplied ",


            ]);
        }


        $updated = $dept->update([
            "deleted" => "1",
        ]);

        if (!$updated) {
            return response()->json([
                "ok" => false,
                "msg" => "An internal error ocurred",
            ]);
        }

        return response()->json([
            "ok" => true,
        ]);
    }
    //updating department

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "departmentupdatedname" => "required",

        ], [
            // This has our own custom error messages for each validation
            "departmentupdatedname.required" => "No department name supplied",



        ]);


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Updating departement failed. " . join(". ", $validator->errors()->all()),
                'x' => $request->all()
            ]);
        }
        $updatingDept = DB::table('tbldept')->where('dept_code', $request->id);


        if (empty($updatingDept)) {
            return response()->json([
                "ok" => false,
                "msg" => "updating department failed!",



            ]);
        }

        try {

            $transactionResult = DB::transaction(function () use ($request, $updatingDept) {

                $updatingDept->update([
                    'dept_desc' => $request->departmentupdatedname,
                ]);
            });


            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }
            return response()->json([
                "ok" => true,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed updating department: " . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Couldn't update department failed",
                "error" => [
                    "msg" => $e->__toString(),
                    "err_msg" => $e->getMessage(),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }
    }

    public function fetchDepartmentList($school_code, $deptCode)
    {
        $dept = DB::table("tblemp_details")->select(
            "tblstaff.*",
            "tbldept.dept_desc"
        )
            ->join("tblstaff", "tblstaff.staffno", "tblemp_details.staffno")
            ->join("tbldept", "tbldept.dept_code", "tblemp_details.dept_code")
            ->where("tblstaff.school_code", $school_code)
            ->where("tbldept.school_code", $school_code)
            ->where("tblemp_details.school_code", $school_code)
            ->where("tblstaff.deleted", 0)
            ->where("tbldept.deleted", 0)
            ->where("tblemp_details.deleted", 0)
            ->where("tblemp_details.dept_code", $deptCode)
            ->get();

        return response()->json([
            "data" => DepartmentListResource::collection($dept)
        ]);
    }
}
