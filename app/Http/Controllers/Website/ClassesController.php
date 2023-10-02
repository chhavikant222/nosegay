<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Validation\ValidationException;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = Section::all();
        return response()->json([
            "response" => $classes,
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
                'section' => 'required',
                'incharge' => 'required|string',
                'total_students' => 'required|numeric',
            ]);

            $sec = new Section;
            $sec->class = $validatedData['class'];
            $sec->section = $validatedData['section'];
            $sec->incharge = $validatedData['incharge'];
            $sec->total_students = $validatedData['total_students'];
            $sec->save();
            return response()->json([
                'message' => 'Class created successfully',
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
                'section' => 'nullable',
                'incharge' => 'nullable|string',
                'total_students' => 'nullable|numeric',
            ]);

            $sec = Section::find($validatedData['id']);
            if($sec){
                $sec->class = $validatedData['class'];
                $sec->section = $validatedData['section'];
                $sec->incharge = $validatedData['incharge'];
                $sec->total_students = $validatedData['total_students'];
                $sec->save();
                return response()->json([
                    'message' => 'Class updated successfully',
                    'status' => 201
                ],201);
            }else{
                return response()->json([
                    'error' => 'Class does not exist',
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
        $sec = Section::find($id);
        if($sec){
            $sec->delete();
            return response()->json([
                'message' => 'Class deleted successfully',
                'status' => 204
            ],204);
        }else{
            return response()->json([
                'error' => 'Class does not exist',
                'status' => 400
            ],400);
        }
    }
}
