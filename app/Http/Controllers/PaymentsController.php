<?php

namespace App\Http\Controllers;

use App\Http\Resources\dailyPaymentResource;
use App\Http\Resources\DebtorsResource;
use App\Http\Resources\fullPaymentResource;
use App\Http\Resources\LedgerResource;
use App\Http\Resources\PaymentControllerResource;
use App\Http\Resources\PaymentHistoryResource;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\School;
use App\Models\Student;
use App\Models\ViewPaymentTotal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
    //fetching all payments
    public function index(Request $request, $school_code)
    {
        $payments = PaymentResource::collection(
            DB::table("vtblpayment_total")->distinct()->select(
                "tblprog.prog_desc",
                "tblstudent.fname",
                "tblstudent.lname",
                "tblstudent.mname",
                "tblstudent.student_no",
                "vtblbill_total.total_bill",
                "vtblpayment_total.semester",
                "vtblpayment_total.school_code",
                "vtblpayment_total.total_paid"
            )
                ->join("vtblbill_total", "vtblpayment_total.student_no", "vtblbill_total.student_no")
                ->join("tblstudent", "vtblpayment_total.student_no", "tblstudent.student_no")
                ->join("tblprog", "tblstudent.prog", "tblprog.prog_code")
                ->where("vtblbill_total.school_code", $school_code)
                ->where("vtblpayment_total.school_code", $school_code)
                ->where("tblprog.school_code", $school_code)
                ->where("vtblpayment_total.semester", $request->semester)
                ->where("vtblbill_total.semester", $request->semester)
                // ->where("vtblpayment_total.student_no", $request->student)
                ->where("vtblpayment_total.branch", $request->branch)
                // ->where("tblpayment.deleted", "0")
                ->where("tblstudent.deleted", "0")
                ->where("tblprog.deleted", "0")
                // ->orderByDesc("tblpayment.createdate")
                ->get()
        );
        return response()->json([
            "ok" => true,
            "data" => $payments
        ]);
    }

    public function store(Request $request)
    {
        // return  $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                "student" => "required",
                "bill" => "required",
                "payment_type" => "required",
                "amtpaid" => "required",
                "semester" => "required",
                "payment_date" => "required",
                "arrears" => "required",
                "overallPaid" => "required",
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


        //Fetch student details  
        $studentDetails = Student::where("student_no", $request->student)
            ->where("school_code", $request->school_code)
            ->where("deleted", "0")
            ->first();
        if (empty($studentDetails)) {
            return response()->json([
                "ok" => false,
                "msg" => "Student does not exist",
            ]);
        }

        // if($request->totalPaid === $request->bill){
        //     return response()->json([
        //         "ok" => false,
        //         "msg" => "Student has fully paid for this semester"
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

        if ((int)$request->arrears < 0) {
            $newBill = $request->bill;
            if ($request->amtpaid >= $request->bill) {
                $totalPaid = $request->amtpaid;
            } else if ($request->amtpaid < $request->bill) {
                $totalPaid = $request->amtpaid + abs((int)$request->arrears);
            } else {
                $totalPaid = $request->bill;
            }
        } else {
            if (abs((int)$request->arrears) === 0) {
                if ($request->amtpaid >= $request->bill) {
                    $totalPaid = $request->bill;
                } else {
                    $totalPaid = $request->amtpaid;
                }
                $newBill = $request->bill + abs((int)$request->arrears);
            }
            if ((int)$request->amtpaid >= (int)$request->bill) {
                $totalPaid = $request->bill;
                $newBill = $request->bill;
            } else if ($request->amtpaid < $request->bill) {
                $totalPaid = $request->amtpaid;
                $newBill = $request->bill;
            } else {
                $newBill = $request->bill;
                $totalPaid = $request->amtpaid - abs((int)$request->arrears);
            }
        }

        //Count payment table for receipt number
        $count = DB::table("tblpayment")->where("school_code", $request->school_code)->where("deleted", "0")->get();
        $tableCount = $count->count();

        $tableCount++;
        $prefix = 'REC';
        $receiptno = null;

        switch (strlen($tableCount)) {
            case 1:
                $receiptno = $prefix . '000' . $tableCount;
                break;
            case 2:
                $receiptno = $prefix . '00' . $tableCount;
                break;
            case 3:
                $receiptno = $prefix . '0' . $tableCount;
                break;
            case 4:
            default:
                $receiptno = $prefix . '' . $tableCount;
                break;
        }

        //Count student ledger table for item code
        $countLedger = DB::table("tblledger_student")->where("school_code", $request->school_code)
            ->where("type", "payment")
            ->where("deleted", "0")->get();
        $ledgerTableCount = $countLedger->count();

        $ledgerTableCount++;
        $pref = 'PT';
        $itemCode = null;

        switch (strlen($ledgerTableCount)) {
            case 1:
                $itemCode = $pref . '000' . $ledgerTableCount;
                break;
            case 2:
                $itemCode = $pref . '00' . $ledgerTableCount;
                break;
            case 3:
                $itemCode = $pref . '0' . $ledgerTableCount;
                break;
            case 4:
            default:
                $itemCode = $pref . '' . $tableCount;
                break;
        }

        //STUDENT PAYMENT ARRAY
        $studentPayment = [
            "transid" => strtoupper(bin2hex(random_bytes(5))),
            "branch" => $studentDetails->branch_code,
            "school_code" => $request->school_code,
            "payment_mode" => $request->payment_type,
            "payment_type" => $request->payment_type,
            "student_no" => $request->student,
            "semester" => $request->semester,
            "receipt_no" => $receiptno,
            "payment_date" => $request->payment_date,
            "cheque_no" => $request->cheque_no,
            "cheque_bank" => $request->cheque_bank,
            "network" => $request->momoName,
            "trans_id" => $request->momoTransid,
            "phone_number" => $request->momoNo,
            "payment_desc" => ucfirst($request->payment_desc),
            "amount" => $newBill,
            "total_paid" => $totalPaid,
            "deleted" => "0",
            "createdate" => date("Y-m-d h:i:s"),
            "createuser" => $request->createuser,
        ];

        //LEDGER ARRAY
        $ledger = [
            "transid" => "TRANS" . strtoupper(bin2hex(random_bytes(5))),
            "school_code" => $request->school_code,
            "type" => "payment",
            "item_code" => $itemCode,
            "ref_code" => $receiptno,
            "student_no" => $studentDetails->student_no,
            "sem_code" => $request->semester,
            "branch_code" => $studentDetails->branch_code,
            // "class_code" => $studentDetails->current_class,
            "debit" => $newBill,
            "credit" => $request->amtpaid,
            "deleted" => "0",
            "createdate" => date("Y-m-d"),
            "createuser" => $request->createuser,
        ];
        try {
            $transactionResult = DB::transaction(function () use ($request, $ledger, $studentPayment, $newBill) {

                $test = DB::table("tblledger_student")->where("school_code", $request->school_code)
                    ->where("sem_code", $request->semester)
                    ->where("student_no", $request->student)
                    ->where("type", "payment")
                    ->where("deleted", "0")->first();

                if (empty($test)) {
                    $ledger["balance"] = $newBill - $request->amtpaid;
                    $studentPayment["cur_balance"] = $newBill - $request->amtpaid;
                } else {
                    if ((int)$request->arrears < 0) {
                        $amountLeft = $request->amtpaid + abs((int)$request->arrears);
                        $amount = $request->balance - $amountLeft;
                    } else {
                        $amount = $request->balance - $request->amtpaid + (int)$request->arrears;
                    }
                    $ledger["balance"] = $amount;
                    $studentPayment["cur_balance"] = $amount;
                }

                DB::table("tblledger_student")->insert($ledger);
                DB::table("tblpayment")->insert($studentPayment);
            });

            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }

            //Add student name to the array
            $studentPayment["studentName"] = $request->studentName;

            //  //Fetch total_paid and current_balance
            $paymentDet = DB::table("vtblpayment_total")
                ->where("school_code", $request->school_code)
                ->where("semester", $request->semester)
                ->where("student_no", $request->student)->first();

            //fetch class and grade of student
            // $studentGrade = Student::where("deleted", "0")->where("school_code", $request->school_code)
            //     ->where("student_no", $request->student)->first();
            // $studentCurrentGrade = Grade::where("school_code", $request->school_code)->where("deleted", "0")
            //     ->where("grade_code", $studentGrade->current_grade)->first();
            // $studentCurrentClass = ClassRoom::where("school_code", $request->school_code)->where("deleted", "0")
            //     ->where("class_code", $studentGrade->current_class)->first();

            // $studentPayment["class"] = $studentCurrentClass->class_desc;
            // $studentPayment["grade"] = $studentCurrentGrade->grade_desc;
            
            if ($paymentDet) {
                $studentPayment["total_amt_paid"] = $paymentDet->total_paid;
                $studentPayment["balance"] = $request->bill - $paymentDet->total_paid;
            } else {
                // Handle the case when no payment details are found
                $studentPayment["total_amt_paid"] = 0; // Default to 0 if no record is found
                $studentPayment["balance"] = $request->bill; // Full bill as balance
            }
            
            $studentPayment["school_name"] = $school->school_name;
            $studentPayment["phone_main"] = $school->phone_main;

            // $studentPayment["total_amt_paid"] = $paymentDet->total_paid;
            // $studentPayment["balance"] = $request->bill - $paymentDet->total_paid;
            // $studentPayment["school_name"] = $school->school_name;
            // $studentPayment["phone_main"] = $school->phone_main;



            // $studentPhones = Student::where("deleted", "0")->where("school_code", $request->school_code)
            //     ->where("student_no", $request->student)
            //     ->whereNotNull("student_phone")->get();

            //Route to send mail to parents after payment done
            // Mail::to("kwakuoseikwakye@gmail.com")->send(new MailSchoolFeesPayment());

            // $receipt = <<<MSG
            // Payment Receipt from {$school->school_name}
            // -----------------------
            // Date: {$studentPayment['createdate']}
            // Receipt No: {$studentPayment['receipt_no']}
            // Academic Year: {$studentPayment['acyear']}
            // Academic Term: {$studentPayment['acterm']}
            // Bill Amount: GHS {$studentPayment['amount']} 
            // Student Number: {$studentDetails->student_no}
            // Student Name: {$studentPayment['studentName']}
            // Grade: {$studentPayment['grade']} 
            // Payment Type: {$studentPayment['payment_type']} 
            // Amount Paid: GHS {$studentPayment['total_paid']}
            // Total Paid: GHS {$studentPayment['total_amt_paid']}
            // Arrears: GHS {$studentPayment['balance']}
            // MSG;

            // event(new SchoolFeesPayment(Student::find($studentDetails->student_no), $receipt));

            //Send receipt via sms to student phone number
            // foreach ($studentPhones as $phone) {
            //     if (!empty($phone->student_phone)) {

            //         $sms = new Sms($school->sms_id, env("ARKESEL_SMS_API_KEY"));
            //         $sms->send($phone->student_phone, $receipt);

            //         // $checkMessageLength = strlen($receipt);
            //         // $numberOfPages =  ceil($checkMessageLength / 150);
            //         // $smsToBeSent = $numberOfPages * count($studentPhones);

            //         // DB::table('tblsms_invoice')->insert([
            //         //     "transid" =>  strtoupper(bin2hex(random_bytes(5))),
            //         //     'sms_balance' =>  - ($smsToBeSent),
            //         //     'school_code' => $request->school_code,
            //         //     "createdate" => date('Y-m-d'),
            //         //     "createuser" => $request->school_code,
            //         // ]);
            //     }
            // }


            //Send receipt via sms to parent phone number
            // foreach ($parentPhones as $parentPhone) {
            //     if (!empty($parentPhone->phone)) {

            //         $sms = new Sms($school->sms_id, env("ARKESEL_SMS_API_KEY"));
            //         $sms->send($parentPhone->phone, $receipt);

            //         // $checkMessageLength = strlen($receipt);
            //         // $numberOfPages =  ceil($checkMessageLength / 150);
            //         // $smsToBeSent = $numberOfPages * count($parentPhones);

            //         // DB::table('tblsms_invoice')->insert([
            //         //     "transid" =>  strtoupper(bin2hex(random_bytes(5))),
            //         //     'sms_balance' =>  - ($smsToBeSent),
            //         //     'school_code' => $request->school_code,
            //         //     "createdate" => date('Y-m-d'),
            //         //     "createuser" => $request->school_code,
            //         // ]);
            //     }
            // }

            // foreach ($parentEmail as $email) {
            //     if (!empty($email->email)) {
            //         Mail::to($email->email)->send(new SchoolFeesPaymentToMail($studentPayment));
            //     }
            // }

            // if (!empty($school->email)) {
            //     Mail::to($school->email)->send(new SchoolFeesPaymentToMail($studentPayment));
            // }

            // if ($school->school_code === "1000052") {
            //     if (!empty($school->phone_main)) {
            //         $sms = new Sms($school->sms_id, env("ARKESEL_SMS_API_KEY"));
            //         $sms->send($school->phone_main, $receipt);

            //         $checkMessageLength = strlen($receipt);
            //         $numberOfPages =  ceil($checkMessageLength / 150);
            //         $smsToBeSent = $numberOfPages * 1;

            //         DB::table('tblsms_invoice')->insert([
            //             "transid" =>  strtoupper(bin2hex(random_bytes(5))),
            //             'sms_balance' =>  - ($smsToBeSent),
            //             'school_code' => $request->school_code,
            //             "createdate" => date('Y-m-d'),
            //             "createuser" => $request->school_code,
            //         ]);
            //     }

            //     if (!empty($school->phone_extra)) {
            //         $sms = new Sms($school->sms_id, env("ARKESEL_SMS_API_KEY"));
            //         $sms->send($school->phone_extra, $receipt);

            //         $checkMessageLength = strlen($receipt);
            //         $numberOfPages =  ceil($checkMessageLength / 150);
            //         $smsToBeSent = $numberOfPages * 1;

            //         DB::table('tblsms_invoice')->insert([
            //             "transid" =>  strtoupper(bin2hex(random_bytes(5))),
            //             'sms_balance' =>  - ($smsToBeSent),
            //             'school_code' => $request->school_code,
            //             "createdate" => date('Y-m-d'),
            //             "createuser" => $request->school_code,
            //         ]);
            //     }
            // }

            return response()->json([
                "ok" => true,
                "msg" => "Payment added successfully",
                "data" => $studentPayment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add payment. {$e->getMessage()} On line {$e->getLine()} {$e->getFile()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    //returning selected students bill items
    public function fetchStudentBills(Request $request)
    {

        $billAmount = 0;
        $phone = "";

        $studentBills = DB::table('tblbills')->select("tblbills.*", "tblstudent.*")
            ->join("tblstudent", "tblbills.student_no", "tblstudent.student_no")
            ->where('tblstudent.school_code', $request->schoolCode)
            ->where('tblstudent.student_no', $request->studentID)
            ->where('tblbills.item', $request->billItem)
            ->where('tblbills.school_code', $request->schoolCode)
            ->where('tblbills.student_no', $request->studentID)
            ->where('tblbills.deleted', 0)
            ->where('tblstudent.deleted', 0)
            ->get();
        if (count($studentBills) != 0) {
            $billAmount = $studentBills[0]->amount;
            $phone = $studentBills[0]->phone;
        }
        return response()->json([
            "ok" => true,
            "data" => [$billAmount, $phone],
            "msg" => $studentBills
        ]);
    }

    //fetch debtors
    public function fetchDebtors($school_code)
    {
        $studentWhoHaveFullyPaid = DB::table('tblbills')
            ->select(DB::raw('SUM(IFNULL(tblpayment.total_paid, 0)) AS totalpaid'), DB::raw('SUM(tblbills.amount-IFNULL(tblpayment.total_paid, tblbills.amount)) as balance'), 'tblstudent.fname', 'tblstudent.lname')
            ->leftJoin('tblpayment', 'tblpayment.student_no', 'tblbills.student_no')
            ->leftJoin('tblstudent', 'tblpayment.student_no', 'tblstudent.student_no')
            ->groupBy('tblstudent.fname')
            ->groupBy('tblstudent.lname')
            ->havingRaw('SUM(tblbills.amount- IFNULL(tblpayment.total_paid, 0))>0')
            ->where('tblbills.school_code', $school_code)
            ->where('tblpayment.school_code', $school_code)
            ->where('tblstudent.school_code', $school_code)
            ->where('tblbills.deleted', 0)
            ->where('tblpayment.deleted', 0)
            ->where('tblstudent.deleted', 0)
            ->get();
        return response()->json([
            "ok" => true,
            "data" => DebtorsResource::collection($studentWhoHaveFullyPaid)
        ]);
    }
    //fetch all payemnts
    public function fetchPaymentHistory($school_code)
    {
        $allPayments = DB::table('tblstudent')
            ->select('tblstudent.*', 'tblpayment_history.*')
            ->join('tblpayment_history', 'tblpayment_history.student_no', 'tblstudent.student_no')
            ->where('tblstudent.deleted', '0')
            ->where('tblstudent.school_code', $school_code)
            ->where('tblpayment_history.school_code', $school_code)
            ->where('tblpayment_history.deleted', 0)
            ->get();

        return response()->json([

            "data" => PaymentHistoryResource::collection($allPayments)
        ]);
    }

    //fetch daily payment
    public function fetchDailyPayment($school_code)
    {
        $dailyPyament = DB::table('tblpayment')
            ->select('tblstudent.fname', 'tblstudent.lname', 'tblpayment.*')
            ->join('tblstudent', 'tblstudent.student_no', 'tblpayment.student_no')
            ->where('tblpayment.school_code', $school_code)
            ->where('tblpayment.deleted', 0)
            ->where('tblstudent.school_code', $school_code)
            ->where('tblstudent.deleted', 0)
            ->where('tblpayment.createdate', date('Y-m-d'))
            ->get();
        return response()->json([

            "data" => dailyPaymentResource::collection($dailyPyament)
        ]);
    }

    //Adding offline payment
    public function makeOfflinePayment(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'payment_student_no' => "required",
                "student_phone_number" => "required",
                "payment_type" => "required",
                "payment_desc" => "required",
                "amount_to_pay" => "required",
                "amount_paid" => "required"
            ],
            [
                'payment_student_no.required' => 'No student ID provided',
                "student_phone_number.required" => "No student phone number selected",
                "payment_type.required" => "No payment type selected",
                "payment_desc.required" => "No payment description selected",
                "amount_to_pay.required" => "Please, enter amount to pay",
                "amount_paid.required" => "Please, enter total amount paid"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Making payment failed. " . join(". ", $validator->errors()->all()),


            ]);
        }
        try {
            $balance = $request->amount_to_pay - $request->amount_paid;
            //code...
            $paymentNum = DB::table("tblpayment")
                ->where('school_code', $request->school_code)
                ->get();
            $tableCount = $paymentNum->count();
            $tableCount++;
            $receipt_code = 'REC' . str_pad($tableCount, 4, "0", STR_PAD_LEFT);
            DB::table("tblpayment")->insert([
                'transid' => strtoupper(bin2hex(random_bytes(5))),
                "school_code" => $request->school_code,
                "acyear" => $request->acyear,
                "student_no" => $request->payment_student_no,
                "acterm" => 1,
                "payment_date" => date('Y-m-d'),
                "receipt_no" => $receipt_code,
                "payment_type" => $request->payment_type,
                "payment_desc" => $request->payment_desc,
                "payment_mode" => $request->payment_type,
                "cheque_bank" => null,
                "cheque_no" => null,
                "network" => null,
                "phone_number" => $request->student_phone_number,
                "amount" => $request->amount_to_pay,
                "total_paid" => $request->amount_paid,
                "cur_balance" => $balance,
                "deleted" => "0",
                "import" => 0,
                "export" => 0,
                'createuser' =>  $request->school_code,
                'createdate' => date('Y-m-d'),
                'modifyuser' => $request->school_code,
                'modifydate' => date('Y-m-d'),
            ]);
            DB::table("tblpayment_history")->insert([
                'transid' => strtoupper(bin2hex(random_bytes(5))),
                "school_code" => $request->school_code,
                "acyear" => $request->acyear,
                "student_no" => $request->payment_student_no,
                "acterm" => 1,
                "payment_date" => date('Y-m-d'),
                "receipt_no" => $receipt_code,
                "payment_type" => $request->payment_type,
                "payment_desc" => $request->payment_desc,
                "payment_mode" => $request->payment_type,
                "cheque_bank" => null,
                "cheque_no" => null,
                "network" => null,
                "phone_number" => $request->student_phone_number,
                "amount" => $request->amount_to_pay,
                "total_paid" => $request->amount_paid,
                "cur_balance" => $balance,
                "deleted" => "0",
                "import" => 0,
                "export" => 0,
                'createuser' =>  $request->school_code,
                'createdate' => date('Y-m-d'),
                'modifyuser' => $request->school_code,
                'modifydate' => date('Y-m-d'),
            ]);
            return response()->json([
                "ok" => true,

            ]);
        } catch (\Throwable $e) {
            Log::error("Failed making payment: " . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "making payment failed!",

            ]);
        }
    }
    //Making full payment

    public function fetchFullPayment($school_code)
    {
        $studentWhoHaveFullyPaid = DB::table('tblbills')
            ->select(DB::raw('SUM(tblpayment.total_paid) AS totalpaid'), DB::raw('SUM(tblbills.amount-tblpayment.total_paid) as balance'), 'tblstudent.fname', 'tblstudent.lname')
            ->join('tblpayment', 'tblpayment.student_no', 'tblbills.student_no')
            ->join('tblstudent', 'tblpayment.student_no', 'tblstudent.student_no')
            ->groupBy('tblstudent.fname')
            ->groupBy('tblstudent.lname')
            ->havingRaw('SUM(tblbills.amount-tblpayment.total_paid)=0')
            ->where('tblbills.school_code', $school_code)
            ->where('tblpayment.school_code', $school_code)
            ->where('tblstudent.school_code', $school_code)
            ->where('tblbills.deleted', 0)
            ->where('tblpayment.deleted', 0)
            ->where('tblstudent.deleted', 0)
            ->get();
        return response()->json([
            "ok" => true,
            "data" => fullPaymentResource::collection($studentWhoHaveFullyPaid)
        ]);
    }

    public function fetchStudentBill(Request $request)
    {
        // $student = DB::table("tblstudent")->where("school_code", $request->school_code)
        //     ->where("deleted", "0")->where("student_no", $request->student_no)->first();

        // $sem = DB::table("tblcourse")->select("semester")->where("school_code", $request->school_code)
        //     ->where("prog", $student->prog)->where("deleted", "0")->first();

        // if (empty($student)) {
        //     return response()->json([
        //         "ok" => false,
        //         "msg" => "Student has not registered for a course"
        //     ]);
        // }

        $bill = DB::table("vtblbill_total")->select("total_bill")
            ->where("school_code", $request->school_code)
            ->where("student_no", $request->student_no)
            ->where("semester", $request->semester)
            ->first();

        if (empty($bill)) {
            return response()->json([
                "ok" => false,
                "msg" => "Please there is no bill generated for this student!"
            ]);
        }
        return response()->json([
            "data" => $bill,
            "ok" => true
        ]);
    }

    public function fetchStudent(Request $request)
    {
        $student = Student::select("tblstudent.*", "tblprog.prog_desc")
            ->join("tblprog", "tblprog.prog_code", "tblstudent.prog")
            ->where("tblstudent.student_no", $request->student_no)
            ->where("tblstudent.school_code", $request->school_code)
            ->where("tblprog.school_code", $request->school_code)
            ->where("tblstudent.deleted", "0")
            ->where("tblprog.deleted", "0")
            ->first();

        if (empty($student)) {
            return response()->json([
                "ok" => false,
                "data" => "Student code not available! Sorry cannot complete the process, 
                make sure this particular student number is available",
            ]);
        }
        return response()->json([
            "ok" => true,
            "data" => $student
        ]);
    }

    public function fetchStudentBalance(Request $request)
    {
        $balance = DB::table("vtblbill_balance")->where("student_no", $request->student_no)
            ->where("school_code", $request->school_code)
            ->where("semester", $request->semester)
            ->first();
        if (empty($balance)) {
            $bal = 0;
        } else if ($balance->total_balance <= 0) {
            $bal = 0;
        } else {
            $bal = $balance->total_balance;
        }

        return response()->json([
            "ok" => true,
            "data" => $bal
        ]);
    }

    public function fetchStudentArrears(Request $request)
    {
        $bill = DB::table("vtblbill_total")->where("student_no", $request->student_no)
            ->where("school_code", $request->school_code)
            // ->where("deleted", 0)
            ->sum("total_bill");
        // $balance = DB::table("tblpayment")->where("student_no", $request->student_no)
        //     ->where("school_code", $request->school_code)
        //     ->where("deleted", 0)
        //     ->sum("cur_balance");
        $paid = DB::table("vtbloverall_total")->where("student_no", $request->student_no)
            ->where("school_code", $request->school_code)
            // ->where("semester", $request->semester)
            ->first();
        if (empty($paid)) {
            $amount = 0;
            return response()->json([
                "ok" => true,
                "data" => $amount
            ]);
        }

        $amount =  $bill - $paid->total_paid;
        // - $balance;

        return response()->json([
            "ok" => true,
            "data" => $amount
        ]);
    }

    public function fetchStudentTotalPayment(Request $request)
    {
        // $student = DB::table("tblstudent")->where("school_code", $request->school_code)
        //     ->where("deleted", "0")->where("student_no", $request->student_no)->first();

        // $sem = DB::table("tblcourse")->select("semester")->where("school_code", $request->school_code)
        //     ->where("prog", $student->prog)->where("deleted", "0")->first();

        // if (empty($student)) {
        //     return response()->json([
        //         "ok" => false,
        //         "msg" => "Student has not registered for a course"
        //     ]);
        // }

        $totalPayment = DB::table("vtblpayment_total")->where("student_no", $request->student_no)
            ->where("school_code", $request->school_code)
            ->where("semester", $request->semester)
            ->first();

        return response()->json([
            "ok" => true,
            "data" => $totalPayment
        ]);
    }

    public function fetchStudentOverallPayment(Request $request)
    {
        $totalPayment = DB::table("tblpayment")->where("student_no", $request->student_no)
            ->where("school_code", $request->school_code)
            ->where("deleted", 0)
            // ->where("semester", $request->semester)
            ->sum("total_paid");

        return response()->json([
            "ok" => true,
            "data" => $totalPayment
        ]);
    }

    public function deleteAllPayment(Request $request)
    {
        // return $request->all();
        try {
            DB::transaction(function () use ($request) {
                DB::table("tblpayment")->where("student_no", $request->student_no)
                    ->where("school_code", $request->school_code)
                    ->where("semester", $request->semester)
                    ->delete();

                DB::table("tblledger_student")->where("student_no", $request->student_no)
                    ->where("sem_code", $request->semester)
                    ->where("school_code", $request->school_code)
                    ->where("type", "payment")
                    ->delete();
            });

            return response()->json([
                "ok" => true,
                "msg" => "Payment deleted successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not delete payment. {$e->getMessage()} On line {$e->getLine()} {$e->getFile()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }

    public function debtors(Request $request)
    {
        $paymentTotals = Payment::select("student_no")
            ->where("school_code", $request->school_code)
            ->where("deleted", 0)
            ->where('semester', $request->semester)
            ->where('branch', $request->branch)
            ->get()->toArray();

        $data = array_map(function ($student) {
            return $student['student_no'];
        }, $paymentTotals);

        $query1 = Student::select(
            "tblstudent.student_no",
            "tblstudent.school_code",
            "tblstudent.fname",
            "tblstudent.lname",
            "tblstudent.mname",
            "tblprog.deleted AS total_paid"
        )
            ->join("tblprog", "tblstudent.prog", "tblprog.prog_code")
            ->whereNotIn('student_no', $data)
            ->where("tblprog.school_code", $request->school_code)
            ->where("tblstudent.session", $request->session)
            ->where("tblstudent.prog", $request->prog)
            ->where("tblstudent.batch", $request->batch)
            ->where("tblstudent.branch_code", $request->branch)
            ->where("tblstudent.school_code", $request->school_code)
            ->where("tblstudent.deleted", "0")
            ->where("tblprog.deleted", "0")
            ->get()->toArray();

        $query2 = ViewPaymentTotal::distinct()->select(
            "tblstudent.student_no",
            "vtblpayment_total.school_code",
            "tblstudent.fname",
            "tblstudent.lname",
            "tblstudent.mname",
            "vtblpayment_total.total_paid"

        )
            ->join("vtblbill_total", "vtblpayment_total.student_no", "vtblbill_total.student_no")
            ->join("tblstudent", "vtblpayment_total.student_no", "tblstudent.student_no")
            ->join("vtbloverall_total", "vtblpayment_total.student_no", "vtbloverall_total.student_no")
            ->join("vtbloverall_bill_total", "vtblpayment_total.student_no", "vtbloverall_bill_total.student_no")
            ->where("vtblpayment_total.school_code", $request->school_code)
            ->where("vtblbill_total.school_code", $request->school_code)
            ->where("tblstudent.school_code", $request->school_code)
            ->where("tblstudent.session", $request->session)
            ->where("tblstudent.prog", $request->prog)
            ->where("tblstudent.branch_code", $request->branch)
            ->where("tblstudent.batch", $request->batch)
            ->where("vtblpayment_total.semester", "=", $request->semester)
            ->where("vtblpayment_total.branch", "=", $request->branch)
            ->where("vtblbill_total.semester", "=", $request->semester)
            ->where("vtblbill_total.branch", "=", $request->branch)
            ->where("vtbloverall_total.branch", "=", $request->branch)
            ->where("vtbloverall_total.school_code", $request->school_code)
            ->where("vtbloverall_bill_total.school_code", $request->school_code)
            ->where("tblstudent.deleted", "0")
            ->whereColumn("vtbloverall_total.total_paid", "<", "vtbloverall_bill_total.total_bill")
            ->get()->toArray();
        $debt = DebtorsResource::collection(array_merge($query2, $query1));
        return response()->json([
            "data" => $debt
        ]);
    }

    public function fullPayment(Request $request)
    {
        $query2 = DB::table("vtblpayment_total")->distinct()->select(
            "tblstudent.student_no",
            "vtblpayment_total.school_code",
            "tblstudent.fname",
            "tblstudent.lname",
            "tblstudent.mname",
            "vtblbill_total.total_bill AS amount",
            "vtblbill_total.branch",
            "vtblbill_total.semester",
            "vtblpayment_total.total_paid"

        )
            ->join("vtblbill_total", "vtblpayment_total.student_no", "vtblbill_total.student_no")
            ->join("tblstudent", "vtblpayment_total.student_no", "tblstudent.student_no")
            ->join("vtbloverall_total", "vtblpayment_total.student_no", "vtbloverall_total.student_no")
            ->where("vtblpayment_total.school_code", $request->school_code)
            ->where("vtblbill_total.school_code", $request->school_code)
            ->where("vtblpayment_total.semester", "=", $request->semester)
            ->where("vtblpayment_total.branch", "=", $request->branch)
            ->where("vtblbill_total.semester", "=", $request->semester)
            ->where("vtblbill_total.branch", "=", $request->branch)
            ->where("tblstudent.school_code", $request->school_code)
            ->where("tblstudent.session", $request->session)
            ->where("tblstudent.prog", $request->prog)
            ->where("tblstudent.batch", $request->batch)
            ->where("tblstudent.branch_code", $request->branch)
            ->where("vtbloverall_total.branch", "=", $request->branch)
            ->where("vtbloverall_total.school_code", $request->school_code)
            ->where("tblstudent.deleted", "0")
            ->whereColumn("vtblpayment_total.total_paid", ">=", "vtblbill_total.total_bill")
            ->get();
        $debt = fullPaymentResource::collection($query2);
        return response()->json([
            "data" => $debt
        ]);
    }

    public function dailyPayment($schoolCode)
    {
        $daily = dailyPaymentResource::collection(
            DB::table("tblledger_student")->select(
                "tblledger_student.*",
                "tblstudent.fname",
                "tblstudent.lname",
                "tblsemester.sem_desc",
                "tblstudent.mname"
            )
                ->join("tblstudent", "tblstudent.student_no", "tblledger_student.student_no")
                ->join("tblsemester", "tblsemester.sem_code", "tblledger_student.sem_code")
                ->where("tblstudent.deleted", "0")
                ->where("tblledger_student.deleted", "0")
                ->where("tblstudent.school_code", $schoolCode)
                ->where("tblledger_student.school_code", $schoolCode)
                ->where("tblledger_student.type", "payment")
                ->where('tblledger_student.createdate', date('Y-m-d'))
                ->orderByDesc("tblledger_student.createdate")
                ->get()
        );

        return response()->json([
            "data" => $daily,
        ]);
    }

    public function filterDailyPayment($schoolCode, $branchCode)
    {
        $daily = dailyPaymentResource::collection(
            DB::table("tblledger_student")->select(
                "tblledger_student.*",
                "tblstudent.fname",
                "tblstudent.lname",
                "tblsemester.sem_desc",
                "tblstudent.mname"
            )
                ->join("tblstudent", "tblstudent.student_no", "tblledger_student.student_no")
                ->join("tblsemester", "tblsemester.sem_code", "tblledger_student.sem_code")
                ->where("tblledger_student.deleted", "0")
                ->where("tblstudent.deleted", "0")
                ->where("tblledger_student.branch_code", $branchCode)
                ->where("tblledger_student.school_code", $schoolCode)
                ->where("tblledger_student.type", "payment")
                ->where("tblstudent.school_code", $schoolCode)
                ->where('tblledger_student.createdate', date('Y-m-d'))
                ->orderByDesc("tblledger_student.createdate")
                ->get()
        );

        return response()->json([
            "data" => $daily,
        ]);
    }

    public function paymentHistory(Request $request)
    {
        if ($request->semester != "all") {
            $bills = PaymentHistoryResource::collection(
                DB::table("tblledger_student")->distinct()->select(
                    "tblledger_student.*",
                    "tblpayment.cheque_bank",
                    "tblpayment.network",
                    "tblpayment.phone_number",
                    "tblpayment.payment_date",
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
                    ->where("tblledger_student.sem_code", $request->semester)
                    ->where("tblledger_student.student_no", $request->student)
                    ->where("tblpayment.student_no", $request->student)
                    ->where("tblpayment.branch", $request->branch)
                    ->where("tblpayment.deleted", "0")
                    ->where("tblstudent.deleted", "0")
                    ->where("tblstudent.school_code", $request->school_code)
                    ->where("tblpayment.school_code", $request->school_code)
                    ->where("tblledger_student.type", "payment")
                    ->where("tblledger_student.deleted", "0")
                    ->where("tblledger_student.branch_code", $request->branch)
                    ->where("tblledger_student.school_code", $request->school_code)
                    ->orderByDesc("tblledger_student.createdate")
                    ->get()
            );
        } else {
            $bills = PaymentHistoryResource::collection(
                DB::table("tblledger_student")->distinct()->select(
                    "tblledger_student.*",
                    "tblpayment.cheque_bank",
                    "tblpayment.network",
                    "tblpayment.phone_number",
                    "tblpayment.payment_date",
                    "tblpayment.cur_balance",
                    "tblpayment.cheque_no",
                    "tblprog.prog_desc",
                    "tblsession.session_desc",
                    "tblbatch.batch_desc",
                    "tblstudent.phone",
                    "tblstudent.fname",
                    "tblstudent.lname",
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
                    ->where("tblpayment.branch", $request->branch)
                    ->where("tblpayment.deleted", "0")
                    ->where("tblstudent.deleted", "0")
                    ->where("tblstudent.school_code", $request->school_code)
                    ->where("tblpayment.school_code", $request->school_code)
                    ->where("tblledger_student.deleted", "0")
                    ->where("tblledger_student.branch_code", $request->branch)
                    ->where("tblledger_student.student_no", $request->student)
                    ->where("tblpayment.student_no", $request->student)
                    ->where("tblledger_student.type", "payment")
                    ->where("tblledger_student.school_code", $request->school_code)
                    ->orderByDesc("tblledger_student.createdate")
                    ->get()
            );
        }

        return response()->json([
            "data" => $bills
        ]);
    }

    public function fetchPaymentLedger(Request $request)
    {
        if ($request->semester != "all") {
            $bills = LedgerResource::collection(
                DB::table("tblledger_student")->distinct()->select(
                    "tblledger_student.*",
                    "tblpayment.cheque_bank",
                    "tblpayment.network",
                    "tblpayment.phone_number",
                    "tblpayment.payment_date",
                    "tblpayment.cur_balance",
                    "tblpayment.cheque_no",
                    "tblstudent.fname",
                    "tblstudent.lname",
                    // "tblbranch.branch_desc",
                    "tblsemester.sem_desc",
                    "tblstudent.mname"
                )
                    ->join("tblstudent", "tblstudent.student_no", "tblledger_student.student_no")
                    ->join("tblsemester", "tblsemester.sem_code", "tblledger_student.sem_code")
                    ->join("tblpayment", "tblledger_student.ref_code", "tblpayment.receipt_no")
                    ->where("tblledger_student.sem_code", $request->semester)
                    ->where("tblpayment.deleted", "0")
                    ->where("tblstudent.deleted", "0")
                    ->where("tblledger_student.deleted", "0")
                    ->where("tblstudent.school_code", $request->school_code)
                    ->where("tblpayment.school_code", $request->school_code)
                    ->where("tblledger_student.school_code", $request->school_code)
                    ->where("tblledger_student.type", "payment")
                    ->where("tblpayment.branch", $request->branch)
                    ->where("tblledger_student.branch_code", $request->branch)
                    ->where("tblstudent.prog", $request->prog)
                    ->whereBetween("tblpayment.payment_date", [$request->from, $request->to])
                    ->orderByDesc("tblledger_student.createdate")
                    ->get()
            );
        } else {
            $bills = LedgerResource::collection(
                DB::table("tblledger_student")->distinct()->select(
                    "tblledger_student.*",
                    "tblpayment.cheque_bank",
                    "tblpayment.network",
                    "tblpayment.phone_number",
                    "tblpayment.payment_date",
                    "tblpayment.cur_balance",
                    "tblpayment.cheque_no",
                    "tblstudent.fname",
                    "tblstudent.lname",
                    // "tblbranch.branch_desc",
                    "tblsemester.sem_desc",
                    "tblstudent.mname"
                )
                    ->join("tblstudent", "tblstudent.student_no", "tblledger_student.student_no")
                    ->join("tblsemester", "tblsemester.sem_code", "tblledger_student.sem_code")
                    ->join("tblpayment", "tblledger_student.ref_code", "tblpayment.receipt_no")
                    ->where("tblpayment.deleted", "0")
                    ->where("tblstudent.deleted", "0")
                    ->where("tblledger_student.deleted", "0")
                    ->where("tblstudent.school_code", $request->school_code)
                    ->where("tblpayment.school_code", $request->school_code)
                    ->where("tblledger_student.school_code", $request->school_code)
                    ->where("tblledger_student.type", "payment")
                    ->where("tblledger_student.branch_code", $request->branch)
                    ->where("tblpayment.branch", $request->branch)
                    ->whereBetween("tblpayment.payment_date", [$request->from, $request->to])
                    ->get()
            );
        }

        return response()->json([
            "data" => $bills
        ]);
    }

    public function deletePaymentHistory(Request $request)
    {
        // return $request->all();
        try {
            DB::transaction(function () use ($request) {
                DB::table("tblpayment")->where("student_no", $request->student_no)
                    ->where("school_code", $request->school_code)
                    ->where("receipt_no", $request->receipt)
                    ->delete();

                DB::table("tblledger_student")->where("student_no", $request->student_no)
                    ->where("ref_code", $request->receipt)
                    ->where("school_code", $request->school_code)
                    ->where("type", "payment")
                    ->delete();
            });

            return response()->json([
                "ok" => true,
                "msg" => "Payment deleted successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not delete payment. {$e->getMessage()} On line {$e->getLine()} {$e->getFile()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }
}
