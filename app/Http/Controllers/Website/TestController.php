<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Test;
use Illuminate\Validation\ValidationException;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tests = Test::paginate(5);
        return response()->json([
            "response" => $tests,
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
                'title' => 'required',
                'description' => 'required',
                'teacher_id' => 'required|numeric|min:1',
                'class_id' => 'required|numeric',
                'subject' => 'required',
                'test_type' => 'required|in:SUBJECTIVE,OBJECTIVE',
                'questions' => 'required',
                'total_marks' => 'required|numeric',
                'passing_marks' => 'required|numeric',
                'duration' => 'required'
            ]);

            $test = new Test;
            $test->title = $validatedData['title'];
            $test->description = $validatedData['description'];
            $test->teacher_id = $validatedData['teacher_id'];
            $test->class_id = $validatedData['class_id'];
            $test->subject = $validatedData['subject'];
            $test->test_type = $validatedData['test_type'];
            $test->questions = $validatedData['questions'];
            $test->total_marks = $validatedData['total_marks'];
            $test->passing_marks = $validatedData['passing_marks'];
            $test->duration = $validatedData['duration'];
            $test->save();

            return response()->json([
                'message' => 'Test created successfully',
                'status' => 201
            ],201);
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
                'title' => 'nullable',
                'description' => 'nullable',
                'teacher_id' => 'nullable|numeric|min:1',
                'class_id' => 'nullable|numeric',
                'subject' => 'nullable',
                'test_type' => 'nullable|in:SUBJECTIVE,OBJECTIVE',
                'questions' => 'nullable',
                'total_marks' => 'nullable|numeric',
                'passing_marks' => 'nullable|numeric',
                'duration' => 'nullable'
            ]);

            $test = Test::find($validatedData['id']);
            if($test){
                $test->title = $validatedData['title'];
                $test->description = $validatedData['description'];
                $test->teacher_id = $validatedData['teacher_id'];
                $test->class_id = $validatedData['class_id'];
                $test->subject = $validatedData['subject'];
                $test->test_type = $validatedData['test_type'];
                $test->questions = $validatedData['questions'];
                $test->total_marks = $validatedData['total_marks'];
                $test->passing_marks = $validatedData['passing_marks'];
                $test->duration = $validatedData['duration'];
                $test->save();
                return response()->json([
                    'message' => 'Test updated successfully',
                    'status' => 201
                ],201);
            }else{
                return response()->json([
                    'error' => 'Test does not exist',
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $test = Test::find($id);
        if($test){
            $test->delete();
            return response()->json([
                'message' => 'Test deleted successfully',
                'status' => 204
            ],204);
        }else{
            return response()->json([
                'error' => 'Test does not exist',
                'status' => 400
            ],400);
        }
    }
}
