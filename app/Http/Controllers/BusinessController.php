<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Models\Business;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // list of businesses for user auth
        if (!auth()->user()) {
            return response()->json([
                "success" => false,
                "message" => "You need to be logged in to register interest",
            ], 401);
        }
        $businesses = Business::with(['property', 'buyer', 'seller', 'intermediary'])
            ->orWhere('buyer_id', auth()->user()->id)
            ->orWhere('seller_id', auth()->user()->id)
            ->orWhere('intermediary_id', auth()->user()->id)
            ->get();
        //if no businesses found
        if ($businesses->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "No Business found",
            ], 404);
        }
        //filtro pelo estado do negócio por query request
        if (request()->has('status')) {
            $businesses = $businesses->where('status', request()->query('status'));
        }

        // build response businesses
        foreach ($businesses as $business) {
            $business->property = $business->property()->with(['user'])->first();
            $business->buyer = $business->buyer()->with(['user'])->first();
            $business->seller = $business->seller()->with(['user'])->first();
            $business->intermediary = $business->intermediary()->with(['user'])->first();
        }
        return response()->json([
            "success" => true,
            "data" => $businesses,
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Business $business)
    {
        //if user is not authenticated
        if (!auth()->user()) {
            return response()->json([
                "success" => false,
                "message" => "You need to be logged in to register interest",
            ], 401);
        }
        // show business
        $business->property = $business->property()->with(['user'])->first();
        $business->buyer = $business->buyer()->with(['user'])->first();
        $business->seller = $business->seller()->with(['user'])->first();
        $business->intermediary = $business->intermediary()->with(['user'])->first();
        return response()->json([
            "success" => true,
            "data" => $business,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBusinessRequest $request, Business $business)
    {
        //if user is not authenticated
        if (!auth()->user()) {
            return response()->json([
                "success" => false,
                "message" => "You need to be logged in to register interest",
            ], 401);
        }
        // update business
        $business->update($request->validated());
        return response()->json([
            "success" => true,
            "data" => $business,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Business $business)
    {
        //if user is not authenticated
        if (!auth()->user()) {
            return response()->json([
                "success" => false,
                "message" => "You need to be logged in to register interest",
            ], 401);
        }
        // update delete business
        $business->update(['deleted' => true]);
        return response()->json([
            "success" => true,
            "message" => "Business deleted successfully",
            "data" => $business,
        ]);
    }
}
