<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Resources\Staff\StaffNoticeResource;
use App\Models\Notice;
use App\Models\School;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StaffNoticeController extends Controller
{
    public function fetchCurrNotice($schoolCode)
    {
        $currentdate = Carbon::now("Africa/Accra");
        $notice = DB::table("tblnotice")->select("tblnotice.*","tblnotice_type.type_desc","tblnotice_recipient.recipient_desc","tblnotice_recipient.recipient_code")
        ->join("tblnotice_type","tblnotice.notice_type","tblnotice_type.type_code")
        ->join("tblnotice_recipient","tblnotice.notice_recipient","tblnotice_recipient.recipient_code")
        ->where("tblnotice_recipient.deleted","0")
        ->where("tblnotice_type.deleted","0")
        ->where("tblnotice.deleted","0")
        ->where("tblnotice_recipient.school_code",$schoolCode)
        ->where("tblnotice_type.school_code",$schoolCode)
        ->where("tblnotice.school_code",$schoolCode)
        ->whereRaw( '"'. $currentdate . '" BETWEEN tblnotice.date_posted AND tblnotice.date_end')
        ->get();

        return response()->json([
            "data" => StaffNoticeResource::collection($notice)
        ]);
    }

    public function fetchPrevNotice($schoolCode)
    {
        $currentdate = date("Y-m-d");
        $notice = DB::table("tblnotice")->select("tblnotice.*","tblnotice_type.type_desc","tblnotice_recipient.recipient_desc","tblnotice_recipient.recipient_code")
        ->join("tblnotice_type","tblnotice.notice_type","tblnotice_type.type_code")
        ->join("tblnotice_recipient","tblnotice.notice_recipient","tblnotice_recipient.recipient_code")
        ->where("tblnotice_recipient.deleted","0")
        ->where("tblnotice_type.deleted","0")
        ->where("tblnotice.deleted","0")
        ->where("tblnotice_recipient.school_code",$schoolCode)
        ->where("tblnotice_type.school_code",$schoolCode)
        ->where("tblnotice.school_code",$schoolCode)
        ->whereDate("tblnotice.date_end","<",$currentdate)
        ->get();

        return response()->json([
            "data" => StaffNoticeResource::collection($notice)
        ]);
    }

    public function fetchAllNotice($schoolCode)
    {
        $notice = DB::table("tblnotice")->select("tblnotice.*","tblnotice_type.type_desc","tblnotice_recipient.recipient_desc","tblnotice_recipient.recipient_code")
        ->join("tblnotice_type","tblnotice.notice_type","tblnotice_type.type_code")
        ->join("tblnotice_recipient","tblnotice.notice_recipient","tblnotice_recipient.recipient_code")
        ->where("tblnotice_recipient.deleted","0")
        ->where("tblnotice_type.deleted","0")
        ->where("tblnotice.deleted","0")
        ->where("tblnotice_recipient.school_code",$schoolCode)
        ->where("tblnotice_type.school_code",$schoolCode)
        ->where("tblnotice.school_code",$schoolCode)
        ->orderByDesc("tblnotice.createdate")
        ->get();

        return response()->json([
            "data" => StaffNoticeResource::collection($notice)
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "type" => "required",
                "course_recipient" => "required",
                "title" => "required",
                "details" => "required",
                "date_start" => "required",
                "date_end" => "required",
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
                $academicDet =  DB::table("tblacyear")->where("school_code", $request->school_code)
                ->where("deleted", "0")->where("current_term","1")->first();
                $count = Notice::where("school_code", $request->school_code)->where("deleted", "0")->get();
                $tableCount = $count->count();

                $tableCount++;
                $prefix = 'NOT';
                $noticeCode = null;

                switch (strlen($tableCount)) {
                    case 1:
                        $noticeCode =  $prefix . '00' . $tableCount;
                        break;
                    case 2:
                        $noticeCode =  $prefix . '0' . $tableCount;
                        break;
                    default:
                        $noticeCode = $prefix . '' . $tableCount;
                        break;
                }
                $transid = strtoupper(bin2hex(random_bytes(5)));
                Notice::insert([
                    "transid" => $transid,
                    "school_code" => $request->school_code,
                    "notice_code" => $noticeCode,
                    "notice_type" => $request->type,
                    "notice_title" => strtoupper($request->title),
                    "notice_recipient" => 'STU',
                    "notice_details" => $request->details,
                    "course_recipient" => $request->course_recipient,
                    "posted_by" => $request->posted_by,
                    "acyear" => $academicDet->acyear_desc,
                    "term" => $academicDet->acterm,
                    "date_posted" => date("Y-m-d"),
                    "date_start" => $request->date_start,
                    "date_end" => $request->date_end,
                    "deleted" => "0",
                    "createdate" => date("Y-m-d"),
                    "createuser" => $request->createuser,
                ]);

                if (null !== $request->file("fileToUpload")) {

                    $path = $request->file("fileToUpload")->store("public/Notices");

                    Notice::where("transid", $transid)->update([
                        "image_link" => explode('/', $path)[2],
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
                "msg" => "notice added successfully",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add notice. {$e->getMessage()}",
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
                "type" => "required",
                "recipient" => "required",
                "title" => "required",
                "details" => "required",
                "date_start" => "required",
                "date_end" => "required",
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
               
                Notice::where("transid",$request->transid)->update([
                    "notice_type" => $request->type,
                    "notice_title" => strtoupper($request->title),
                    "notice_recipient" => $request->recipient,
                    "notice_details" => $request->details,
                    "posted_by" => $request->posted_by,
                    "date_start" => $request->date_start,
                    "date_end" => $request->date_end,
                    "modifydate" => date("Y-m-d"),
                    "modifyuser" => $request->createuser,
                ]);

                if (null !== $request->file("fileToUpload")) {

                    $path = $request->file("fileToUpload")->store("public/Notices");

                    Notice::where("transid", $request->transid)->update([
                        "image_link" => explode('/', $path)[2],
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
                "msg" => "notice added successfully",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "ok" => false,
                "msg" => "Registration failed. An internal error occured. If this continues please contact an administrator",
                "error" => [
                    "msg" => "Could not add notice. {$e->getMessage()}",
                    "fix" => "Check the error message for clues",
                ]
            ]);
        }
    }
    
    public function destroy($id)
    {
        $not = Notice::find($id);
        if (empty($not)) {
            return response()->json([
                "ok" => false,
                "msg" => "Unknown code supplied",
            ]);
        }

        $updated = $not->update([
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
            "msg" => "Delete successful",
        ]);
    }

    public function noticeStaff($schoolCode)
    {
        $notice = DB::table("tblnotice")
        ->where("deleted","0")
        ->where("school_code",$schoolCode)
        ->Where("notice_recipient","STA")
        ->orWhere("notice_recipient","ALL")
        ->orderByDesc("createdate")
        ->get();

        return response()->json([
            "data" => $notice
        ]);
    }
}
