<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use App\Models\temporayregistration;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
class registercontroller extends Controller

{
    public function register(){
        return view('auth.register');
    }
    //
    public function registeruser(Request $request){
        $validator = Validator::make($request->all(), [
            "firstname"=>"required",
            "surname"=>"required",
            "email"=>"required|email|unique:users",
            "nationality"=>"required",
            "birthday"=>"required",
            "birthplace"=>"required",
            "password"=>[
                "required",
                Password::min(8)->letters()->numbers()->symbols()
            ],
            'cpassword'=>'required|same:password',
            "marital_status"=>"required",
            "gender"=>"required",
            "contactaddress"=>"required",
            "phonenumber"=>"required",
            "whatsappnumber"=>"required",
            "employername"=>"required",
            "refereename"=>"required",
            "refereephonenumber"=>"required",
            "refereesignature"=>"required",
            "refereestamp"=>"required",
            "classsession"=>"required"
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "msg" => "Registering failed failed. " . join(". ", $validator->errors()->all()),
                
            ]);
        }
      /*  
      $request->validate([
        
            "firstname"=>"required",
            "surname"=>"required",
            "email"=>"required|email|unique:users",
            "nationality"=>"required",
            "birthday"=>"required",
            "birthplace"=>"required",
            "password"=>[
                "required",
                Password::min(8)->letters()->numbers()->symbols()
            ],
            'cpassword'=>'required|same:password',
            "marital_status"=>"required",
            "gender"=>"required",
            "contactaddress"=>"required",
            "phonernumber"=>"required",
            "whatsappnumber"=>"required",
            "employername"=>"required",
            "refereename"=>"required",
            "refereephonenumber"=>"required",
            "refereesignature"=>"required",
            "refereestamp"=>"required",
            "class_session"=>"required"
            
       ]);
       */
       try {
        $transactionresult=DB::transaction(function() use ($request){
           $id=temporayregistration::count();
           
               $id++;
            $admissionnumber= strtoupper(strtoupper(bin2hex(random_bytes(5))));
           DB::table("temporayregistrations")->insert([
            'id'=>$id,
            'firstname'=>$request->firstname,
            'surname'=>$request->surname,
            'email'=>$request->email,
            'nationality'=>$request->nationality,
            'birthday'=>$request->birthday,
            'password'=>$request->password,
            'maritalstatus'=>$request->marital_status,
            'gender'=>$request->gender,
            'pobox'=>$request->contactaddress,
            'phonenumber'=>$request->phonenumber,
            'whatsappnumber'=>$request->whatsappnumber,
            'employername'=>$request->employername,
            'refereename'=>$request->refereename,
            'refereephone'=>$request->refereephonenumber,
            'refereeoccupation'=>$request->refereeoccupation,
             'refereesignature'=>$request->refereesignature,
            'refereestamp'=>$request->refereestamp,
             'classsession'=>$request->classsession,
             'branch'=>$request->branch,
             'isapproved'=>'0',
              'admissionnumber'=>$admissionnumber
             
            ]);
            
            $modules=array();
            for ($i=0; $i <sizeof(json_decode(html_entity_decode(stripslashes($request->module)))); $i++) { 
                # code...
                $modules=json_decode(html_entity_decode(stripslashes($request->module)));
                DB::table("tblregisteringstudents_courses")->insert([
                    'studentemail'=>$request->email,
                    'courses'=>$modules[$i]
                ]);
            }
          
                   
        });

        if (!empty($transactionResult)) {
            throw new Exception($transactionResult);
        }
        return response()->json([
            "ok" => true,
            
        ]);
       } 
       catch (\Exception $e) {
        Log::error("Failed to register: " . $e->getMessage());
        return response()->json([
            "ok" => false,
            "msg" => "Registering failed!",
            "error" => [
                "msg" => $e->__toString(),
                "err_msg" => $e->getMessage(),
                
                "fix" => "Please complete all required fields",
            ]
        ]);
    }
       
    
      
    }        
        
    
}
