<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $req){
        try{
            $validatedData = $req->validate([
                'email' => 'required|unique:users,email|email:rds,dns',
                'first_name' => 'required',
                'last_name' => 'required',
                'age' => 'required',
                'password' => 'required|confirmed|min:6',
                'password_confirmation' => 'same:password',
                'phone_code' => 'required|regex:/^\+[0-9]+$/',
                'contact' => 'required|unique:users,contact|regex:/^\d{10}$/',
                'user_type'=>'required',
                'gender' => 'required',
                'address' => 'required',
                'mother_name' => 'nullable',
                'father_name' => 'nullable',
                'guardian_number' => 'nullable|regex:/^\d{10}$/',
                'blood_group' => 'required',
                'profile_image' => 'nullable|image',
                'status' => 'required|in:0,1'
            ],
            [
                'email.dns' => 'Email format is not valid',
                'email.rds' => 'Email format is not valid',
            ]);

            $profile_image =  null;
            if(isset($validatedData['profile_image']) && !is_null($validatedData['profile_image'])){
                $tmp = explode('.',$req->file('profile_image')->getClientOriginalName());
                $ext = end($tmp);
                $save_imgfile = time() . '.' . $ext;
                $destinationPath = public_path('/uploads/users/');
                $req->file('profile_image')->move($destinationPath, $save_imgfile);
                $profile_image = '/uploads/users/'.$save_imgfile;
            }
            User::insert([
                "first_name" => $validatedData['first_name'],
                "last_name" => $validatedData['last_name'],
                "age" => $validatedData['age'],
                "phone_code" => $validatedData['phone_code'],
                "contact" => $validatedData['contact'],
                "email" => $validatedData['email'],
                "password" =>  Hash::make($validatedData['password']),
                "user_type" => $validatedData['user_type'],
                "gender" => $validatedData['gender'],
                "address" => $validatedData['address'],
                "mother_name" => $validatedData['mother_name'],
                "father_name" => $validatedData['father_name'],
                "guardian_number" => $validatedData['guardian_number'],
                "blood_group" => $validatedData['blood_group'],
                "profile_image" => $profile_image,
                "status" => $validatedData['status'],
           ]);
           return response()->json(['message' => 'Registered successfully', "status" => 201], 201);

          }catch(ValidationException $e){
              $errors = $e->errors();
              return response()->json([
                  'error' => $errors,
                  'status' => 400
              ],400);
          }
    }

    public function login(Request $req){
        try{
            $validatedData = $req->validate([
                'email' => 'required|email:rds,dns',
                'password' => 'required'
            ]);
            $user = User::where('email',$req->email)->first();
            if($user){
                if (Hash::check($req->password, $user->password)) {
                    $token = JWTAuth::fromUser($user);
                    User::where('email',$validatedData['email'])->update([
                      'remember_token'=> $token
                     ]);
                    return response()->json(['message' => 'Logged in successfully', 'token' => $token,'base_url' => url('/'),'user_details'=>$user, "status" => 200], 200);
               }else{
                 return response()->json([
                    'error' => 'Incorrect password',
                    'status' => 400
                ],400);
               }
            }else{
                return response()->json([
                    'error' => 'User not exist',
                    'status' => 400
                ],400);
            }
        }catch(ValidationException $e){
            $errors = $e->errors();
            return response()->json([
                'error' => $errors,
                'status' => 400
            ],400);
        }
    }

    public function forgetPassword(Request $req){
       
        if($req->email != null){
            try {
                $validatedData = $req->validate([
                'email' => 'required|email:rds,dns'
                ],
                [
                    'email.dns' => 'Email format is not valid',
                    'email.rds' => 'Email format is not valid',
                ]);
                $user_data = User::where('email', $req->email)->first();
                if($user_data){   // genrate otp
                   $otp= rand(100089, 999945);
                   User::where('email', $req->email)->update([
                    'password' => Hash::make($otp)
                   ]);                
                   
                    return response()->json(["message" => 'A temporary password has been sent to your email.
                    Please login through this otp', "otp" => $otp, "status" => 200], 200);
                }else{
                   return response()->json(["message" => 'Email not matched', "status" => 400],400);
                }
            } catch (ValidationException $exception) {
                $errors = $exception->errors();
                return response()->json(['message' => 'Validation failed', 'errors' => $errors, "status" => 400],400);
            }
        }else{
            return response()->json(["error" => 'Enter email first', "status" => 400],400);
        }

    }

      // Update Password :-
      public function updatePassword(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $email = $request->email;

        try {
            $validatedData = $request->validate([
                'email' => 'required|email:rds,dns',
                "old_password" => 'required',
                "new_password" => 'required|min:8'
            ],
            [
                'email.dns' => 'Email format is not valid',
                'email.rds' => 'Email format is not valid',
            ]);
    
            $data = User::where('email',$validatedData['email'])->first();
            if($data){
                if (Hash::check($validatedData['old_password'], $data->password)){
                    User::where('email',$email)->update([
                        'password'=> Hash::make($validatedData['new_password'])
                    ]);
                    return response()->json([
                        "message" => "Password changed"
                        , "status" => 201], 201);
                }else{
                    return response()->json([
                        "message" => "Old password not matched", "status" => 400], 400);
                }
            }else{
                return response()->json(["result"=>"User does not exist", "status" => 400]);
            }
        } catch (ValidationException $exception) {
            // Validation failed
            $errors = $exception->errors();
    
            return response()->json(['message' => 'Validation failed', 'errors' => $errors, "status" => 400],400);
        }
    }
}
