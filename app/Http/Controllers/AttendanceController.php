<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Course;
use App\Models\CourseRegistration;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $courses = DB::table('tblcourse')->where('deleted', '0')->get();
        $selectedCourse = $request->input('course_code');
        $dates = Attendance::select('attendance_date')->distinct()->orderBy('attendance_date')->pluck('attendance_date')->toArray();

        $students = [];
        $attendanceData = [];

        if ($selectedCourse) {
            $students = Student::whereIn('student_no', function ($query) use ($selectedCourse) {
                $query->select('student_code')->from('tblcourse_reg')->where('course_code', $selectedCourse);
            })->get();

            foreach ($students as $student) {
                $attendanceData[$student->student_no] = [];
                foreach ($dates as $date) {
                    $attendance = Attendance::where('student_code', $student->student_no)->where('attendance_date', $date)->first();
                    $attendanceData[$student->student_no][$date] = $attendance ? $attendance->status : 'Not Taken';
                }
            }
        }

        return view('modules.attendance.index', compact('courses', 'selectedCourse', 'students', 'dates', 'attendanceData'));
    }

    public function create()
    {
        $courses = DB::table('tblcourse')->where('deleted', '0')->get();
        return view('modules.attendance.create', compact('courses'));
    }

    public function store(Request $request)
    {
        foreach ($request->students as $student_code => $status) {
            Attendance::updateOrCreate(
                [
                    'transid' => uniqid(),
                    'school_code' => Auth::user()->school_code,
                    'branch_code' => Auth::user()->branch_code,
                    'student_code' => $student_code, 
                    'attendance_date' => $request->attendance_date
                ],
                [
                    'course_code' => $request->course_code, 
                    'status' => $status
                ]
            );
        }

        return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully.');
    }

    public function getStudents(Request $request)
    {
        $students = Student::whereIn('student_no', function ($query) use ($request) {
            $query->select('student_code')->from('tblcourse_reg')->where('course_code', $request->course_code);
        })->get();

        return response()->json($students);
    }
}
