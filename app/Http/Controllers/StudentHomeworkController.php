<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class StudentHomeworkController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $school_code = $user->school_code;
    $student_code = $user->student_code;

    $currentdate = Carbon::today("Africa/Accra")->format("Y-m-d");;

    DB::table('tblhomework')
    ->whereDate('date_start', '>=', $currentdate)
    ->update(['deleted' => 1]);

    DB::table('tblhomework')
    ->whereDate('date_start', '<=', $currentdate)
    ->update(['deleted' => 0]);

    DB::table('tblhomework')
    ->whereDate('date_end', '<=', $currentdate)
    ->update(['deleted' => 1]);

    // Fetch only homeworks assigned to the logged-in student
    $studenthomeworks = DB::table('tblhomework')
        ->where('tblhomework.school_code', $school_code)
        ->where('tblhomework.deleted', '0')
        ->join('tblcourse_reg', 'tblhomework.course_recipient', '=', 'tblcourse_reg.course_code')
        ->where('tblcourse_reg.student_code', $user->userid)
        ->select('tblhomework.*')
        ->distinct()
        ->orderBy('tblhomework.date_posted', 'desc')
        ->get();

    return view('modules.studenthomework.index', compact('studenthomeworks'));
}


public function store(Request $request)
{
    $request->validate([
        'homework_title' => 'required|string|max:255',
        //'homework_details' => 'required|string',
        //'date_start' => 'required|date',
        //'date_end' => 'required|date|after_or_equal:date_start',
        // 'recipient_type' => 'required|string',
        'course_code' => 'nullable|string',
        'file' => 'required|mimes:pdf,doc,docx,odt,jpg,png|max:2048'
    ]);

    $school_code = auth()->user()->school_code;
    $userid = auth()->user()->userid;
    $fname = auth()->user()->fname;
    $lname = auth()->user()->lname;
    $recipient_type = $request->recipient_type;
    $course_code = $request->course_code;
    $file = $request->file('file');
    $filePath = $file->store('document', 'public');

    DB::transaction(function () use ($school_code, $request, $recipient_type, $course_code, $filePath, $userid, $fname, $lname) {
        // Insert homework into database
        $homeworkData = [
            'transid' => uniqid(),
            'school_code' => $school_code,
            'userid' => $userid,
            'fname' => $fname,
            'lname' => $lname,
            'acyear' => date('Y'),
            'term' => '1',
            'homework_code' => strtoupper(uniqid('N')),
            'homework_type' => 'General',
            //'homework_recipient' => $recipient_type,
            'homework_title' => $request->homework_title,
            //'homework_details' => $request->homework_details,
            'course_recipient' => $course_code ?? '',
            'file_path' => $filePath,
            'posted_by' => auth()->user()->id,
            'submit_to' => $request->submit_to,
            'date_posted' => now(),
            //'date_start' => $request->date_start,
            //'date_end' => $request->date_end,
            'deleted' => '0',
            'createuser' => auth()->user()->id,
            'createdate' => now(),
        ];

        DB::table('tblsubmit_homework')->insert($homeworkData);

        // Fetch recipients based on selection
        // $recipients = match ($recipient_type) {
        //     'students' => DB::table('tblstudent')->pluck('student_no')->toArray(),
        //     'staff' => DB::table('tblstaff')->pluck('staffno')->toArray(),
        //     'course_students' => $course_code ? DB::table('tblcourse_reg')
        //         ->where('course_code', $course_code)
        //         ->pluck('student_code')
        //         ->toArray() : [],
        //     'all' => array_merge(
        //         DB::table('tblstudent')->pluck('student_no')->toArray(),
        //         DB::table('tblstaff')->pluck('staffno')->toArray()
        //     ),
        //     default => []
        // };

        
    });

    return redirect()->route('studenthomeworks.index')->with('success', 'Homework created successfully.');
}

}
