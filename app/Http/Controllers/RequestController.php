<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\ReqCatResource;
use App\Http\Resources\RequestResource;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{
    public function index($schoolCode)
    {
        $data = DB::table("tblrequisition")
        ->select("tblrequisition.*", "tblitem.item_desc","tblsemester.sem_desc")
            ->join("tblitem", "tblrequisition.item_code", "tblitem.item_code")
            ->join("tblsemester", "tblsemester.sem_code", "tblrequisition.semester")
            ->where("tblrequisition.school_code", $schoolCode)
            ->where("tblitem.school_code", $schoolCode)
            ->where("tblitem.deleted", 0)
            ->where("tblsemester.deleted", 0)
            ->where("tblrequisition.deleted", 0)
            ->get();
        // return response()->json([
        //     $data
        // ]);
        return response()->json([
            "data" => RequestResource::collection($data)
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "branch" => "required",
                "semester" => "required",
                "item" => "required",
                "req" => "required",
                "date" => "required",
                "quantity" => "required",
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding failed. Please complete all required fields",
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
                "msg" => "Registration failed. Could not determine the school this student belongs to",
                "error" => [
                    "msg" => "The supplied school code was not found in the system: {$request->school_code}",
                    "fix" => "Ensure that the supplied school code is correct"
                ]
            ]);
        }

        try {
            DB::table("tblrequisition")->insert([
                "transid" => strtoupper(bin2hex(random_bytes(5))),
                "school_code" => $request->school_code,
                "semester" => $request->semester,
                "branch" => $request->branch,
                "item_code" => $request->item,
                "requestor" => $request->req,
                "requested_date" => date("Y-m-d"),
                "requested_quantity" => $request->quantity,
                "deleted" => "0",
                "status" => "0",
                "createdate" => date("Y-m-d"),
                "createuser" => $request->createuser,
            ]);


            return response()->json([
                "ok" => true,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add expenditure. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function destroy($id, $schoolCode)
    {

        DB::table("tblrequisition")->where("school_code", $schoolCode)->where('transid', $id)
            ->update([
                "deleted" => 1
            ]);

        return response()->json([
            "ok" => true,
        ]);
    }

    public function fetchCategory($schoolCode)
    {
        $data = DB::table("tblitem")->where("school_code", $schoolCode)->where("deleted", 0)->get();
        return response()->json([
            "data" => ReqCatResource::collection($data)
        ]);
    }

    public function addItem(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "desc" => "required",
            ]
        );


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding failed. Please complete all required fields",
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
                "msg" => "Registration failed. Could not determine the school this student belongs to",
                "error" => [
                    "msg" => "The supplied school code was not found in the system: {$request->school_code}",
                    "fix" => "Ensure that the supplied school code is correct"
                ]
            ]);
        }

        try {
            DB::table("tblitem")->insert([
                "transid" => "TRANS" . strtoupper(bin2hex(random_bytes(5))),
                "school_code" => $request->school_code,
                "item_desc" => $request->desc,
                "item_code" => "ITM" . strtoupper(bin2hex(random_bytes(3))),
                "deleted" => "0",
                "createdate" => date("Y-m-d"),
                "createuser" => $request->createuser,
            ]);


            return response()->json([
                "ok" => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add expenditure. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function updateItem(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "desc" => "required",
                "code" => "required",
            ]
        );


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Update failed. Please complete all required fields",
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
                "msg" => "Registration failed. Could not determine the school this student belongs to",
                "error" => [
                    "msg" => "The supplied school code was not found in the system: {$request->school_code}",
                    "fix" => "Ensure that the supplied school code is correct"
                ]
            ]);
        }

        try {
            DB::table("tblitem")->where("item_code", $request->code)->update([
                "item_desc" => $request->desc,
                "deleted" => "0",
                "modifydate" => date("Y-m-d"),
                "modifyuser" => $request->createuser,
            ]);


            return response()->json([
                "ok" => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Updating failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add expenditure. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function deleteCat($id)
    {

        DB::table("tblitem")->where('item_code', $id)
            ->update([
                "deleted" => 1
            ]);

        return response()->json([
            "ok" => true,
        ]);
    }

    public function admin_services(Request $request){
        $validator = Validator::make($request->all(),[
            // 'school_name' => 'required',
            'service_name' => 'required',
            'service_cost' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'msg' => "Adding service failed." . join(" ", $validator->errors()->all())
            ]);
        }

        try {
            // $school_code = DB::table('tblschool')
            // ->where('school_name', $request->school_name)
            // ->first();

            DB::table('tblservices')->insert([
                'transid' => 'TRANS'. strtoupper(bin2hex(random_bytes(5))),
                // 'school_code' => $request->school_name,
                'service_code' => 'SER' . strtoupper(bin2hex(random_bytes(3))),
                'service_name' => $request->service_name,
                'service_cost' => $request->service_cost,
                'deleted' => 0
            ]);

            return response()->json([
                'ok' => true,
                'msg' => "Service added successfully"
            ]);
        } catch (\Exception $e) {
            Log::error("Adding service failed: " . $e->getMessage());
            return response()->json([
                'ok' => false,
                'msg' => "Adding service failed. An internal error occured. If this continues, please contact an administrator.",
                'error' => [
                    'msg' => "Adding service failed . {$e->getMessage()}",
                    'fix' => "Check errors for clues"
                ]
            ]);
        }
    }

    public function updateService(Request $request){
        $validator = Validator::make($request->all(),[
            'status' => 'required|in:pending,processing,ready,completed'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'msg' => 'Updating service status failed.' . join(" ", $validator->errors()->all())
            ]);
        }

        try {
            DB::table('tblservice_student')
            ->where('id', $request->id)
            ->update(['status' => $request->status]);
            return response()->json([
                'ok' => true,
                'msg' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error("Updating service status failed: " . $e->getMessage());
            return response()->json([
                'ok' => false,
                'msg' => "Updating service status failed. An internal error occured. If this continues, please contact an administrator.",
                'error' => [
                    'msg' => "Updating service failed . {$e->getMessage()}",
                    'fix' => "Check errors for clues"
                ]
            ]);
        }
    }

    

    
}
