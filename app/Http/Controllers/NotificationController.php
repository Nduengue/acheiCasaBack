<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoticeRequest;
use App\Models\User;
use App\Notifications\Notice;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Listar notificações do usuário autenticado
    public function index()
    {
        return response()->json([
            'data' => Auth::user()->notifications,
            'count' => Auth::user()->unreadNotifications->count(),
        ]);
    }

    // Enviar uma notificação para um usuário específico
    public function store(NoticeRequest $request)
    {
        /* $user = User::find($request->user_id);

        // Verifica se o usuário existe antes de enviar a notificação
        if (!$user)
        return response()->json([
            "success" =>false,
            'message' => 'Usuário não encontrado'
        ], 404);

        $user->notify(new Notice(
            $request->title, 
            $request->message, 
            $request->url
        ));

        return response()->json([
            "success" =>true,
            'message' => 'Notificação enviada com sucesso',
            "data" =>$user
        ]); */
    }

    // Marcar uma notificação como lida
    public function marcarComoLida($id)
    {
        $notificacao =  Auth::user()
                        ->notifications()
                        ->find($id);

        if (!$notificacao) {
            return response()->json([
                "success" =>false,
                'message' => 'Notificação não encontrada'
            ], 404);
        }

        $notificacao->markAsRead();

        return response()->json([
            "success" =>true,
            'message' => 'Notificação marcada como lida',
            'data' => $notificacao
        ]);
    }
}
