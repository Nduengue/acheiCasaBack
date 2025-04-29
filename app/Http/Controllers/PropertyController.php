<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\Notice;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->query('page', 1);
        // Get the properties for the authenticated user
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
            $property->contact;
            $property->comments = $property->comment()->where('deleted', false)->get();
            $property->likes = $property->like()->where("deleted", false)->get();
            $property->user;
        });
        $data = [
            'data' => $properties
        ];
        return response()->json($data, 200);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function agency()
    {
        $page = request()->query('page', 1);
        // Check if Call to a member function contains() on null
        if (empty(Auth::user()->agencyUsers)) {
            return response()->json([
                'message' => 'Agency not found'
            ], 404);
        }
       
        // Get the properties for the authenticated user
        if(request('agency_id') == null){
            $properties = Property::where('deleted', false)
                ->where('user_id', Auth::id())
                ->where('agency_id', Auth::user()->agencyUsers->pluck('agency_id'))
                ->get();
        }else{
           $properties = Property::where('deleted', false)
                    ->whereIn('agency_id',Auth::user()->agencyUsers->pluck('agency_id'))
                    ->where('agency_id', request()->query('agency_id'))
                    ->get();
        }
        if ($properties->isEmpty()) {
            return response()->json([
                'message' => 'No properties found'
            ], 404);
        }
        // Check if the agency has any properties
        $properties->each(function ($property) {
            $property->accommodationPhoto;
            $property->offer;
            $property->contact;
            $property->comments = $property->comment()->where('deleted', false)->get();
            $property->likes = $property->like()->where("deleted", false)->get();
            $property->user;
        });
    
        $data = [
            'data' => $properties
        ];
        return response()->json($data, 200);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function base()
    {
        $page = request()->query('page', 1);
        if (request()->query('category_id') == null) {
        $properties = Property::where('deleted', false)
            ->where('province', request()->query('province', 'Luanda'))
            ->orWhere('announces', true)
            ->orderBy('announces', 'desc')
            ->paginate(8, ['*'], 'page', $page);
        }
        if (request()->query('category_id') != null) {
            $properties = Property::where('deleted', false)
                    ->where('province', request()->query('province', 'Luanda'))
                    ->where('category_id', request()->query('category_id'))
                    ->paginate(8, ['*'], 'page', $page);
        }
        // Check if the user has any properties
        if ($properties->isEmpty()) {
            return response()->json([
                'message' => 'No properties found'
            ], 404);
        }
        $properties->each(function ($property) {
            $property->accommodationPhoto;
            $property->offer;
            $property->contact;
            $property->comments = $property->comment()->where('deleted', false)->get();
            $property->likes = $property->like()->where("deleted", false)->get();
            $property->user;
        });
        $data = [
            'data' => $properties
        ];
        return response()->json($data, 200);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all()
    {
        $page = request()->query('page', 1);
        if (request()->query('category_id') == null) {
        $properties = Property::where('deleted', false)
            ->where('province', request()->query('province', 'Luanda'))
            ->orWhere('announces', true)
            ->orderBy('announces', 'desc')
            ->paginate(8, ['*'], 'page', $page);
        }
        if (request()->query('category_id') != null) {
            $properties = Property::where('deleted', false)
                    ->where('province', request()->query('province', 'Luanda'))
                    ->where('category_id', request()->query('category_id'))
                    ->paginate(8, ['*'], 'page', $page);
        }
        // Check if the user has any properties
        if ($properties->isEmpty()) {
            return response()->json([
                'message' => 'No properties found'
            ], 404);
        }
        $properties->each(function ($property) {
            $property->accommodationPhoto;
            $property->offer;
            $property->contact;
            $property->comments = $property->comment()->where('deleted', false)->get();
            $property->likes = $property->like()->where("deleted", false)->get();
            $property->user;
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
        $page = request()->query('page', 1);
        // Get the latitude and longitude from the request
        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');
        $radius = $request->query('radius', 10);
        
        if (!$latitude || !$longitude) {
            return response()->json(['error' => 'Latitude and longitude are required'], 422);
        }

        $properties = Property::where('deleted', false)->paginate(8, ['*'], 'page', $page)->filter(function ($property) use ($latitude, $longitude, $radius) {
            $location = $property->location; // Já é um array
            $distance = $this->haversine($latitude, $longitude, $location['lat'], $location['lng']);
            return $distance <= $radius;
        });

        $properties->each(function ($property) {
            $property->accommodationPhoto;
            $property->offer;
            $property->contact;
            $property->comments = $property->comment()->where('deleted', false)->get();
            $property->likes = $property->like()->where("deleted", false)->get();
            $property->user;
            $property->distance = $this->haversine(
            request()->query('latitude'),
            request()->query('longitude'),
            $property->location['lat'],
            $property->location['lng']
            );
        });

        return response()->json([
            "data"=>$properties
        ]);
    }
    /**
     * Calculate the distance between two points using the Haversine formula.
     * @param float $lat1 Latitude of the first point
     * @param float $lon1 Longitude of the first point
     * @param float $lat2 Latitude of the second point
     * @param float $lon2 Longitude of the second point
     * @return float Distance in kilometers
     */
    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Raio da Terra em quilômetros
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
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
        // Attach the accommodation photos if they exist
        foreach ($request->file('photo') as $image) {
            $path = $image->store('uploads', 'public');
            $property->accommodationPhoto()->create([
                'photo_path' => $path
            ]);
        }
        // Attach the contact if it exists
        if ($request->has('contact')) {
            $property->contact()->createMany($request->input('contact'));
        }
        // if $request->agency_id is not null notifcation to the all agencyUsers
        if ($request->agency_id != null) {
            $agencyUsers = Auth::user()->agencyUsers->where('agency_id', $request->agency_id);
            foreach ($agencyUsers as $agencyUser) {
                $agencyUser->user->notify(new Notice(
                    'Criação de Anúncio',
                    'Sua agência criou um novo anúncio.',
                    route('property.show', $property->id),
                    "GET",
                    "link"
                ));
            }  
        }
        $property->load('offer');
        $property->load('accommodationPhoto');
        $property->load('contact');
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
        $property = Property::find($property);
        // Check if the property exists
        $property->load('offer');
        $property->load('accommodationPhoto');
        $property->load('contact');
        $property->load('comment');
        $property->comments = $property->comment()->where('deleted', false)->get();
        $property->likes = $property->like()->where("deleted", false)->get();
        // like count
        $property->likeCount = $property->like()->where("deleted", false)->count();
        $property->load('user');
        $property->load('checkPoint');
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
     * Display the specified resource.
     */
    public function detail($property)
    {
        $property = Property::find($property);
        // Check if the property exists
        $property->load('offer');
        $property->load('accommodationPhoto');
        $property->load('contact');
        $property->load('comment');
        $property->comments = $property->comment()->where('deleted', false)->get();
        $property->likes = $property->like()->where("deleted", false)->get();
        // like count
        $property->likeCount = $property->like()->where("deleted", false)->count();
        $property->load('user');
        $property->load('checkPoint');
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
        // Update the offers if they exist
        if ($request->has('offer')) {
            $property->offer()->delete();
            $property->offer()->createMany($request->input('offer'));
        }
        // Update the accommodation photos if they exist
        if ($request->hasFile('photo')) {
            $property->accommodationPhoto()->delete();
            foreach ($request->file('photo') as $image) {
                $path = $image->store('uploads', 'public');
                $property->accommodationPhoto()->create([
                    'photo_path' => $path
                ]);
            }
        }
        // Update the contact if it exists
        if ($request->has('contact')) {
            $property->contact()->delete();
            $property->contact()->createMany($request->input('contact'));
        }
        // Load the related models
        $property->load('offer');
        $property->load('accommodationPhoto');
        $property->load('contact');
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
        // Check if the property exists
        if (!$property) {
            return response()->json([
                'message' => 'Property not found'
            ], 400);
        }

        // check if the property is already deleted
        if ($property->deleted) {
            return response()->json([
                'message' => 'Property already deleted'
            ], 400);
        }
        // remove the property
        $property->update(['deleted' => true]);
        $property->accommodationPhoto()->update(['deleted' => true]);
        $property->offer()->update(['deleted' => true]);
        $property->load('offer');
        $property->load('accommodationPhoto');
        $property->load('contact');
        $property->load('comment');
        $property->load('like');
        $property->load('user');
        $property->load('business');
        $property->load('checkPoint');
        return response()->json([
            'success' => true,
            'message' => 'Property deleted successfully',
            'data' => $property
        ], 200);
    }
}
