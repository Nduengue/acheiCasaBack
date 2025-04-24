<?php

namespace App\Http\Controllers;

use App\Models\Property;

class LikeController extends Controller
{ 
    /**
     * Update the specified resource in storage.
     */
    public function like($property)
    {
        $property = Property::find($property);
        //delete the like from the property
        if($property->like()->where('user_id', auth()->user()->id)->exists()) {
            $property->like()->where('user_id', auth()->user()->id)->delete();
            return response()->json(['message' => 'Like removed successfully']);
        }
        //if the user has not liked the property, create a new like
        if($property->like()->where('user_id', auth()->user()->id)->doesntExist()) {
            $property->like()->create([
                'user_id' => auth()->user()->id,
            ]);
            return response()->json(['message' => 'Like added successfully']);
        }
    }
}