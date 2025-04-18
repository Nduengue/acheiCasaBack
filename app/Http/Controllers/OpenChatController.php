<?php

namespace App\Http\Controllers;

use App\Models\OpenChat;
use App\Http\Requests\StoreOpenChatRequest;
use App\Http\Requests\UpdateOpenChatRequest;
use App\Models\Property;

class OpenChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //if user is not authenticated
        if (!auth()->user()) {
            return response()->json([
                "success" => false,
                "message" => "You need to be logged in to register interest",
            ], 401);
        }
        // filter open chats by user with property query user auth and where properties
        $openChats = OpenChat::with(['property', 'property.user', 'messages.sender'])
            ->whereHas('property', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->orWhere('user_id', auth()->user()->id)
            ->get();

        //if no open chats found
        if ($openChats->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "No Message found",
            ], 404);
        }
        // montar resposta

        //bluid response open chats
        foreach ($openChats as $openChat) {
            $openChat->property = $openChat->property()->with(['user'])->first();
            $openChat->property->user = $openChat->property->user()->first();
            $openChat->messages = $openChat->messages()->with(['sender'])->get();
        }

        return response()->json([
            "success" => true,
            "data" => $openChats,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOpenChatRequest $request, $chat)
    {
        //if user is not authenticated
        if (!auth()->user()) {
            return response()->json([
                "success" => false,
                "message" => "You need to be logged in to register interest",
            ], 401);
        }
        $openChat = OpenChat::find($chat);
        $property = Property::find($openChat->property_id);
        //if property not found
        if (!$property) {
            return response()->json([
                "success" => false,
                "message" => "Property not found",
            ], 404);
        }
        
        //if property not found
        $openChat->messages()->create([
            "sender_id" => auth()->user()->id,
            "content" => $request->content,
            "sent_in" => date("Y-m-d H:i:s"),
            "read" => false,
        ]);
        
        //notificar o corretor
        $property->user->notify(new \App\Notifications\Notice(
            "Nova Mensagem",
            "Você recebeu uma nova mensagem no imóvel " . $property->title,
            route("openChat.show", $openChat->id),
            "GET",
            "message"
        ));
        // build response tree messages
        $openChat->load('messages');
        $openChat->load('messages.sender');
        foreach ($openChat->messages as $message) {
            $message->sender = $message->sender()->first();
        }
        $openChat->property->user = $openChat->property->user()->first();
        return response()->json([
            "success" => true,
            "data" => $openChat,
        ]);
    }
    /**
     * Register interest in a property.
     *
     * @param  int  $property
     * @return \Illuminate\Http\JsonResponse
     */
    public function interest($property)
    {
        //if user is not authenticated
        if (!auth()->user()) {
            return response()->json([
                "success" => false,
                "message" => "You need to be logged in to register interest",
            ], 401);
        }
        //if property not found
        $property = Property::find($property);
        if (!$property) {
            return response()->json([
                "success" => false,
                "message" => "Imóvel não encontrado",
            ], 404);
        }
        //if property exists open chat
        if (OpenChat::where('user_id', auth()->user()->id)->where('property_id', $property->id)->exists()) {
            return response()->json([
                "success" => false,
                "message" => "You have already registered interest in this property",
            ], 400);
        }
        $openChat = OpenChat::create([
            'user_id' => auth()->user()->id,
            'property_id' => $property->id,
        ]);
        
        if($property->type_of_business=="V" ){
            $openChat->messages()->create([
                "sender_id"=>auth()->user()->id,
                "content"=>"Interesse neste Imovel !",
                "sent_in"=>date("Y-m-d H:i:s"),
                "read"=>false,
            ]);
            //business
            $property->business()->create([
                'property_id' => $property->id,
                'buyer_id' => auth()->user()->id,
                'seller_id' => $property->user->id,
                'intermediary_id' => null,
                'price' => $property->price,
                'status' => 'pending',
                'type_of_business' => $property->type_of_business,
                'started_at' => now(),
                'closed_at' => null,
                'notes' => null,
            ]);
            //notificar o corretor
            $property->user->notify(new \App\Notifications\Notice(
                "Novo Interesse",
                "Você recebeu um novo interesse no imóvel ".$property->title,
                route("openChat.show", $openChat->id),
                "GET",
                "message"
            ));
        }
        //if property type of business is Aluguel
        if($property->type_of_business=="A" ){
            
            $openChat->messages()->create([
                "sender_id"=>auth()->user()->id,
                "content"=>"Interesse neste Imovel !",
                "sent_in"=>date("Y-m-d H:i:s"),
                "read"=>false,
            ]);
            //notificar o corretor
            $property->user->notify(new \App\Notifications\Notice(
                "Novo Interesse",
                "Você recebeu um novo interesse no imóvel ".$property->title,
                route("openChat.show", $openChat->id),
                "GET",
                "message"
            ));
            // checkin
            $property->checkPoint()->create([
                'user_id' => auth()->user()->id,
                'property_id' => $property->id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'status' => 'check_in',
            ]);
            //business
            $property->business()->create([
                'property_id' => $property->id,
                'buyer_id' => auth()->user()->id,
                'seller_id' => $property->user->id,
                'intermediary_id' => null,
                'price' => $property->price,
                'status' => 'pending',
                'type_of_business' => $property->type_of_business,
                'started_at' => now(),
                'closed_at' => null,
                'notes' => null,
            ]);
        }
        $openChat->load('property');
        $openChat->load('property.user');
        $openChat->load('messages');
        $openChat->load('messages.sender');

        $data = [
            "success"=>true,
            "message"=>"Interesse registrado com sucesso",
            "data"=>$openChat,
        ];

        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($chat)
    {
        $openChat = OpenChat::find($chat);
        //if user is not authenticated
        if (!auth()->user()) {
            return response()->json([
                "success" => false,
                "message" => "You need to be logged in to register interest",
            ], 401);
        }
        //if open chat not found
        if (!$openChat) {
            return response()->json([
                "success" => false,
                "message" => "Open chat not found",
            ], 404);
        }
        $openChat->load('property');
        $openChat->load('property.user');
        $openChat->load('messages');
        $openChat->load('messages.sender');

        return response()->json([
            "success" => true,
            "data" => $openChat->messages()->with(['sender'])->get(),
        ]);
    }
}
