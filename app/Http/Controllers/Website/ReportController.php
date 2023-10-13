<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = Report::paginate(5);
        return response()->json([
            "response" => $reports,
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
                'marks_obtained' => 'required|numeric|min:1',
                'test_id' => 'required|numeric',
                'generated_by' => 'required',
                'total_marks' => 'required|numeric',
            ]);

            $report = new Report;
            $report->title = $validatedData['title'];
            $report->description = $validatedData['description'];
            $report->marks_obtained = $validatedData['marks_obtained'];
            $report->test_id = $validatedData['test_id'];
            $report->generated_by = $validatedData['generated_by'];
            $report->total_marks = $validatedData['total_marks'];
            $report->save();
            return response()->json([
                'message' => 'Report created successfully',
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
                'marks_obtained' => 'nullable|numeric|min:1',
                'test_id' => 'nullable|numeric',
                'generated_by' => 'nullable',
                'total_marks' => 'nullable|numeric',
            ]);

            $report = Report::find($validatedData['id']);
            if($report){
                $report->title = $validatedData['title'];
                // $report->description = $validatedData['description'];
                // $report->marks_obtained = $validatedData['marks_obtained'];
                // $report->test_id = $validatedData['test_id'];
                // $report->generated_by = $validatedData['generated_by'];
                // $report->total_marks = $validatedData['total_marks'];
                $report->save();;
                return response()->json([
                    'message' => 'Report updated successfully',
                    'status' => 201
                ],201);
            }else{
                return response()->json([
                    'error' => 'Report does not exist',
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
        $report = Report::find($id);
        if($report){
            $report->delete();
            return response()->json([
                'message' => 'Report deleted successfully',
                'status' => 204
            ],204);
        }else{
            return response()->json([
                'error' => 'Report does not exist',
                'status' => 400
            ],400);
        }
    }
}
