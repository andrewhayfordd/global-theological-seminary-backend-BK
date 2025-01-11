<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function index($schoolCode)
    {
        $data = DB::table("tblapplications")
            ->select(
                "tblapplications.*",
                "tblbatch.batch_desc",
                "tblprog.prog_desc",
                "tblsession.session_desc"
            )
            ->join("tblprog", "tblprog.prog_code", "tblapplications.prog")
            ->join("tblbatch", "tblbatch.batch_code", "tblapplications.batch")
            ->join("tblsession", "tblsession.session_code", "tblapplications.session")
            ->where("tblapplications.school_code", $schoolCode)
            ->where("tblprog.school_code", $schoolCode)
            ->where("tblapplications.deleted", 0)
            ->where("tblprog.deleted", 0)
            ->get();

        return response()->json([
            "data" => ApplicationResource::collection($data)
        ]);
    }

    public function destroy($id)
    {
        $dept = DB::table("tblapplications")
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
}
