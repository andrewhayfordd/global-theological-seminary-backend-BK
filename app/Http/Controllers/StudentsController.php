<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use App\Models\User;
use App\Gitplus\Arkesel as Sms;
use App\Http\Resources\InactiveResource;
use App\Http\Resources\StudentResource;
use App\Models\School;
use Exception;
use Illuminate\Support\Facades\Date;

class StudentsController extends Controller
{
    public function index($schoolcode)
    {
        $student = DB::table("tblstudent")->select(
            "tblstudent.*",
            "tblprog.prog_desc",
            "tblbatch.batch_desc",
            "tblsession.session_desc",
            "tbllevel.level_desc"
        )
            ->leftJoin("tblprog", "tblstudent.prog", "tblprog.prog_code")
            ->leftJoin("tbllevel", "tblstudent.current_level", "tbllevel.level_code")
            ->leftJoin("tblbatch", "tblstudent.batch", "tblbatch.batch_code")
            ->leftJoin("tblsession", "tblstudent.session", "tblsession.session_code")
            // ->where("tblprog.school_code", $schoolcode)
            ->where("tblstudent.school_code", $schoolcode)
            ->where("tblstudent.deleted", 0)
            // ->where("tblprog.deleted", 0)
            ->get();

        return response()->json([
            "data" => StudentResource::collection($student)
        ]);
    }

    public function inactive($schoolcode)
    {
        $student = DB::table("tblstudent")->select(
            "tblstudent.*",
            "tblprog.prog_desc",
            "tblbatch.batch_desc",
            "tblsession.session_desc",
            "tbllevel.level_desc"
        )
            ->leftJoin("tblprog", "tblstudent.prog", "tblprog.prog_code")
            ->leftJoin("tbllevel", "tblstudent.current_level", "tbllevel.level_code")
            ->leftJoin("tblbatch", "tblstudent.batch", "tblbatch.batch_code")
            ->leftJoin("tblsession", "tblstudent.session", "tblsession.session_code")
            // ->where("tblprog.school_code", $schoolcode)
            ->where("tblstudent.school_code", $schoolcode)
            ->where("tblstudent.deleted", 1)
            // ->where("tblprog.deleted", 0)
            ->get();

        return response()->json([
            "data" => InactiveResource::collection($student)
        ]);
    }

    public function getAcademicDetails(School $school)
    {
        $details = DB::table("tblacyear")
            ->where("current_term", "=", "1")
            ->where("school_code", "=", $school->school_code)
            ->select("acyear_desc", "acterm")
            ->first();

        return $details;
    }

    private function generateStudentNumber(School $school)
    {
        $count = (int) Student::where("school_code", "=", $school->school_code)->count();
        $count++;

        switch (strlen($count)) {
            case 1:
                return "{$school->school_prefix}000{$count}";
                break;

            case 2:
                return "{$school->school_prefix}00{$count}";
                break;

            case 3:
                return "{$school->school_prefix}0{$count}";
                break;

            case 4:
            default:        // This is a fallthrough
                return "{$school->school_prefix}{$count}";
                break;      // This break is for defensive purposes.
        }
    }

    private function generateTransid()
    {
        return strtoupper(bin2hex(random_bytes(5)));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "fname" => "required",
                "lname" => "required",
                "program" => "required",
                "current_level" => "required",
                "gender" => "required",
                "student_no" => "required|unique:tblstudent,student_no",
                "email" => "required",
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. Please complete all required fields",
                "error" => [
                    "msg" => "Some required fields are missing: " . join(" ", $validator->errors()->all()),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }

        $school_code = $request->school_code;
        $studentNo = $request->student_no;

        $checkStudentNumberInStudentTable = Student::where("school_code", $school_code)
            ->where("student_no", $studentNo)->exists();

        if ($checkStudentNumberInStudentTable) {
            return response()->json([
                'ok'   =>  false,
                'msg'    => 'Registration failed, student number already exists',
            ]);
        }

        // Make sure that the supplied school code exists and belongs to a
        // school in the system
        $school = School::where("school_code", $school_code)->where("deleted", "0")->first();
        if (empty($school)) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. Could not determine the school this student belongs to",
                "error" => [
                    "msg" => "The supplied school code was not found in the system: {$request->school_code}",
                    "fix" => "Ensure that the supplied school code is correct"
                ]
            ]);
        }

        $academicDetails = $this->getAcademicDetails($school);

        $createuser = Auth::user()->userid;

        try {
            $transactionResult = DB::transaction(function () use ($request, $studentNo, $academicDetails,$createuser,$school_code) {
                DB::table("tblstudent")->insert([
                    "transid" => $this->generateTransid(),
                    "school_code" => $school_code,
                    "branch_code" => $request->branch,
                    "admyear" => $academicDetails->acyear_desc ?? null,
                    "admsemester" => $academicDetails->acterm,
                    "admdate" => date("Y-m-d"),
                    "student_no" => $studentNo,
                    "gender" => $request->gender,
                    "fname" => strtoupper($request->fname),
                    "mname" => strtoupper($request->mname),
                    "lname" => strtoupper($request->lname),
                    "dob" => $request->dob,
                    "prog" => $request->program,
                    "batch" => $request->batch,
                    "session" => $request->session,
                    "marital_status" => $request->marital_status,
                    // "pob" => $request->dob,
                    // "hometown" => $request->dob,
                    // "nationality" => $request->dob,
                    // "religion" => $request->dob,
                    "postal_add" => $request->postal_address,
                    "residential_gps" => $request->residential_address,
                    "picture" => env("APP_URL") . "/storage/student/user.jpg",
                    "education_level" => $request->education_level,
                    "current_level" => $request->current_level,
                    "level_admitted" => $request->current_level,
                    "disability" => $request->current_grade,
                    "phone" => $request->student_phone,
                    "email" => $request->email,
                    // "convicted" => $request->student_email,
                    // "conviction_details" => $request->student_email,
                    // "aboutus_code" => $request->student_email,
                    // "confirmed" => $request->student_email,
                    // "initials" => $request->student_email,
                    "completed" => "0",
                    "deleted" => "0",
                    "createdate" => date("Y-m-d"),
                    "createuser" => $createuser,
                    // "conviction_details" => $request->student_email,
                    
                    // "reason" => $request->reason,
                    // "session" => $request->session,
                    "education_level" => $request->education_level,
                    // "current_level" => $request->current_level,
                    // "level_admitted" => $request->current_level,
                    "church_name" => $request->church_name,
                    "phy_challenge" => $request->physical_challenge,
                    "emerg_cont_name" => $request->emergency_contact_name,
                    "emerg_cont_number" => $request->emergency_contact_number,
                    "prog_reason" => $request->program_reason,
                    "eng_lang_grade" => $request->english_language_grade,
                    "eng_lang_year" => $request->english_language_year,
                    "math_grade" => $request->mathematics_grade,
                    "math_year" => $request->mathematics_year,
                    "science_grade" => $request->science_grade,
                    "science_year" => $request->science_year,
                    "elective1_grade" => $request->elective1_grade,
                    "elective1_year" => $request->elective1_year,
                    "elective2_grade" => $request->elective_grade,
                    "elective2_year" => $request->elective2_year,
                    "sch_attended_name" => $request->school_attended_name,
                    "certificate" => $request->certificate_awarded,
                    "date_awarded" => $request->date_awarded,
                    "religious_affiliation" => $request->religious_affiliation,
                    "employer_name" => $request->employer,
                    "refree_name" => $request->refree,
                    "refree_phone" => $request->refree_phone,
                    "refree_occ" => $request->refree_occupation,
                    "refree_address" => $request->refree_address,

                ]);

                DB::table("tbluser")->insert([
                    "school_code" => $school_code,
                    "userid" => $studentNo,
                    "email" => $request->email,
                    "fname" => strtoupper($request->fname),
                    "lname" => strtoupper($request->lname),
                    "usertype" => User::TYPE_STUDENT,
                    "password" => Hash::make($studentNo),
                    "deleted" => "0",
                    "createdate" => date("Y-m-d"),
                    "modifydate" => date("Y-m-d"),
                    "createuser" => $createuser,
                    "modifyuser" => $createuser,
                ]);


                if (null !== $request->file("image")) {

                    $filePath = $request->file("image")->store("public/student");

                    Student::where("student_no", $studentNo)->update([
                        "picture" => env("APP_URL") . "/" . str_replace("public", "storage", $filePath),
                    ]);
                }

                $mods = DB::table("tblmodule")->where("student_mod", "1")->get();

                foreach ($mods as $mod) {
                    DB::table("tbluser_module_privileges")->insert([
                        "transid" => $this->generateTransid(),
                        "userid" => $studentNo,
                        "school_code" => $request->school_code,
                        "mod_read" => "1",
                        "mod_id" => $mod->mod_id,
                        "createdate" => date("Y-m-d"),
                        "createuser" => "admin",
                    ]);
                }
            });

            if (!empty($request->student_phone)) {

                $msgBody = <<<MSG
                            Your account on {$school->school_name} has been created.
                            These are your student portal credentials;
                            Student Number:{$studentNo} 
                            Password:{$studentNo}
                            MSG;
                // $sms->setRecipient($request->student_phone);
                // $sms->setMessageBody($msgBody);
                // $sms->send();
                // $sms = new Sms("PHARMATRUST", env("ARKESEL_SMS_API_KEY"));
                // $sms->send($request->student_phone, $msgBody);
            }
            // If the return value of DB::transaction is null (meaning it's empty)
            // then it means our transaction succeeded. If this is however not the
            // case, then we throw an exception here.
            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }

            return response()->json([
                "ok" => true,
                "msg" => "Registration successful",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not save student model. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",

                ]
            ]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "fname" => "required",
                "lname" => "required",
                "program" => "required",
                "current_level" => "required",
                "session" => "required",
                "gender" => "required",
                "student_phone" => "required",
                "student_id" => "required",
                "id" => "required"

            ]
        );

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. Please complete all required fields",
                "error" => [
                    "msg" => "Some required fields are missing: " . join(" ", $validator->errors()->all()),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }

        // $request->school_code = Auth::user()->school->school_code;

        // Make sure that the supplied school code exists and belongs to a
        // school in the system
        // $school = School::where("school_code", $request->school_code)->where("deleted", "0")->first();
        // if (empty($school)) {
        //     return response()->json([
        //         "ok" => false,
        //         "msg" => "Registration failed. Could not determine the school this student belongs to",
        //         "error" => [
        //             "msg" => "The supplied school code was not found in the system: {$request->school_code}",
        //             "fix" => "Ensure that the supplied school code is correct"
        //         ]
        //     ]);
        // }

        try {
            $transactionResult = DB::transaction(function () use ($request) {

                $student = DB::table("tblstudent")->where('transid', $request->id)->first();

                DB::table("tblstudent")->where('transid', $request->id)->update([
                    "student_no" => $request->student_id,
                    "branch_code" => $request->branch,
                    "gender" => empty($request->gender) ? "M" : $request->gender,
                    "fname" => ucfirst($request->fname),
                    "mname" => ucfirst($request->mname),
                    "lname" => ucfirst($request->lname),
                    "dob" => $request->dob,
                    "prog" => $request->program,
                    "batch" => $request->batch,
                    // "reason" => $request->reason,
                    "session" => $request->session,
                    "marital_status" => $request->marital_status,
                    "postal_add" => $request->postal_address,
                    "residential_gps" => $request->residential_address,
                    "education_level" => $request->education_level,
                    "current_level" => $request->current_level,
                    "level_admitted" => $request->current_level,
                    "phone" => $request->student_phone,
                    "email" => $request->email,

                    "church_name" => $request->church_name,
                    "phy_challenge" => $request->physical_challenge,
                    "emerg_cont_name" => $request->emergency_contact_name,
                    "emerg_cont_number" => $request->emergency_contact_number,
                    "prog_reason" => $request->program_reason,
                    "eng_lang_grade" => $request->english_language_grade,
                    "eng_lang_year" => $request->english_language_year,
                    "math_grade" => $request->mathematics_grade,
                    "math_year" => $request->mathematics_year,
                    "science_grade" => $request->science_grade,
                    "science_year" => $request->science_year,
                    "elective1_grade" => $request->elective1_grade,
                    "elective1_year" => $request->elective1_year,
                    "elective2_grade" => $request->elective_grade,
                    "elective2_year" => $request->elective2_year,
                    "sch_attended_name" => $request->school_attended_name,
                    "certificate" => $request->certificate_awarded,
                    "date_awarded" => $request->date_awarded,
                    "religious_affiliation" => $request->religious_affiliation,
                    "employer_name" => $request->employer,
                    "refree_name" => $request->refree,
                    "refree_phone" => $request->refree_phone,
                    "refree_occ" => $request->refree_occupation,
                    "refree_address" => $request->refree_address,
                    "modifydate" => date("Y-m-d"),
                    "modifyuser" => $request->createuser,
                ]);

                DB::table("tbluser")->where("userid", $student->student_no)->update([
                    "userid" => $request->student_id,
                    "email" => $request->student_id,
                ]);

                DB::table("tbluser_module_privileges")->where("userid", $student->student_no)->update([
                    "userid" => $request->student_id,
                ]);



                if (null !== $request->file("image")) {

                    $filePath = $request->file("image")->store("public/student");

                    Student::where("transid", $request->id)->update([
                        "picture" => env("APP_URL") . "/" . str_replace("public", "storage", $filePath),
                    ]);
                }
            });


            // If the return value of DB::transaction is null (meaning it's empty)
            // then it means our transaction succeeded. If this is however not the
            // case, then we throw an exception here.
            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }

            return response()->json([
                "ok" => true,
                "msg" => "Update successful",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Update failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not save student model. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function destroy($id)
    {
        $dept = DB::table("tblstudent")
            ->where("transid", $id)->update([
                "deleted" => 1
            ]);


        if (!$dept) {
            return response()->json([
                "ok" => false,
                "msg" => "An internal error ocurred",
            ]);
        }

        $student = DB::table("tblstudent")
            ->where("transid", $id)->first();

        DB::table("tbluser_module_privileges")
            ->where("userid", $student->student_no)->delete();

        DB::table("tbluser")
            ->where("userid", $student->student_no)->update([
                "deleted" => 1
            ]);


        // if (!$user) {
        //     return response()->json([
        //         "ok" => false,
        //         "msg" => "An internal error ocurred",
        //     ]);
        // }

        return response()->json([
            "ok" => true,
        ], 200);
    }

    public function restore($id)
    {
        $dept = DB::table("tblstudent")
            ->where("transid", $id)->update([
                "deleted" => 0
            ]);


        if (!$dept) {
            return response()->json([
                "ok" => false,
                "msg" => "An internal error ocurred",
            ]);
        }

        $student = DB::table("tblstudent")
            ->where("transid", $id)->first();

        DB::table("tbluser")
            ->where("userid", $student->student_no)->update([
                "deleted" => 0
            ]);

        return response()->json([
            "ok" => true,
        ], 200);
    }

    public function studentStats($schoolCode)
    {
        $totalStudentsCount = Student::where("school_code", $schoolCode)->where("deleted", "0")->count();
        $femaleStudentsCount = Student::where("school_code", $schoolCode)->where("gender", "F")->where("deleted", "0")->count();
        $maleStudentsCount = Student::where("school_code", $schoolCode)->where("gender", "M")->where("deleted", "0")->count();
        $inactiveStudents = Student::where("school_code", $schoolCode)->where("deleted", "1")->count();

        return response()->json([
            "ok" => true,
            "msg" => "Request successful",
            "data" => [
                "totalStudents" => $totalStudentsCount,
                "totalFemales" => $femaleStudentsCount,
                "totalMales" => $maleStudentsCount,
                "inactiveStudents" => $inactiveStudents,
            ],
        ]);
    }
    //filtering students
    public function filterStudent($studentdata)
    {
        $studentdata = json_decode(html_entity_decode(stripslashes($studentdata)));
        //Filtering per program, batch and session
        if ($studentdata->school != "" and $studentdata->program != "" and $studentdata->batch != ""  and $studentdata->session != "" and $studentdata->branch !="") {
            $student = DB::table("tblstudent")->select(
                "tblstudent.*",
                "tblprog.prog_desc",
                "tblbatch.batch_desc",
                "tblsession.session_desc",
                "tbllevel.level_desc",
                "tblbranch.branch_desc"
            )
                ->join("tblprog", "tblstudent.prog", "tblprog.prog_code")
                ->leftJoin("tbllevel", "tblstudent.current_level", "tbllevel.level_code")
                ->leftJoin("tblbatch", "tblstudent.batch", "tblbatch.batch_code")
                ->leftJoin("tblsession", "tblstudent.session", "tblsession.session_code")
                ->leftJoin("tblbranch", "tblstudent.branch_code", "tblbranch.branch_code")
                ->where("tblprog.school_code", $studentdata->school)
                ->where("tblstudent.school_code", $studentdata->school)
                ->where("tblbatch.school_code", $studentdata->school)
                ->where("tblsession.school_code", $studentdata->school)
                ->where("tblprog.prog_code", $studentdata->program)
                ->where("tblbranch.school_code", $studentdata->school)
                ->where("tblbatch.batch_code", $studentdata->batch)
                ->where("tblsession.session_code", $studentdata->session)
                ->where("tblbranch.branch_code",  $studentdata->branch)
                ->where("tblstudent.deleted", 0)
                ->where("tblsession.deleted", 0)
                ->where("tblbatch.deleted", 0)
                ->where("tblprog.deleted", 0)
                ->where("tblbranch.deleted", 0)
                ->get();

            return response()->json([
                "data" => StudentResource::collection($student)
            ]);
        }
        //Filtering students per program
        if ($studentdata->school != "" and $studentdata->program != "" and $studentdata->batch == ""  and $studentdata->session == "" and $studentdata->branch == "") {
            $student = DB::table("tblstudent")->select(
                "tblstudent.*",
                "tblprog.prog_desc",
                "tblbatch.batch_desc",
                "tblsession.session_desc",
                "tbllevel.level_desc"
            )
                ->join("tblprog", "tblstudent.prog", "tblprog.prog_code")
                ->leftJoin("tbllevel", "tblstudent.current_level", "tbllevel.level_code")
                ->leftJoin("tblbatch", "tblstudent.batch", "tblbatch.batch_code")
                ->leftJoin("tblsession", "tblstudent.session", "tblsession.session_code")
                ->where("tblprog.school_code", $studentdata->school)
                ->where("tblstudent.school_code", $studentdata->school)
                ->where("tblbatch.school_code", $studentdata->school)
                ->where("tblsession.school_code", $studentdata->school)
                ->where("tblprog.prog_code", $studentdata->program)
                ->where("tblstudent.deleted", 0)
                ->where("tblbatch.deleted", 0)
                ->where("tblprog.deleted", 0)
                ->get();

            return response()->json([
                "data" => StudentResource::collection($student)
            ]);
        }
        //Filtering students per batch 
        if ($studentdata->school != "" and $studentdata->program == "" and $studentdata->batch != ""  and $studentdata->session == "" and $studentdata->branch == "") {
            $student = DB::table("tblstudent")->select(
                "tblstudent.*",
                "tblprog.prog_desc",
                "tblbatch.batch_desc",
                "tblsession.session_desc",
                "tbllevel.level_desc"
            )
                ->join("tblprog", "tblstudent.prog", "tblprog.prog_code")
                ->leftJoin("tbllevel", "tblstudent.current_level", "tbllevel.level_code")
                ->leftJoin("tblbatch", "tblstudent.batch", "tblbatch.batch_code")
                ->leftJoin("tblsession", "tblstudent.session", "tblsession.session_code")
                ->where("tblprog.school_code", $studentdata->school)
                ->where("tblstudent.school_code", $studentdata->school)
                ->where("tblbatch.school_code", $studentdata->school)
                ->where("tblsession.school_code", $studentdata->school)
                ->where("tblbatch.batch_code", $studentdata->batch)
                ->where("tblstudent.deleted", 0)
                ->where("tblbatch.deleted", 0)
                ->where("tblprog.deleted", 0)
                ->get();

            return response()->json([
                "data" => StudentResource::collection($student)
            ]);
        }
        //Filtering per session
        if ($studentdata->school != "" and $studentdata->program == "" and $studentdata->batch == ""  and $studentdata->session != "" and $studentdata->branch == "") {
            $student = DB::table("tblstudent")->select(
                "tblstudent.*",
                "tblprog.prog_desc",
                "tblbatch.batch_desc",
                "tblsession.session_desc",
                "tbllevel.level_desc"
            )
                ->join("tblprog", "tblstudent.prog", "tblprog.prog_code")
                ->leftJoin("tbllevel", "tblstudent.current_level", "tbllevel.level_code")
                ->leftJoin("tblbatch", "tblstudent.batch", "tblbatch.batch_code")
                ->leftJoin("tblsession", "tblstudent.session", "tblsession.session_code")
                ->where("tblprog.school_code", $studentdata->school)
                ->where("tblstudent.school_code", $studentdata->school)
                ->where("tblbatch.school_code", $studentdata->school)
                ->where("tblsession.school_code", $studentdata->school)
                ->where("tblsession.session_code", $studentdata->session)
                ->where("tblstudent.deleted", 0)
                ->where("tblbatch.deleted", 0)
                ->where("tblprog.deleted", 0)
                ->get();

            return response()->json([
                "data" => StudentResource::collection($student)
            ]);
        }
        //filtering  per  branch
        if ($studentdata->school != "" and $studentdata->program == "" and $studentdata->batch == ""  and $studentdata->session == "" and $studentdata->branch !="") {
            $student = DB::table("tblstudent")->select(
                "tblstudent.*",
                "tblprog.prog_desc",
                "tblbatch.batch_desc",
                "tblsession.session_desc",
                "tbllevel.level_desc",
                "tblbranch.branch_desc"
            )
                ->join("tblprog", "tblstudent.prog", "tblprog.prog_code")
                ->leftJoin("tbllevel", "tblstudent.current_level", "tbllevel.level_code")
                ->leftJoin("tblbatch", "tblstudent.batch", "tblbatch.batch_code")
                ->leftJoin("tblsession", "tblstudent.session", "tblsession.session_code")
                ->leftJoin("tblbranch", "tblstudent.branch_code", "tblbranch.branch_code")
                ->where("tblprog.school_code", $studentdata->school)
                ->where("tblstudent.school_code", $studentdata->school)
                ->where("tblbatch.school_code", $studentdata->school)
                ->where("tblsession.school_code", $studentdata->school)
                ->where("tblbranch.branch_code", $studentdata->branch)
                ->where("tblsession.deleted", "0")
                ->where("tblstudent.deleted", 0)
                ->where("tblbatch.deleted", 0)
                ->where("tblprog.deleted", 0)
                ->where("tblbranch.deleted", 0)
                ->get();

            return response()->json([
                "data" => StudentResource::collection($student)
            ]);
        }
        //filtering per program and batch
        if ($studentdata->school != "" and $studentdata->program != "" and $studentdata->batch != ""  and $studentdata->session == "") {
            $student = DB::table("tblstudent")->select(
                "tblstudent.*",
                "tblprog.prog_desc",
                "tblbatch.batch_desc",
                "tblsession.session_desc",
                "tbllevel.level_desc"
            )
                ->join("tblprog", "tblstudent.prog", "tblprog.prog_code")
                ->leftJoin("tbllevel", "tblstudent.current_level", "tbllevel.level_code")
                ->leftJoin("tblbatch", "tblstudent.batch", "tblbatch.batch_code")
                ->leftJoin("tblsession", "tblstudent.session", "tblsession.session_code")
                ->where("tblprog.school_code", $studentdata->school)
                ->where("tblstudent.school_code", $studentdata->school)
                ->where("tblbatch.school_code", $studentdata->school)
                ->where("tblprog.school_code", $studentdata->school)
                ->where("tblbatch.batch_code", $studentdata->batch)
                ->where("tblprog.prog_code", $studentdata->program)
                ->where("tblstudent.deleted", 0)
                ->where("tblbatch.deleted", 0)
                ->where("tblprog.deleted", 0)
                ->get();

            return response()->json([
                "data" => StudentResource::collection($student)
            ]);
        }
        //filtering per program and session
        if ($studentdata->school != "" and $studentdata->program != "" and $studentdata->batch == ""  and $studentdata->session != "") {
            $student = DB::table("tblstudent")->select(
                "tblstudent.*",
                "tblprog.prog_desc",
                "tblbatch.batch_desc",
                "tblsession.session_desc",
                "tbllevel.level_desc"
            )
                ->join("tblprog", "tblstudent.prog", "tblprog.prog_code")
                ->leftJoin("tbllevel", "tblstudent.current_level", "tbllevel.level_code")
                ->leftJoin("tblbatch", "tblstudent.batch", "tblbatch.batch_code")
                ->leftJoin("tblsession", "tblstudent.session", "tblsession.session_code")
                ->where("tblprog.school_code", $studentdata->school)
                ->where("tblstudent.school_code", $studentdata->school)
                ->where("tblprog.school_code", $studentdata->school)
                ->where("tblsession.session_code", $studentdata->session)
                ->where("tblprog.prog_code", $studentdata->program)
                ->where("tblstudent.deleted", 0)
                ->where("tblsession.deleted", 0)
                ->where("tblprog.deleted", 0)
                ->get();

            return response()->json([
                "data" => StudentResource::collection($student)
            ]);
        }
        //filtering per program and branch
        if ($studentdata->school != "" and $studentdata->program != "" and $studentdata->batch == ""  and $studentdata->session == "" and  $studentdata->branch != "") {
            $student = DB::table("tblstudent")->select(
                "tblstudent.*",
                "tblprog.prog_desc",
                "tblbatch.batch_desc",
                "tblsession.session_desc",
                "tbllevel.level_desc",
                "tblbranch.branch_desc"
            )
                ->join("tblprog", "tblstudent.prog", "tblprog.prog_code")
                ->leftJoin("tbllevel", "tblstudent.current_level", "tbllevel.level_code")
                ->leftJoin("tblbatch", "tblstudent.batch", "tblbatch.batch_code")
                ->leftJoin("tblsession", "tblstudent.session", "tblsession.session_code")
                ->leftJoin("tblbranch", "tblstudent.branch_code", "tblbranch.branch_code")
                ->where("tblprog.school_code", $studentdata->school)
                ->where("tblstudent.school_code", $studentdata->school)
                ->where("tblprog.school_code", $studentdata->school)
                ->where("tblsession.branch_code", $studentdata->branch)
                ->where("tblprog.prog_code", $studentdata->program)
                ->where("tblstudent.deleted", 0)
                ->where("tblsession.deleted", 0)
                ->where("tblprog.deleted", 0)
                ->where("tblbranch.deleted", 0)
                ->get();

            return response()->json([
                "data" => StudentResource::collection($student)
            ]);
        }
        //Filtering per batch and session
        if ($studentdata->school != "" and $studentdata->program == "" and $studentdata->batch != ""  and $studentdata->session != "") {
            $student = DB::table("tblstudent")->select(
                "tblstudent.*",
                "tblprog.prog_desc",
                "tblbatch.batch_desc",
                "tblsession.session_desc",
                "tbllevel.level_desc"
            )
                ->join("tblprog", "tblstudent.prog", "tblprog.prog_code")
                ->leftJoin("tbllevel", "tblstudent.current_level", "tbllevel.level_code")
                ->leftJoin("tblbatch", "tblstudent.batch", "tblbatch.batch_code")
                ->leftJoin("tblsession", "tblstudent.session", "tblsession.session_code")
                ->where("tblstudent.school_code", $studentdata->school)
                ->where("tblbatch.school_code", $studentdata->school)
                ->where("tblsession.school_code", $studentdata->school)
                ->where("tblbatch.batch_code", $studentdata->batch)
                ->where("tblsession.session_code", $studentdata->session)
                ->where("tblstudent.deleted", 0)
                ->where("tblsession.deleted", 0)
                ->where("tblsession.deleted", 0)
                ->get();

            return response()->json([
                "data" => StudentResource::collection($student)
            ]);
        }
        
        //Filtering per batch and branch
        if ($studentdata->school != "" and $studentdata->program == "" and $studentdata->batch != ""  and $studentdata->session == "" and $studentdata->branch != "") {
            $student = DB::table("tblstudent")->select(
                "tblstudent.*",
                "tblprog.prog_desc",
                "tblbatch.batch_desc",
                "tblsession.session_desc",
                "tbllevel.level_desc",
                "tblbranch.branch_desc"
            )
                ->join("tblprog", "tblstudent.prog", "tblprog.prog_code")
                ->leftJoin("tbllevel", "tblstudent.current_level", "tbllevel.level_code")
                ->leftJoin("tblbatch", "tblstudent.batch", "tblbatch.batch_code")
                ->leftJoin("tblsession", "tblstudent.session", "tblsession.session_code")
                ->leftJoin("tblbranch", "tblstudent.branch_code", "tblbranch.branch_code")
                ->where("tblstudent.school_code", $studentdata->school)
                ->where("tblbatch.school_code", $studentdata->school)
                ->where("tblsession.school_code", $studentdata->school)
                ->where("tblbatch.batch_code", $studentdata->batch)
                ->where("tblsession.branch_code", $studentdata->branch)
                ->where("tblstudent.deleted", 0)
                ->where("tblsession.deleted", 0)
                ->where("tblsession.deleted", 0)
                ->where("tblbranch.deleted",0)
                ->get();

            return response()->json([
                "data" => StudentResource::collection($student)
            ]);
        }
        //Filtering per session and branch
        if ($studentdata->school != "" and $studentdata->program == "" and $studentdata->batch == ""  and $studentdata->session != "" and $studentdata->branch != "") {
            $student = DB::table("tblstudent")->select(
                "tblstudent.*",
                "tblprog.prog_desc",
                "tblbatch.batch_desc",
                "tblsession.session_desc",
                "tbllevel.level_desc"
            )
                ->join("tblprog", "tblstudent.prog", "tblprog.prog_code")
                ->leftJoin("tbllevel", "tblstudent.current_level", "tbllevel.level_code")
                ->leftJoin("tblbatch", "tblstudent.batch", "tblbatch.batch_code")
                ->leftJoin("tblsession", "tblstudent.session", "tblsession.session_code")
                ->where("tblstudent.school_code", $studentdata->school)
                ->where("tblbatch.school_code", $studentdata->school)
                ->where("tblsession.school_code", $studentdata->school)
                ->where("tblbatch.branch_code", $studentdata->branch)
                ->where("tblsession.branch_code", $studentdata->branch)
                ->where("tblstudent.deleted", 0)
                ->where("tblsession.deleted", 0)
                ->where("tblsession.deleted", 0)
                ->where("tblbranch.deleted",0)
                ->get();

            return response()->json([
                "data" => StudentResource::collection($student)
            ]);
        }
    }
}
