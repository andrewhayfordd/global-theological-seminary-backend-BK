<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\NoticeResource;
use App\Http\Resources\User;
use App\Models\Notice;
use App\Models\User as ModelsUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    public function index(Request $request, $usertype,$schoolCode)
    {
        $usertype = strtoupper($usertype);
        $today = Carbon::today("Africa/Accra")->format("Y-m-d");

        if (!in_array($usertype, ModelsUser::USERTYPES)) {
            return response()->json([
                "ok" => false,
                "msg" => "Invalid request. Unknown usertype supplied"
            ]);
        }

        $academicDetails = DB::table('tblacyear')->where("school_code",$schoolCode)
        ->where("current_term","1")
        ->where("deleted","0")
        ->first();
        $notices = Notice::where("notice_recipient",  $usertype)
            ->where("school_code",$schoolCode)
            ->where("acyear",$academicDetails->acyear_desc)
            ->where("term",$academicDetails->acterm)
            ->orWhere("notice_recipient", "ALL")
            ->whereDate("date_end", ">", $today)
            ->orderBy("date_posted", "desc")->get();

        return response()->json([
            "ok" => true,
            "msg" => "Request is successful",
            "data" => NoticeResource::collection($notices),
        ]);
    }
}
