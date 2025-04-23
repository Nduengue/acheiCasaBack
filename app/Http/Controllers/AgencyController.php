<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListUserRequest;
use App\Models\Agency;
use App\Http\Requests\StoreAgencyRequest;
use App\Http\Requests\UpdateAgencyRequest;
use App\Http\Requests\UserToAgencyRequest;
use App\Models\User;
use App\Notifications\Notice;
use Illuminate\Http\Request;
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
        
        if ($agency->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No agencies found',
            ], 404);
        }
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
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }
        // uploda de imagem
        $path = null;
        if ($request->hasFile('path_photo')) {
            $path = $request->file('path_photo')->store('photo', 'public');
        }
        $agency = $user->agencies()->create(array_merge($request->validated(), ['path_photo' => $path ]));
        $agency->agencyUsers()->create([
            'user_id' => $user->id,
        ]);
        $user->notify(new Notice('Criação de Agencia','Seu pedido de Agencia foi crianda ',route('agency.show',$agency->id),"GET","link"));
        
        if (!$agency) {
            return response()->json([
                'success' => false,
                'message' => 'Agency not created',
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Agency store successfully',
            'data' => $agency
        ]);
    }
    /**
     * Search for a user by email or phone number.
     */
    public function searchUser(Request $request)
    {
        $request->validate([
            'email_or_phone' => 'required|string',
        ]);

        $user = User::orWhere('email', 'like', '%' . $request->email_or_phone . '%')
                ->orWhere('phone_number', 'like', '%' . $request->email_or_phone . '%')
                ->limit(5)
                ->get();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User found',
            'data' => $user,
        ]);
    }

    /**
     * Add a user to the agency.
     */
    public function addUserToAgency(UserToAgencyRequest $request,$agency)
    {
        $request->validated();
        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }
        $agency = Agency::where('user_id', Auth::id())
                ->where('id', $agency)
                ->where('deleted', false)
                ->first();
        if (!$agency) {
            return response()->json([
                'success' => false,
                'message' => 'Agency not found',
            ], 404);
        }

        $agency->agencyUsers()->create([
            'user_id' => $user->id,
            "deleted" => true,
        ]);

        $user->notify(new Notice('Adição a Agencia','Você foi adicionado a agencia '.$agency->name,route('agency.show',$agency->id),"GET","confirmation"));
        if (!$agency) {
            return response()->json([
                'success' => false,
                'message' => 'User not added to agency',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'User added to agency successfully',
        ]);
    }
    /**
     * list of users to agencies
     */
    public function listUserToAgency(ListUserRequest $request)
    {
        $request->validated();

        $agency = Agency::where('user_id', Auth::id())
                ->where('id', $request->agency_id)
                ->where('deleted', false)
                ->first();

        if (!$agency) {
            return response()->json([
                'success' => false,
                'message' => 'Agency not found',
            ], 404);
        }

        $users = $agency->agencyUsers()->with('user')->get();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    


    /**
     * Display the specified resource.
     */
    public function show(Agency $agency)
    {
        //check if the agency is deleted is user_id
        if ($agency->deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Agency not found',
            ], 404);
        }
        //check if the agency is owned by the user
        if ($agency->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
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
        if ($agency->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        if ($agency->deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Agency already deleted',
            ], 400);
        }
        $agency->update(['deleted' => true]);
        return response()->json([
            'success' => true,
            'message' => 'Agency deleted successfully',
            'data' => $agency
        ]);
    }
}
