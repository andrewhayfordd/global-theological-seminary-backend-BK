<?php 

// app/Http/Controllers/TimetableController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Timetable;
use App\Models\Student;

class TimetableController extends Controller
{
    // Display the list of timetables
    public function index()
    {
        $timetables = DB::table('tbltime_table')
            ->join('tblstudent', 'tbltime_table.student_id', '=', 'tblstudent.transid')
            ->join('tblcourse', 'tbltime_table.course_code', '=', 'tblcourse.course_code')
            ->select('tbltime_table.*', 'tblstudent.fname', 'tblstudent.lname', 'tblcourse.course_title')
            ->get();

        return view('modules.timetables.index', compact('timetables'));
    }

    // Show the form to create a new timetable
    public function create()
    {
        $students = DB::table('tblstudent')->get();
    $courses = DB::table('tblcourse')->get();
    return view('modules.timetables.modals.create', compact('students', 'courses'));
    }

    // Store a new timetable
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:tblstudent,transid',
            'course_code' => 'required|exists:tblcourse,course_code',
            'day' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'location' => 'required|string',
        ]);

        DB::table('tbltime_table')->insert([
            'student_id' => $request->student_id,
            'course_code' => $request->course_code,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
        ]);

        return redirect()->route('timetables.index')->with('success', 'Timetable created successfully');
    }

    // Show the form to edit an existing timetable
    public function edit($id)
    {
        $timetable = DB::table('tbltime_table')->where('id', $id)->first();
        $students = DB::table('tblstudent')->get();
        $courses = DB::table('tblcourse')->get();
        return view('modules.timetables.modals.edit', compact('timetable', 'students', 'courses'));
    }

    // Update an existing timetable
    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:tblstudent,transid',
            'course_code' => 'required|exists:tblcourse,course_code',
            'day' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'location' => 'required|string',
        ]);

        DB::table('tbltime_table')
            ->where('id', $id)
            ->update([
                'student_id' => $request->student_id,
                'course_code' => $request->course_code,
                'day' => $request->day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
            ]);

        return redirect()->route('timetables.index')->with('success', 'Timetable updated successfully');
    }

    // Delete an existing timetable
    public function destroy($id)
    {
        DB::table('tbltime_table')->where('id', $id)->delete();

        return redirect()->route('timetables.index')->with('success', 'Timetable deleted successfully');
    }
}
