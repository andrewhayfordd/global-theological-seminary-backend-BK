<?php

namespace App\Http\Controllers;

use App\Http\Resources\AssessmentResource;
use App\Http\Resources\TranscriptResource;
use App\Models\Student;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AssessmentController extends Controller
{
    public function filterFetchTerminalReport(Request $request, $schoolCode)
    {
        $studentArray = [];
        $assessment = [];
        $remark = [];

        $studentDetails = Student::distinct()->select("tblstudent.*")
            ->join("tblassmain", "tblstudent.student_no", "tblassmain.student_code")
            ->where("tblstudent.deleted", "0")
            ->where("tblassmain.deleted", "0")
            ->where("tblassmain.school_code", $schoolCode)
            ->where("tblstudent.school_code", $schoolCode)
            ->where("tblstudent.batch", $request->batch)
            ->where("tblassmain.semester", $request->semester)
            ->where("tblassmain.branch_code", $request->branch)
            ->where("tblassmain.prog_code", $request->prog)
            ->get();

        foreach ($studentDetails as $student) {
            $rows = DB::table("tblassmain")->select(
                "tblassmain.semester",
                "tblassmain.course_code",
                "tblassmain.pexam",
                "tblassmain.total_test",
                "tblassmain.total_exam",
                "tblassmain.total_score",
                "tblassmain.student_code",
                "tblcourse.course_desc",
                "tblprog.prog_desc"
            )
                ->join("tblprog", "tblassmain.prog_code", "tblprog.prog_code")
                ->join("tblcourse", "tblassmain.course_code", "tblcourse.course_code")
                ->where("tblassmain.deleted", "0")
                ->where("tblprog.deleted", "0")
                ->where("tblcourse.deleted", "0")
                ->where("tblcourse.school_code", $schoolCode)
                ->where("tblassmain.school_code", $schoolCode)
                ->where("tblprog.school_code", $schoolCode)
                ->where("tblassmain.student_code", $student->student_no)
                ->where("tblassmain.branch_code", $request->branch)
                ->where("tblassmain.semester", $request->semester)
                ->where("tblassmain.prog_code", $request->prog)
                ->get();


            $studentArray[] = $student;
            $student->assessment = $rows;
        } //End of if statement

        return response()->json([
            "data" => TranscriptResource::collection($studentArray),
        ]);
    }

    //get all assessment 

    public function all(Request $request, $code)
    {

        try {
            
            ;
            $assessment = DB::table("tblassmain")
                ->select("tblstudent.fname", "tblstudent.mname", "tblstudent.lname", "tblassmain.transid as assid", "tblassmain.*")
                ->join("tblstudent", "tblassmain.student_code", "tblstudent.student_no")
                ->when( request("branch") ?? false, function ($query) {
                    return $query->where("tblassmain.branch_code", request("branch"));
                })
                ->when(request('batch') ?? false, function ($query) {
                    return $query->where("tblassmain.acyear", request('batch'));
                })
                ->when(request('semester') ?? false, function ($query) {
                    return $query->where("tblassmain.semester", request('semester'));
                })
                ->when(request('prog') ?? false, function ($query) {
                    return $query->where("tblassmain.prog_code", request('prog'));
                })
                ->where("tblassmain.school_code",$code)
                ->where("tblassmain.deleted", 0)
                ->get();


            return response()->json([
                "data" => AssessmentResource::collection($assessment)
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                "data" => null
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "course" => "required",
                "branch" => "required",
                "semester" => "required",
                "exam" => "required",
                "student" => "required",
                "test" => "required",
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. Please complete all require fields",
                "error" => [
                    "msg" => "Some required fields are missing: " . join(" ", $validator->errors()->all()),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }

        $checkTable = DB::table("tblassmain")
            ->where("student_code", $request->student)
            ->where("course_code", $request->course)
            ->where("semester", $request->semester)
            ->where("branch_code", $request->branch)
            ->get();
        if (count($checkTable)) {
            return response()->json([
                "ok" => false,
                "msg" => "Student Assessment already exists"
            ]);
        }

        if (empty($request->test) && empty($request->exam)) {
            return response()->json([
                "ok" => false,
                "msg" => "Test score or Exam score must not be empty"
            ]);
        }
        $studentDetails = Student::where("deleted", "0")->where("school_code", $request->school_code)
            ->where("student_no", $request->student)->first();

        // fetch the data you want to update
        // $assess = DB::table("tblassmain")->where("deleted", "0")
        //     ->where("school_code", $request->school_code)
        //     ->where("student_code", $request->student)
        //     ->where("course_code", $request->course)
        //     ->where("acyear", $studentDetails->batch)
        //     ->where("semester", $request->semester)
        //     ->first();

        // if (!empty($assess)) {
        //     return response()->json([
        //         "ok" => false,
        //         "msg" => "Student Assessment already exists"
        //     ]);
        // }


        try {
            $transactionResult = DB::transaction(function () use ($request, $studentDetails) {

                // $classTotal = $request->class_work + $request->home_work + $request->class_test;
                $classTotal = $request->test;
                $percentageClass = 0.40 * ($classTotal);
                $percentageExams = 0.60 * ($request->exam);
                $totalScore = $percentageExams + $percentageClass;

                DB::table("tblassmain")->updateOrInsert([
                    "total_exam" => $percentageExams,
                    "total_test" => $percentageClass,
                    "total_score" => $totalScore,
                ], [
                    "transid" => strtoupper(bin2hex(random_bytes(5))),
                    "school_code" => $request->school_code,
                    "student_code" => $request->student,
                    "branch_code" => $request->branch,
                    "acyear" => $studentDetails->batch,
                    "semester" => $request->semester,
                    "course_code" => $request->course,
                    "total_exam" => $percentageExams,
                    "total_test" => $percentageClass,
                    "total_score" => $totalScore,
                    "prog_code" => $studentDetails->prog,
                    "deleted" => "0",
                    "createdate" => date("Y-m-d"),
                    "createuser" => $request->createuser,
                ]);
            });

            // If the return value of DB::transaction is null (meaning it's empty)
            // then it means our transaction succeeded. If this is however not the
            // case, then we throw an exception here.
            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }

            return response()->json([
                "ok" => true,
                "msg" => "Assessment added successfully",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact your administrator",
                "error" => [
                    "msg" => "An internal error ocurred Could not update assessment. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function delete($code)
    {
        try {
            request()->merge([
                "assid" => $code
            ]);

            $validator = Validator::make(
                request()->all(),
                [
                    "assid" => "required|exists:tblassmain,transid"
                ],
                [
                    "assid.required" => "Assessment code is required",
                    "assid.exists" => "Assessment code is cannir be found"
                ]
            );


            if ($validator->fails()) {
                return response()->json([
                    "ok" => false,
                    "msg" => "Deleting failed ," . join(" ,", $validator->errors()->all())
                ]);
            }

            $update =  DB::table("tblassmain")
                ->where("transid", $code)
                ->update(
                    [
                        "deleted" => 1
                    ]
                );
            if (!$update) {
                return response()->json([
                    "ok" => false,
                    "msg" => "Sorry! An i"
                ]);
            }


            return response()->json([
                "ok" => true,
                "msg" => "Student assessment deleted"
            ]);
        } catch (Exception $th) {
            Log::error($th->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Sorry! An internal error occured"
            ]);
        }
    }

    public function update(Request  $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "assessment_id" => "required",
                "course" => "required",
                "branch" => "required",
                "semester" => "required",
                "exam" => "required",
                "student" => "required",
                "test" => "required",
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. Please complete all require fields",
                "error" => [
                    "msg" => "Some required fields are missing: " . join(" ", $validator->errors()->all()),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }

        if (empty($request->test) && empty($request->exam)) {
            return response()->json([
                "ok" => false,
                "msg" => "Test score or Exam score must not be empty"
            ]);
        }
        $studentDetails = Student::where("deleted", "0")->where("school_code", $request->school_code)
            ->where("student_no", $request->student)->first();

        try {
            $transactionResult = DB::transaction(function () use ($request, $studentDetails) {

                // $classTotal = $request->class_work + $request->home_work + $request->class_test;
                $classTotal = $request->test;
                $percentageClass = 0.40 * ($classTotal);
                $percentageExams = 0.60 * ($request->exam);
                $totalScore = $percentageExams + $percentageClass;

                DB::table("tblassmain")
                    ->where("transid", $request->assessment_id)
                    ->update([
                        "school_code" => $request->school_code,
                        "student_code" => $request->student,
                        "branch_code" => $request->branch,
                        "acyear" => $studentDetails->batch,
                        "semester" => $request->semester,
                        "course_code" => $request->course,
                        "total_exam" => $percentageExams,
                        "total_test" => $percentageClass,
                        "total_score" => $totalScore,
                        "prog_code" => $studentDetails->prog,
                        "deleted" => "0",
                        "modifydate" => date("Y-m-d"),
                        "modifyuser" => $request->createuser,
                    ]);
            });

            // If the return value of DB::transaction is null (meaning it's empty)
            // then it means our transaction succeeded. If this is however not the
            // case, then we throw an exception here.
            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }

            return response()->json([
                "ok" => true,
                "msg" => "Assessment edit successfully",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Editing failed. An internal error occured. If this continues please contact your administrator",
                "error" => [
                    "msg" => "An internal error ocurred Could not update assessment. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function filterAssessment()
    {
    }
}
