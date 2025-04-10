<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $verify_token = 'meu_token_secreto';

        // Verificação do webhook (GET)
        if ($request->isMethod('get')) {
            $mode = $request->get('hub_mode');
            $token = $request->get('hub_verify_token');
            $challenge = $request->get('hub_challenge');

            if ($mode === 'subscribe' && $token === $verify_token) {
                return response($challenge, 200);
            } else {
                return response('Token inválido', 403);
            }
        }

        // Recebimento de eventos (POST)
        if ($request->isMethod('post')) {
            $payload = $request->all();

            // Aqui você pode processar os dados recebidos
            Log::info('Webhook recebido:', $payload);

            return response('EVENT_RECEIVED', 200);
        }

        return response('Método não suportado', 405);
    }
}
