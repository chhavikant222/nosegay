<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    public function register(Request $req){
        try{
            $validatedData = $req->validate([
                'email' => 'required|unique:users,email|email:rds,dns',
                'first_name' => 'required',
                'last_name' => 'required',
                'DOB' => 'nullable|date_format:Y-m-d',
                'password' => 'required|confirmed|min:6',
                'password_confirmation' => 'same:password',
                'phone_code' => 'required|regex:/^\+[0-9]+$/',
                'contact' => 'required|unique:users,contact|regex:/^\d{10}$/',
                'user_type'=>'required',
                'gender' => 'required',
                'address' => 'nullable',
                'blood_group' => 'nullable',
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
                "DOB" => $validatedData['DOB'],
                "phone_code" => $validatedData['phone_code'],
                "contact" => $validatedData['contact'],
                "email" => $validatedData['email'],
                "password" =>  Hash::make($validatedData['password']),
                "user_type" => $validatedData['user_type'],
                "gender" => $validatedData['gender'],
                "address" => $validatedData['address'],
                "blood_group" => $validatedData['blood_group'],
                "profile_image" => $profile_image,
                "status" => $validatedData['status'],
           ]);
        //    if($validatedData['user_type'] == ){
            
        //    }
           return response()->json(['message' => 'Registered successfully', "status" => 201], 201);

          }catch(ValidationException $e){
              $errors = $e->errors();
              return response()->json([
                  'error' => $errors,
                  'status' => 400
              ],400);
          }
    }

    public function allUsers(){
        $users = User::paginate(5);
        return response()->json([
            "response" => $users,
            "status" => 200,
        ],200);
    }


    public function singleUser(String $id){
        $user = User::find($id);
        return response()->json([
            "response" => $user,
            "status" => 200,
        ],200);
    }

    public function deleteUser(string $id)
    {
        $user = User::find($id);
        if($user){
            $user->delete();
            return response()->json([
                'message' => 'User deleted successfully',
                'status' => 204
            ],204);
        }else{
            return response()->json([
                'error' => 'User does not exist',
                'status' => 400
            ],400);
        }
    }
}
