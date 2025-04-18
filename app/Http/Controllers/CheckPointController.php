<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckPointRequest;
use App\Http\Requests\UpdateCheckPointRequest;
use App\Models\CheckPoint;

class CheckPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // where user_id = auth()->user()->id
        $checkpoints =  CheckPoint::where('user_id', auth()->user()->id)
                        ->get();
        return response()->json($checkpoints);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCheckPointRequest $request)
    {
        $checkPoint = CheckPoint::create([
            'user_id' => auth()->user()->id,
            'property_id' => $request->property_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'status' => $request->status,
        ]);

        return response()->json($checkPoint, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CheckPoint $checkPoint)
    {
        // where user_id = auth()->user()->id
        if ($checkPoint->user_id != auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($checkPoint);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCheckPointRequest $request, CheckPoint $checkPoint)
    {
        // where user_id = auth()->user()->id
        if ($checkPoint->user_id != auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        // Check if the status is cancelled
        if ($request->status == 'cancelled') {
            $checkPoint->update([
                'status' => 'cancelled',
                'check_out' => null,
            ]);
            return response()->json($checkPoint);
        }
        // Check if the status is check_out
        $checkPoint->update($request->validated());
        return response()->json($checkPoint);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CheckPoint $checkPoint)
    {
        // where user_id = auth()->user()->id
        if ($checkPoint->user_id != auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $checkPoint->delete();
        return response()->json(['message' => 'CheckPoint deleted successfully']);
    }
}
