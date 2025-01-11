<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Gitplus\Arkesel as Sms;
use App\Http\Resources\EmailResource;
use App\Http\Resources\messageResource;
use App\Http\Resources\SmsResource;
use App\Mail\BulkMail;
use App\Models\AcademicDetails;
use App\Models\MessageSMS;
use App\Models\School;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use yeboahnanaosei\FayaSMS\FayaSMS;

class messagecontroller extends Controller
{
  public function fetchSms($schoolCode)
  {
      $sms = MessageSMS::where("school_code", $schoolCode)->get();
      return response()->json([
          "data" => SmsResource::collection($sms)
      ]);
  }

  public function fetchEmail($schoolCode)
  {
      $email = DB::table("tblemail_sent")->where("school_code", $schoolCode)
          ->where("deleted", "0")->orderByDesc("createdate")->get();

      return response()->json([
          "data" => EmailResource::collection($email)
      ]);
  }

  public function sendNotification(Request $request)
  {
      $validator = Validator::make($request->all(), [
          "notificationBody" => "required",
          "notificationType" => "required",
          "notificationRecipients" => "required",
      ]);

      if ($validator->fails()) {
          return response()->json([
              "ok" => false,
              "msg" => join(" ", $validator->errors()->all()),
          ]);
      }
      $academicDetails = AcademicDetails::where("school_code", $request->school_code)
          ->where("deleted", "0")->where("current_term", "1")->first();

      DB::table("tblsms_sent")->insert([
          "transid" => strtoupper(bin2hex(random_bytes(5))),
          "acyear" => $academicDetails->acyear_desc,
          "acterm" => $academicDetails->acterm,
          "school_code" => $request->school_code,
          "sms" => $request->notificationBody,
          "deleted" => "0",
          "createuser" => $request->createuser,
          "createdate" => date("Y-m-d"),
      ]);
      // Prepare the recipients of the notification
      $notificationRecipients = [];
      switch (strtolower($request->notificationType)) {
          case "sms":
              $this->sendSMSNotification($request);
              break;
          case "push":
              $this->sendPushNotification();  // TODO: To be implemented later
              break;
      }

      return response()->json($notificationRecipients);
  }

  private function sendSMSNotification(Request $request)
  {
      $notificationRecipients = [];

      foreach (json_decode($request->notificationRecipients, true) as $recipient) {

          if (strtolower($recipient) === "users") {
              $students = DB::table("tblstudent")->select("student_phone")
                  ->where("school_code", $request->school_code)
                  ->where("deleted", "0")->get()->toArray();

              $phoneNumbers = array_map(function ($student) {
                  return $student->student_phone;
              }, $students);
              $notificationRecipients = array_merge($notificationRecipients, $phoneNumbers);

              $staffs = DB::table("tblstaff")->select("phone")
                  ->where("school_code", $request->school_code)
                  ->where("deleted", "0")->get()->toArray();

              // Foreach doctor extract their phone number and append it to
              // the list of notification recipients defined above
              $phoneNumbersStaff = array_map(function ($staff) {
                  return $staff->phone;
              }, $staffs);

              $notificationRecipients = array_merge($notificationRecipients, $phoneNumbersStaff);

              $parents = DB::table("tblparent")->select("phone")
                  ->where("school_code", $request->school_code)
                  ->where("deleted", "0")->get()->toArray();

              // Foreach doctor extract their phone number and append it to
              // the list of notification recipients defined above
              $phoneNumbersParent = array_map(function ($parent) {
                  return $parent->phone;
              }, $parents);

              $notificationRecipients = array_merge($notificationRecipients, $phoneNumbersParent);
          }

          if (strtolower($recipient) === "students") {
              $students = DB::table("tblstudent")->select("student_phone")
                  ->where("school_code", $request->school_code)
                  ->where("deleted", "0")->get()->toArray();

              $phoneNumbers = array_map(function ($student) {
                  return $student->student_phone;
              }, $students);
              $notificationRecipients = array_merge($notificationRecipients, $phoneNumbers);
          }

          if (strtolower($recipient) === "staff") {
              // Fetch all doctors as an array
              $staffs = DB::table("tblstaff")->select("phone")
                  ->where("school_code", $request->school_code)
                  ->where("deleted", "0")->get()->toArray();

              // Foreach doctor extract their phone number and append it to
              // the list of notification recipients defined above
              $phoneNumbers = array_map(function ($staff) {
                  return $staff->phone;
              }, $staffs);

              $notificationRecipients = array_merge($notificationRecipients, $phoneNumbers);
          }

          if (strtolower($recipient) === "parents") {
              // Fetch all doctors as an array
              $parents = DB::table("tblparent")->select("phone")
                  ->where("school_code", $request->school_code)
                  ->where("deleted", "0")->get()->toArray();

              // Foreach doctor extract their phone number and append it to
              // the list of notification recipients defined above
              $phoneNumbers = array_map(function ($parent) {
                  return $parent->phone;
              }, $parents);

              $notificationRecipients = array_merge($notificationRecipients, $phoneNumbers);
          }
      }
      // $sms->setRecipientsByArray($notificationRecipients);
      // $sms->setMessageBody($request->notificationBody);
      // $sms->send();

      $school = School::where("school_code", $request->school_code)
          ->where("deleted", "0")->first();
      $sms = new Sms($school->school_prefix, env("ARKESEL_SMS_API_KEY"));
      //    $res=  $sms->send($request->parentPhone, $request->messageBody);
      // Log::info($res);


      $recipientPhones = array_chunk($notificationRecipients, 100);
      foreach ($recipientPhones as $phone) {
          $sms->send(join(",", $phone), $request->notificationBody);
      }
      // $this->saveNotificationMessage($request);
  }

  public function sendBulkEmail(Request $request)
  {
      $validator = Validator::make($request->all(), [
          "email" => "required",
          "emailRecipients" => "required",
      ]);

      if ($validator->fails()) {
          return response()->json([
              "ok" => false,
              "msg" => join(" ", $validator->errors()->all()),
          ]);
      }

      foreach (json_decode($request->emailRecipients, true) as $recipient) {

          if (strtolower($recipient) === "users") {

              $academicDetails = DB::table('tblacyear')->where("school_code", $request->school_code)

                  ->where("deleted", "0")->where("current_term", "1")->first();
              $emailCode = "EM" . strtoupper(bin2hex(random_bytes(3)));
              DB::table("tblemail_sent")->insert([
                  "transid" => strtoupper(bin2hex(random_bytes(5))),
                  "email_code" => $emailCode,
                  "acyear" => $academicDetails->acyear_desc,
                  "acterm" => $academicDetails->acterm,
                  "school_code" => $request->school_code,
                  "email_subject" => $request->subject,
                  "email_message" => $request->email,
                  "deleted" => "0",
                  "createuser" => $request->createuser,
                  "createdate" => date("Y-m-d"),
              ]);




              $students = DB::table("tblstudent")->select("email")
                  ->where("school_code", $request->school_code)
                  ->whereNotNull('email')

                  ->where("deleted", "0")->get();

              $school = School::where("school_code", $request->school_code)->where("deleted", "0")->first();

              $mail = [];
              foreach ($students as $email) {

                  DB::table("tblemail_recipient")->insert([
                      "transid" => strtoupper(bin2hex(random_bytes(5))),
                      "email_code" => $emailCode,
                      "acyear" => $academicDetails->acyear_desc,
                      "acterm" => $academicDetails->acterm,
                      "school_code" => $request->school_code,
                      "recipient_email" => $email->student_email,
                      "deleted" => "0",
                      "createuser" => $request->createuser,
                      "createdate" => date("Y-m-d"),
                  ]);

                  $mail['msg'] = $request->email;
                  $mail['subject'] = $request->subject;
                  $mail['school'] = $school->school_name;
                  $mail['twitter'] = $school->twitter;
                  $mail['facebook'] = $school->facebook;
                  Mail::to($email->student_email)->send(new BulkMail($mail));
                  return;
              }

              $staffs = DB::table("tblstaff")->select("email")
                  ->where("school_code", $request->school_code)
                  ->whereNotNull('email')
                  ->where("deleted", "0")->get();

              $school = School::where("school_code", $request->school_code)->where("deleted", "0")->first();

              $mail = [];
              foreach ($staffs as $email) {

                  DB::table("tblemail_recipient")->insert([
                      "transid" => strtoupper(bin2hex(random_bytes(5))),
                      "email_code" => $emailCode,
                      "acyear" => $academicDetails->acyear_desc,
                      "acterm" => $academicDetails->acterm,
                      "school_code" => $request->school_code,
                      "recipient_email" => $email->email,
                      "deleted" => "0",
                      "createuser" => $request->createuser,
                      "createdate" => date("Y-m-d"),
                  ]);

                  $mail['msg'] = $request->email;
                  $mail['subject'] = $request->subject;
                  $mail['school'] = $school->school_name;
                  $mail['twitter'] = $school->twitter;
                  $mail['facebook'] = $school->facebook;

                  if (!empty($email->email)) {
                      Mail::to($email->email)->send(new BulkMail($mail));
                  }
                  return;
              }
          }

          if (strtolower($recipient) === "students") {
              $academicDetails = AcademicDetails::where("school_code", $request->school_code)
                  ->where("deleted", "0")->where("current_term", "1")->first();
              $emailCode = "EM" . strtoupper(bin2hex(random_bytes(3)));
              DB::table("tblemail_sent")->insert([
                  "transid" => strtoupper(bin2hex(random_bytes(5))),
                  "email_code" => $emailCode,
                  "acyear" => $academicDetails->acyear_desc,
                  "acterm" => $academicDetails->acterm,
                  "school_code" => $request->school_code,
                  "email_subject" => $request->subject,
                  "email_message" => $request->email,
                  "deleted" => "0",
                  "createuser" => $request->createuser,
                  "createdate" => date("Y-m-d"),
              ]);



              $students = DB::table("tblstudent")->select("student_email")
                  ->where("school_code", $request->school_code)
                  ->whereNotNull('student_email')
                  ->where("deleted", "0")->get();

              $school = School::where("school_code", $request->school_code)->where("deleted", "0")->first();

              $mail = [];
              foreach ($students as $email) {

                  DB::table("tblemail_recipient")->insert([
                      "transid" => strtoupper(bin2hex(random_bytes(5))),
                      "email_code" => $emailCode,
                      "acyear" => $academicDetails->acyear_desc,
                      "acterm" => $academicDetails->acterm,
                      "school_code" => $request->school_code,
                      "recipient_email" => $email->student_email,
                      "deleted" => "0",
                      "createuser" => $request->createuser,
                      "createdate" => date("Y-m-d"),
                  ]);

                  $mail['msg'] = $request->email;
                  $mail['subject'] = $request->subject;
                  $mail['school'] = $school->school_name;
                  $mail['twitter'] = $school->twitter;
                  $mail['facebook'] = $school->facebook;
                  Mail::to($email->student_email)->send(new BulkMail($mail));
                  return;
              }
          }

          if (strtolower($recipient) === "staff") {
              $academicDetails = AcademicDetails::where("school_code", $request->school_code)
                  ->where("deleted", "0")->where("current_term", "1")->first();
              $emailCode = "EM" . strtoupper(bin2hex(random_bytes(3)));
              DB::table("tblemail_sent")->insert([
                  "transid" => strtoupper(bin2hex(random_bytes(5))),
                  "email_code" => $emailCode,
                  "acyear" => $academicDetails->acyear_desc,
                  "acterm" => $academicDetails->acterm,
                  "school_code" => $request->school_code,
                  "email_subject" => $request->subject,
                  "email_message" => $request->email,
                  "deleted" => "0",
                  "createuser" => $request->createuser,
                  "createdate" => date("Y-m-d"),
              ]);

              $staffs = DB::table("tblstaff")->select("email")
                  ->where("school_code", $request->school_code)
                  ->whereNotNull('email')
                  ->where("deleted", "0")->get();

              $school = School::where("school_code", $request->school_code)->where("deleted", "0")->first();

              $mail = [];
              foreach ($staffs as $email) {

                  DB::table("tblemail_recipient")->insert([
                      "transid" => strtoupper(bin2hex(random_bytes(5))),
                      "email_code" => $emailCode,
                      "acyear" => $academicDetails->acyear_desc,
                      "acterm" => $academicDetails->acterm,
                      "school_code" => $request->school_code,
                      "recipient_email" => $email->email,
                      "deleted" => "0",
                      "createuser" => $request->createuser,
                      "createdate" => date("Y-m-d"),
                  ]);

                  $mail['msg'] = $request->email;
                  $mail['subject'] = $request->subject;
                  $mail['school'] = $school->school_name;
                  $mail['twitter'] = $school->twitter;
                  $mail['facebook'] = $school->facebook;

                  if (!empty($email->email)) {
                      Mail::to($email->email)->send(new BulkMail($mail));
                  }
                  return;
              }
          }

          if (strtolower($recipient) === "parents") {

              $academicDetails = AcademicDetails::where("school_code", $request->school_code)
                  ->where("deleted", "0")->where("current_term", "1")->first();
              $emailCode = "EM" . strtoupper(bin2hex(random_bytes(3)));
              DB::table("tblemail_sent")->insert([
                  "transid" => strtoupper(bin2hex(random_bytes(5))),
                  "email_code" => $emailCode,
                  "acyear" => $academicDetails->acyear_desc,
                  "acterm" => $academicDetails->acterm,
                  "school_code" => $request->school_code,
                  "email_subject" => $request->subject,
                  "email_message" => $request->email,
                  "deleted" => "0",
                  "createuser" => $request->createuser,
                  "createdate" => date("Y-m-d"),
              ]);


              $parents = DB::table("tblparent")->select("email")
                  ->where("school_code", $request->school_code)
                  ->whereNotNull('email')
                  ->where("deleted", "0")->get();

              $school = School::where("school_code", $request->school_code)->where("deleted", "0")->first();
              $mail = [];
              foreach ($parents as $email) {

                  DB::table("tblemail_recipient")->insert([
                      "transid" => strtoupper(bin2hex(random_bytes(5))),
                      "email_code" => $emailCode,
                      "acyear" => $academicDetails->acyear_desc,
                      "acterm" => $academicDetails->acterm,
                      "school_code" => $request->school_code,
                      "recipient_email" => $email->email,
                      "deleted" => "0",
                      "createuser" => $request->createuser,
                      "createdate" => date("Y-m-d"),
                  ]);

                  $mail['msg'] = $request->email;
                  $mail['subject'] = $request->subject;
                  $mail['school'] = $school->school_name;
                  $mail['twitter'] = $school->twitter;
                  $mail['facebook'] = $school->facebook;
                  Mail::to($email->email)->send(new BulkMail($mail));
                  return;
              }
          }
          return response()->json([
              "ok" => true,
              "msg" => "Request successful"
          ]);
      }
  }
}
