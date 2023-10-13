<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Student::paginate(5);
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
                'user_id' => 'required|numeric',
                'class_id' => 'required|numeric',
                'mother_name' => 'required',
                'father_name' => 'required',
                'guardian_number' => 'required',
                'status' => 'required|in:0,1',

            ],
            [
                'email.dns' => 'Email format is not valid',
                'email.rds' => 'Email format is not valid',
            ]);

            Student::insert([
                "class_id" => $validatedData['class_id'],
                
                "mother_name" => $validatedData['mother_name'],
                "father_name" => $validatedData['father_name'],
                "guardian_number" => $validatedData['guardian_number'],
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
        $user = JWTAuth::parseToken()->authenticate();
        try{
            $validatedData = $req->validate([
                'class_id' => 'nullable',
                'email' => 'required|email:rds,dns',
                'first_name' => 'nullable|string',
                'last_name' => 'nullable|string',
                'DOB' => 'nullable|string',
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
            
            if(isset($user->id)){
                $profile_image =  null;
                if(isset($validatedData['profile_image']) && !is_null($validatedData['profile_image'])){
                    $tmp = explode('.',$req->file('profile_image')->getClientOriginalName());
                    $ext = end($tmp);
                    $save_imgfile = time() . '.' . $ext;
                    $destinationPath = public_path('/uploads/teachers/');
                    $req->file('profile_image')->move($destinationPath, $save_imgfile);
                    $profile_image = '/uploads/teachers/'.$save_imgfile;
                }
                $user_data = User::where('email',$validatedData['email'])->first();
                if($user_data){
                    User::where('email',$validatedData['email'])->update([
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

                    $student = Student::where('user_id',$user_data->id)->first();

                    if($student){
                        Student::where('user_id',$user_data->id)
                        ->update([
                            "class_id" => is_null($validatedData['class_id']) ? $student->class_id:$validatedData['class_id'],
                            "mother_name" => is_null($validatedData['mother_name'])? $user_data->mother_name:$validatedData['mother_name'],
                            "father_name" => is_null($validatedData['father_name'])? $user_data->father_name:$validatedData['father_name'],
                            "guardian_number" => is_null($validatedData['guardian_number'])? $user_data->guardian_number:$validatedData['guardian_number'],                   "status" => is_null($validatedData['status'])? $user->status:$validatedData['status'],
                        ]);
                        return response()->json(['message' => 'Student updated successfully', "status" => 201], 201);
                    }
                }else{
                    return response()->json(['error' => 'User not found', "status" => 400], 400);
                }
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
