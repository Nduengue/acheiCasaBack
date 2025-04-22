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

        // Check if the user has any checkpoints
        if ($checkpoints->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No CheckPoint found',
            ], 404);
        }
        // Check if the user has any checkpoints
        if (request()->has('status')) {
            $checkpoints = $checkpoints->where('status', request()->query('status'));
        }
        // Check if the user has any checkpoints
        if (request()->has('property_id')) {
            $checkpoints = $checkpoints->where('property_id', request()->query('property_id'));
        }
        // Check if the user has any checkpoints
        if (request()->has('check_in')) {
            $checkpoints = $checkpoints->where('check_in', request()->query('check_in'));
        }
        // Check if the user has any checkpoints
        if (request()->has('check_out')) {
            $checkpoints = $checkpoints->where('check_out', request()->query('check_out'));
        }
        
        $data = [
            'data'=>$checkpoints,
        ];

        return response()->json($data);
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

        $data = [
            'success' => true,
            'data'=>$checkPoint,
            'message' => 'CheckPoint created successfully',
        ];

        return response()->json($data, 201);
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

        $data = [
            'data'=>$checkPoint,
        ];

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCheckPointRequest $request, CheckPoint $checkPoint = null)
    {
        // if checkPoint is null, find it by id
        if (!$checkPoint) {
            $checkPoint = CheckPoint::find($request->id);
        }
        if (!$checkPoint) {
            return response()->json(['message' => 'CheckPoint not found'], 404);
        }

        if ($checkPoint->user_id != auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($request->status == 'cancelled') {
            $checkPoint->update([
                'status' => 'cancelled',
                'check_out' => null,
            ]);
            return response()->json($checkPoint);
        }

        $checkPoint->update($request->validated());
        return response()->json([
            'success' => true,
            'data' => $checkPoint,
            'message' => 'CheckPoint updated successfully',
        ]);
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
