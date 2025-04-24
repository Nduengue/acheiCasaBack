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
       /*  como os dados
       {
            "id": 11,
            "user_id": 1,
            "category_id": "Praia",
            "title": "Apartamento do Sequele",
            "type": null,
            "status": "usado",
            "type_of_business": "A",
            "furnished": "yes",
            "country": "Angola",
            "address": "Centralidade do Sequele",
            "city": "Cacuaco",
            "province": "Icolo Bengo",
            "location": {
                "": -8.8383,
                "": 13.2344
            },
            "description": "Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid reprehenderit sint cum optio obcaecati mollitia, quidem in, iusto necessitatibus cupiditate sequi ratione eum iure vitae odio delectus autem quasi eligendi!",
            "room": 1,
            "bathroom": 1,
            "useful_sand": null,
            "price": 150000,
            "deleted": false,
            "favorite": 0,
            "created_at": "2025-04-22T15:47:30.000000Z",
            "updated_at": "2025-04-22T15:47:30.000000Z",
            "accommodation_photo": [
                {
                    "id": 1,
                    "property_id": 11,
                    "photo_path": "http://achei-casa-api.mtapp.ao/storage/uploads/E8ggfmRAHEBLMAD03qRxBRcsjl1dNRyMdbUQrU6J.png",
                    "deleted": false
                },
                {
                    "id": 2,
                    "property_id": 11,
                    "photo_path": "http://achei-casa-api.mtapp.ao/storage/uploads/qDRj5rEGSx2fAt3zUSJwMZS6qqZQTQ5qN6J7wNTF.jpg",
                    "deleted": false
                }
            ],
            "offer": [
                {
                    "id": 1,
                    "property_id": 11,
                    "offer_option_id": 1,
                    "deleted": false
                },
                {
                    "id": 2,
                    "property_id": 11,
                    "offer_option_id": 2,
                    "deleted": false
                }
            ],
            "contact": [
                {
                    "id": 1,
                    "agency_id": null,
                    "property_id": 11,
                    "type": "W",
                    "value": "+244936028718"
                },
                {
                    "id": 2,
                    "agency_id": null,
                    "property_id": 11,
                    "type": "C",
                    "value": "+244956654336"
                },
                {
                    "id": 3,
                    "agency_id": null,
                    "property_id": 11,
                    "type": "M",
                    "value": "geral@achei.ao"
                }
            ]
        } */

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
        if (request()->query('useful_sand')) {
            $query->orderBy('properties.useful_sand', request()->query('useful_sand'));
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
                'useful_sand' => $comparison->property->useful_sand,
                'price' => $comparison->property->price,
                'deleted' => $comparison->property->deleted,
                'favorite' => $comparison->property->favorite,
                'created_at' => $comparison->property->created_at,
                'updated_at' => $comparison->property->updated_at,
                'accommodation_photo' => $comparison->property->accommodationPhoto,
                'offer' => $comparison->property->offer,
                'contact' => $comparison->property->contact,
                'check_point' => $comparison->property->checkPoint,
                'user' => [
                    'id' => $comparison->user->id,
                    'name' => $comparison->user->name,
                    'email' => $comparison->user->email,
                    'phone' => $comparison->user->phone,
                    'created_at' => $comparison->user->created_at,
                    'updated_at' => $comparison->user->updated_at,
                ],
                'comparison' => [
                    'id' => $comparison->id,
                    'user_id' => $comparison->user_id,
                    'property_id' => $comparison->property_id,
                    'deleted' => $comparison->deleted,
                    'created_at' => $comparison->created_at,
                    'updated_at' => $comparison->updated_at,
                ],
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
