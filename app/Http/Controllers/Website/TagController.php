<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Validation\ValidationException;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::all();
        return response()->json([
            "response" => $tags,
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
                'tag' => 'required',
                'status' => 'required'
            ]);

            $tag = new Tag;
            $tag->tag = $validatedData['tag'];
            $tag->status = $validatedData['status'];
            $tag->save();

            return response()->json([
                'message' => 'Tag created successfully',
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
                'tag' => 'nullable',
                'status' => 'nullable'
            ]);

            $tag = Tag::find($validatedData['id']);
            if($tag){
                $tag->tag = $validatedData['tag'];
                $tag->status = $validatedData['status'];
                $tag->save();
                return response()->json([
                    'message' => 'Tag updated successfully',
                    'status' => 201
                ],201);
            }else{
                return response()->json([
                    'error' => 'Tag does not exist',
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
        $tag = Tag::find($id);
        if($tag){
            $tag->delete();
            return response()->json([
                'message' => 'Tag deleted successfully',
                'status' => 204
            ],204);
        }else{
            return response()->json([
                'error' => 'Tag does not exist',
                'status' => 400
            ],400);
        }
    }
}
