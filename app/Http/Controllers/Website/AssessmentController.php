<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assessment;
use Illuminate\Validation\ValidationException;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assessment = Assessment::all();
        return response()->json([
            "response" => $assessment,
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
                'test_id' => 'required|numeric',
                'total_marks' => 'required|numeric',
                'passing_marks' => 'required|numeric',
                'duration' => 'required'
            ]);

            $assessment = new Assessment;
            $assessment->title = $validatedData['title'];
            $assessment->description = $validatedData['description'];
            $assessment->teacher_id = $validatedData['teacher_id'];
            $assessment->class_id = $validatedData['class_id'];
            $assessment->subject = $validatedData['subject'];
            $assessment->test_type = $validatedData['test_type'];
            $assessment->test_id = $validatedData['test_id'];
            $assessment->total_marks = $validatedData['total_marks'];
            $assessment->passing_marks = $validatedData['passing_marks'];
            $assessment->duration = $validatedData['duration'];
            $assessment->save();

            return response()->json([
                'message' => 'Assessment created successfully',
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
                'test_id' => 'nullable|numeric',
                'total_marks' => 'nullable|numeric',
                'passing_marks' => 'nullable|numeric',
                'duration' => 'nullable'
            ]);

            $assessment = Assessment::find($validatedData['id']);
            if($assessment){
                $assessment->title = $validatedData['title'];
                $assessment->description = $validatedData['description'];
                $assessment->teacher_id = $validatedData['teacher_id'];
                $assessment->class_id = $validatedData['class_id'];
                $assessment->subject = $validatedData['subject'];
                $assessment->test_type = $validatedData['test_type'];
                $assessment->test_id = $validatedData['test_id'];
                $assessment->total_marks = $validatedData['total_marks'];
                $assessment->passing_marks = $validatedData['passing_marks'];
                $assessment->duration = $validatedData['duration'];
                $assessment->save();
                return response()->json([
                    'message' => 'Assessment updated successfully',
                    'status' => 201
                ],201);
            }else{
                return response()->json([
                    'error' => 'Assessment does not exist',
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
        $assessment = Assessment::find($id);
        if($assessment){
            $assessment->delete();
            return response()->json([
                'message' => 'Assessment deleted successfully',
                'status' => 204
            ],204);
        }else{
            return response()->json([
                'error' => 'Assessment does not exist',
                'status' => 400
            ],400);
        }
    }
}
