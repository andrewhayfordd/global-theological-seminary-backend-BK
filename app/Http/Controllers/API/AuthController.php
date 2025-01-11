<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Gitplus\Arkesel as Sms;
use App\Mail\ForgotPasswordMail;
use App\Models\Program;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                "userid" => "required",
                "password" => "required",
            ]
        );

        // Payload to be sent with response
        $payload = [
            "ok" => false,
        ];

        if ($validator->fails()) {
            $payload["msg"] = "Login failed. No email or password";
            $payload["error"] = [
                "msg" => join(" ", $validator->errors()->all()),
                "fix" => "Kindly fix the above errors",
            ];
            return response($payload);
        }

        $authenticatedUser = User::where("userid", strtoupper($request->userid))
            ->where("deleted", "0")
            ->first();


        // Return if no user is found
        if (empty($authenticatedUser)) {
            $payload["msg"] = "Login failed. Wrong userid or password";
            return response()->json($payload);
        }

        // Return if password is invalid
        if (!Hash::check($request->password, $authenticatedUser->password)) {
            $payload["msg"] = "Login failed. Wrong userid or password";
            return response()->json($payload);
        }


        // The assumption here is that a parent will always have his/her children
        // in the same school. So when the parent logs in, we get the details of
        // the school and add it to the response.

        // $school = DB::table("tblacyear")
        //     ->where([
        //         "school_code" => $authenticatedUser->school_code,
        //         "current_term" => "1",
        //     ])->first();

        $schoolDetails = [
            "name" => $authenticatedUser->school->school_name,
            // "term" => $school->acterm,
            // "year" => $school->acyear_desc,
            "logo" => $authenticatedUser->school->logo,
            "long" => $authenticatedUser->school->longitude,
            "lat" => $authenticatedUser->school->latitude,
            "distance" => $authenticatedUser->school->distance,
            "streetAddress" => $authenticatedUser->school->street_address,
            "PostalAddress" => $authenticatedUser->school->postal_address,
            "email" => $authenticatedUser->school->email,
            "phone" => $authenticatedUser->school->phone_main,
            "motto" => $authenticatedUser->school->school_motto,
            "schoolType" => $authenticatedUser->school->type,
            "schoolPrefix" => $authenticatedUser->school->school_prefix,
        ];

        // Determine the usertype to know the appropriate data to send back
        $usertype = strtoupper($authenticatedUser->usertype);

        switch ($usertype) {
            case "STU":
                $student = Student::where("student_no", $authenticatedUser->userid)
                    ->where("deleted", "0")
                    ->first();


                // Make sure the student exists in the system. If not return an error response
                if (empty($student)) {
                    $payload["msg"] = "Login failed. Unknown student, please try again later";
                    $payload["error"] = [
                        "msg" => "Details of this user was not found. Perhaps this student does not exist or has been deleted",
                        "fix" => "Check the system to correct such errors",
                    ];
                    return response($payload);
                }

                $programDetails = Program::where("prog_code", $student->prog)
                    ->where("deleted", "0")
                    ->first();
                $semester = DB::table("tblsemester")->where("deleted", 0)->get();
                $payload["ok"] = true;
                $payload["msg"] = "Login successful";
                $payload["data"] = [
                    "usertype" => $usertype,
                    "user" => $student,
                    "sch" => $schoolDetails,
                    "program" => $programDetails,
                    "semester" => $semester,
                ];
                break;


            case "STA":
                $staff = Staff::where("email", $authenticatedUser->email)
                    ->where("deleted", "0")
                    ->first();

                if (empty($staff)) {
                    $payload["msg"] = "Login failed. Unknown staff, report this to your school if this keeps happening";
                    $payload["error"] = [
                        "msg" => "Details of this staff was not found. Perhaps this staff does not exist or has been deleted",
                        "fix" => "Check the system to correct such errors",
                    ];
                    return response($payload);
                }

                "select ct.class_code, c.class_desc from tblclass_teacher ct
                join tblclass c on c.class_code = ct.class_code
                where  ct.school_code = '1000001'
                and  ct.staff_no = 'S001'
                and  ct.acyear = '2019/2020'";

                $assignedClasses = DB::table("tblclass")
                    ->join("tblclass_teacher", "tblclass.class_code", "=", "tblclass_teacher.class_code")
                    ->select("tblclass_teacher.class_code", "tblclass.class_desc")
                    ->where("tblclass_teacher.school_code", "=", $staff->school_code)
                    ->where("tblclass.school_code", "=", $staff->school_code)
                    ->where("tblclass_teacher.staff_no", "=", $staff->staffno)
                    // ->where("tblclass_teacher.acyear", "=", $school->acyear_desc)
                    ->where("tblclass_teacher.deleted", "0")
                    ->where("tblclass.deleted", "0")
                    // ->where("tblclass_teacher.term", "=", $school->acterm)
                    ->get();

                $classes =  [];
                foreach ($assignedClasses->toArray() as $class) {
                    $classes[] = $class;
                }

                $subjects = DB::table("tblstaff")
                    ->join("tblcourse_assignment", "tblcourse_assignment.staffno", "=", "tblstaff.staffno")
                    ->join("tblcourse", "tblcourse.course_code", "=", "tblcourse_assignment.course_code")
                    ->where("tblstaff.staffno", "=", $staff->staffno)
                    ->where("tblstaff.deleted", "=", "0")
                    // ->where("tblcourse_assignment.acyear", "=", $school->acyear_desc)
                    // ->where("tblcourse_assignment.semester", "=", $school->acterm)
                    ->select(
                        DB::raw("CONCAT_WS(' ', tblstaff.fname, tblstaff.mname, tblstaff.lname) as staff"),
                        "tblstaff.phone",
                        "tblstaff.email",
                        // "tblcourse_assignment.class_code",
                        "tblcourse_assignment.course_code",
                        "tblcourse.course_title"
                    )->get();


                $payload["ok"] = true;
                $payload["msg"] = "Login successful";
                $payload["data"] = [
                    "usertype" => $usertype,
                    "user" => $staff,
                    "sch" => $schoolDetails,
                    "classes" => $classes,
                    "subjects" => $subjects,
                ];
                break;
            default:
                $payload["msg"] = "Login failed. An internal error occured. Report this to your school if this keeps happening";
        }

        return response($payload);
    }


    public function passwordReset(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "password" => "required|confirmed|min:8",
                "current_password" => "required",
                "email" => "required",
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Reset failed. " . join(" ", $validator->errors()->all()),
            ], 400);
        }

        $authenticatedUser = User::where("email", strtoupper($request->email))
            ->where("deleted", "0")
            ->first();
        // Return if user not found
        if (empty($authenticatedUser)) {
            return response()->json([
                "ok" => false,
                "msg" => "Sorry account not found"
            ], 400);
        }


        // Return if old password is invalid
        if (!Hash::check($request->current_password, $authenticatedUser->password)) {
            return response()->json([
                "ok" => false,
                "msg" => "Sorry your current password is incorrect"
            ], 400);
        }

        //create new password
        $password = Hash::make($request->password);


        //update new password with the authenticated user
        try {
            $authenticatedUser->update([
                'password' => $password,
                'modifydate' => date("Y-m-d"),
                'modifyuser' => $authenticatedUser->email,
            ]);

            return response()->json([
                "ok" => true,
                "msg" => "Password successfully changed",
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "An internal error occured. Reset failed",
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "email" => "required",
            ]
        );
        // Payload to be sent with response
        $payload = [
            "ok" => false,
        ];
        if ($validator->fails()) {
            $payload["msg"] = "Reset failed. No email provided";
            $payload["error"] = [
                "msg" => join(" ", $validator->errors()->all()),
                "fix" => "Kindly fix the above errors",
            ];
            return response($payload);
        }

        //Fetch user details based on email provided
        $authenticatedUser = User::where("email", strtoupper($request->email))
            ->where("deleted", "0")
            ->first();

        // Return if no user is found
        if (empty($authenticatedUser)) {
            $payload["msg"] = "Sorry account not found";
            return response()->json($payload);
        }

        //create new password
        $random = uniqid(); //TODO: Send generated password via email to user
        $password = password_hash($random, PASSWORD_DEFAULT);


        //update new password with the authenticated user
        try {
            $authenticatedUser->update([
                'password' => $password,
                'modifydate' => date("Y-m-d"),
                'modifyuser' => $authenticatedUser->email,
            ]);

            // $authenticatedUser->newPassword = $random;

            // event(new PasswordReset($authenticatedUser));

            $msg = " Here is your new password: {$random}";

            $newsms = new Sms(env('ARKESEL_SMS_SENDER_ID'), env('ARKESEL_SMS_API_KEY'));

            $response = $newsms->send($authenticatedUser->phone, $msg);

            $receipt = [

                "password" => $random,
            ];

            Mail::to($request->email)->send(new ForgotPasswordMail($receipt));

            return response()->json([
                "ok" => true,
                "msg" => "Reset successful.",
            ]);
        } catch (\Exception $th) {
            return response()->json([
                "ok" => false,
                "msg" => "An internal error occured. Reset failed",
                "error" => [
                    "msg" => $th->__toString(),
                    "fix" => "Error is explained in fix",
                ]
            ]);
        }
    }
}
