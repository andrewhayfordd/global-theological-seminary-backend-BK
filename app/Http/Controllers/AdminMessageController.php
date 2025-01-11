<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Gitplus\Arkesel as Sms;
use App\Http\Resources\EmailResource;
use App\Http\Resources\messageResource;
use App\Http\Resources\SmsResource;
use App\Mail\AdminMessageCentreEmail;
use App\Mail\BulkMail;
use App\Models\AcademicDetails;
use App\Models\MessageSMS;
use App\Models\School;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use yeboahnanaosei\FayaSMS\FayaSMS;

class AdminMessageController extends Controller
{

    public function fetchSms($schoolCode)
    {
        $sms = MessageSMS::where("school_code", $schoolCode)->get();
        return response()->json([
            "data" => SmsResource::collection($sms)
        ]);
    }

    public function fetchEmail($schoolCode)
    {
        $email = DB::table("tblemail_sent")->where("school_code", $schoolCode)
            ->where("deleted", "0")->orderByDesc("createdate")->get();

        return response()->json([
            "data" => EmailResource::collection($email)
        ]);
    }

    public function sendNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "notificationBody" => "required",
            "notificationType" => "required",
            "notificationRecipients" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => join(" ", $validator->errors()->all()),
            ]);
        }
        $academicDetails = AcademicDetails::where("school_code", $request->school_code)
            ->where("deleted", "0")->where("current_term", "1")->first();

        DB::table("tblsms_sent")->insert([
            "transid" => strtoupper(bin2hex(random_bytes(5))),
            "acyear" => $academicDetails->acyear_desc,
            "acterm" => $academicDetails->acterm,
            "school_code" => $request->school_code,
            "sms" => $request->notificationBody,
            "deleted" => "0",
            "createuser" => $request->createuser,
            "createdate" => date("Y-m-d"),
        ]);
        // Prepare the recipients of the notification
        $notificationRecipients = [];
        switch (strtolower($request->notificationType)) {
            case "sms":
                $this->sendSMSNotification($request);
                break;
            case "push":
                $this->sendPushNotification();  // TODO: To be implemented later
                break;
        }

        return response()->json($notificationRecipients);
    }

    public function sendSms(Request $request)
    {
        // return $request->all();
        $recipients = [];

        if (!empty($request->student)) {
            switch ($request->student) {
                case 'all_students':
                    $students = DB::table("tblstudent")->select("phone")
                        ->where("school_code", $request->school_code)
                        ->where("deleted", "0")
                        ->get();

                    $studentCount = count($students);
                    $sms = new Sms("PHARMATRUST", env("ARKESEL_SMS_API_KEY"));
                    foreach ($students as $key => $student) {
                        $recipients[] = $student->phone;
                        $numbers = join(",", $recipients);
                        if (count($recipients) === 100 || ($key + 1 === $studentCount)) {

                            $responses = $sms->send($numbers, $request->notificationBody);
                            $recipients = [];
                        }
                    }


                    DB::table("tblsms_sent")->insert([
                        "transid" => strtoupper(bin2hex(random_bytes(5))),
                        "school_code" => $request->school_code,
                        "sms" => $request->notificationBody,
                        "recipient" => 'All Students',
                        "deleted" => "0",
                        "createuser" => $request->createuser,
                        "createdate" => date("Y-m-d"),
                    ]);
                    return response()->json([
                        "ok" => true,
                        "msg" => "Response from Arkesel",
                        "data" => [
                            "responses" => $responses,
                        ]
                    ]);

                    break;

                default:
                    $student = DB::table("tblstudent")->select("phone", "fname", "mname", "lname")
                        ->where("school_code", $request->school_code)
                        ->where("student_no", $request->student)
                        ->where("deleted", "0")
                        ->first();

                    $sms = new Sms("PHARMATRUST", env("ARKESEL_SMS_API_KEY"));
                    $res = $sms->send($student->phone, $request->notificationBody);

                    DB::table("tblsms_sent")->insert([
                        "transid" => strtoupper(bin2hex(random_bytes(5))),
                        "school_code" => $request->school_code,
                        "sms" => $request->notificationBody,
                        "recipient" => $student->fname . '' . $student->mname . '' . $student->lname,
                        "deleted" => "0",
                        "createuser" => $request->createuser,
                        "createdate" => date("Y-m-d"),
                    ]);
                    return response()->json([
                        "ok" => true,
                        "msg" => "Response from Arkesel",
                        "data" => [
                            "responses" => $res,
                        ]
                    ]);
                    break;
            }
        }

        if (!empty($request->staff)) {
            switch ($request->staff) {
                case 'all_staff':
                    $staff = DB::table("tblstaff")->select("phone")
                        ->where("school_code", $request->school_code)
                        ->where("deleted", "0")
                        ->get();

                    $studentCount = count($staff);
                    $sms = new Sms("PHARMATRUST", env("ARKESEL_SMS_API_KEY"));
                    foreach ($staff as $key => $item) {
                        $recipients[] = $item->phone;
                        $numbers = join(",", $recipients);
                        if (count($recipients) === 100 || ($key + 1 === $studentCount)) {

                            $responses = $sms->send($numbers, $request->notificationBody);
                            $recipients = [];
                        }
                    }


                    DB::table("tblsms_sent")->insert([
                        "transid" => strtoupper(bin2hex(random_bytes(5))),
                        "school_code" => $request->school_code,
                        "sms" => $request->notificationBody,
                        "recipient" => 'All Staff',
                        "deleted" => "0",
                        "createuser" => $request->createuser,
                        "createdate" => date("Y-m-d"),
                    ]);
                    return response()->json([
                        "ok" => true,
                        "msg" => "Response from Arkesel",
                        "data" => [
                            "responses" => $responses,
                        ]
                    ]);

                    break;

                default:
                    $staff = DB::table("tblstaff")->select("phone", "fname", "mname", "lname")
                        ->where("school_code", $request->school_code)
                        ->where("staffno", $request->staff)
                        ->where("deleted", "0")
                        ->first();

                    $sms = new Sms("PHARMATRUST", env("ARKESEL_SMS_API_KEY"));
                    $res = $sms->send($staff->phone, $request->notificationBody);

                    DB::table("tblsms_sent")->insert([
                        "transid" => strtoupper(bin2hex(random_bytes(5))),
                        "school_code" => $request->school_code,
                        "sms" => $request->notificationBody,
                        "recipient" => $staff->fname . ' ' . $staff->mname . ' ' . $staff->lname,
                        "deleted" => "0",
                        "createuser" => $request->createuser,
                        "createdate" => date("Y-m-d"),
                    ]);
                    return response()->json([
                        "ok" => true,
                        "msg" => "Response from Arkesel",
                        "data" => [
                            "responses" => $res,
                        ]
                    ]);
                    break;
            }
        }
    }

// aba@ppc.edu.gh

    public function sendBulkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required",
            "emailRecipients" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => join(" ", $validator->errors()->all()),
            ]);
        }


        $courses = DB::table('tblcourse')->select('tblcourse.course_code')
            ->join('tblcourse_assignment', "tblcourse_assignment.course_code", 'tblcourse.course_code')
            ->where('tblcourse.deleted', '0')
            ->where('tblcourse_assignment.deleted', '0')
            ->where('tblcourse_assignment.staffno', $request->staff_code)
            ->get()->toArray();

        $courseCode = [];
        foreach ($courses as  $value) {
            // $courseCode = $value->course_code;
            $courseCode[] = $value->course_code;
        }

        // return $courseCode;
        $student = DB::table("tblcourse_reg")->select(
            "tblstudent.email",
            "tblstudent.fname",
        )
            ->join("tblstudent", "tblstudent.student_no", "tblcourse_reg.student_code")
            ->join("tblcourse", "tblcourse.course_code", "tblcourse_reg.course_code")
            ->whereIn("tblcourse_reg.course_code", $courseCode)
            ->where("tblstudent.deleted", 0)
            ->where("tblcourse.deleted", 0)
            ->where("tblcourse_reg.deleted", 0)
            ->get();


        foreach ($student->email as $recipient) {



            $academicDetails = DB::table('tblacyear')->where("school_code", $request->school_code)
                ->where("deleted", "0")->where("current_term", "1")->first();
            $emailCode = "EM" . strtoupper(bin2hex(random_bytes(3)));
            DB::table("tblemail_sent")->insert([
                "transid" => strtoupper(bin2hex(random_bytes(5))),
                "email_code" => $emailCode,
                "acyear" => $academicDetails->acyear_desc,
                "acterm" => $academicDetails->acterm,
                "school_code" => $request->school_code,
                "email_subject" => $request->subject,
                "email_message" => $request->email,
                "deleted" => "0",
                "createuser" => $request->createuser,
                "createdate" => date("Y-m-d"),
            ]);



            // $students = DB::table("tblstudent")->select("email")
            //     ->where("school_code", $request->school_code)
            //     ->whereNotNull('email')
            //     ->where("deleted", "0")->get();

            $school = School::where("school_code", $request->school_code)->where("deleted", "0")->first();

            $mail = [];

            DB::table("tblemail_recipient")->insert([
                "transid" => strtoupper(bin2hex(random_bytes(5))),
                "email_code" => $emailCode,
                "acyear" => $academicDetails->acyear_desc,
                "acterm" => $academicDetails->acterm,
                "school_code" => $request->school_code,
                "recipient_email" => $recipient,
                "deleted" => "0",
                "createuser" => $request->createuser,
                "createdate" => date("Y-m-d"),
            ]);

            $mail['msg'] = $request->email;
            $mail['subject'] = $request->subject;
            $mail['school'] = $school->school_name;
            $mail['twitter'] = $school->twitter;
            $mail['facebook'] = $school->facebook;
            Mail::to($recipient)->send(new BulkMail($mail));
            return;

            return response()->json([
                "ok" => true,
                "msg" => "Request successful"
            ]);
        }
    }

    public function sendEmail(Request $request)
    {
        // return $request->all();
        $recipients = [];

        if (!empty($request->student)) {
            switch ($request->student) {
                case 'all_students':
                    $students = DB::table("tblstudent")->select("email")
                        ->where("school_code", $request->school_code)
                        ->where("deleted", "0")
                        ->get();

                    foreach ($students as $student) {
                        $recipients[] = $student->email;
                    }

                    $messageDetail = [
                        "subject" => $request->subject,
                        "body" => $request->email,
                    ];

                    if (!empty($recipients)) {
                        foreach ($recipients as $recipient) {
                            Mail::to($recipient)->send(new AdminMessageCentreEmail($messageDetail));
                        }
                    }


                    DB::table("tblemail_sent")->insert([
                        "transid" => strtoupper(bin2hex(random_bytes(5))),
                        "school_code" => $request->school_code,
                        "email_subject" => $request->subject,
                        "email_message" => $request->email,
                        "recipient" => 'All Students',
                        "deleted" => "0",
                        "createuser" => $request->createuser,
                        "createdate" => date("Y-m-d"),
                    ]);
                    return response()->json([
                        "ok" => true,
                        "msg" => "Email sent!",

                    ]);

                    break;

                default:
                    $student = DB::table("tblstudent")->select("email", "fname", "mname", "lname")
                        ->where("school_code", $request->school_code)
                        ->where("student_no", $request->student)
                        ->where("deleted", "0")
                        ->first();

                    $messageDetail = [
                        "subject" => $request->subject,
                        "body" => $request->email,
                    ];

                    if (!empty($student->email)) {
                        Mail::to($student->email)->send(new AdminMessageCentreEmail($messageDetail));
                    }


                    DB::table("tblemail_sent")->insert([
                        "transid" => strtoupper(bin2hex(random_bytes(5))),
                        "school_code" => $request->school_code,
                        "email_subject" => $request->subject,
                        "email_message" => $request->email,
                        "recipient" => $student->fname . '' . $student->mname . '' . $student->lname,
                        "deleted" => "0",
                        "createuser" => $request->createuser,
                        "createdate" => date("Y-m-d"),
                    ]);
                    return response()->json([
                        "ok" => true,
                        "msg" => "Email sent!",

                    ]);
                    break;
            }
        }

        if (!empty($request->staff)) {
            switch ($request->staff) {
                case 'all_staff':
                    $staff = DB::table("tblstaff")->select("email")
                        ->where("school_code", $request->school_code)
                        ->where("deleted", "0")
                        ->get();

                        foreach ($staff as $item) {
                            $recipients[] = $item->email;
                        }
    
                        $messageDetail = [
                            "subject" => $request->subject,
                            "body" => $request->email,
                        ];
    
                        if (!empty($recipients)) {
                            foreach ($recipients as $recipient) {
                                Mail::to($recipient)->send(new AdminMessageCentreEmail($messageDetail));
                            }
                        }
    
    
                        DB::table("tblemail_sent")->insert([
                            "transid" => strtoupper(bin2hex(random_bytes(5))),
                            "school_code" => $request->school_code,
                            "email_subject" => $request->subject,
                            "email_message" => $request->email,
                            "recipient" => 'All Staff',
                            "deleted" => "0",
                            "createuser" => $request->createuser,
                            "createdate" => date("Y-m-d"),
                        ]);

                    return response()->json([
                        "ok" => true,
                        "msg" => "Email sent!",
                        
                    ]);

                    break;

                default:
                    $staff = DB::table("tblstaff")->select("email", "fname", "mname", "lname")
                        ->where("school_code", $request->school_code)
                        ->where("staffno", $request->staff)
                        ->where("deleted", "0")
                        ->first();

                        $messageDetail = [
                            "subject" => $request->subject,
                            "body" => $request->email,
                        ];
    
                        if (!empty($staff->email)) {
                            Mail::to($staff->email)->send(new AdminMessageCentreEmail($messageDetail));
                        }
    
    
                        DB::table("tblemail_sent")->insert([
                            "transid" => strtoupper(bin2hex(random_bytes(5))),
                            "school_code" => $request->school_code,
                            "email_subject" => $request->subject,
                            "email_message" => $request->email,
                            "recipient" => $staff->fname . ' ' . $staff->mname . ' ' . $staff->lname,
                            "deleted" => "0",
                            "createuser" => $request->createuser,
                            "createdate" => date("Y-m-d"),
                        ]);
                    return response()->json([
                        "ok" => true,
                        "msg" => "Email  sent!",
                        
                    ]);
                    break;
            }
        }
    }
}
