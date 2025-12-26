<?php

namespace App\Http\Controllers;

use App\Models\part;
use Illuminate\Http\Request;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{

  $parts = part::all(); 
    
    return response()->json([
        'status' => 'success',
        'data' => $parts // نعرض البيانات مباشرة كما هي في قاعدتك
    ]);
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
    public function store(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'stock' => 'required|integer|min:0',
        'status'   => 'required|in:available,out_of_stock,discontinued',
        'location' => 'required|string',

    ]);
    $part = part:: create([
        'name'     => $request->name,
        'stock' => $request->stock,
        'status'   => $request->status,
        'location' => $request->location
    ]);
 

    return response()->json([
        'message' => 'Part created successfully',
        'data'=> $part
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(part $part)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(part $part)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    
    $part = part::findOrFail($id);
    $request->validate([
        'name'     => 'sometimes|string|max:255',
        'stock' => 'sometimes|integer|min:0',
        'status'   => 'sometimes|in:available,out_of_stock,discontinued',
        
    ]);

    $part->update($request->all());

    return response()->json([
        'message' => 'Part updated successfully',
        'data'    => $part
    ]);
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $part = part::findOrFail($id);
    $part->delete();

    return response()->json(['message' => 'Part deleted successfully']);
}

   public function getPartDetails($id)
   {
    $part = Part::findOrFail($id);
    if ($part->stock <= 0) {

        $replacement = $part->replacementItem; 
        $originalPartName = $replacement->part->name;

        if ($replacement) {
            return response()->json([ 
                'status' => 'out_of_stock',
                'message' => 'The main part is not available, try the replacement item',
                'original_item' => [
                    'name' => $part->name,
                    'location' => $part->location
                ],
    
                'replacement_details' => [
                    'name' => $replacement->name,
                    'location' => $replacement->location
                ] ,
                
            ]);
        }
        
        return response()->json([ 
            'status' => 'out_of_stock',
            'message' => 'The part is not available, and no replacement item found'
        ], 404);
    }

    return response()->json([
        'status' => 'available',
        'data' => $part 
    ]); 
   }
}
