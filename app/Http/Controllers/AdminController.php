<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ForgotPasswordMail;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Gitplus\Arkesel as Sms;
use Illuminate\Support\Facades\DB;;
class AdminController extends Controller
{
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
            'current_password' => 'required',
            'userid' => 'required',
            'school_code' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'msg' => 'Sorry, all fields are required',
                'error' => [
                    'msg' => $validator->errors()->first(),
                    'fix' => 'Kindly fix the above error',
                ],
            ]);
        }
    
        $authenticatedUser = User::where('email', $request->userid)
            ->where('deleted', false)
            ->where('school_code', $request->school_code)
            ->first();
    
        if (!$authenticatedUser) {
            return response()->json([
                'ok' => false,
                'msg' => 'Sorry, you cannot change password for this account',
            ]);
        }
    
        if (!Hash::check($request->current_password, $authenticatedUser->password)) {
            return response()->json([
                'ok' => false,
                'msg' => 'Sorry, wrong current password',
            ]);
        }
    
        // Hash the new password
        $newPasswordHash = Hash::make($request->new_password);
    
        // Update user's password and modification date
        try {
            $authenticatedUser->update([
                'password' => $newPasswordHash,
                'modifydate' => now()->toDateString(),
                'modifyuser' => $authenticatedUser->email,
            ]);
    
            // Send SMS notification (assuming Sms class is correctly implemented)
            // $school = DB::table('tblschool')->where('school_code', $request->school_code)->first();
            // $msgBody = "Your password was successfully changed. Your new password is: {$request->new_password}.";
            // $sms = new Sms("PHARMATRUST", env('ARKESEL_SMS_API_KEY'));
            // $sms->send($school->phone_main, $msgBody);
    
            return response()->json([
                'ok' => true,
                'msg' => 'Password changed successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'msg' => 'An internal error occurred. Reset failed',
                'error' => [
                    'msg' => $e->getMessage(),
                    'fix' => 'Please try again later',
                ],
            ]);
        }
    }
    

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "new_password" => "required",
                "email" => "required",
            ]
        );

        // Payload to be sent with response
        $payload = [
            "ok" => false,
        ];

        if ($validator->fails()) {
            $payload["msg"] = "Sorry, all fields are required";
            $payload["error"] = [
                "msg" => join(" ", $validator->errors()->all()),
                "fix" => "Kindly fix the above errors",
            ];
            return response($payload);
        }

        $authenticatedUser = User::where("email", $request->email)
            ->where("deleted", "0")
            ->first();



        // Return if user not found
        if (empty($authenticatedUser)) {
            $payload["msg"] = "Sorry, the supplied email is not valid in our system";
            return response()->json($payload);
        }


        $schoolUser = School::where("email", $request->email)
            ->where("deleted", "0")
            ->first();

        //create new password
        $password = password_hash($request->new_password, PASSWORD_DEFAULT);


        //update new password with the authenticated user
        try {
            $authenticatedUser->update([
                'password' => $password,
                'modifydate' => date("Y-m-d"),
                'modifyuser' => "admin_forgot_password",
            ]);

            $url = env("EMAIL_URL");

            $receipt = [
                "password" => $request->new_password,
                "school" => $schoolUser->school_name,
            ];
            Mail::to($request->email)->send(new ForgotPasswordMail($receipt));

            $msgBody = <<<MSG
                Your password was successfully changed. Your credentials are;
                password:{$request->new_password}.
                Click the link to login {$url}
            MSG;
            $sms = new Sms("PHARMATRUST", env("ARKESEL_SMS_API_KEY"));
            $sms->send($schoolUser->phone_main, $msgBody);

            return response()->json([
                "ok" => true,
                "msg" => "Password changed successfully",
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
