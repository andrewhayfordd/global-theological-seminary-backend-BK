<?php

namespace App\Http\Controllers;

use App\Http\Resources\BillItemAmountResource;
use App\Http\Resources\BillItemAmountResourceController;
use App\Http\Resources\BillResourceController;
use App\Http\Resources\IndividualBillResource;
use App\Http\Resources\ProgramBillResource;
use App\Http\Resources\StudentBillResourceController;
use App\Models\Bills;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{

    public function fetchProgramBill(Request $request, $schoolCode)
    {
        $bills = ProgramBillResource::collection(
            Student::select(
                "tblstudent.*",
                "vtblbill_total.total_bill"
            )
                ->join("vtblbill_total", "tblstudent.student_no", "vtblbill_total.student_no")
                ->where("tblstudent.school_code", $schoolCode)
                ->where("tblstudent.prog", $request->program)
                ->where("tblstudent.batch", $request->batch)
                ->where("vtblbill_total.semester", $request->semester)
                ->where("vtblbill_total.branch", $request->branch)
                ->where("tblstudent.deleted", "0")
                ->get()
        );

        return response()->json([
            "data" => $bills
        ]);
    }

    public function fetchIndividualBillItems($chool_code, $studentNo)
    {
        $student = DB::table("tblstudent")->where("school_code", $chool_code)
            ->where("student_no", $studentNo)
            ->where("deleted", "0")->first();
        $data = DB::table("tblbill_item")->where("school_code", $chool_code)
            ->where("batch_code", $student->batch)->where("deleted", "0")->get();
        return response()->json([
            "data" => $data
        ]);
    }

    public function fetchProgrammeBillItems($schoolCode, $bactchNo)
    {
        $data = DB::table("tblbill_item")->where("school_code", $schoolCode)
            ->where("prog_code", $bactchNo)->where("deleted", "0")->get();
        return response()->json([
            "data" => $data
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($school_code)
    {
        $Bills = BillResourceController::collection(
            DB::table("tblbill_item")->where("school_code", $school_code)->where("deleted", "0")
                ->orderByDesc("createdate")->get()
        );
        return response()->json([
            "data" => $Bills
        ]);
    }

    public function fetchStudentBill(Request $request, $school_code)
    {
        $bills = IndividualBillResource::collection(
            DB::table("tblbills")
                ->distinct()->select(
                    "tblbills.*",
                    "tblstudent.*",
                    "tblsemester.sem_desc",
                    "tblprog.prog_desc",
                    "tblbill_item.bill_desc"
                )
                ->join("tblbill_item", "tblbills.item", "tblbill_item.bill_code")
                ->join("tblsemester", "tblsemester.sem_code", "tblbills.sem_code")
                ->join("tblstudent", "tblbills.student_no", "tblstudent.student_no")
                ->join("tblprog", "tblprog.prog_code", "tblstudent.prog")
                ->where("tblbills.sem_code", $request->semester)
                ->where("tblbills.student_no", $request->student)
                ->where("tblbills.branch_code", $request->branch)
                ->where("tblbills.deleted", "0")
                ->where("tblbill_item.deleted", "0")
                ->where("tblstudent.deleted", "0")
                ->where("tblsemester.deleted", "0")
                ->where("tblstudent.school_code", $school_code)
                ->where("tblbills.school_code", $school_code)
                ->where("tblbill_item.school_code", $school_code)
                ->get()
        );

        return response()->json([
            "data" => $bills
        ]);
    }

    public function fetchStudentTotalBill(Request $request)
    {
        $bills = DB::table("vtblbill_total")->select("total_bill")->where("student_no", $request->student)
            ->where("semester", $request->semester)
            ->where("branch", $request->branch)
            ->where("school_code", $request->school_code)->first();

        if (empty($bills)) {
            return response()->json([
                "ok" => false,
                "msg" => "No record found"
            ]);
        }

        return response()->json([
            "ok" => true,
            "data" => $bills
        ]);
    }

    //Adding Bill Item 
    public function addBillItem(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'desc' => "required",
                "program" => "required",
                // "batch" => "required",
                "semester" => "required",
                "branch" => "required",
                "amount" => "required"
            ],
            [
                'desc.required' => 'No bill item name provided ',
                "program.required" => "No program selected",
                // "batch.required" => "No batch selected",
                "semester.required" => "No semester selected",
                "branch.required" => "No branch selected"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding bill item failed. " . join(". ", $validator->errors()->all()),
            ]);
        }
        try {
            //code...
            $billItemNum = DB::table("tblbill_item")
                ->where('school_code', $request->school_code)
                ->get();
            $tableCount = $billItemNum->count();
            $tableCount++;
            $bill_code = 'BIT' . str_pad($tableCount, 4, "0", STR_PAD_LEFT);
            DB::table("tblbill_item")->insert([
                'transid' => strtoupper(bin2hex(random_bytes(5))),
                "school_code" => $request->school_code,
                "bill_code" => $bill_code,
                "bill_desc" => $request->desc,
                "branch_code" => $request->branch,
                "prog_code" => $request->program,
                // "batch_code" => $request->batch,
                "sem_code" => $request->semester,
                "amount" => $request->amount,
                "deleted" => "0",
                "source" => "1",
                "import" => null,
                "export" => null,
                'createuser' =>  $request->createuser,
                'createdate' => date('Y-m-d'),
            ]);
            return response()->json([
                "ok" => true,
            ]);
        } catch (\Throwable $e) {
            Log::error("Failed adding bill item: " . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Adding bill item failed!",
            ]);
        }
    }

    //Adding student bills
    public function addStudentBill(Request $request)
    {
        $validator = validator::make($request->all(), [
            "student_no" => "required",
            "branch" => "required",
            "semester" => "required",
        ], [
            "student_no.required" => "No student selected",
            "branch.required" => "No branch selected",
            "semester.required" => "No semester selected",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding bill to student failed. " . join(". ", $validator->errors()->all())
            ]);
        }

        //Fetch student details  
        $studentDetails = Student::where("deleted", "0")
            ->where("student_no", $request->student_no)
            ->where("school_code", $request->school_code)
            ->first();
        if (empty($studentDetails)) {
            return response()->json([
                "ok" => false,
                "msg" => "Unknown student",
            ]);
        }
        if (empty(json_decode($request->billItems))) {
            return response()->json([
                "ok" => false,
                "msg" => "Student does not have any bill items",
            ]);
        }

        try {
            $transactionResult = DB::transaction(function () use ($request, $studentDetails) {

                $checkBill = Bills::where("branch_code", $request->branch)
                    ->where("sem_code", $request->semester)
                    ->where("student_no", $request->student_no)
                    ->where("school_code", $request->school_code)
                    ->where("deleted", "0")
                    ->first();

                if (empty($checkBill)) {
                    foreach (json_decode($request->billItems) as $items => $amount) {
                        DB::table("tblbills")->insert([
                            "transid" => strtoupper(bin2hex(random_bytes(5))),
                            "school_code" => $request->school_code,
                            "branch_code" => $request->branch,
                            "student_no" => $request->student_no,
                            "sem_code" => $request->semester,
                            "item" => $items,
                            "amount" => $amount,
                            "deleted" => "0",
                            "createdate" => date("Y-m-d"),
                            "createuser" => $request->createuser,
                        ]);
                    }
                } else {

                    Bills::where("school_code", $request->school_code)
                        ->where("deleted", "0")
                        ->where("student_no", $request->student_no)
                        ->where("sem_code", $request->semester)
                        ->where("branch_code", $request->branch)
                        ->delete();

                    foreach (json_decode($request->billItems) as $items => $amount) {
                        DB::table("tblbills")->insert([
                            "transid" => strtoupper(bin2hex(random_bytes(5))),
                            "school_code" => $request->school_code,
                            "branch_code" => $request->branch,
                            "student_no" => $request->student_no,
                            "sem_code" => $request->semester,
                            "item" => $items,
                            "amount" => $amount,
                            "deleted" => "0",
                            "createdate" => date("Y-m-d"),
                            "createuser" => $request->createuser,
                        ]);
                    }
                }

                //Set default value
                $defaultValue = 0;

                $studentLedger = StudentLedger::where("school_code", $request->school_code)
                    ->where("deleted", "0")->where("student_no", $request->student_no)
                    ->where("type", "b")
                    ->where("branch_code", $request->branch)
                    ->where("sem_code", $request->semester)
                    ->first();

                if (empty($studentLedger)) {
                    foreach (json_decode($request->billItems) as $items => $amount) {
                        $allAmounts = $defaultValue + (int)$amount;
                        //Insert into table ledger student
                        DB::table("tblledger_student")->insert([
                            "transid" => strtoupper(bin2hex(random_bytes(5))),
                            "school_code" => $request->school_code,
                            "branch_code" => $request->branch,
                            "type" => "b",
                            "student_no" => $request->student_no,
                            "sem_code" => $request->semester,
                            "debit" => $amount,
                            "item_code" => $items,
                            "balance" => $allAmounts,
                            "deleted" => "0",
                            "createdate" => date("Y-m-d"),
                            "createuser" => $request->createuser,
                        ]);
                    }
                } else {

                    StudentLedger::where("school_code", $request->school_code)
                        ->where("deleted", "0")->where("student_no", $request->student_no)
                        ->where("branch_code", $request->branch)
                        ->where("type", "b")
                        ->where("sem_code", $request->semester)
                        ->delete();

                    foreach (json_decode($request->billItems) as $items => $amount) {
                        $allAmounts = $defaultValue + (int)$amount;
                        DB::table("tblledger_student")->insert([
                            "transid" => strtoupper(bin2hex(random_bytes(5))),
                            "branch_code" => $request->branch,
                            "school_code" => $request->school_code,
                            "type" => "b",
                            "student_no" => $request->student_no,
                            "sem_code" => $request->semester,
                            "debit" => $amount,
                            "item_code" => $items,
                            "balance" => $allAmounts,
                            "deleted" => "0",
                            "createdate" => date("Y-m-d"),
                            "createuser" => $request->createuser,
                        ]);
                    }
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
                "msg" => "Bill successfully added!"
            ]);
        } catch (\Throwable $e) {
            Log::error("Failed adding bill to student: " . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Adding bill to student failed!",

            ]);
        }
    }

    public function addProgrammeBill(Request $request)
    {
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                "branch" => "required",
                "semester" => "required",
                "batch" => "required",
                "program" => "required",
                "school_code" => "required",
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

        //Fetch student details for the particular programme  
        $studentDetails = DB::table("tblstudent")->where("deleted", "0")
            ->where("prog", $request->program)
            ->where("batch", $request->batch)
            ->where("branch_code", $request->branch)
            ->where("school_code", $request->school_code)
            ->get();
        // return $studentDetails;
        if (count($studentDetails) === 0) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding bill(s) failed, there are no students available for this programme",
            ]);
        }

        //Check if bill items exist
        $checkBill = DB::table("tblbills")
            ->where("batch_code", $request->batch)
            ->where("branch_code", $request->branch)
            ->where("sem_code", $request->semester)
            ->where("prog_code", $request->program)
            ->where("batch_code", $request->batch)
            ->where("school_code", $request->school_code)
            ->where("deleted", "0")
            ->first();
        if (!empty($checkBill)) {
            return response()->json([
                "ok" => false,
                "msg" => "Bill items already exits for this programme, 
                please try generating a different bill for this programme",
            ]);
        }


        try {
            $transactionResult = DB::transaction(function () use ($request, $studentDetails) {

                foreach ($studentDetails as $students) {
                    foreach (json_decode($request->billItems) as $items => $amount) {
                        DB::table("tblbills")->insert([
                            "transid" => strtoupper(bin2hex(random_bytes(5))),
                            "school_code" => $request->school_code,
                            "branch_code" => $request->branch,
                            "student_no" => $students->student_no,
                            "sem_code" => $request->semester,
                            "batch_code" => $request->batch,
                            "item" => $items,
                            "amount" => $amount,
                            "deleted" => "0",
                            "createdate" => date("Y-m-d"),
                            "createuser" => $request->createuser,
                        ]);
                    }
                }

                //Set default value
                $defaultValue = 0;

                foreach ($studentDetails as $students) {
                    foreach (json_decode($request->billItems) as $items => $amount) {
                        $allAmounts = $defaultValue + (int)$amount;
                        //Insert into table ledger student

                        DB::table("tblledger_student")->insert([
                            "transid" => strtoupper(bin2hex(random_bytes(5))),
                            "branch_code" => $request->branch,
                            "school_code" => $request->school_code,
                            "type" => "b",
                            "student_no" => $students->student_no,
                            "sem_code" => $request->semester,
                            "debit" => $amount,
                            "item_code" => $items,
                            "balance" => $allAmounts,
                            "deleted" => "0",
                            "createdate" => date("Y-m-d"),
                            "createuser" => $request->createuser,
                        ]);
                    }
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
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact your administrator",
                "error" => [
                    "msg" => "Could not add bill items. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function addBill(Request $request)
    {
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                "branch" => "required",
                "semester" => "required",
                "item" => "required",
                "student" => "required",
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

        //Fetch student details for the particular programme  
        $studentDetails = DB::table("tblstudent")->where("deleted", "0")
            ->where("student_no", $request->student)
            ->where("branch_code", $request->branch)
            ->where("school_code", $request->school_code)
            ->first();
        // return $studentDetails;
        if (empty($studentDetails)) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding bill(s) failed, there are no students available for this programme",
            ]);
        }

        if (empty($studentDetails->batch)) {
            return response()->json([
                "ok" => false,
                "msg" => "Student does not belong to a batch",
            ]);
        }


        try {
            $transactionResult = DB::transaction(function () use ($request, $studentDetails) {

                StudentLedger::where("school_code", $request->school_code)
                    ->where("deleted", "0")->where("student_no", $request->student)
                    ->where("branch_code", $request->branch)
                    ->where("type", "b")
                    ->where("sem_code", $request->semester)
                    ->where("item_code", $request->item)
                    ->delete();

                Bills::where("school_code", $request->school_code)
                    ->where("deleted", "0")
                    ->where("student_no", $request->student)
                    ->where("sem_code", $request->semester)
                    ->where("item", $request->item)
                    ->where("branch_code", $request->branch)
                    ->delete();

                $amount = DB::table("tblbill_item")->where("school_code", $request->school_code)
                    ->where("bill_code", $request->item)->first();

                DB::table("tblbills")->insert([
                    "transid" => strtoupper(bin2hex(random_bytes(5))),
                    "school_code" => $request->school_code,
                    "branch_code" => $request->branch,
                    "student_no" => $request->student,
                    "sem_code" => $request->semester,
                    "batch_code" => !empty($studentDetails->batch) ? $studentDetails->batch : "",
                    "item" => $request->item,
                    "amount" => $amount->amount,
                    "deleted" => "0",
                    "createdate" => date("Y-m-d"),
                    "createuser" => $request->createuser,
                ]);

                //Set default value
                $defaultValue = 0;
                $allAmounts = $defaultValue + (int)$amount->amount;
                //Insert into table ledger student

                DB::table("tblledger_student")->insert([
                    "transid" => strtoupper(bin2hex(random_bytes(5))),
                    "branch_code" => $request->branch,
                    "school_code" => $request->school_code,
                    "type" => "b",
                    "student_no" => $request->student,
                    "sem_code" => $request->semester,
                    "debit" => $amount->amount,
                    "item_code" => $request->item,
                    "balance" => $allAmounts,
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
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact your administrator",
                "error" => [
                    "msg" => "Could not add bill items. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function addProgBillItem(Request $request)
    {
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                "branch" => "required",
                "semester" => "required",
                "item" => "required",
                "program" => "required",
                "batch" => "required",
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

        //Fetch student details for the particular programme  
        $studentDetails = DB::table("tblstudent")->where("deleted", "0")
            ->where("prog", $request->program)
            ->where("branch_code", $request->branch)
            ->where("batch", $request->batch)
            ->where("school_code", $request->school_code)
            ->get();
        // return $studentDetails;
        if (count($studentDetails) === 0) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding bill(s) failed, there are no students available for this programme",
            ]);
        }

        $amount = DB::table("tblbill_item")->where("school_code", $request->school_code)
            ->where("bill_code", $request->item)->first();

        if (empty($amount->amount)) {
            return response()->json([
                "ok" => false,
                "msg" => "Bill item does not have an amount"
            ]);
        }

        try {
            $transactionResult = DB::transaction(function () use ($request, $studentDetails, $amount) {

                foreach ($studentDetails as $student) {

                    StudentLedger::where("school_code", $request->school_code)
                        ->where("deleted", "0")->where("student_no", $student->student_no)
                        ->where("branch_code", $request->branch)
                        ->where("item_code", $request->item)
                        ->where("type", "b")
                        ->where("sem_code", $request->semester)
                        ->delete();

                    Bills::where("school_code", $request->school_code)
                        ->where("deleted", "0")
                        ->where("student_no", $student->student_no)
                        ->where("sem_code", $request->semester)
                        ->where("item", $request->item)
                        ->where("branch_code", $request->branch)
                        ->delete();
                }



                //Set default value
                $defaultValue = 0;
                $allAmounts = $defaultValue + (int)$amount->amount;
                //Insert into table ledger student

                foreach ($studentDetails as $student) {
                    DB::table("tblbills")->insert([
                        "transid" => strtoupper(bin2hex(random_bytes(5))),
                        "school_code" => $request->school_code,
                        "branch_code" => $request->branch,
                        "student_no" => $student->student_no,
                        "sem_code" => $request->semester,
                        "batch_code" => $request->batch,
                        "item" => $request->item,
                        "amount" => $amount->amount,
                        "deleted" => "0",
                        "createdate" => date("Y-m-d"),
                        "createuser" => $request->createuser,
                    ]);
                    DB::table("tblledger_student")->insert([
                        "transid" => strtoupper(bin2hex(random_bytes(5))),
                        "branch_code" => $request->branch,
                        "school_code" => $request->school_code,
                        "type" => "b",
                        "student_no" => $student->student_no,
                        "sem_code" => $request->semester,
                        "debit" => $amount->amount,
                        "item_code" => $request->item,
                        "balance" => $allAmounts,
                        "deleted" => "0",
                        "createdate" => date("Y-m-d"),
                        "createuser" => $request->createuser,
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
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact your administrator",
                "error" => [
                    "msg" => "Could not add bill items. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($school_code)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStudentBill(Request $request)
    {
        //validating user inputs
        $validator = validator::make($request->all(), [
            "acyear" => "required",
            "bill_item_amount" => "required",
            "selected_item" => "required",
            "selected_student" => "required"

        ], [
            "acyear.required" => "No accademic year provided",
            "bill_item_amount" => "Please enter bill amount",
            "selected_item" => "Please, select bill item",
            "selected_student" => "No student selected"
        ]);
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Updating student's bill failed" . join("." . $validator->errors()->all())
            ]);
        }
        try {
            //fecting student bill
            $studentBill = DB::table("tblbills")->where("school_code", $request->school_code)->where("transid", $request->transid)->where("student_no", $request->studentcode)->where("deleted", "0");
            //checking if student exists in database
            if (count($studentBill->get()) == 0) {
                return response()->json([
                    "msg" => "Invalid input value provided",
                    "data" => $request->transid,
                    "iteme" => $request->studentcode
                ]);
            }
            $studentBill->update([
                "acyear" => $request->acyear,
                "item" => $request->selected_item,
                "amount" => $request->bill_item_amount,
                "student_no" => $request->selected_student
            ]);
            if (!$studentBill) {
                return response()->json([
                    "msg" => "Updating student's bill failed"
                ]);
            }
            return response()->json([
                "ok" => true
            ]);
        } catch (\Throwable $e) {
            Log::error("Failed updating bill item: " . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Couldn't update student bill ",

            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyBillItem($billcode, $schoolcode)
    {
        //Deleting bill item from tblbill_amt, tblbill_item and tblbills
        $bilItem = DB::table('tblbill_item')
            ->where('bill_code', $billcode)
            ->where('deleted', 0);
        $billListAmount = DB::table('tblbill_amt')
            ->where('school_code', $schoolcode)
            ->where('deleted', 0)
            ->where('bill_code', $billcode);
        $tblbills = DB::table('tblbills')
            ->where('school_code', $schoolcode)
            ->where('deleted', 0)
            ->where('item', $billcode);

        if (count($tblbills->get()) != 0) {
            if (empty($bilItem)) {
                return response()->json([
                    "ok" => false,
                    "msg" => "Unknown bill code supplied ",


                ]);
            }

            try {
                $transactionResult = DB::transaction(function () use ($bilItem, $tblbills, $billListAmount) {

                    $updateBill = $tblbills->update([
                        "deleted" => 1
                    ]);
                    $updateBillListAmount = $billListAmount->update([
                        "deleted" => 1
                    ]);
                    $updated = $bilItem->update([
                        "deleted" => 1,
                    ]);
                });
                if (!empty($transactionResult)) {
                    throw new Exception($transactionResult);
                }

                return response()->json([
                    "ok" => true,
                ]);
            } catch (\Throwable $e) {
                Log::error("Destroying bill item failed" . $e->getMessage());
                return response()->json([
                    "ok" => false,
                    "msg" => "Couldn't delete  bill item",

                ]);
            }
        } else {
            if (empty($bilItem)) {
                return response()->json([
                    "ok" => false,
                    "msg" => "Unknown bill code supplied ",


                ]);
            }


            $updated = $bilItem->update([
                "deleted" => "1",
            ]);

            if (!$updated) {
                return response()->json([
                    "ok" => false,
                    "msg" => "An internal error ocurred",
                ]);
            }


            return response()->json([
                "ok" => true,
            ]);
        }
    }

    public function updateBllItem(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "program" => "required",
            "amount" => "required",
            "semester" => "required",
            "branch" => "required",
            "batch" => "required",
            "bill_desc" => "required",
            "transid" => "required",
            "school_code" => "required",

        ], [
            // This has our own custom error messages for each validation
            "bllitemname.required" => "Bill item description  is required",
        ]);


        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Updating bill item failed. " . join(". ", $validator->errors()->all()),
                'x' => $request->all()
            ]);
        }
        try {

            DB::table('tblbill_item')
                ->where('transid', $request->transid)
                ->update([
                    'batch_code' => $request->batch,
                    'branch_code' => $request->branch,
                    'amount' => $request->amount,
                    'sem_code' => $request->semester,
                    'bill_desc' => $request->bill_desc,
                    'prog_code' => $request->program,
                    'modifydate' => date("Y-m-d"),
                    'modifyuser' => $request->createuser,
                ]);

            return response()->json([
                "ok" => true,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed updating bill item: " . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Couldn't update bill item",

            ]);
        }
    }

    public function destroyStudentBill($billcode, $studentcode, $schoolcode)
    {
        try {

            $Bill = DB::table("tblbills")->where("school_code", $schoolcode)->where("student_no", $studentcode)->where("item", $billcode)->where("deleted", "0");
            if (count($Bill->get()) == 0) {
                # code...
                return response()->json([
                    "ok" => false,
                    "msg" => "Unknown student ID or school code provided",
                    "data" => $schoolcode . $studentcode . $billcode
                ]);
            }
            $deleteBill = $Bill->update([
                "deleted" => "1"
            ]);
            if (!$deleteBill) {
                return response()->json([
                    "ok" => false,
                    "msg" => "An internal error occured"
                ]);
            }
            return response()->json([
                "ok" => true,
                "msg" => "Student's bill was successfully deleted"
            ]);
        } catch (\Throwable $e) {
            Log::error("Deleting student's bill failed" . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Couldn't delete student bill",

            ]);
        }
    }

    //fetching total bill amount for particular student
    public function studentAmount($chool_code, $selectedStudent)
    {

        try {
            $studentAmount = DB::table("tblbills")->select("amount")->where("school_code", $chool_code)->where("student_no", $selectedStudent)->where("deleted", "0")->get();
            $totalamount = 0;
            foreach ($studentAmount as $amount) {
                $totalamount += $amount->amount;
            }
            return response()->json([
                "data" => $totalamount
            ]);
        } catch (\Throwable $e) {
            Log::error("Fetching student Amount failed" . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Couldn't fetch student Amount ",

            ]);
        }
    }
    //filter bill  items
    public function filterBill($billdata)
    {
        try {
            $billdata = json_decode(html_entity_decode(stripslashes($billdata)));
            //Filtering per program, batch and session
            if ($billdata->school != "" and $billdata->program != "" and $billdata->batch != ""  and $billdata->branch != "") {
                //Fetching bills
                $Bills = BillResourceController::collection(
                    DB::table("tblbill_item")
                        ->where("school_code", $billdata->school)
                        ->where("prog_code", $billdata->program)
                        ->where("batch_code", $billdata->batch)
                        ->where("branch_code", $billdata->branch)
                        ->where("deleted", "0")
                        ->get()
                );
                return response()->json([
                    "data" => $Bills
                ]);
            }
            //Filtering by program
            if ($billdata->school != "" and $billdata->program != "" and $billdata->batch == ""  and $billdata->branch == "") {
                //Fetching bills
                $Bills = BillResourceController::collection(
                    DB::table("tblbill_item")
                        ->where("school_code", $billdata->school)
                        ->where("prog_code", $billdata->program)
                        ->where("deleted", "0")
                        ->get()
                );
                return response()->json([
                    "data" => $Bills
                ]);
            }
            //Filtering by batch 
            if ($billdata->school != "" and $billdata->program == "" and $billdata->batch != ""  and $billdata->branch == "") {
                $Bills = BillResourceController::collection(
                    DB::table("tblbill_item")
                        ->where("school_code", $billdata->school)
                        ->where("batch_code", $billdata->batch)
                        ->where("deleted", "0")
                        ->get()
                );
                return response()->json([
                    "data" => $Bills
                ]);
            }
            //Filtering by branch
            if ($billdata->school != "" and $billdata->program == "" and $billdata->batch == ""  and $billdata->branch != "") {
                $Bills = BillResourceController::collection(
                    DB::table("tblbill_item")
                        ->where("school_code", $billdata->school)
                        ->where("branch_code", $billdata->branch)
                        ->where("deleted", "0")
                        ->get()
                );
                return response()->json([
                    "data" => $Bills
                ]);
            }
            //filtering per program and batch
            if ($billdata->school != "" and $billdata->program != "" and $billdata->batch != ""  and $billdata->branch == "") {
                $Bills = BillResourceController::collection(
                    DB::table("tblbill_item")
                        ->where("school_code", $billdata->school)
                        ->where("batch_code", $billdata->batch)
                        ->where("prog_code", $billdata->program)
                        ->where("deleted", "0")
                        ->get()
                );
                return response()->json([
                    "data" => $Bills
                ]);
            }
            //filtering per program and session
            if ($billdata->school != "" and $billdata->program != "" and $billdata->batch == ""  and $billdata->branch != "") {
                $Bills = BillResourceController::collection(
                    DB::table("tblbill_item")
                        ->where("school_code", $billdata->school)
                        ->where("branch_code", $billdata->branch)
                        ->where("prog_code", $billdata->program)
                        ->where("deleted", "0")
                        ->get()
                );
                return response()->json([
                    "data" => $Bills
                ]);
            }
            //Filtering by batch and session
            if ($billdata->school != "" and $billdata->program == "" and $billdata->batch != ""  and $billdata->branch != "") {
                $Bills = BillResourceController::collection(
                    DB::table("tblbill_item")
                        ->where("school_code", $billdata->school)
                        ->where("batch_code", $billdata->batch)
                        ->where("branch_code", $billdata->branch)
                        ->where("deleted", "0")
                        ->get()
                );
                return response()->json([
                    "data" => $Bills
                ]);
            }
        } catch (\Throwable $e) {
            Log::error("Filtering bill failed" . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Couldn't filter  bill",

            ]);
        }
    }
    //adding amount to bill item
    public function addBillItemAmount(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'add_bill_item_amount__branch' => "required",
                "add_bill_item_amount__semester" => "required",
                "add_bill_item_amount__program" => "required",
                "add_bill_item_amount__level" => "required",
                "add_bill_item_amount__session" => "required",
                "add_bill_item_name" => 'required',
                "add_bill_item_amount__batch" => 'required',
                "amount" => 'required',
                "add_bill_item_amount__department" => 'required'

            ],
            [
                'add_bill_item_name.required' => 'No bill item name selected',
                "add_bill_item_amount__program.required" => "No program selected",
                "add_bill_item_amount__batch.required" => "No batch selected",
                "add_bill_item_amount__semester.required" => "No semester selected",
                "amount.required" => "No amount entered",
                "add_bill_item_amount__level.required" => "No level selected",
                "add_bill_item_amount__session.required" => "No session selected",
                "add_bill_item_name.required" => "No bill item selected"

            ]
        );
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding amount to bill item failed. " . join(". ", $validator->errors()->all()),


            ]);
        }
        // Inserting data into database
        try {
            $transactionResult = DB::transaction(function () use ($request) {
                DB::table("tblbill_amt")->insert([
                    'transid' => strtoupper(bin2hex(random_bytes(5))),
                    "school_code" => $request->school_code,
                    "branch_code" => $request->add_bill_item_amount__branch,
                    "sem_code" => $request->add_bill_item_amount__semester,
                    "acyear" => $request->acyear,
                    "prog_code" => $request->add_bill_item_amount__program,
                    "level" => $request->add_bill_item_amount__level,
                    "batch_code" => $request->add_bill_item_amount__batch,
                    "session_code" => $request->add_bill_item_amount__session,
                    "bill_code" => $request->add_bill_item_name,
                    "amount" => $request->amount,
                    "deleted" => "0",
                    "source" => "1",
                    "import" => null,
                    "export" => null,
                    'createuser' =>  $request->school_code,
                    'createdate' => date('Y-m-d'),
                    'modifyuser' => $request->school_code,
                    'modifydate' => date('Y-m-d'),

                ]);
                // //selecting students' IDs using program,session, semester, batch,
                $studentID = DB::table('tblstudent')
                    ->where('school_code', $request->school_code)
                    ->where('prog', $request->add_bill_item_amount__program)
                    ->where('batch', $request->add_bill_item_amount__batch)
                    ->where('session',  $request->add_bill_item_amount__session)
                    ->where('branch_code', $request->add_bill_item_amount__branch)
                    ->where('current_level', $request->add_bill_item_amount__level)
                    ->where('admsemester', $request->add_bill_item_amount__semester)
                    ->get();
                if (count($studentID) != 0) {
                    foreach ($studentID as $key) {
                        DB::table("tblbills")->insert([
                            "transid" => strtoupper(bin2hex(random_bytes(5))),
                            "student_no" => $key->student_no,
                            "school_code" => $request->school_code,
                            "branch_code" => $request->add_bill_item_amount__branch,
                            "prog_code" => $request->add_bill_item_amount__program,
                            "batch_code" => $request->add_bill_item_amount__batch,
                            "dept_code" => $request->add_bill_item_amount__department,
                            "session_code" => $request->add_bill_item_amount__session,
                            "sem_code" => $request->add_bill_item_amount__semester,
                            "acyear" => $request->acyear,
                            "acterm" => null,
                            "item" => $request->add_bill_item_name,
                            "amount" => $request->amount,
                            "source" => null,
                            "deleted" => "0",
                            "import" => null,
                            "export" => null,
                            'createuser' =>  $request->school_code,
                            'createdate' => date('Y-m-d'),
                            'modifyuser' => $request->school_code,
                            'modifydate' => date('Y-m-d'),

                        ]);
                    }
                }
            });
            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }

            return response()->json([
                "ok" => true,
            ]);
        } catch (\Throwable $e) {
            Log::error("Adding amount to bill item failed" . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Couldn't add amount to bill item",

            ]);
        }
    }
    //Fetching bill item amounts
    public function FetchBillItemAmount($school_code)
    {
        $billItemAmount = BillItemAmountResource::collection(
            DB::table('tblbill_amt')
                ->select('tblbill_amt.*', 'tblbill_item.bill_desc')
                ->join('tblbill_item', 'tblbill_item.bill_code', 'tblbill_amt.bill_code')
                ->where('tblbill_item.school_code', $school_code)
                ->where('tblbill_amt.school_code', $school_code)
                ->where('tblbill_amt.deleted', 0)
                ->where('tblbill_item.deleted', 0)
                ->get()
        );
        return response()->json([
            "data" => $billItemAmount
        ]);
    }
    //updating bill item amount 
    public function updateBillItemAmount(Request $request)
    {


        $validator = Validator::make(
            $request->all(),
            [
                'edit_bill_item_amount__branch' => "required",
                "edit_bill_item_amount__semester" => "required",
                "edit_bill_item_amount__program" => "required",
                "edit_bill_item_amount__level" => "required",
                "edit_bill_item_amount__session" => "required",
                "edit_bill_item_amount__item_name" => 'required',
                "edit_bill_item_amount__batch" => 'required',
                "edit_bill_amount" => 'required',
                "edit_bill_item_amount__transid" => 'required'

            ],
            [

                "edit_bill_item_amount__program.required" => "No program selected",
                "edit_bill_item_amount__batch.required" => "No batch selected",
                "edit_bill_item_amount__semester.required" => "No semester selected",
                "edit_bill_amount.required" => "No amount entered",
                "edit_bill_item_amount__level.required" => "No level selected",
                "edit_bill_item_amount__session.required" => "No session selected",
                "edit_bill_item_amount__item_name.required" => "No bill item selected",
                "edit_bill_item_amount__transid" => "No transid found "

            ]
        );
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Updating amount to bill item failed. " . join(". ", $validator->errors()->all()),
                "data" => $request->all()


            ]);
        }

        $billIem = DB::table('tblbill_amt')
            ->where('transid', $request->edit_bill_item_amount__transid)
            ->where('school_code', $request->school_code)
            ->where("deleted", 0);
        //selecting bills list
        $billList = DB::table('tblbills')
            ->where('school_code', $request->school_code)
            ->where('prog_code', $request->edit_bill_item_amount__program)
            ->where('batch_code', $request->edit_bill_item_amount__batch)
            ->where('session_code',  $request->edit_bill_item_amount__session)
            ->where('branch_code', $request->edit_bill_item_amount__branch)
            ->where('sem_code', $request->edit_bill_item_amount__semester)
            ->where('item', $request->edit_bill_item_amount__item_name)
            ->get();
        if (count($billIem->get()) == 0) {
            return response()->json([
                "ok" => false,
                "msg" => "updating bill failed!",
            ]);
        }
        $updateBillAmount = $billIem->update([

            "amount" => $request->edit_bill_amount
        ]);
        foreach ($billList  as $key) {
            $billList->update([

                "amount" => $request->edit_bill_amount
            ]);
        }
        if (!$updateBillAmount) {
            return response()->json([
                "msg" => "Updating bill item failed!",
            ]);
        }
        return response()->json([
            "ok" => true,
        ]);
    }
    //Deletinng bill amount
    public function destroyBillItemAmount(Request $request)
    {
        //retrieving all items present in both tblbils and tblbill_amt
        $items = DB::table('tblbill_amt')
            ->select('tblbill_amt.*', 'tblbills.*')
            ->join('tblbills', 'tblbill_amt.bill_code', 'tblbills.item')
            ->where('tblbill_amt.bill_code', $request->billCode)
            ->where('tblbill_amt.branch_code', $request->billBranch)
            ->where('tblbill_amt.prog_code', $request->billProgram)
            ->where('tblbill_amt.batch_code', $request->billBatch)
            ->where('tblbill_amt.session_code', $request->billSession)
            ->where('tblbill_amt.sem_code', $request->billSemester)
            ->where('tblbill_amt.transid', $request->billTransId)
            ->where('tblbills.item', $request->billCode)
            ->where('tblbills.branch_code', $request->billBranch)
            ->where('tblbills.prog_code', $request->billProgram)
            ->where('tblbills.batch_code', $request->billBatch)
            ->where('tblbills.session_code', $request->billSession)
            ->where('tblbills.sem_code', $request->billSemester)
            ->where('tblbills.deleted', 0)
            ->where('tblbill_amt.deleted', 0)
            ->get();

        $billListAmount = DB::table('tblbill_amt')
            ->where('school_code', $request->schoolCode)
            ->where('transid', $request->billTransId)
            ->where('deleted', 0);
        $tblbills = DB::table('tblbills')
            ->where('school_code', $request->schoolCode)
            ->where('deleted', 0)
            ->where('item', $request->billCode)
            ->where('branch_code', $request->billBranch)
            ->where('prog_code', $request->billProgram)
            ->where('batch_code', $request->billBatch)
            ->where('session_code', $request->billSession)
            ->where('sem_code', $request->billSemester);

        //deletion can be done from both  tables if join is not null
        try {
            if (count($items) != 0) {
                try {
                    $transactionResult = DB::transaction(function () use ($tblbills, $billListAmount) {

                        $tblbills->update([
                            "deleted" => 1
                        ]);
                        $billListAmount->update([
                            "deleted" => 1
                        ]);
                    });
                    if (!empty($transactionResult)) {
                        throw new Exception($transactionResult);
                    }

                    return response()->json([
                        "ok" => true,
                    ]);
                } catch (\Throwable $e) {
                    Log::error("Destroying bill item failed" . $e->getMessage());
                    return response()->json([
                        "ok" => false,
                        "msg" => "Couldn't delete  bill item",

                    ]);
                }
            }

            //Deleting bill item from tblbill_amt only

            if (count($billListAmount->get()) == 0) {
                # code...
                return response()->json([
                    "ok" => false,
                    "msg" => "Unknown bill code provided",
                    "data" => $billListAmount->get()

                ]);
            }
            $deleteBill = $billListAmount->update([
                "deleted" => "1"
            ]);
            if (!$deleteBill) {
                return response()->json([
                    "ok" => false,
                    "msg" => "An internal error occured"
                ]);
            }
            return response()->json([
                "ok" => true,
                "msg" => "Student's bill was successfully deleted"
            ]);
        } catch (\Throwable $e) {
            Log::error("Deleting  bill failed" . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Couldn't delete  bill",

            ]);
        }
    }
    //filtering bill  amount
    public function filterBillAmount($billdata)
    {
        try {
            $billdata = json_decode(html_entity_decode(stripslashes($billdata)));
            //Filtering per program, batch and session
            if ($billdata->school != "" and $billdata->program != "" and $billdata->batch != ""  and $billdata->branch != "") {
                //Fetching bills
                $Bills = BillItemAmountResource::collection(
                    DB::table('tblbill_amt')
                        ->select('tblbill_amt.*', 'tblbill_item.bill_desc')
                        ->join('tblbill_item', 'tblbill_item.bill_code', 'tblbill_amt.bill_code')
                        ->where('tblbill_item.school_code',  $billdata->school)
                        ->where('tblbill_amt.school_code', $billdata->school)
                        ->where('tblbill_amt.deleted', 0)
                        ->where('tblbill_item.deleted', 0)
                        ->where("tblbill_amt.prog_code", $billdata->program)
                        ->where("tblbill_amt.batch_code", $billdata->batch)
                        ->where("tblbill_amt.branch_code", $billdata->branch)
                        ->get()
                );
                return response()->json([
                    "data" => $Bills
                ]);
            }
            //Filtering by program
            if ($billdata->school != "" and $billdata->program != "" and $billdata->batch == ""  and $billdata->branch == "") {
                //Fetching bills
                $Bills = BillItemAmountResource::collection(
                    DB::table('tblbill_amt')
                        ->select('tblbill_amt.*', 'tblbill_item.bill_desc')
                        ->join('tblbill_item', 'tblbill_item.bill_code', 'tblbill_amt.bill_code')
                        ->where('tblbill_item.school_code',  $billdata->school)
                        ->where('tblbill_amt.school_code', $billdata->school)
                        ->where('tblbill_amt.deleted', 0)
                        ->where('tblbill_item.deleted', 0)
                        ->where("tblbill_amt.prog_code", $billdata->program)
                        ->get()
                );
                return response()->json([
                    "data" => $Bills
                ]);
            }
            //Filtering by batch 
            if ($billdata->school != "" and $billdata->program == "" and $billdata->batch != ""  and $billdata->branch == "") {
                $Bills = BillItemAmountResource::collection(
                    DB::table('tblbill_amt')
                        ->select('tblbill_amt.*', 'tblbill_item.bill_desc')
                        ->join('tblbill_item', 'tblbill_item.bill_code', 'tblbill_amt.bill_code')
                        ->where('tblbill_item.school_code',  $billdata->school)
                        ->where('tblbill_amt.school_code', $billdata->school)
                        ->where('tblbill_amt.deleted', 0)
                        ->where('tblbill_item.deleted', 0)
                        ->where("tblbill_amt.batch_code", $billdata->batch)
                        ->get()
                );
                return response()->json([
                    "data" => $Bills
                ]);
            }
            //Filtering by branch
            if ($billdata->school != "" and $billdata->program == "" and $billdata->batch == ""  and $billdata->branch != "") {
                $Bills = BillItemAmountResource::collection(
                    DB::table('tblbill_amt')
                        ->select('tblbill_amt.*', 'tblbill_item.bill_desc')
                        ->join('tblbill_item', 'tblbill_item.bill_code', 'tblbill_amt.bill_code')
                        ->where('tblbill_item.school_code',  $billdata->school)
                        ->where('tblbill_amt.school_code', $billdata->school)
                        ->where('tblbill_amt.deleted', 0)
                        ->where('tblbill_item.deleted', 0)
                        ->where("tblbill_amt.branch_code", $billdata->branch)
                        ->get()
                );
                return response()->json([
                    "data" => $Bills
                ]);
            }
            //filtering per program and batch
            if ($billdata->school != "" and $billdata->program != "" and $billdata->batch != ""  and $billdata->branch == "") {
                $Bills = BillItemAmountResource::collection(
                    DB::table('tblbill_amt')
                        ->select('tblbill_amt.*', 'tblbill_item.bill_desc')
                        ->join('tblbill_item', 'tblbill_item.bill_code', 'tblbill_amt.bill_code')
                        ->where('tblbill_item.school_code',  $billdata->school)
                        ->where('tblbill_amt.school_code', $billdata->school)
                        ->where('tblbill_amt.deleted', 0)
                        ->where('tblbill_item.deleted', 0)
                        ->where("tblbill_amt.prog_code", $billdata->program)
                        ->where("tblbill_amt.batch_code", $billdata->batch)
                        ->get()
                );
                return response()->json([
                    "data" => $Bills
                ]);
            }
            //filtering per program and session
            if ($billdata->school != "" and $billdata->program != "" and $billdata->batch == ""  and $billdata->branch != "") {
                $Bills = BillItemAmountResource::collection(
                    DB::table('tblbill_amt')
                        ->select('tblbill_amt.*', 'tblbill_item.bill_desc')
                        ->join('tblbill_item', 'tblbill_item.bill_code', 'tblbill_amt.bill_code')
                        ->where('tblbill_item.school_code',  $billdata->school)
                        ->where('tblbill_amt.school_code', $billdata->school)
                        ->where('tblbill_amt.deleted', 0)
                        ->where('tblbill_item.deleted', 0)
                        ->where("tblbill_amt.prog_code", $billdata->program)
                        ->where("tblbill_amt.branch_code", $billdata->branch)
                        ->get()
                );
                return response()->json([
                    "data" => $Bills
                ]);
            }
            //Filtering by batch and session
            if ($billdata->school != "" and $billdata->program == "" and $billdata->batch != ""  and $billdata->branch != "") {
                $Bills = BillItemAmountResource::collection(
                    DB::table('tblbill_amt')
                        ->select('tblbill_amt.*', 'tblbill_item.bill_desc')
                        ->join('tblbill_item', 'tblbill_item.bill_code', 'tblbill_amt.bill_code')
                        ->where('tblbill_item.school_code',  $billdata->school)
                        ->where('tblbill_amt.school_code', $billdata->school)
                        ->where('tblbill_amt.deleted', 0)
                        ->where('tblbill_item.deleted', 0)
                        ->where("tblbill_amt.batch_code", $billdata->batch)
                        ->where("tblbill_amt.branch_code", $billdata->branch)
                        ->get()
                );
                return response()->json([
                    "data" => $Bills
                ]);
            }
        } catch (\Throwable $e) {
            Log::error("Filtering bill failed" . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Couldn't filter  bill",

            ]);
        }
    }

    public function fetchProgramSemester($schoolCode, $progCode)
    {
        $data = DB::table("tblcourse")->where("school_code", $schoolCode)
            ->where("prog", $progCode)->where("deleted", 0)->first();

        if (empty($data)) {
            return response()->json([
                "ok" => false,
                "msg" => "The selected programme does not have an active semester"
            ]);
        }
        $sem = DB::table("tblsemester")->where("sem_code", $data->semester)->where("deleted", 0)->first();

        return response()->json([
            "ok" => true,
            "data" => $sem
        ]);
    }

    public function fetchProgramSemesterForIndBill($schoolCode, $studentCode)
    {
        $student = DB::table("tblstudent")->where("school_code", $schoolCode)
            ->where("student_no", $studentCode)->where("deleted", 0)->first();

        $data = DB::table("tblcourse")->where("school_code", $schoolCode)
            ->where("prog", $student->prog)->where("deleted", 0)->first();
        if (empty($data)) {
            return response()->json([
                "ok" => false,
                "msg" => "Student does not belong to an active semester"
            ]);
        }
        $sem = DB::table("tblsemester")->where("sem_code", $data->semester)->where("deleted", 0)->first();

        return response()->json([
            "ok" => true,
            "data" => $sem
        ]);
    }

    public function fetchStudentBillItemsForBilling($schoolCode, $studentCode)
    {
        $student = DB::table("tblstudent")->where("school_code", $schoolCode)
            ->where("student_no", $studentCode)->where("deleted", 0)->first();

        if (empty($student)) {
            return response()->json([
                "ok" => false,
                "msg" => "Student does not belong to an active semester"
            ]);
        }
        $sem = DB::table("tblbill_item")->where("prog_code", $student->prog)->where("deleted", 0)->first();

        return response()->json([
            "ok" => true,
            "data" => $sem
        ]);
    }

    public function fetchProgBillItemsForBilling($schoolCode, $progCode)
    {
        $sem = DB::table("tblbill_item")
            ->where("school_code", $schoolCode)
            ->where("prog_code", $progCode)->where("deleted", 0)->first();

        return response()->json([
            "ok" => true,
            "data" => $sem
        ]);
    }

    public function discountIndividualBill(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "branch" => "required",
                "student" => "required",
                "semester" => "required",
                "item" => "required",
                "amount" => "required",
                "discount" => "required",
                "school_code" => "required",
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
                $discountAmount = $request->amount - ($request->discount / 100 * $request->amount);
                //Update the particular bill item amount
                Bills::where("branch_code", $request->branch)
                    ->where("sem_code", $request->semester)
                    ->where("student_no", $request->student)
                    ->where("school_code", $request->school_code)
                    ->where("item", $request->item)
                    ->where("deleted", "0")
                    ->update([
                        "amount" => $discountAmount,
                        "modifydate" => date("Y-m-d"),
                        "modifyuser" => $request->createuser,
                    ]);

                $newBillAmount = Bills::where("sem_code", $request->semester)
                    ->where("branch_code", $request->branch)
                    ->where("student_no", $request->student)
                    ->where("school_code", $request->school_code)
                    ->where("deleted", "0")
                    ->sum("amount");

                //Update the bill item amount in student ledger
                StudentLedger::where("school_code", $request->school_code)
                    ->where("deleted", "0")
                    ->where("student_no", $request->student)
                    ->where("sem_code", $request->semester)
                    ->where("branch_code", $request->branch)
                    ->where("item_code", $request->item)
                    ->where("type", "b")
                    ->update([
                        "debit" => $discountAmount,
                        "balance" => $discountAmount,
                        "modifydate" => date("Y-m-d"),
                        "modifyuser" => $request->createuser,
                    ]);

                //Then update the total bill of student in ledger
                StudentLedger::where("school_code", $request->school_code)
                    ->where("deleted", "0")->where("student_no", $request->student)
                    ->where("sem_code", $request->semester)
                    ->where("branch_code", $request->branch)
                    ->where("type", "payment")
                    ->update([
                        "debit" => $newBillAmount,
                        "modifydate" => date("Y-m-d"),
                        "modifyuser" => $request->createuser,
                    ]);

                //Then fetch payments of student in student ledger
                $ledger = StudentLedger::where("school_code", $request->school_code)
                    ->where("deleted", "0")->where("student_no", $request->student)
                    ->where("sem_code", $request->semester)
                    ->where("branch_code", $request->branch)
                    ->where("type", "payment")
                    ->get();

                if (!empty($ledger)) {
                    foreach ($ledger as $calculation) {
                        $balance = $newBillAmount - $calculation->credit;
                        StudentLedger::where("school_code", $request->school_code)
                            ->where("deleted", "0")->where("student_no", $request->student)
                            ->where("sem_code", $request->semester)
                            ->where("branch_code", $request->branch)
                            ->where("item_code", $calculation->item_code)
                            ->where("type", "payment")
                            ->update([
                                "balance" => $balance,
                                "modifydate" => date("Y-m-d"),
                                "modifyuser" => $request->createuser,
                            ]);
                    }
                }

                //Then fetch payments of student in payment table
                $newPayment = DB::table("tblpayment")->where("school_code", $request->school_code)
                    ->where("deleted", "0")->where("student_no", $request->student)
                    ->where("semester", $request->semester)
                    ->where("branch", $request->branch)
                    ->get();

                if (!empty($newPayment)) {
                    foreach ($newPayment as $calculation) {
                        $balance = $newBillAmount - $calculation->total_paid;
                        DB::table("tblpayment")->where("school_code", $request->school_code)
                            ->where("deleted", "0")->where("student_no", $request->student)
                            ->where("semester", $request->semester)
                            ->where("branch", $request->branch)
                            ->where("receipt_no", $calculation->receipt_no)
                            ->update([
                                "amount" => $newBillAmount,
                                "cur_balance" => $balance,
                                "modifydate" => date("Y-m-d"),
                                "modifyuser" => $request->createuser,
                            ]);
                    }
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
                "msg" => "Bills added successfully",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact your administrator",
                "error" => [
                    "msg" => "Could not add bill items. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function deleteStudentBill(Request $request)
    {
        // return $request->all();
        DB::table("tblledger_student")->where("school_code", $request->school_code)
            ->where("student_no", $request->student)
            ->where("sem_code", $request->semester)
            ->where("item_code", $request->item)
            ->where("type","b")
            ->delete();
        DB::table("tblbills")->where("school_code", $request->school_code)
            ->where("student_no", $request->student)
            ->where("sem_code", $request->semester)
            ->where("item", $request->item)
            ->delete();

        return response()->json([
            "ok" => true,
            "msg" => "Delete successful",
        ]);
    }

    public function updateIndividualBill(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "student" => "required",
                "branch" => "required",
                "semester" => "required",
                "item" => "required",
                "amount" => "required",
                "school_code" => "required",
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

                //Update the particular bill item amount
                Bills::where("sem_code", $request->semester)
                    ->where("branch_code", $request->branch)
                    ->where("student_no", $request->student)
                    ->where("school_code", $request->school_code)
                    ->where("item", $request->item)
                    ->where("deleted", "0")
                    ->update([
                        "amount" => $request->amount,
                        "modifydate" => date("Y-m-d"),
                        "modifyuser" => $request->createuser,
                    ]);

                $newBillAmount = Bills::where("sem_code", $request->semester)
                    ->where("branch_code", $request->branch)
                    ->where("student_no", $request->student)
                    ->where("school_code", $request->school_code)
                    ->where("deleted", "0")
                    ->sum("amount");

                //Update the bill item amount in student ledger
                StudentLedger::where("school_code", $request->school_code)
                    ->where("deleted", "0")
                    ->where("student_no", $request->student)
                    ->where("sem_code", $request->semester)
                    ->where("branch_code", $request->branch)
                    ->where("item_code", $request->item)
                    ->update([
                        "debit" => $request->amount,
                        "balance" => $request->amount,
                        "modifydate" => date("Y-m-d"),
                        "modifyuser" => $request->createuser,
                    ]);

                //Then update the total bill of student in ledger
                StudentLedger::where("school_code", $request->school_code)
                    ->where("deleted", "0")->where("student_no", $request->student)
                    ->where("sem_code", $request->semester)
                    ->where("branch_code", $request->branch)
                    ->where("type", "payment")
                    ->update([
                        "debit" => $newBillAmount,
                        "modifydate" => date("Y-m-d"),
                        "modifyuser" => $request->createuser,
                    ]);

                //Then fetch payments of student in student ledger
                $ledger = StudentLedger::where("school_code", $request->school_code)
                    ->where("deleted", "0")->where("student_no", $request->student)
                    ->where("sem_code", $request->semester)
                    ->where("branch_code", $request->branch)
                    ->where("type", "payment")
                    ->get();

                if (!empty($ledger)) {
                    foreach ($ledger as $calculation) {
                        $balance = $newBillAmount - $calculation->credit;
                        StudentLedger::where("school_code", $request->school_code)
                            ->where("deleted", "0")->where("student_no", $request->student)
                            ->where("sem_code", $request->semester)
                            ->where("branch_code", $request->branch)
                            ->where("item_code", $calculation->item_code)
                            ->where("type", "payment")
                            ->update([
                                "balance" => $balance,
                                "modifydate" => date("Y-m-d"),
                                "modifyuser" => $request->createuser,
                            ]);
                    }
                }

                //Then fetch payments of student in payment table
                $newPayment = DB::table("tblpayment")->where("school_code", $request->school_code)
                    ->where("deleted", "0")->where("student_no", $request->student)
                    ->where("semester", $request->semester)
                    ->where("branch", $request->branch)
                    ->get();

                if (!empty($newPayment)) {
                    foreach ($newPayment as $calculation) {
                        $balance = $newBillAmount - $calculation->total_paid;
                        DB::table("tblpayment")->where("school_code", $request->school_code)
                            ->where("deleted", "0")->where("student_no", $request->student)
                            ->where("semester", $request->semester)
                            ->where("branch", $request->branch)
                            ->where("receipt_no", $calculation->receipt_no)
                            ->update([
                                "amount" => $newBillAmount,
                                "cur_balance" => $balance,
                                "modifydate" => date("Y-m-d"),
                                "modifyuser" => $request->createuser,
                            ]);
                    }
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
                "msg" => "Bills added successfully",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact your administrator",
                "error" => [
                    "msg" => "Could not add bill items. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }
}