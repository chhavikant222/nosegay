<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Validation\ValidationException;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subject = Subject::all();
        return response()->json([
            "response" => $subject,
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
                'class' => 'required',
                'subject' => 'required'
            ]);

            $subject = new Subject;
            $subject->class = $validatedData['class'];
            $subject->subject = $validatedData['subject'];
            $subject->save();

            return response()->json([
                'message' => 'Subject created successfully',
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
                'class' => 'nullable',
                'subject' => 'nullable'
            ]);

            $subject = Subject::find($validatedData['id']);
            if($subject){
                $subject->class = $validatedData['class'];
                $subject->subject = $validatedData['subject'];
                $subject->save();
                return response()->json([
                    'message' => 'Subject updated successfully',
                    'status' => 201
                ],201);
            }else{
                return response()->json([
                    'error' => 'Subject does not exist',
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
        $subject = Subject::find($id);
        if($subject){
            $subject->delete();
            return response()->json([
                'message' => 'Subject deleted successfully',
                'status' => 204
            ],204);
        }else{
            return response()->json([
                'error' => 'Subject does not exist',
                'status' => 400
            ],400);
        }
    }
}
