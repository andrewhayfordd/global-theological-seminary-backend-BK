<?php

namespace App\Http\Controllers;

use App\Http\Resources\managecoursesResourcecontroller;
use App\Http\Resources\ProgramListResource;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use App\Models\School;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProgrammesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($schoolCode)
    {
        $data = Program::select(
            "tblprog.*",
            "tblprog_duration.dur_desc",
            "tblprog_duration.dur_code",
            "tblprog_type.prog_type_code",
            "tblprog_type.prog_type_desc"
        )
            ->join("tblprog_duration", "tblprog_duration.dur_code", "tblprog.prog_duration")
            ->join("tblprog_type", "tblprog_type.prog_type_code", "tblprog.prog_type")
            ->where("tblprog.school_code", $schoolCode)
            ->where("tblprog.deleted", 0)
            ->where("tblprog_duration.deleted", 0)
            ->where("tblprog_type.deleted", 0)
            ->orderByDesc("tblprog.createdate")->get();

        return response()->json([
            "data" => ProgramResource::collection($data)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "prog_desc" => "required",
            "prog_code" => "required|unique:tblprog,prog_code",
        ], [

            "prog_desc.required" => "No program description supplied",
            "prog_code.unique" => "Program code already exist",
            "prog_code.required" => "No program code supplied",
        ]);


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding program failed: " . join(" ", $validator->errors()->all()),
                "error" => [
                    "msg" => "Some required fields are missing: " . join(" ", $validator->errors()->all()),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }

        $program = DB::table("tblprog")->where("deleted", 0)
            ->where("school_code", $request->school_code)
            ->where("prog_code", $request->prog_code)->first();

        if (!empty($program)) {
            return response()->json([
                "ok" => false,
                "msg" => "Program already exist"
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
            $transactionResult = DB::transaction(function () use ($request) {

                Program::insert([
                    "transid" => strtoupper(bin2hex(random_bytes(5))),
                    "school_code" => $request->school_code,
                    "branch_code" => "100",
                    "prog_code" => $request->prog_code,
                    "prog_desc" => $request->prog_desc,
                    "prog_duration" => $request->prog_duration,
                    "prog_type" => $request->prog_type,
                    "source" => "O",
                    "deleted" => "0",
                    "createdate" => date("Y-m-d"),
                    "createuser" => $request->createuser,
                ]);
            });

            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }

            return response()->json([
                "ok" => true,
            ], 200);
        } catch (\Exception $e) {
            Log::error("\n\Adding Program failed", [
                "errMsg" => $e->getMessage(),
                "trace" => $e->getTrace(),
                "request" => $request->all(),
            ]);
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add program. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "prog_desc" => "required",
            "prog_code" => "required",
            "id" => "required",
        ], [

            "prog_desc.required" => "No program description supplied",
            // "prog_code.unique" => "Program code already exist",
            "prog_code.required" => "No program code supplied",
        ]);


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Updating program failed: " . join(" ", $validator->errors()->all()),
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
            $transactionResult = DB::transaction(function () use ($request) {

                Program::where("transid", $request->id)->update([
                    "prog_code" => $request->prog_code,
                    "prog_desc" => $request->prog_desc,
                    "prog_duration" => $request->prog_duration,
                    "prog_type" => $request->prog_type,
                    "modifydate" => date("Y-m-d"),
                    "modifyuser" => $request->createuser,
                ]);
            });

            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }

            return response()->json([
                "ok" => true,
            ], 200);
        } catch (\Exception $e) {
            Log::error("\n\Updating Program failed", [
                "errMsg" => $e->getMessage(),
                "trace" => $e->getTrace(),
                "request" => $request->all(),
            ]);
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add program. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dept = DB::table("tblprog")
            ->where("transid", $id)->update([
                "deleted" => 1
            ]);


        if (!$dept) {
            return response()->json([
                "ok" => false,
                "msg" => "An internal error ocurred",
            ]);
        }

        return response()->json([
            "ok" => true,
        ], 200);
    }

    public function fetchProgramList($school_code, $progCode)
    {
        $dept = DB::table("tblstudent_prog")->select(
            "tblstudent.*",
            "tblprog.prog_desc"
        )
            ->join("tblstudent", "tblstudent.student_no", "tblstudent_prog.student_no")
            ->join("tblprog", "tblprog.prog_code", "tblstudent_prog.prog_code")
            ->where("tblstudent.school_code", $school_code)
            ->where("tblprog.school_code", $school_code)
            ->where("tblstudent_prog.school_code", $school_code)
            ->where("tblstudent.deleted", 0)
            ->where("tblprog.deleted", 0)
            ->where("tblstudent_prog.deleted", 0)
            ->where("tblstudent_prog.prog_code", $progCode)
            ->get();

        return response()->json([
            "data" => ProgramListResource::collection($dept)
        ]);
    }
}
