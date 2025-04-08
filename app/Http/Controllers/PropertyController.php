<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = Property::where('deleted', false)
                    ->where('user_id', Auth::id())
                    ->get();
        // Check if the user has any properties
        if ($properties->isEmpty()) {
            return response()->json([
                'message' => 'No properties found'
            ], 404);
        }
        $properties->each(function ($property) {
            $property->accommodationPhoto;
            $property->offer;
        });
        $data = [
            'data' => $properties
        ];
        return response()->json($data, 200);
    }
    /**
     * Calculate the distance between two points using the Haversine formula.
     */
    public function nearby(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 10);
        $province = $request->query('province',"luanda");
        if (!$latitude || !$longitude) {
            return response()->json(['error' => 'Latitude and longitude are required'], 422);
        }

        $properties = Property::where('deleted', false)
        ->orWhere('province', $province)
        ->get()
        ->filter(function ($property) use ($latitude, $longitude, $radius) {
            $location = $property->location; // Já é um array
            $distance = $this->haversine($latitude, $longitude, $location['lat'], $location['log']);
            return $distance <= $radius;
        });

        foreach ($properties as $property) {
            $property->users;
            $property->proposal;
            $property->ratings;
        }

        return response()->json([
            "data"=>$properties
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePropertyRequest $request)
    {
        $property = Property::create($request->validated());
        // Attach the offers if they exist
        // Assuming 'offer' is a many-to-many relationship
        if ($request->has('offer')) {
            $property->offer()->createMany($request->input('offer'));
        }
        foreach ($request->file('photo') as $image) {
            $path = $image->store('uploads', 'public');
            $property->accommodationPhoto()->create([
                'photo_path' => $path
            ]);
        }
        $property->load('offer');
        $property->load('accommodationPhoto');
        return response()->json([
            'success' => true,
            'data' => $property,
            'message' => 'Property created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($property)
    {
        $property = Property::find($property->id);
        if ($property) {
            return response()->json([
                'data' => $property
            ], 200);
        } else {
            return response()->json([
                'message' => 'Property not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $property->update($request->validated());
        return response()->json([
            'success' => true,
            'data' => $property,
            'message' => 'Property updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        $property->update(['deleted' => true]);
        $property->accommodationPhoto()->update(['deleted' => true]);
        $property->offer()->update(['deleted' => true]);
        $property->load('offer');
        $property->load('accommodationPhoto');
        return response()->json([
            'success' => true,
            'message' => 'Property deleted successfully',
            'data' => $property
        ], 200);
    }
}
