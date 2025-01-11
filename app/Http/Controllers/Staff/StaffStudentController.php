<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Resources\Staff\StaffStudentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffStudentController extends Controller
{
    public function index($courseCode)
    {
        // return $courseCode;
        $student = DB::table("tblcourse_reg")->select(
            "tblstudent.*",
            "tblcourse_reg.semester",
            "tblcourse.course_title",
            "tblcourse.course_code"
        )   
            ->join("tblstudent", "tblstudent.student_no", "tblcourse_reg.student_code")
            ->join("tblcourse", "tblcourse.course_code", "tblcourse_reg.course_code")
            ->where("tblcourse_reg.course_code", $courseCode)
            ->where("tblstudent.deleted", 0)
            ->where("tblcourse.deleted", 0)
            ->where("tblcourse_reg.deleted", 0)
            ->get();

        return response()->json([
            "data1" => $student,
            "data" => StaffStudentResource::collection($student)
        ]);
    }
}
