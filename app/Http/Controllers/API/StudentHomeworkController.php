<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\StudentHomeworkResource;
use App\Http\Resources\User;
use App\Models\StudentHomework;
use App\Models\User as ModelsUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentHomeworkController extends Controller
{
    public function index(Request $request, $usertype, $schoolCode)
{
    //$usertype = strtoupper($usertype);
    $today = Carbon::today("Africa/Accra")->format("Y-m-d");

    // if (!in_array($usertype, ModelsUser::USERTYPES)) {
    //     return response()->json([
    //         "ok" => false,
    //         "msg" => "Invalid request. Unknown usertype supplied"
    //     ]);
    // }

    $academicDetails = DB::table('tblacyear')
        ->where("school_code", $schoolCode)
        ->where("current_term", "1")
        ->where("deleted", "0")
        ->first();

    if (!$academicDetails) {
        return response()->json([
            "ok" => false,
            "msg" => "Academic details not found for the given school code."
        ]);
    }

    $homeworks = StudentHomework::where(function ($query) use ($schoolCode, $academicDetails) {
        $query->where("school_code", $schoolCode)
            ->where("acyear", $academicDetails->acyear_desc)
            ->where("term", $academicDetails->acterm);
    })
    //->whereDate("date_end", ">", Carbon::today("Africa/Accra"))
    ->orderBy("date_posted", "desc")
    ->get();

    return response()->json([
        "ok" => true,
        "msg" => "Request is successful",
        "data" => StudentHomeworkResource::collection($homeworks),
    ]);
}

}
