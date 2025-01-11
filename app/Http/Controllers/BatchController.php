<?php

namespace App\Http\Controllers;

use App\Http\Resources\BatchListResource;
use App\Http\Resources\BatchResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($school_code)
    {
        $batch = BatchResource::collection(
            DB::table('tblbatch')
                ->where('school_code', $school_code)
                ->where('deleted', '0')
                ->get()
        );
        return response()->json(['data' => $batch]);
    }

    public function fetchStudent($schoolCode, $batchCode)
    {
        $students = DB::table("tblbatch")->select(
            "tblstudent.*",
            "tblprog.prog_desc",
            "tblbatch.batch_desc"
        )
            ->join("tblstudent", "tblbatch.batch_code", "tblstudent.batch")
            ->join("tblprog", "tblprog.prog_code", "tblstudent.prog")
            ->where("tblstudent.deleted", 0)
            ->where("tblprog.deleted", 0)
            ->where("tblprog.school_code", $schoolCode)
            ->where("tblstudent.school_code", $schoolCode)
            ->where("tblbatch.deleted", 0)
            ->where("tblstudent.school_code", $schoolCode)
            ->where("tblstudent.batch", $batchCode)
            ->get();

        return response()->json([
            "data" => BatchListResource::collection($students)
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
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'month' => "required",
                'year' => "required",
            ],
            [
                'month.required' => 'No month selected ',
                'year.required' => 'No year selected ',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding batch failed. " . join(". ", $validator->errors()->all()),
            ]);
        }
        try {
            $batchCode = $request->month . $request->year;

            $check = DB::table("tblbatch")->where("school_code", $request->school_code)
                ->where("deleted", 0)->where("batch_code", $batchCode)->first();
            // return $check;
            if (!empty($check)) {
                return response()->json([
                    "ok" => false,
                    "msg" => "Sorry, batch already exist."
                ]);
            }
            if (strtoupper($request->month) === "AUG") {
                $desc = "August " . $request->year;
            }
            if (strtoupper($request->month) === "SEPT") {
                $desc = "September " . $request->year;
            }
            if (strtoupper($request->month) === "OCT") {
                $desc = "October " . $request->year;
            }
            if (strtoupper($request->month) === "NOV") {
                $desc = "November " . $request->year;
            }
            if (strtoupper($request->month) === "DEC") {
                $desc = "December " . $request->year;
            }
            if (strtoupper($request->month) === "JAN") {
                $desc = "JANUARY " . $request->year;
            }
            if (strtoupper($request->month) === "FEB") {
                $desc = "February " . $request->year;
            }
            if (strtoupper($request->month) === "MAR") {
                $desc = "March " . $request->year;
            }
            if (strtoupper($request->month) === "APR") {
                $desc = "April " . $request->year;
            }
            if (strtoupper($request->month) === "JUN") {
                $desc = "June " . $request->year;
            }
            if (strtoupper($request->month) === "JUL") {
                $desc = "July " . $request->year;
            }
            if (strtoupper($request->month) === "MAY") {
                $desc = "May " . $request->year;
            }
            $transactionResult = DB::transaction(function () use ($request, $desc, $batchCode) {
                DB::table('tblbatch')->insert([
                    'transid' => strtoupper(bin2hex(random_bytes(5))),
                    'school_code' => $request->school_code,
                    'branch_code' => null,
                    'batch_code' => $batchCode,
                    'batch_desc' => $desc,
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
            Log::error("Failed adding batch: " . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Adding batch failed!",
                "error" => [
                    "msg" => $e->__toString(),
                    "err_msg" => $e->getMessage(),
                    "fix" => "Please complete all required fields",
                ]
            ]);
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
        $validator = Validator::make(
            $request->all(),
            [
                'desc' => "required",
                'id' => "required",
            ],
            [
                'desc.required' => 'No batch description provided ',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Update batch failed. " . join(". ", $validator->errors()->all()),
            ]);
        }
        try {
            $transactionResult = DB::transaction(function () use ($request) {
                DB::table('tblbatch')->where("batch_code", $request->id)->update([
                    'batch_desc' => $request->desc,
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
            Log::error("Failed Update batch: " . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Update batch failed!",
                "error" => [
                    "msg" => $e->__toString(),
                    "err_msg" => $e->getMessage(),
                    "fix" => "Please complete all required fields",
                ]
            ]);
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
        $dept = DB::table('tblbatch')
            ->where('batch_code', $id)->first();
        if (empty($dept)) {
            return response()->json([
                "ok" => false,
                "msg" => "Unknown code supplied ",
            ]);
        }

        $updated = DB::table('tblbatch')
            ->where('batch_code', $id)->update([
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
}
