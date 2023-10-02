<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function updateUser(Request $req){
        try{
            $validatedData = $req->validate([
                'email' => 'required|email:rds,dns',
                'first_name' => 'nullable',
                'last_name' => 'nullable',
                'age' => 'nullable',
                'phone_code' => 'nullable|regex:/^\+[0-9]+$/',
                'contact' => 'nullable|regex:/^\d{10}$/',
                'user_type'=>'nullable',
                'mother_name' => 'nullable',
                'father_name' => 'nullable',
                'guardian_number' => 'nullable',
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
            if($user ){
                User::where('email',$validatedData['email'])
                ->update([
                    "first_name" => is_null($validatedData['first_name'])? $user->first_name:$validatedData['first_name'],
                    "last_name" => is_null($validatedData['last_name'])? $user->last_name:$validatedData['last_name'],
                    "age" => is_null($validatedData['age'])? $user->age:$validatedData['age'],
                    "phone_code" => is_null($validatedData['phone_code'])? $user->phone_code:$validatedData['phone_code'],
                    "contact" => is_null($validatedData['contact'])? $user->contact:$validatedData['contact'],
                    "user_type" => is_null($validatedData['user_type'])? $user->user_type:$validatedData['user_type'],
                    "gender" => is_null($validatedData['gender'])? $user->gender:$validatedData['gender'],
                    "mother_name" => is_null($validatedData['mother_name'])? $user->mother_name:$validatedData['mother_name'],
                    "father_name" => is_null($validatedData['father_name'])? $user->father_name:$validatedData['father_name'],
                    "guardian_number" => is_null($validatedData['guardian_number'])? $user->guardian_number:$validatedData['guardian_number'],
                    "address" => is_null($validatedData['address'])? $user->address:$validatedData['address'],
                    "blood_group" => is_null($validatedData['blood_group'])? $user->blood_group:$validatedData['blood_group'],
                    "profile_image" => $profile_image,
                    "status" => is_null($validatedData['status'])? $user->status:$validatedData['status'],
                ]);
                return response()->json(['message' => 'User updated successfully', "status" => 201], 201);
            }else{
                return response()->json(['error' => 'User not found', "status" => 400], 400);
           }
        }catch(ValidationException $e){
              $errors = $e->errors();
              return response()->json([
                  'error' => $errors,
                  'status' => 400
              ],400);
        }
    }

    public function allUsers(){
        $users = User::all();
        return response()->json([
            "response" => $users,
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
