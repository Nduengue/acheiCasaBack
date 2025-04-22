<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComparisonRequest;
use App\Http\Requests\UpdateComparisonRequest;
use App\Models\Comparison;

class ComparisonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if the user is authenticated
        if (!auth()->user()) {
            return response()->json([
                "success" => false,
                "message" => "You need to be logged in to register interest",
            ], 401);
        }

        $query = Comparison::with(['property.checkPoint', 'user'])
            ->join('properties', 'comparisons.property_id', '=', 'properties.id')
            ->where('comparisons.user_id', auth()->user()->id)
            ->where('comparisons.deleted', false);

        // Ordenações
        if (request()->query('price')) {
            $query->orderBy('properties.price', request()->query('price'));
        }

        if (request()->query('bathroom')) {
            $query->orderBy('properties.bathroom', request()->query('bathroom'));
        }

        if (request()->query('room')) {
            $query->orderBy('properties.room', request()->query('room'));
        }

        $comparisons = $query->get();


        // Check if the user has any comparisons
        if ($comparisons->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "No Comparison found",
            ], 404);
        }

        // Map the results
        $comparisonsArray = $comparisons->map(function ($comparison) {
            return [
                'id' => $comparison->property->id,
                'title' => $comparison->property->title,
                'type' => $comparison->property->type,
                'status' => $comparison->property->status,
                'type_of_business' => $comparison->property->type_of_business,
                'furnished' => $comparison->property->furnished,
                'country' => $comparison->property->country,
                'address' => $comparison->property->address,
                'city' => $comparison->property->city,
                'province' => $comparison->property->province,
                'location' => $comparison->property->location,
                'description' => $comparison->property->description,
                'room' => $comparison->property->room,
                'bathroom' => $comparison->property->bathroom,
                'price' => $comparison->property->price
            ];
        })->toArray();

        return response()->json([
            "success" => true,
            "data" => $comparisonsArray,
        ]);
       
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreComparisonRequest $request)
    {
        // Check if the user is authenticated
        if (!auth()->user()) {
            return response()->json([
                "success" => false,
                "message" => "You need to be logged in to register interest",
            ], 401);
        }

        // Check if the property is already in the comparison list
        $comparison = Comparison::where('property_id', $request->property_id)
            ->where('user_id', auth()->user()->id)
            ->first();
        if ($comparison) {
            return response()->json([
                "success" => false,
                "message" => "Property already in comparison list",
            ], 400);
        }
        // Create a new comparison
        $comparison = Comparison::create([
            'property_id' => $request->property_id,
            'user_id' => auth()->user()->id,
        ]);
        return response()->json([
            "success" => true,
            "message" => "Property added to comparison list",
            "data" => $comparison,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comparison $comparison)
    {
        // Check if the user is authenticated
        if (!auth()->user()) {
            return response()->json([
                "success" => false,
                "message" => "You need to be logged in to register interest",
            ], 401);
        }
        // Check if the comparison exists
        $comparison = Comparison::where('id', $comparison->id)
            ->where('user_id', auth()->user()->id)
            ->first();
        if (!$comparison) {
            return response()->json([
                "success" => false,
                "message" => "Comparison not found",
            ], 404);
        }
        // Delete the comparison
        $comparison->delete();
        return response()->json([
            "success" => true,
            "message" => "Comparison deleted",
        ]);
    }
}
