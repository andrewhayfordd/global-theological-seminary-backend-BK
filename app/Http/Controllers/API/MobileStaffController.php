<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MobileStaffController extends Controller
{
    public function allStaffCourses($StaffCode)
    {
        $courses = DB::table('tblcourse')->select('tblcourse.*')
        ->join('tblcourse_assignment', "tblcourse_assignment.course_code", 'tblcourse.course_code')
        ->where('tblcourse.deleted', '0')
        ->where('tblcourse_assignment.deleted', '0')
        ->where('tblcourse_assignment.staffno', $StaffCode)
        ->get()->toArray();

        return response()->json([
            "ok" => true,
            "msg" => "Request successful",
            "data" => $courses
        ]);
    }
}
