<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Validation\ValidationException;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $question = Question::paginate(5);
        return response()->json([
            "response" => $question,
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
                'question' => 'required',
                'type' => 'required',
                'difficulty' => 'required',
                'subject' => 'required',
                'status' => 'nullable|in:0,1'
            ]);

            $question = new Question;
            $question->question = $validatedData['question'];
            $question->type = $validatedData['type'];
            $question->difficulty = $validatedData['difficulty'];
            $question->subject = $validatedData['subject'];
            $question->status = $validatedData['status'];
            $question->save();

            return response()->json([
                'message' => 'Question created successfully',
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
                'question' => 'nullable',
                'type' => 'nullable',
                'difficulty' => 'nullable',
                'subject' => 'required',
                'status' => 'nullable|in:0,1'
            ]);

            $question = Question::find($validatedData['id']);
            if($question){
                $question->question = $validatedData['question'];
                $question->type = $validatedData['type'];
                $question->difficulty = $validatedData['difficulty'];
                $question->subject = $validatedData['subject'];
                $question->save();
                return response()->json([
                    'message' => 'Question updated successfully',
                    'status' => 201
                ],201);
            }else{
                return response()->json([
                    'error' => 'Question does not exist',
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
        $question = Question::find($id);
        if($question){
            $question->delete();
            return response()->json([
                'message' => 'Question deleted successfully',
                'status' => 204
            ],204);
        }else{
            return response()->json([
                'error' => 'Question does not exist',
                'status' => 400
            ],400);
        }
    }
}
