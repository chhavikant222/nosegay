<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Student::all();
        return response()->json([
            "response" => $user,
            "status" => 200,
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        try{
            $validatedData = $req->validate([
                'email' => 'required|unique:users,email|email:rds,dns',
                'class_id' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'age' => 'required',
                'password' => 'required|confirmed|min:8',
                'password_confirmation' => 'same:password',
                'phone_code' => 'required|regex:/^\+[0-9]+$/',
                'contact' => 'required|unique:users,contact|regex:/^\d{10}$/',
                'user_type'=>'required',
                'mother_name' => 'required',
                'father_name' => 'required',
                'guardian_number' => 'required',
                'gender' => 'required|in:MALE,FEMALE,OTHER',
                'address' => 'required',
                'blood_group' => 'required',
                'profile_image' => 'nullable|image',
                'status' => 'required|in:0,1',

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
            Student::insert([
                "class_id" => $validatedData['class_id'],
                "first_name" => $validatedData['first_name'],
                "last_name" => $validatedData['last_name'],
                "age" => $validatedData['age'],
                "phone_code" => $validatedData['phone_code'],
                "contact" => $validatedData['contact'],
                "email" => $validatedData['email'],
                "password" =>  Hash::make($validatedData['password']),
                "user_type" => $validatedData['user_type'],
                "gender" => $validatedData['gender'],
                "mother_name" => $validatedData['mother_name'],
                "father_name" => $validatedData['father_name'],
                "guardian_number" => $validatedData['guardian_number'],
                "address" => $validatedData['address'],
                "blood_group" => $validatedData['blood_group'],
                "profile_image" => $profile_image,
                "status" => $validatedData['status'],
           ]);
           return response()->json(['message' => 'Student registered successfully', "status" => 201], 201);

          }catch(ValidationException $e){
              $errors = $e->errors();
              return response()->json([
                  'error' => $errors,
                  'status' => 400
              ],400);
          }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req)
    {
        try{
            $validatedData = $req->validate([
                'id' => 'required',
                'class_id' => 'required',
                'email' => 'required|email:rds,dns',
                'first_name' => 'required',
                'last_name' => 'required',
                'age' => 'required',
                'password' => 'required|min:8',
                // 'password_confirmation' => 'same:password',
                'phone_code' => 'required|regex:/^\+[0-9]+$/',
                'contact' => 'required|regex:/^\d{10}$/',
                'user_type'=>'required',
                'mother_name' => 'required',
                'father_name' => 'required',
                'guardian_number' => 'required',
                'gender' => 'required|in:MALE,FEMALE,OTHER',
                'address' => 'required',
                'blood_group' => 'required',
                'profile_image' => 'nullable|image',
                'status' => 'required|in:0,1',
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
            $student = Student::where('id',$validatedData['id'])->first();
            if($student){
                Student::where('id',$validatedData['id'])
                ->update([
                    "class_id" => $validatedData['class_id'],
                    "first_name" => $validatedData['first_name'],
                    "last_name" => $validatedData['last_name'],
                    "age" => $validatedData['age'],
                    "phone_code" => $validatedData['phone_code'],
                    "contact" => $validatedData['contact'],
                    "email" => $validatedData['email'],
                    "password" =>  Hash::make($validatedData['password']),
                    "user_type" => $validatedData['user_type'],
                    "gender" => $validatedData['gender'],
                    "mother_name" => $validatedData['mother_name'],
                    "father_name" => $validatedData['father_name'],
                    "guardian_number" => $validatedData['guardian_number'],
                    "address" => $validatedData['address'],
                    "blood_group" => $validatedData['blood_group'],
                    "profile_image" => $profile_image,
                    "status" => $validatedData['status'],
                ]);
                return response()->json(['message' => 'Student updated successfully', "status" => 201], 201);
            }else{
                return response()->json(['error' => 'Student not found', "status" => 400], 400);
           }
        }catch(ValidationException $e){
              $errors = $e->errors();
              return response()->json([
                  'error' => $errors,
                  'status' => 400
              ],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Student::find($id);
        if($user){
            $user->delete();
            return response()->json([
                'message' => 'Student removed successfully',
                'status' => 204
            ],204);
        }else{
            return response()->json([
                'error' => 'Student does not exist',
                'status' => 400
            ],400);
        }
    }
}
