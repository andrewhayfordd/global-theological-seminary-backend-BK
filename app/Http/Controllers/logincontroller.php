<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rules\Password;

class logincontroller extends Controller
{
    //login function

    public function login(Request $request)
    {
        $request->validate([
    
        "email"=>"required|email",
        "password"=>[
            "required",
            Password::min(8)->letters()->numbers()->symbols()
        ],
        
   ]);
    
   $authenticatedUser = User::where("email", strtolower($request->email))
   ->where("deleted", "0")
   ->where("attempts",">", "0")
   ->first();
       if ($authenticatedUser) {
          if ($request->password==$authenticatedUser->password) {
            # code...
            $request->session()->put('loggedinuser',$authenticatedUser->email );
            return redirect('/'); 
          }
          else{
            $attempts=$authenticatedUser->attempts;
            
            $attempts--;
              $authenticatedUser =User::where("email", strtolower($request->email))
              ->where("deleted", "0")
              ->where("attempts",">", "0"); 
              
                $authenticatedUser->update([
                  "attempts"=> $attempts,
              ]); 
                
              return back()->with('fail', "Password does not match");
            
          }
       }
       else{
        return back()->with('fail', 'Hey, sorry , this email is not registered, or we have to block you at this time! Too many attempts. Please contact your administrator!');
       }
    }
}      

        
