<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Http\Resources\VisitorResource;
use Illuminate\Http\Request;
use App\Models\Patients\Visitors;
use Illuminate\Support\Facades\Validator;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //retrieve all visitors

        //$visitors = Visitors::all();

        $visitors = Visitors::with('patient')
        ->orderBy('created_at', 'desc')
        ->get();

        if($visitors->isEmpty()){
            return response()->json([
                'message' => 'No Visitors found',
            ], 404);
        }

        return response()->json([
            'message' => 'Visitors retrieved successfully',
            'vistors'=> VisitorResource::collection($visitors),
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate request

        $validatedData = Validator::make( $request->all(), [
            'patientId' => 'required|exists:patients,patientId',
            'fullName' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'numOfPeople' => 'required|numeric',
            'relationship' => 'required|in:parent,sibling,spouse,child,relative,friend,other',
        ]);

        if($validatedData->fails()){

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validatedData->errors(),
            ], 400);

        }

        //create a new visitor
        $visitor = Visitors::create([
            'patientId' => $request->patientId,
            'fullName' => $request->fullName,
            'address' => $request->address,
            'phone' => $request->phone,
            'numOfPeople' => $request->numOfPeople,
            'relationship' => $request->relationship,
        ]);

        return response()->json([
            'message' => 'Visitor created successfully',
            'visitor' => $visitor,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $visitor = Visitors::find($id);

        if(!$visitor){
            return response()->json([
                'message' => 'Visitor not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Visitor retrieved successfully',
            'visitor' => $visitor,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //validate request

        $validatedData = Validator::make( $request->all(), [
            'patientId' => 'required|exists:patients,patientId',
            'fullName' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'numOfPeople' => 'required|numeric',
            'relationship' => 'required|in:parent,sibling,spouse,child,relative,friend,other',
        ]);

        if($validatedData->fails()){

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validatedData->errors(),
            ], 400);

        }

        //update the visitor
        $visitor = Visitors::find($id);

        if(!$visitor){
            return response()->json([
                'message' => 'Visitor not found',
            ], 404);
        }

        $visitor->update([
            'patientId' => $request->patientId,
            'fullName' => $request->fullName,
            'address' => $request->address,
            'phone' => $request->phone,
            'numOfPeople' => $request->numOfPeople,
            'relationship' => $request->relationship,
        ]);

        return response()->json([
            'message' => 'Visitor updated successfully',
            'visitor' => $visitor,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $visitor = Visitors::find($id);

        if(!$visitor){
            return response()->json([
                'message' => 'Visitor not found',
            ], 404);
        }

        $visitor->delete();

        return response()->json([
            'message' => 'Visitor deleted successfully',
        ], 200);
    }
}
