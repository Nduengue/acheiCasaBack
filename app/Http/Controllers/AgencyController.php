<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Http\Requests\StoreAgencyRequest;
use App\Http\Requests\UpdateAgencyRequest;
use Illuminate\Support\Facades\Auth;

class AgencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agency =   Agency::where("deleted",false)
                    ->where("user_id",Auth::id())
                    ->get();
        
        $data = [
            "data"=>$agency
        ];

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAgencyRequest $request)
    {
        $user = Auth::user();
        // uploda de imagem
        $path = null;
        if ($request->hasFile('path_photo')) {
            $path = $request->file('path_photo')->store('photo', 'public');
        }
        $agency = $user->agencies()->create(array_merge($request->validated(), ['path_photo' => $path ]));
        $agency = Agency::create(array_merge($request->validated(), ['path_photo' => $path ]));
        return response()->json([
            'success' => true,
            'message' => 'Agency store successfully',
            'data' => $agency
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Agency $agency)
    {
        $data = [
            "data"=>$agency
        ];

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAgencyRequest $request, Agency $agency)
    {
        // uploda de imagem
        $path = $agency->path_photo;
        if ($request->hasFile('path_photo')) {
            $path = $request->file('path_photo')->store('photo', 'public');
        }
        
        $agency->update(array_merge($request->validated(), ['path_photo' => $path ]));
        
        return response()->json([
            'success' => true,
            'message' => 'Agency store successfully',
            'data' => $agency
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agency $agency)
    {
        $agency->update(['deleted' => true]);
        return response()->json([
            'success' => true,
            'message' => 'Agency deleted successfully',
        ]);
    }
}
