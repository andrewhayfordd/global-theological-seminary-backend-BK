<?php

namespace App\Http\Controllers;

use App\Http\Resources\managecoursesResourcecontroller;
use App\Http\Resources\ManageUserResource;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Gitplus\Arkesel as Sms;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\UserMail;
use Illuminate\Support\Facades\Mail;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($school_code)
    {
        try {
            //fetching alll users
            $user = ManageUserResource::collection(DB::table('tbluser')->where('school_code', $school_code)->where('deleted', '0')->get());
            return response()->json([
                'data' => $user
            ]);
        } catch (\Throwable $e) {
            //Logging errors
            Log::error("Fetching users failed: " . $e->getMessage(), [
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ]);

            return response()->json([
                "ok" => false,
                "msg" => "An internal error occurred",

            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */




















    public function store(Request $request)
    {
        try {

            // Validating user inputs
            $validator = Validator::make(
                $request->all(),
                [

                    "userEmail" => "required|email|unique:tbluser,email",
                    "userPhone" => "required",
                    "userType" => "required",
                    "user_branch" => "required",
                    // "user_department" => "required",
                    "userFname" => "required",
                    "userLname" => "required"

                ],
                [

                    "userEmail.required" => "User's email not provided",
                    "userPhone.required" => "User's phone not provided",
                    "userType.required" => "User's type not provided",
                    "user_branch.required" => "user branch not selected",
                    // "user_department" => "user department not selected"
                ]
            );
            //displaying validation errors
            if ($validator->fails()) {
                return response()->json([
                    "ok" => false,
                    "msg" => "Adding user failed. " . join(". ", $validator->errors()->all()),
                ]);
            }


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
            //Generating random pass
            // $randomPassword = generateStrongPassword();
            $randomPassword = "12345678";
            // $randomPassword = Str::random(5);
            //User id
            $schoolPrefix = DB::table('tblschool')->select('school_prefix')->where('school_code', $request->school_code)->get();
            $tableCount = DB::table('tbluser')->where('school_code', $request->school_code)->where('deleted', '0')->count();

            $userId = $schoolPrefix[0]->school_prefix . str_pad($tableCount, 4, "0", STR_PAD_LEFT);
            //uploading user profile to img folder

            if ($request->hasFile('userPic')) {
                $fileName = $request->file('userPic')->getClientOriginalName();
                $request->userPic->move(public_path('images/faces/'), $fileName . $userId);
                $fileName = $fileName . $userId;
            } else {
                $fileName = "face5.jpg";
            }

            // Check if user already exists
                $existingUser = DB::table('tbluser')
                ->where('userid', $userId)
                ->orWhere('email', $request->userEmail)
                ->first();

                if ($existingUser) {
                return response()->json([
                    "ok" => false,
                    "msg" => "A user with this email or user ID already exists.",
                 ]);
                }

            //inserting records into database
            DB::table('tbluser')->insert([
                //"id" => strtoupper(bin2hex(random_bytes(20))),
                "id" => null,
                "school_code" => $request->school_code,
                "userid" => $userId,
                "fname" => $request->userFname,
                "lname" => $request->userLname,
                "email" => $request->userEmail,
                "password" => Hash::make($randomPassword),
                "phone" => $request->userPhone,
                "picture" => $fileName,
                "deleted" => '0',
                "dept_code" => $request->user_department,
                "prog_code" => $request->user_program,
                "branch_code" => $request->user_branch,
                "usertype" => $request->userType,
                'createuser' =>  $request->school_code,
                'createdate' => date('Y-m-d'),
                'modifyuser' => $request->school_code,
                'modifydate' => date('Y-m-d'),

            ]);

            $mods = DB::table("tblmodule")->where("system_mod", "=", "1")->get();

            foreach ($mods as $mod) {
                $exists = DB::table("tbluser_module_privileges")
                    ->where("userid", $request->userEmail)
                    ->where("school_code", $request->school_code)
                    ->where("mod_id", $mod->mod_id)
                    ->exists();
            
                if (!$exists) {
                    DB::table("tbluser_module_privileges")->insert([
                        "userid" => $request->userEmail,
                        "school_code" => $request->school_code,
                        "mod_read" => "1",
                        "mod_id" => $mod->mod_id,
                        "createdate" => date("Y-m-d"),
                        "createuser" => "admin",
                    ]);
                }
            }


            return response()->json([
                "ok" => true,
            ]);

            if (!empty($request->phone)) {
                $firstName = strtoupper($request->fname);
                $url = "https://sms.ppc.edu.gh/";

                $msgBody = <<<MSG
                Hello {$firstName}, your {$school->school_name} account has been created.
                URL: {$url}
                Username:{$userId}
                Password:{$randomPassword} 
                Do not share your credentials.
                MSG;
                $sms = new Sms($school->school_prefix, env("ARKESEL_SMS_API_KEY"));
                $sms->send($request->phone, $msgBody);
            }
        } catch (\Throwable $e) {
            Log::error("Failed adding user: " . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Adding user failed.",
                "msg" => env('APP_DEBUG') ? $e->getMessage() : "Adding user failed.",
            ]);
        }
    }



   





    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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


     
    


    // public function update(Request $request)
    // {
    //     //updating users
    //     try {

    //         // Validating user inputs
    //         $validator = Validator::make(
    //             $request->all(),
    //             [

    //                 "userEmail" => "required|email",
    //                 "userPhone" => "required",
    //                 "userType" => "required"
    //             ],
    //             [

    //                 "userEmail.required" => "User's email not provided",
    //                 "userPhone.required" => "User's phone not provided",
    //                 "userType.required" => "User's type not selected"
    //             ]
    //         );
    //         //displaying validation errors
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 "ok" => false,
    //                 "msg" => "Adding user failed. " . join(". ", $validator->errors()->all()),
    //             ]);
    //         }
    //         //selecting user form database
    //         $user = DB::table('tbluser')->where('school_code', $request->school_code)->where('email', $request->userEmail);
    //         if (count($user->get()) == 0) {
    //             return response()->json([
    //                 "ok" => false,
    //                 "msg" => "updating user failed!",



    //             ]);
    //         }
    //         $user->update([
    //             "email" => $request->userEmail,
    //             "phone" => $request->userPhone,
    //             "usertype" => $request->userType
    //         ]);
    //         return response()->json([
    //             "ok" => true,
    //         ]);
    //     } catch (\Throwable $e) {
    //         Log::error("Couldn't delete user: " . $e->getMessage());
    //         return response()->json([
    //             "ok" => false,
    //             "msg" => "Updating user failed!",
    //             "msg" => env('APP_DEBUG') ? $e->getMessage() : "Updating user failed.",

    //         ]);
    //     }
    // }




    public function update(Request $request)
{
    try {
        // Validating user inputs
        $validator = Validator::make(
            $request->all(),
            [
                "userId" => "required", // Validate that userId is provided
                "userEmail" => "required|email",
                "userPhone" => "required",
                "userType" => "required",
                "school_code" => "required" // Validate that school_code is provided
            ],
            [
                "userId.required" => "User ID not provided",
                "userEmail.required" => "User's email not provided",
                "userPhone.required" => "User's phone not provided",
                "userType.required" => "User's type not selected",
                "school_code.required" => "School code not provided"
            ]
        );

        // Return validation errors
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Updating user failed. " . join(". ", $validator->errors()->all()),
            ]);
        }

        // Selecting user from the database using userId and school_code
        $user = DB::table('tbluser')
            ->where('school_code', $request->school_code)
            ->where('userid', $request->userId) // Use userId for a unique match
            ->first();

        // If user not found, return an error
        if (!$user) {
            return response()->json([
                "ok" => false,
                "msg" => "Updating user failed! User not found.",
            ]);
        }

        // Update the user's details
        DB::table('tbluser')
            ->where('school_code', $request->school_code)
            ->where('userid', $request->userId)
            ->update([
                "email" => $request->userEmail,
                "phone" => $request->userPhone,
                "usertype" => $request->userType,
                "modifyuser" => $request->school_code,
                "modifydate" => now(), // Add modification timestamp
            ]);

        // Return success response
        return response()->json([
            "ok" => true,
            "msg" => "User updated successfully.",
        ]);
    } catch (\Throwable $e) {
        // Log the error and return a failure response
        Log::error("Couldn't update user: " . $e->getMessage());
        return response()->json([
            "ok" => false,
            "msg" => "Updating user failed!",
            "error" => env('APP_DEBUG') ? $e->getMessage() : "An error occurred while updating the user.",
        ]);
    }
}





    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($userEmail)
    {
        //
        try {
            $user = DB::table('tbluser')->where('email', $userEmail)->where('deleted', '0');
            if (count($user->get()) == 0) {
                return response()->json([
                    "ok" => false,
                    "msg" => "Unknown Email supplied ",


                ]);
            }
            $updated = $user->update([
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
        } catch (\Throwable $e) {
            Log::error("Couldn't delete user: " . $e->getMessage());
            return response()->json([
                "ok" => false,
                "msg" => "Deleting user failed!",

            ]);
        }
    }






    //filering users
    public function filterUser($userdata)
    {
        // try {
        $userdata = json_decode(html_entity_decode(stripslashes($userdata)));
        //Filtering per program, batch and department
        if ($userdata->school != "" and $userdata->program != "" and $userdata->branch != ""  and $userdata->department != "") {
            //Fetching bills
            $users = ManageUserResource::collection(
                DB::table("tbluser")
                    ->where("school_code", $userdata->school)
                    ->where("prog_code", $userdata->program)
                    ->where("branch_code", $userdata->branch)
                    ->where("dept_code", $userdata->department)
                    ->where("deleted", "0")
                    ->get()
            );
            return response()->json([
                "data" => $users,

            ]);
        }
        //Filtering by program
        if ($userdata->school != "" and $userdata->program != "" and $userdata->branch == ""  and $userdata->department == "") {
            //Fetching bills
            $users = ManageUserResource::collection(
                DB::table("tbluser")
                    ->where("school_code", $userdata->school)
                    ->where("prog_code", $userdata->program)
                    ->where("deleted", "0")
                    ->get()
            );
            return response()->json([
                "data" => $users,

            ]);
        }
        //Filtering by branch 
        if ($userdata->school != "" and $userdata->program == "" and $userdata->branch != ""  and $userdata->department == "") {
            $users = ManageUserResource::collection(
                DB::table("tbluser")
                    ->where("school_code", $userdata->school)
                    ->where("branch_code", $userdata->branch)
                    ->where("deleted", "0")
                    ->get()
            );
            return response()->json([
                "data" => $users,

            ]);
        }
        //Filtering by department
        if ($userdata->school != "" and $userdata->program == "" and $userdata->branch == ""  and $userdata->department != "") {
            $users =  ManageUserResource::collection(
                DB::table("tbluser")
                    ->where("school_code", $userdata->school)
                    ->where("dept_code", $userdata->department)
                    ->where("deleted", "0")
                    ->get()
            );
            return response()->json([
                "data" => $users
            ]);
        }
        //filtering per program and branch
        if ($userdata->school != "" and $userdata->program != "" and $userdata->branch != ""  and $userdata->department == "") {
            $users = ManageUserResource::collection(
                DB::table("tbluser")
                    ->where("school_code", $userdata->school)
                    ->where("branch_code", $userdata->branch)
                    ->where("prog_code", $userdata->program)
                    ->where("deleted", "0")
                    ->get()
            );
            return response()->json([
                "data" => $users
            ]);
        }
        //filtering per program and department
        if ($userdata->school != "" and $userdata->program != "" and $userdata->branch == ""  and $userdata->department != "") {
            $users = ManageUserResource::collection(
                DB::table("tbluser")
                    ->where("school_code", $userdata->school)
                    ->where("dept_code", $userdata->department)
                    ->where("prog_code", $userdata->program)
                    ->where("deleted", "0")
                    ->get()
            );
            return response()->json([
                "data" => $users
            ]);
        }
        //Filtering by branch and department
        if ($userdata->school != "" and $userdata->program == "" and $userdata->branch != ""  and $userdata->department != "") {
            $users = ManageUserResource::collection(
                DB::table("tbluser")
                    ->where("school_code", $userdata->school)
                    ->where("branch_code", $userdata->branch)
                    ->where("dept_code", $userdata->department)
                    ->where("deleted", "0")
                    ->get()
            );
            return response()->json([
                "data" => $users
            ]);
        }
        // } catch (\Throwable $e) {
        //     Log::error("Filtering users failed" . $e->getMessage());
        //     return response()->json([
        //         "ok" => false,
        //         "msg" => "Couldn't filter  users",

        //     ]);
        // }
    }
}
