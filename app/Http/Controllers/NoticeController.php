<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    public function index()
    {
        // Fetch all courses
        $courses = DB::table('tblcourse')->select('course_code', 'course_title')->get();

        $notices = DB::table('tblnotice')
            ->where('deleted', '0') // Only show notices that are not deleted
            ->orderBy('date_posted', 'desc')
            ->get();

        $user = auth()->user();
        $school_code = $user->school_code;

         // Fetch notices created by the logged-in user
        $user_notices = DB::table('tblnotice')
        ->where('school_code', $school_code)
        ->where('posted_by', $user->id)
        ->where('deleted', '0')
        ->orderBy('date_posted', 'desc')
        ->get();

        return view('modules.notice.index', compact('notices', 'courses', 'user_notices'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'notice_title' => 'required|string|max:255',
            'notice_details' => 'required|string',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'recipient_type' => 'required|string', // New field for selecting recipients
            'course_code' => 'nullable|string',
        ]);

        $school_code = auth()->user()->school_code;

        // Determine recipient type
        $recipient_type = $request->recipient_type; // student, staff, course_students, all
        $course_code = $request->course_code;

        // Prepare notice data
        $noticeData = [
            'transid' => uniqid(), // Generate a unique ID
            'school_code' => $school_code,
            'acyear' => date('Y'), // Example academic year
            'term' => '1', // Example term
            'notice_code' => strtoupper(uniqid('N')), // Unique notice code
            'notice_type' => 'General', // Change if needed
            'notice_recipient' => $recipient_type,
            'notice_title' => $request->notice_title,
            'notice_details' => $request->notice_details,
            'course_recipient' => $course_code ?? '',
            'posted_by' => auth()->user()->id,
            'date_posted' => now(),
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'deleted' => '0',
            'createuser' => auth()->user()->id,
            'createdate' => now(),
        ];

        // Insert notice into database
        DB::table('tblnotice')->insert($noticeData);

        // Fetch recipients based on selection
        $recipients = [];

        if ($recipient_type === 'students') {
            $recipients = DB::table('tblstudent')->pluck('student_no');
        } elseif ($recipient_type === 'staff') {
            $recipients = DB::table('tblstaff')->pluck('staffno');
        } elseif ($recipient_type === 'course_students' && $course_code) {
            $recipients = DB::table('tblcourse_reg')
                ->where('course_code', $course_code)
                ->pluck('student_code');
        } elseif ($recipient_type === 'all') {
            $students = DB::table('tblstudent')->pluck('student_no');
            $staff = DB::table('tblstaff')->pluck('staffno');
            $recipients = $students->merge($staff);
        }

        // Notify recipients (You can replace this with email, SMS, or system notifications)
        foreach ($recipients as $recipient) {
            // Notification logic here (e.g., insert into a notifications table)
        }

        return redirect()->route('notice.index')->with('success', 'Notice created successfully.');
    }


    public function edit($transid)
{
    $notice = DB::table('tblnotice')->where('transid', $transid)->first();

    if (!$notice) {
        return redirect()->route('notice.index')->with('error', 'Notice not found.');
    }

    $courses = DB::table('tblcourse')->select('course_code', 'course_title')->get();

    return view('modules.notice.modals.edit', compact('notice', 'courses'));
}




public function update(Request $request, $id)
{
    $request->validate([
        'notice_title' => 'required|string|max:255',
        'notice_details' => 'required|string',
        'date_start' => 'required|date',
        'date_end' => 'required|date|after_or_equal:date_start',
        'course_code' => 'nullable|string',
    ]);

    $user_id = auth()->user()->id;

    // Find the notice and ensure the logged-in user is the creator
    $notice = DB::table('tblnotice')->where('transid', $id)->where('posted_by', $user_id)->first();

    if (!$notice) {
        return redirect()->route('notice.index')->with('error', 'Notice not found or access denied.');
    }

    // Update the notice
    DB::table('tblnotice')->where('transid', $id)->update([
        'notice_title' => $request->notice_title,
        'notice_details' => $request->notice_details,
        'course_recipient' => $request->course_code ?? '',
        'date_start' => $request->date_start,
        'date_end' => $request->date_end,
        'modifydate' => now(),
    ]);

    return redirect()->route('notice.index')->with('success', 'Notice updated successfully.');
}


    // Delete Notice
    public function delete($id)
    {
        DB::table('tblnotice')->where('transid', $id)->update(['deleted' => '1']);

        return redirect()->route('notice.index')->with('success', 'Notice deleted successfully.');
    }



    public function userNotices()
{
    $user = auth()->user();
    $school_code = $user->school_code;

    // Fetch all notices that are not deleted
    $noticesQuery = DB::table('tblnotice')
        ->where('school_code', $school_code)
        ->where('deleted', '0')
        ->orderBy('date_posted', 'desc');

    if ($user->is_staff) {
        // Show notices meant for staff or everyone
        $noticesQuery->whereIn('notice_recipient', ['All Staff', 'Everyone']);
    } else {
        // Show notices meant for students, everyone, or registered course
        $studentCourses = DB::table('tblcourse_reg')
            ->where('student_code', $user->student_no)
            ->pluck('course_code');

        $noticesQuery->where(function ($query) use ($studentCourses) {
            $query->whereIn('notice_recipient', ['All Students', 'Everyone'])
                  ->orWhereIn('course_recipient', $studentCourses);
        });
    }

    $notices = $noticesQuery->get();

    return view('modules.notice.notifications', compact('notices'));
}

}
