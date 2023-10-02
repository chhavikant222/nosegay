<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Difficulty;
use Illuminate\Validation\ValidationException;

class DifficultyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $difficult_levels = Difficulty::all();
        return response()->json([
            "response" => $difficult_levels,
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
                'level' => 'required|string'
            ]);

            $difficult = new Difficulty;
            $difficult->level = $validatedData['level'];;
            $difficult->save();

            return response()->json([
                'message' => 'Difficulty level created successfully',
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
                'level' => 'nullable'
            ]);

            $difficult = Difficulty::find($validatedData['id']);
            if($difficult){
                $difficult->level = $validatedData['level'];
                $difficult->save();
                return response()->json([
                    'message' => 'Difficulty level updated successfully',
                    'status' => 201
                ],201);
            }else{
                return response()->json([
                    'error' => 'Difficulty level does not exist',
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
        $difficult = Difficulty::find($id);
        if($difficult){
            $difficult->delete();
            return response()->json([
                'message' => 'Difficulty level deleted successfully',
                'status' => 204
            ],204);
        }else{
            return response()->json([
                'error' => 'Difficulty level does not exist',
                'status' => 400
            ],400);
        }
    }
}
