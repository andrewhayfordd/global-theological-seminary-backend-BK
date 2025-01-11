<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Bills;
use App\Models\School;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\API\PaymentHistoryResource;
use Illuminate\Database\Query\Builder;

class MobileStudentController extends Controller
{
    public function fetchStudentCourses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "school_code" => "required",
            "semester" => "required",
            "program" => "required",
        ], [

            "school_code.required" => "No school code supplied",
            "program.required" => "No program supplied",
            "semester.required" => "No semester supplied"
        ]);


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Fetching courses failed: " . join(" ", $validator->errors()->all()),
                "error" => [
                    "msg" => "Some required fields are missing: " . join(" ", $validator->errors()->all()),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }
        $course = DB::table('tblcourse')->select(
            "course_code",
            "course_title",
            "credit",
            "course_desc"
        )
            ->where('prog', $request->program)
            ->where('deleted', '0')
            ->where('semester', $request->semester)
            ->where('school_code', $request->school_code)
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $course
        ]);
    }

    public function fetchStudentArrears(Request $request)
    {
        $bill = DB::table("vtblbill_total")->where("student_no", $request->student_no)
            ->where("school_code", $request->school_code)
            ->sum("total_bill");

        $paid = DB::table("vtbloverall_total")->where("student_no", $request->student_no)
            ->where("school_code", $request->school_code)
            ->first();

        if (empty($paid)) {
            $amount = 0;
            return response()->json([
                "ok" => true,
                "data" => $amount
            ]);
        }

        $amount =  $bill - $paid->total_paid;

        return response()->json([
            "ok" => true,
            "data" => $amount
        ]);
    }

    public function checkBillAndRegistration($schoolCode, $studentCode)
    {
        // TODO (Maxwell): Check if student has fully paid fees

        $currentTerm = DB::table('tblacyear')->where('school_code', $schoolCode)->where('deleted', '0')->where('current_term', '1')->first();
        $checkRegistration = DB::table('tblcourse_reg')->where('student_code', $studentCode)
            ->where('acyear', $currentTerm->acyear_code)
            ->where('semester', $currentTerm->acterm)
            ->get();
        if (!empty($checkRegistration)) {
            $has_registered = '1';
        } else {
            $has_registered = '0';
        }
        return response()->json([
            'ok' => true,
            'data' => [
                'has_paid' => '1',
                'has_registered' => '0',
            ]
        ]);
    }

    public function registerCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "school_code" => "required",
            "prog_code" => "required",
            "acyear" => "required",
            "semester" => "required",
            "student_code" => "required",
            "course_code" => "required",
            "reg_date" => "required"
        ], [

            "school_code.required" => "No school code supplied",
            "prog_code.required" => "No program code supplied",
            "acyear.required" => "No academic year supplied",
            "semester.required" => "No semester supplied",
            "student_code.required" => "No student code supplied",
            "course_code.required" => "No course code supplied",
            "reg_date.required" => "No registration date supplied",
        ]);


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Registering course failed: " . join(" ", $validator->errors()->all()),
                "error" => [
                    "msg" => "Some required fields are missing: " . join(" ", $validator->errors()->all()),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }

        // $program = DB::table("tblprog")->where("deleted", 0)
        //     ->where("school_code", $request->school_code)
        //     ->where("prog_code", $request->prog_code)->first();

        // if (!empty($program)) {
        //     return response()->json([
        //         "ok" => false,
        //         "msg" => "Program already exist"
        //     ]);
        // }

        // Make sure that the supplied school code exists and belongs to a
        // school in the system
        $school = School::where("school_code", $request->school_code)->where("deleted", "0")->first();
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

        try {
            $transactionResult = DB::transaction(function () use ($request) {
                $courses = json_decode($request->course_code);
                foreach ($courses as $course) {
                    $creditHours = DB::table('tblcourse')->where('deleted', '0')->where('course_code', $course)->select('credit')->first();
                    DB::table('tblcourse_reg')->insert([
                        "transid" => strtoupper(bin2hex(random_bytes(5))),
                        "school_code" => $request->school_code,
                        "branch_code" => $request->branch_code,
                        "acyear" => $request->acyear,
                        "semester" => $request->semester,
                        "student_code" => $request->student_code,
                        "prog_code" => $request->prog_code,
                        "course_code" => $course,
                        "credit" =>  $creditHours->credit,
                        "reg_date" => $request->reg_date,
                        "createdate" => date("Y-m-d"),
                        "createuser" => $request->createuser,
                    ]);
                }
            });

            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }

            return response()->json([
                "ok" => true,
                "msg" => "Registration successful"
            ], 200);
        } catch (\Exception $e) {
            Log::error("\n\Registering course failed", [
                "errMsg" => $e->getMessage(),
                "trace" => $e->getTrace(),
                "request" => $request->all(),
            ]);
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add program. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ], 500);
        }
    }

    public function allStudents($staffno)
    {
        $newCourses = DB::table('tblcourse')->select('tblcourse.course_code')
            ->join('tblcourse_assignment', "tblcourse_assignment.course_code", 'tblcourse.course_code')
            ->where('tblcourse.deleted', '0')
            ->where('tblcourse_assignment.deleted', '0')
            ->where('tblcourse_assignment.staffno', $staffno)
            ->get()->toArray();

        $courseCode = [];
        foreach ($newCourses as  $value) {
            $courseCode[] = $value->course_code;
        }

        $students = DB::table("tblcourse_reg")->select('tblstudent.*')
            ->join("tblstudent", "tblstudent.student_no", "tblcourse_reg.student_code")
            ->join("tblcourse", "tblcourse.course_code", "tblcourse_reg.course_code")
            ->whereIn("tblcourse_reg.course_code", $courseCode)
            ->where("tblstudent.deleted", 0)
            ->where("tblcourse.deleted", 0)
            ->where("tblcourse_reg.deleted", 0)
            ->get();

        return response()->json([
            "ok" => true,
            "msg" => "Request successful",
            "data" => $students
        ]);
    }

    public function suggestion(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "email" => "required",
                "phone" => "required",
                "suggestion" => "required",

            ]
        );

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding suggestion failed." . join(" ", $validator->errors()->all()),

            ]);
        }
        try {
            DB::table("tblsuggestion")->insert([
                "transid" => "TRANS" . strtoupper(bin2hex(random_bytes(5))),
                "suggestion_code" => "SG" . strtoupper(bin2hex(random_bytes(4))),
                "school_code" => $request->school_code,
                "branch_code" => $request->branch_code,
                "email" => $request->email,
                "phone" => $request->phone,
                "suggestion" => $request->suggestion,
                "deleted" => "0",
                "createdate" => date("Y-m-d"),
                "createuser" => $request->createuser,
            ]);


            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }

            return response()->json([
                "ok" => true,
                "msg" => "Suggestion added successful",
            ]);
        } catch (\Exception $e) {
            Log::error("Failed  adding suggestion: " . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Adding suggestion failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not save student model. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function fetchStudentBillHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "school_code" => "required",
            "semester" => "required",
            "student_no" => "required",
        ], [

            "school_code.required" => "No school code supplied",
            "student_no.required" => "No student supplied",
            "semester.required" => "No semester supplied"
        ]);


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Fetching payment failed: " . join(" ", $validator->errors()->all()),
                "error" => [
                    "msg" => "Some required fields are missing: " . join(" ", $validator->errors()->all()),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }

        $student = Student::where("school_code", $request->school_code)->where("student_no", $request->Student_no)
            ->where("deleted", 0)->first();

        $data  = Bills::select("tblbills.amount", "tblbill_items.bill_desc")
            ->join("tblbill_item", "tblbill_item.bill_code", "tblbill.item")
            ->where("tblbills.school_code", $request->school_code)
            ->where("tblbill_item.school_code", $request->school_code)
            ->where("tblbills.batch_code", $student->batch)
            ->where("tblbills.sem_code", $request->semester)
            ->where("tblbills.student_no", $request->student)
            ->where("tblbills.deleted", 0)
            ->where("tblbill_item.deleted", 0)
            ->get();

        return response()->json([
            "ok" => true,
            "msg" => "Request successful",
            "data" => $data
        ]);
    }

    public function fetchPaymentHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "school_code" => "required",
            "student_no" => "required",
            "semester" => "sometimes"
        ], [

            "school_code.required" => "No school code supplied",
            "from_date.required" => "No date supplied",
            "to_date.required" => "No date supplied",
            "student_no.required" => "No student number supplied",
        ]);


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Fetching payment failed: " . join(" ", $validator->errors()->all()),
                "error" => [
                    "msg" => "Some required fields are missing: " . join(" ", $validator->errors()->all()),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }

        // if (!empty($request->semester)) {
        $data = PaymentHistoryResource::collection(
            DB::table("tblledger_student")->distinct()->select(
                "tblledger_student.*",
                "tblpayment.cheque_bank",
                "tblpayment.network",
                "tblpayment.phone_number",
                "tblpayment.payment_date",
                "tblpayment.payment_type",
                "tblpayment.cur_balance",
                "tblpayment.cheque_no",
                "tblstudent.fname",
                "tblstudent.lname",
                "tblprog.prog_desc",
                "tblsession.session_desc",
                "tblstudent.phone",
                "tblbatch.batch_desc",
                // "tblbranch.branch_desc",
                "tblsemester.sem_desc",
                "tblstudent.mname"
            )
                ->join("tblstudent", "tblstudent.student_no", "tblledger_student.student_no")
                ->join("tblbatch", "tblstudent.batch", "tblbatch.batch_code")
                ->join("tblsession", "tblstudent.session", "tblsession.session_code")
                ->join("tblprog", "tblstudent.prog", "tblprog.prog_code")
                ->join("tblsemester", "tblsemester.sem_code", "tblledger_student.sem_code")
                ->join("tblpayment", "tblledger_student.ref_code", "tblpayment.receipt_no")
                ->when($request->semester ?? false, function ($query) use ($request) {
                    return  $query->where('tblpayment.semester', $request->semester);
                })
                // ->where("tblledger_student.sem_code", $request->semester)
                ->where("tblledger_student.student_no", $request->student_no)
                ->where("tblpayment.student_no", $request->student_no)
                // ->where("tblpayment.branch", $request->branch)
                ->where("tblpayment.deleted", "0")
                ->where("tblstudent.deleted", "0")
                ->where("tblstudent.school_code", $request->school_code)
                ->where("tblpayment.school_code", $request->school_code)
                ->where("tblledger_student.type", "payment")
                ->where("tblledger_student.deleted", "0")
                // ->where("tblledger_student.branch_code", $request->branch)
                ->where("tblledger_student.school_code", $request->school_code)
                // ->whereBetween("tblpayment.payment_date", [$request->from, $request->to])
                ->orderByDesc("tblpayment.payment_date")
                ->limit(10)
                ->get()
        );
        return response()->json([
            "ok" => true,
            "msg" => "Request successful",
            "data" => $data
        ]);
    }

    public function fetchStudentTotalBill(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "school_code" => "required",
            "student_no" => "required",
            "semester" => "required"
        ], [

            "school_code.required" => "No school code supplied",
            "student_no.required" => "No student supplied",
            "semester.required" => "No semester supplied"
        ]);


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Fetching total bill failed: " . join(" ", $validator->errors()->all()),
                "error" => [
                    "msg" => "Some required fields are missing: " . join(" ", $validator->errors()->all()),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }

        $data  = DB::table("vtblbill_total")->where("school_code", $request->school_code)
            ->where("student_no", $request->student_no)
            ->where("semester", $request->semester)
            ->first();

        return response()->json([
            "ok" => true,
            "msg" => "Request successful",
            "data" => $data
        ]);
    }

    public function fetchStudentTotalPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "school_code" => "required",
            "student_no" => "required",
            "semester" => "required"
        ], [

            "school_code.required" => "No school code supplied",
            "student_no.required" => "No student supplied",
            "semester.required" => "No semester supplied"
        ]);


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Fetching total payment failed: " . join(" ", $validator->errors()->all()),
                "error" => [
                    "msg" => "Some required fields are missing: " . join(" ", $validator->errors()->all()),
                    "fix" => "Please complete all required fields",
                ]
            ]);
        }

        $data  = DB::table("vtblpayment_total")->where("school_code", $request->school_code)
            ->where("student_no", $request->student_no)
            ->where("semester", $request->semester)
            ->first();

        return response()->json([
            "ok" => true,
            "msg" => "Request successful",
            "data" => $data
        ]);
    }

    public function stu_service(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "service_code" => "required",
            "student_no" => "required",
            "school_code" => "required",
            "collection_date" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'msg' => "Adding service failed." . join(" ", $validator->errors()->all())
            ]);
        }

        try {
            $serviceDetails = DB::table('tblservices')
                ->select('service_cost')
                ->where('service_code', $request->service_code)
                ->first();

            DB::table('tblservice_student')->insert([
                "transid" => "TRANS" . strtoupper(bin2hex(random_bytes(5))),
                "school_code" => $request->school_code,
                "service_code" => $request->service_code,
                "service_cost" => $serviceDetails->service_cost,
                "student_no" => $request->student_no,
                "collection_date" => $request->collection_date,
                "status" => 'pending',
                "deleted" => 0
            ]);

            return response()->json([
                'ok' => true,
                'msg' => "Service added successfully"
            ]);
        } catch (Exception $e) {
            Log::error("Adding service failed: " . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Adding service failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add service. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function service_dropdown()
    {
        $dropdown = DB::table('tblservices')->select('service_code', 'service_name', 'service_cost')
            ->where('deleted', 0)
            ->get()->toArray();

        return response()->json([
            'ok' => true,
            'msg' => "Request Successful",
            'data' => $dropdown
        ]);
    }

    public function get_requests()
    {
        $request = DB::table('tblservice_student')
            ->select(
                'tblservice_student.request_date',
                'tblstudent.fname',
                'tblstudent.mname',
                'tblstudent.lname',
                'tblservice_student.collection_date',
                'tblservice_student.service_code',
                'tblservices.service_name',
                'tblservice_student.status'
            )
            ->join('tblstudent', 'tblservice_student.student_no', 'tblstudent.student_no')
            ->join('tblservices', 'tblservice_student.service_code', 'tblservices.service_code')
            ->get()->toArray();

        return response()->json([
            'ok' => true,
            'msg' => "Request Successful",
            'data' => $request
        ]);
    }

    public function get_stuRequest($userId)
    {
        $stuRequest = DB::table('tblservice_student')
            ->select(
                'tblservice_student.service_cost',
                'tblservice_student.collection_date',
                'tblservice_student.status',
                'tblservices.service_name',
                'tblservice_student.id'
            )
            ->join('tblservices', 'tblservice_student.service_code', 'tblservices.service_code')
            ->where('tblservice_student.student_no', $userId)
            ->where('tblservice_student.deleted', 0)
            ->get()->toArray();

        // Convert 'id' from integer to string
        foreach ($stuRequest as $request) {
            $request->id = strval($request->id);
        }

        return response()->json([
            'ok' => true,
            'msg' => "Request Successful",
            'data' => $stuRequest
        ]);
    }

    public function deleteService($id)
    {
        try {
            // Check if the service exists and its status is 'pending'
            $service = DB::table('tblservice_student')
                ->where('id', $id)
                ->first();

            if (!$service) {
                return response()->json([
                    'ok' => false,
                    'msg' => 'Service not found'
                ]);
            }

            if ($service->status !== 'pending') {
                return response()->json([
                    'ok' => false,
                    'msg' => 'Service status is not pending. It cannot be deleted.'
                ]);
            }

            // Check if the service has already been deleted
            if ($service->deleted) {
                return response()->json([
                    'ok' => false,
                    'msg' => 'Service has already been deleted'
                ]);
            }
            // Perform the deletion
            DB::table('tblservice_student')
                ->where('id', $id)
                ->where('status', 'pending')
                ->update(['deleted' => 1]);
            return response()->json([
                'ok' => true,
                'msg' => 'Service deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error("Deleting service failed:" . $e->getMessage());
            return response()->json([
                'ok' => false,
                'msg' => "Deleting service failed. An internal error occured. If this continues, please contact an administrator.",
                'error' => [
                    'msg' => "Deleting service failed . {$e->getMessage()}",
                    'fix' => "Check errors for clues"
                ]
            ]);
        }
    }

    public function editService(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'service_code' => 'required',
            'collection_date' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'msg' => 'Updating service failed.' . join(" ", $validator->errors()->all())
            ]);
        }

        try {
            $status = DB::table('tblservice_student')
                ->where('id', $id)
                ->first();

            if ($status->deleted) {
                return response()->json([
                    'ok' => false,
                    'msg' => "Item has already been deleted so cannot be updated"
                ]);
            }

            DB::table('tblservice_student')
                ->where('id', $id)
                ->where('deleted', 0)
                ->update([
                    'service_code' => $request->service_code,
                    'collection_date' => $request->collection_date
                ]);

            return response()->json([
                'ok' => true,
                'msg' => "Service updated successfully"
            ]);
        } catch (\Exception $e) {
            Log::error("Updating service failed: " . $e->getMessage());
            return response()->json([
                'ok' => false,
                'msg' => "Updating service failed. An internal error occured. If this continues, please contact an administrator",
                'error' => [
                    'msg' => "Updating service failed . {{$e->getMessage()}}",
                    'fix' => "Check errors for clues"
                ]
            ]);
        }
    }


    public function assessment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'student_code' => 'required',
            'school_code' => 'required',
            'branch_code' => 'required',
            'semester' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'msg' => 'Assessment request failed' . join(' ', $validator->errors()->all())
            ]);
        }

        try {
            // $assessment = [];

            $assessmentReselt = DB::table('tblassmain')

                ->select(
                    'tblassmain.total_test',
                    'tblassmain.total_exam',
                    'tblassmain.total_score',
                    'tblassmain.course_code',
                    'tblcourse.course_title'
                )
                ->when($request->semester ?? false, function ($query) use ($request) {
                    return  $query->where('tblassmain.semester', $request->semester);
                })
                ->join('tblcourse', 'tblassmain.course_code', 'tblcourse.course_code')
                ->where('tblassmain.student_code', $request->student_code)
                // ->where('tblassmain.semester', $request->semester)
                ->where('tblassmain.branch_code', $request->branch_code)
                ->where('tblassmain.school_code', $request->school_code)
                ->get()->toArray();

            return response()->json([
                'ok' => true,
                'msg' => 'Response successful',
                'data' => $assessmentReselt,
            ]);
        } catch (\Throwable $e) {
            Log::error("Fetching assessment failed: " . $e->getMessage());
            return response()->json([
                'ok' => false,
                'msg' => "Fetching assessment failed. An internal error occured. If this continues, please contact an administrator",
                'error' => [
                    'msg' => "Failed . {{$e->getMessage()}}",
                    'fix' => "Check errors for clues"
                ]
            ]);
        }
    }

    public function student_courses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_code' => 'required',
            'semester' => 'required',
            'school_code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'msg' => 'Failed to fetch courses' . join(' ', $validator->errors()->all())
            ]);
        }

        try {
            // $assessment = [];

            $assessmentReselt = DB::table('tblcourse_reg')

                ->select(
                    'tblcourse_reg.course_code',
                    'tblcourse.course_title',
                    'tblcourse.course_desc',
                )



                ->join('tblcourse', 'tblcourse_reg.course_code', 'tblcourse.course_code')
                ->where('tblcourse_reg.student_code', $request->student_code)
                ->where('tblcourse_reg.school_code', $request->school_code)
                ->get()->toArray();

            return response()->json([
                'ok' => true,
                'msg' => 'Response successful',
                'data' => $assessmentReselt,
            ]);
        } catch (\Throwable $e) {
            Log::error("Fetching assessment failed: " . $e->getMessage());
            return response()->json([
                'ok' => false,
                'msg' => "Fetching assessment failed. An internal error occured. If this continues, please contact an administrator",
                'error' => [
                    'msg' => "Failed . {{$e->getMessage()}}",
                    'fix' => "Check errors for clues"
                ]
            ]);
        }
    }
}
