<?php

namespace App\Http\Controllers;

use App\Http\Resources\staffResource;
use App\Models\School;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Gitplus\Arkesel as Sms;
use App\Http\Resources\StaffAccountResource;
use App\Http\Resources\StaffContactsResource;
use App\Http\Resources\StaffEmploymentResource;
use App\Http\Resources\StaffQualResource;

class staffcontroller extends Controller
{
    public function fetchStaffQual($school_code)
    {
        $stafftbl = StaffQualResource::collection(
            DB::table('tblstaff_qual')->distinct()->select(
                "tblstaff_qual.*",
                "tblqual.qual_desc AS qualification",
                "tblstaff.fname",
                "tblstaff.mname",
                "tblstaff.lname"
            )
                ->join("tblstaff", "tblstaff.staffno", "tblstaff_qual.staffno")
                ->join("tblqual", "tblqual.qual_code", "tblstaff_qual.qual")
                ->where('tblstaff_qual.school_code', $school_code)
                ->where('tblstaff.school_code', $school_code)
                ->where('tblstaff_qual.deleted', '0')
                ->where('tblstaff.deleted', '0')
                ->where('tblqual.deleted', '0')
                ->get()
        );
        return response()->json([
            "data" => $stafftbl
        ]);
    }

    public function fetchStaffContact($school_code)
    {
        $stafftbl = StaffContactsResource::collection(
            DB::table('tblstaff_contacts')->distinct()->select(
                "tblstaff_contacts.*",
                "tblrelation_type.rel_desc",
                "tblstaff.fname",
                "tblstaff.mname",
                "tblstaff.lname"
            )
                ->join("tblstaff", "tblstaff.staffno", "tblstaff_contacts.staffno")
                ->join("tblrelation_type", "tblrelation_type.rel_code", "tblstaff_contacts.relation_type")
                ->where('tblstaff_contacts.school_code', $school_code)
                ->where('tblstaff.school_code', $school_code)
                ->where('tblstaff_contacts.deleted', '0')
                ->where('tblstaff.deleted', '0')
                ->where('tblrelation_type.deleted', '0')
                ->get()
        );
        return response()->json([
            "data" => $stafftbl
        ]);
    }

    public function fetchStaffEmployment($school_code)
    {
        $data = DB::table("tblemp_details")->select(
            "tblemp_details.*",
            "tblemp_type.emptype_desc",
            "tblstaff.fname",
            "tblstaff.mname",
            "tblstaff.lname"
        )
            ->join("tblemp_type", "tblemp_type.emptype_code", "tblemp_details.emp_type")
            ->join("tblstaff", "tblstaff.staffno", "tblemp_details.staffno")
            ->where("tblemp_details.deleted", 0)
            ->where("tblemp_details.school_code", $school_code)
            ->orderByDesc("tblemp_details.createdate")
            ->get();

        return response()->json([
            "data" => StaffEmploymentResource::collection($data)
        ]);
    }

    public function fetchStaffAccountDetails($school_code)
    {
        $data = DB::table("tblstaff_bank")->select(
            "tblstaff_bank.*",
            "tblstaff_account_type.account_desc",
            "tblstaff.fname",
            "tblstaff.mname",
            "tblstaff.lname",
            "tblbank.bank_desc"
        )
            ->join("tblstaff_account_type", "tblstaff_account_type.account_code", "tblstaff_bank.account_type")
            ->join("tblstaff", "tblstaff.staffno", "tblstaff_bank.staffno")
            ->join("tblbank", "tblbank.bank_code", "tblstaff_bank.bank_code")
            ->where("tblstaff_bank.deleted", 0)
            ->where("tblstaff.deleted", 0)
            ->where("tblstaff_bank.school_code", $school_code)
            ->where("tblstaff.school_code", $school_code)
            ->orderByDesc("tblstaff_bank.createdate")
            ->get();

        return response()->json([
            "data" => StaffAccountResource::collection($data)
        ]);
    }
    //populating staff's table with data
    public function index($school_code)
    {
        $stafftbl = staffResource::collection(
            DB::table('tblstaff')
                ->where('school_code', $school_code)
                ->where('deleted', '0')
                ->get()
        );
        return response()->json([
            "data" => $stafftbl
        ]);
    }

    /**
     * Generates a new staff number for a particular school
     */
    private function generateStaffNumber(School $school)
    {
        $count = (int) Staff::where("school_code", "=", $school->school_code)->where("deleted","0")->count();
        $count++;

        switch (strlen($count)) {
            case 1:
                return "{$school->school_prefix}00{$count}S";
                break;

            case 2:
                return "{$school->school_prefix}0{$count}S";
                break;
            case 3:
            default:        // This is a fallthrough
                return "{$school->school_prefix}{$count}S";
                break;      // This break is for defensive purposes.
        }
    }









    
    public function store(Request $request)
    {
        $validator = validator::make(
            $request->all(),
            [
                "fname" => 'required',
                "lname" => 'required',
                "phone" => 'required|numeric|unique:tblstaff,phone|unique:tbluser,phone',
                "email" => 'required|email|unique:tblstaff,email|unique:tbluser,email',
            ],
            [
                "fname.required" => "no saff's firstname provided",
                "lname.required" => "No saff's lastname provided",

                "email.email" => "The supplied email [{$request->email}] is not a valid email",
                "email.required" => "No email supplied",
                "email.unique" => "Email already taken",

                "phone.required" => "No phone number supplied",
                "phone.numeric" => "Phone number supplied [{$request->phone}] must contain only numbers",
                "phone.unique" => "Phone number already taken",
                "gender.required" => "No gender provided",
            ]
        );
        //checking if inputs validation failed
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding staff failed. " . join(". ", $validator->errors()->all()),
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
        $staffno = $this->generateStaffNumber($school);

        try {
            $transactionResult = DB::transaction(function () use ($request, $staffno) {

                Staff::insert([
                    'transid' => strtoupper(strtoupper(bin2hex(random_bytes(5)))),
                    'school_code' => $request->school_code,
                    'staffno' => $staffno,
                    'fname' => ucfirst($request->fname),
                    'mname' => ucfirst($request->mname),
                    'lname' => ucfirst($request->lname),
                    'dob' => $request->dob,
                    'gender' => $request->gender,
                    'marital_status' => $request->marital_status,
                    "picture" => env("APP_URL") . "/storage/student/user.jpg",
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'department' => $request->dept,
                    'postal_address' => $request->postAddress,
                    'residential_address' => $request->resAddress,
                    'staff_type' => $request->staff_type,
                    'source' => "o",
                    'deleted' => '0',
                    'createuser' =>  $request->createuser,
                    'createdate' => date('Y-m-d'),
                ]);

                if (strtolower($request->staff_type) === "ac") {
                    $mods = DB::table("tblmodule")->where("teacher_mod", "=", "1")->get();

                    foreach ($mods as $mod) {
                        $exists = DB::table("tbluser_module_privileges")
                        ->where("userid", $staffno)
                        ->where("school_code", $request->school_code)
                        ->where("mod_id", $mod->mod_id)
                        ->exists();
                        
                    if (!$exists) {
                        DB::table("tbluser_module_privileges")->insert([
                            "userid" => $staffno,
                            "school_code" => $request->school_code,
                            'fname' => ucfirst($request->fname),
                            'lname' => ucfirst($request->lname),
                            "mod_read" => "1",
                            "mod_id" => $mod->mod_id,
                            "createdate" => date("Y-m-d"),
                            "createuser" => "admin",
                        ]);
                    }
                    }
                }
                if (null !== $request->file("image")) {
                    $filePath = $request->file("image")->store("public/staff");
                    Staff::where("staffno", $staffno)->update([
                        "picture" => env("APP_URL") . "/" . str_replace("public", "storage", $filePath),
                    ]);
                }

                DB::table("tbluser")->insert([
                    "id" => null,
                    // "id" => strtoupper(bin2hex(random_bytes(5))),
                    "school_code" => $request->school_code,
                    "userid" => $staffno,
                    "email" => $request->email,
                    "phone" => empty($request->phone) ? "0200000000" : $request->phone,
                    "usertype" => User::TYPE_STAFF,
                    "password" => Hash::make($staffno),
                    "deleted" => "0",
                    "createdate" => date("Y-m-d"),
                    "createuser" => $request->createuser,
                ]);
            });

            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }

            // if (!empty($request->phone)) {
            //     $firstName = strtoupper($request->fname);
            //     $url = "https://sms.ppc.edu.gh/";

            //     $msgBody = <<<MSG
            //     Hello {$firstName}, your {$school->school_name} account has been created.
            //     URL: {$url}
            //     Username:{$staffno}
            //     Password:{$staffno} 
            //     Do not share your credentials.
            //     MSG;
            //     $sms = new Sms($school->school_prefix, env("ARKESEL_SMS_API_KEY"));
            //     $sms->send($request->phone, $msgBody);
            // }

            return response()->json([
                "ok" => true,
            ]);
        } catch (\Throwable $e) {
            Log::error("\n\Adding Staff failed", [
                "errMsg" => $e->getMessage(),
                "trace" => $e->getTrace(),
                "request" => $request->all(),
            ]);
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add staff. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ], 500);
        }
    }












    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:tblstaff,transid',
            // Add other validation rules for required fields
        ]);
    
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
    
        try {
            $staff = DB::table("tblstaff")->where('transid', $request->id)->first();
    
            if (!$staff) {
                return response()->json([
                    "ok" => false,
                    "msg" => "Staff record not found",
                    "error" => [
                        "msg" => "No staff found with the given ID: {$request->id}",
                        "fix" => "Ensure the ID is correct and exists in the database",
                    ]
                ]);
            }
    
            // Proceed with updating the record
            DB::table("tblstaff")->where('transid', $request->id)->update([
                // Update fields as needed
                // 'staffno' => $request->staffno,
                'fname' => ucfirst($request->first_name),
                'mname' => ucfirst($request->Middle_name),
                'lname' => ucfirst($request->last_name),
                'dob' => $request->dob,
                'gender' => $request->gender,
                'marital_status' => $request->marital_status,
                "picture" => env("APP_URL") . "/storage/student/user.jpg",
                'phone' => $request->phone_number,
                'email' => $request->email,
                'department' => $request->dept,
                'postal_address' => $request->postal_address,
                'residential_address' => $request->residential_address,
                'staff_type' => $request->staff_type,
                'source' => "o",
                'deleted' => '0',
                'modifydate' => date('Y-m-d'),
                'modifyuser' => $request->createuser,
            ]);
    
            // Optionally, handle image upload if needed
            if ($request->hasFile('profile_pic')) {
                $filePath = $request->file('profile_pic')->store('public/staff');
                DB::table("tblstaff")->where('transid', $request->id)->update([
                    'picture' => env("APP_URL") . "/" . str_replace("public", "storage", $filePath),
                ]);
            }
    
            return response()->json([
                "ok" => true,
                "msg" => "Update successful",
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Update failed. An internal error occurred. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not save staff model. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }
    













    public function addContact(Request $request)
    {
        $validator = validator::make(
            $request->all(),
            [
                "staff_id" => 'required',
                "name" => 'required',
                "relation" => 'required',
                // "dob" => 'required',
                // "phone" => 'required|numeric|unique:tblstaff_contacts,phone',
                // "email" => 'required|email|unique:tblstaff_contacts,email',
            ],
            [
                "name.required" => "No name provided",
                "relation.required" => "No relation type provided",

                // "email.email" => "The supplied email [{$request->email}] is not a valid email",
                // "email.required" => "No email supplied",
                // "email.unique" => "Email already taken",

                // "phone.required" => "No phone number supplied",
                // "phone.numeric" => "Phone number supplied [{$request->phone}] must contain only numbers",
                // "phone.unique" => "Phone number already taken",
            ]
        );
        //checking if inputs validation failed
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding staff failed. " . join(". ", $validator->errors()->all()),
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

                $transid = strtoupper(strtoupper(bin2hex(random_bytes(5))));
                DB::table("tblstaff_contacts")->insert([
                    'transid' => $transid,
                    'school_code' => $request->school_code,
                    'staffno' => $request->staff_id,
                    'relation_type' => $request->relation,
                    'name' => ucfirst($request->name),
                    'dob' => $request->dob,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'landmark' => $request->land_mark,
                    'gps_code' => $request->gps,
                    'residentail_add' => $request->res_address,
                    'source' => "o",
                    'deleted' => '0',
                    'createuser' =>  $request->createuser,
                    'createdate' => date('Y-m-d'),
                ]);

                if (null !== $request->file("image")) {

                    // $path = $request->file("image")->store("public/staff");
                    $filePath = $request->file("image")->store("public/staff");

                    DB::table("tblstaff_contacts")->where("transid", $transid)->update([
                        "guarantor_form" => env("APP_URL") . "/" . str_replace("public", "storage", $filePath),
                    ]);
                }
            });

            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }
            return response()->json([
                "ok" => true,
            ]);
        } catch (\Throwable $e) {
            Log::error("\n\Adding Staff contact failed", [
                "errMsg" => $e->getMessage(),
                "trace" => $e->getTrace(),
                "request" => $request->all(),
            ]);
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add staff. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ], 500);
        }
    }

    public function addQual(Request $request)
    {
        $validator = validator::make(
            $request->all(),
            [
                "staff_id" => 'required',
                "institution" => 'required',
                "qual" => 'required',
                "comp_year" => 'required',
            ],
            [
                "institution.required" => "No name provided",
                "qual.required" => "No qualification provided",
                "comp_year.required" => "No year completed provided",
            ]
        );
        //checking if inputs validation failed
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding staff failed. " . join(". ", $validator->errors()->all()),
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

                DB::table("tblstaff_qual")->insert([
                    'transid' => strtoupper(strtoupper(bin2hex(random_bytes(5)))),
                    'school_code' => $request->school_code,
                    'staffno' => $request->staff_id,
                    'qual' => $request->qual,
                    'qual_desc' => ucfirst($request->qual_desc),
                    'institution' => $request->institution,
                    'comp_year' => $request->comp_year,
                    'source' => "o",
                    'deleted' => '0',
                    'createuser' =>  $request->createuser,
                    'createdate' => date('Y-m-d'),
                ]);
            });

            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }
            return response()->json([
                "ok" => true,
            ]);
        } catch (\Throwable $e) {
            Log::error("\n\Adding Staff qualification failed", [
                "errMsg" => $e->getMessage(),
                "trace" => $e->getTrace(),
                "request" => $request->all(),
            ]);
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add staff. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ], 500);
        }
    }

    public function addEmployment(Request $request)
    {
        $validator = validator::make(
            $request->all(),
            [
                "staff_id" => 'required',
            ],
            [
                "staff_id.required" => "No staff ID provided",
            ]
        );
        //checking if inputs validation failed
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding staff failed. " . join(". ", $validator->errors()->all()),
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

                DB::table("tblemp_details")->insert([
                    'transid' => strtoupper(strtoupper(bin2hex(random_bytes(5)))),
                    'school_code' => $request->school_code,
                    'staffno' => $request->staff_id,
                    'date_employed' => $request->date,
                    'position' => $request->position,
                    'dept_code' => $request->dept,
                    'emp_type' => $request->type,
                    'deleted' => '0',
                    'createuser' =>  $request->createuser,
                    'createdate' => date('Y-m-d'),
                ]);
            });

            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }
            return response()->json([
                "ok" => true,
            ]);
        } catch (\Throwable $e) {
            Log::error("\n\Adding Staff qualification failed", [
                "errMsg" => $e->getMessage(),
                "trace" => $e->getTrace(),
                "request" => $request->all(),
            ]);
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add staff. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ], 500);
        }
    }

    public function addAccount(Request $request)
    {
        $validator = validator::make(
            $request->all(),
            [
                "staff_id" => 'required',
            ],
            [
                "staff_id.required" => "No staff ID provided",
            ]
        );
        //checking if inputs validation failed
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Adding staff failed. " . join(". ", $validator->errors()->all()),
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

                DB::table("tblstaff_bank")->insert([
                    'transid' => strtoupper(strtoupper(bin2hex(random_bytes(5)))),
                    'school_code' => $request->school_code,
                    'staffno' => $request->staff_id,
                    'bank_code' => $request->bank,
                    'branch' => $request->branch,
                    'account_no' => $request->account_no,
                    'account_type' => $request->type,
                    'deleted' => '0',
                    'createuser' =>  $request->createuser,
                    'createdate' => date('Y-m-d'),
                ]);
            });

            if (!empty($transactionResult)) {
                throw new Exception($transactionResult);
            }
            return response()->json([
                "ok" => true,
            ]);
        } catch (\Throwable $e) {
            Log::error("\n\Adding Staff qualification failed", [
                "errMsg" => $e->getMessage(),
                "trace" => $e->getTrace(),
                "request" => $request->all(),
            ]);
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add staff. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ], 500);
        }
    }

    public function destroy($id)
    {
        $dept = DB::table("tblstaff")
            ->where("transid", $id)->update([
                "deleted" => 1
            ]);


        if (!$dept) {
            return response()->json([
                "ok" => false,
                "msg" => "An internal error ocurred",
            ]);
        }

        return response()->json([
            "ok" => true,
        ], 200);
    }

    public function contactDelete($id)
    {
        $dept = DB::table("tblstaff_contacts")
            ->where("transid", $id)->update([
                "deleted" => 1
            ]);


        if (!$dept) {
            return response()->json([
                "ok" => false,
                "msg" => "An internal error ocurred",
            ]);
        }

        return response()->json([
            "ok" => true,
        ], 200);
    }

    public function qualDelete($id)
    {
        $dept = DB::table("tblstaff_qual")
            ->where("transid", $id)->update([
                "deleted" => 1
            ]);


        if (!$dept) {
            return response()->json([
                "ok" => false,
                "msg" => "An internal error ocurred",
            ]);
        }

        return response()->json([
            "ok" => true,
        ], 200);
    }

    public function empDelete($id)
    {
        $dept = DB::table("tblemp_details")
            ->where("transid", $id)->update([
                "deleted" => 1
            ]);


        if (!$dept) {
            return response()->json([
                "ok" => false,
                "msg" => "An internal error ocurred",
            ]);
        }

        return response()->json([
            "ok" => true,
        ], 200);
    }

    public function accDelete($id)
    {
        $dept = DB::table("tblstaff_bank")
            ->where("transid", $id)->update([
                "deleted" => 1
            ]);


        if (!$dept) {
            return response()->json([
                "ok" => false,
                "msg" => "An internal error ocurred",
            ]);
        }

        return response()->json([
            "ok" => true,
        ], 200);
    }
}
