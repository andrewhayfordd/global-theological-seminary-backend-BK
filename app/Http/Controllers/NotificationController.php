<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $school_code = $user->school_code;

    if ($user->usertype === 'STA') {
        // Staff should see notices meant for "Staff" or "All"
        $notices = DB::table('tblnotice')
            ->select('id', 'title', 'message', 'date_posted') // Selecting relevant columns
            ->where('school_code', $school_code)
            ->where(function ($query) {
                $query->where('notice_recipient', 'Staff')
                      ->orWhere('notice_recipient', 'All');
            })
            ->where('deleted', '0')
            ->orderBy('date_posted', 'desc')
            ->get();
    } else {
        // Students should see only their assigned notices
        $student_code = $user->student_code;

        $notices = DB::table('tblnotice')
            ->select('tblnotice.transid', 'tblnotice.notice_title', 'tblnotice.notice_details', 'tblnotice.date_posted')
            ->where('tblnotice.school_code', $school_code)
            ->where('tblnotice.deleted', '0')
            ->where(function ($query) use ($student_code) {
                $query->where('tblnotice.notice_recipient', 'Students')
                      ->orWhere('tblnotice.notice_recipient', 'All')
                      ->orWhereExists(function ($subquery) use ($student_code) {
                          $subquery->select(DB::raw(1))
                              ->from('tblcourse_reg')
                              ->whereRaw('tblcourse_reg.course_code = tblnotice.course_recipient');
                            //   ->where('tblcourse_reg.student_code', $student_code);
                      });
            })
            ->orderBy('tblnotice.date_posted', 'desc')
            ->get();
    }

    return view('modules.notifications.index', compact('notices'));
}

    
}
