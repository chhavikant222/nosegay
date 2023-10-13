<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // Login
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

    // Forget password
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
                   
                    try{
                        //MAIL
                        $data =['user_name'=>$user_data->first_name,'email'=>$user_data->email,'pass'=>$otp];
                        Mail::send('auth.emails.forgot', $data, function($message) use ($data) {
                            $message->to($data['email'],$data['user_name'])->subject('Forgot Password');
                            $message->from('sadarBazaar@gmail.com','SadarBazar');
                        });
                    }catch(\Exception $exp){
                        return response()->json([
                            'message' => 'Failed to send email',
                            'error' => $exp->getMessage(),
                            'status' => 500
                        ], 500);
                    }
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

    // Update user
    public function updateUser(Request $req){
        $user = JWTAuth::parseToken()->authenticate();
        try{
            $validatedData = $req->validate([
                'email' => 'required|email:rds,dns',
                'first_name' => 'nullable',
                'last_name' => 'nullable',
                'DOB' => 'nullable|date_format:Y-m-d',
                'phone_code' => 'nullable|regex:/^\+[0-9]+$/',
                'contact' => 'nullable|regex:/^\d{10}$/',
                'user_type'=>'nullable',
                'gender' => 'nullable|in:MALE,FEMALE,OTHER',
                'address' => 'nullable',
                'blood_group' => 'nullable',
                'profile_image' => 'nullable|image',
                'status' => 'nullable|in:0,1',
            ],
            [
                'email.dns' => 'Email format is not valid',
                'email.rds' => 'Email format is not valid',
            ]);
           
            // if(isset($user->id)){
                $profile_image =  null;
                if(isset($validatedData['profile_image']) && !is_null($validatedData['profile_image'])){
                    $tmp = explode('.',$req->file('profile_image')->getClientOriginalName());
                    $ext = end($tmp);
                    $save_imgfile = time() . '.' . $ext;
                    $destinationPath = public_path('/uploads/teachers/');
                    $req->file('profile_image')->move($destinationPath, $save_imgfile);
                    $profile_image = '/uploads/teachers/'.$save_imgfile;
                }
                $user = User::where('email',$validatedData['email'])->first();
                if($user){
                    User::where('email',$validatedData['email'])
                    ->update([
                        "first_name" => is_null($validatedData['first_name'])? $user->first_name:$validatedData['first_name'],
                        "last_name" => is_null($validatedData['last_name'])? $user->last_name:$validatedData['last_name'],
                        "DOB" => is_null($validatedData['DOB'])? $user->DOB:$validatedData['DOB'],
                        "phone_code" => is_null($validatedData['phone_code'])? $user->phone_code:$validatedData['phone_code'],
                        "contact" => is_null($validatedData['contact'])? $user->contact:$validatedData['contact'],
                        "user_type" => is_null($validatedData['user_type'])? $user->user_type:$validatedData['user_type'],
                        "gender" => is_null($validatedData['gender'])? $user->gender:$validatedData['gender'],
                        "address" => is_null($validatedData['address'])? $user->address:$validatedData['address'],
                        "blood_group" => is_null($validatedData['blood_group'])? $user->blood_group:$validatedData['blood_group'],
                        "profile_image" => $profile_image,
                        "status" => is_null($validatedData['status'])? $user->status:$validatedData['status'],
                    ]);
                    return response()->json(['message' => 'User updated successfully', "status" => 201], 201);
                }else{
                    return response()->json(['error' => 'User not found', "status" => 400], 400);
               }
        //     }else{
        //         return response()->json(['error' => 'User not found', "status" => 400], 400);
        //    }
        }catch(ValidationException $e){
              $errors = $e->errors();
              return response()->json([
                  'error' => $errors,
                  'status' => 400
              ],400);
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
