<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassBreakdownResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashbaordController extends Controller
{
    public function fetchTotalStudentByProg($schoolCode)
    {
        $total = DB::table("tblstudent")
            ->selectRaw("count(tblstudent.prog) as total_grade, tblprog.prog_desc")
            ->join("tblprog", "tblstudent.prog", "tblprog.prog_code")
            ->where("tblstudent.school_code", $schoolCode)
            ->where("tblprog.school_code", $schoolCode)
            ->where("tblprog.deleted", "0")
            ->where("tblstudent.deleted", "0")
            ->whereNotNull("tblstudent.prog")
            ->groupBy("tblprog.prog_desc")
            ->get();

        return response()->json([
            "data" => $total,
        ]);
    }

    public function fetchClassBreakdown($schoolCode)
    {
        $breakdowns = DB::table("tblstudent")
            ->join('tblprog', 'tblprog.prog_code', '=', 'tblstudent.prog')
            ->select([
                "tblprog.prog_desc",
                DB::raw("sum(case when tblstudent.`gender` = 'M' then 1 else 0 end) as 'males'"),
                DB::raw("sum(case when tblstudent.`gender` = 'F' then 1 else 0 end) as 'females'"),
            ])
            ->where("tblstudent.deleted", "0")
            ->where("tblprog.deleted", "0")
            ->where("tblstudent.school_code", $schoolCode)
            ->where("tblprog.school_code", $schoolCode)
            ->groupBy("tblprog.prog_desc")
            ->orderBy("tblprog.prog_desc")
            ->get();

        return response()->json([
            "ok" => true,
            "msg" => "Request successful",
            "data" => ClassBreakdownResource::collection($breakdowns),
        ]);
    }
}
