<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\CodeRequest;
use App\Http\Requests\DocumentRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\RecurveRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use PhpParser\Comment\Doc;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{  
    public function auth(AuthRequest $request)
    {
        $request->validated();
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        Auth::login($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $user,
            'token' => $token,
        ]);
    }
    
    public function me()
    {
        $user = Auth::user();
        $user->load('document');
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }
    
    public function profile(ProfileRequest $request){
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        // uploda de imagem
        $path = $user->path_photo;
        if ($request->hasFile('path_photo')) {
            $path = $request->file('path_photo')->store('photo', 'public');
        }
        $user->update(array_merge($request->validated(), ['path_photo' => $path ]));
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }
    public function password(PasswordRequest $request)
    {
        $request->validated();
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully',
            'data' => $user,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function register(RegisterRequest $request)
    {
        $code = Cache::get("code.$request->email-register");
        if ($code != $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid code'
            ], 422);
        }
        $user = User::create($request->validated());
        
        if ($request->hasFile('front')) {
            $path = $request->file('front')->store('photo', 'public');
            $front = $path;
        }
        if ($request->hasFile('back')) {
            $path = $request->file('back')->store('photo', 'public');
            $back = $path;
        }
        
        $user->document()->createMany([
            [
                "path_id" => $front,
                "name" => "front",
            ],
            [
                "path_id" => $back,
                "name" => "back",
            ]
        ]);
        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => $user,
        ]);
    }
   
    /**
     * Display the specified resource.
     */
    public function code(CodeRequest $request)
    {
        $request->validated();
        $code = $this->generateCode($request->email);
        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'code' => $code,
        ]);
    }

    /**
     * Display the specified resource.
     */
    private function generateCode($email, $type = 'register')
    {
        // Verifica se o usuário já existe
        $user = User::where('email', $email)->first();
        if ($type == 'register' && $user) {
            return response()->json([
                'success' => false,
                'message' => 'User already exists'
            ], 422);
        }
        if ($type == 'recurve' && !$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 422);
        }
        // Gera um código aleatório e armazena em cache
        $code = random_int(100000, 999999);
        Cache::put("code.$email-$type", $code, now()->addMinutes(10));
        // Aqui você pode implementar a lógica para enviar o código por e-mail
        return $code;
    }

    /**
     * Display the specified resource.
     */
    public function recurve(RecurveRequest $request)
    {
        $request->validated();
        // Verifica se o usuário existe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Aqui você pode implementar a lógica para enviar um e-mail de redefinição de senha
        $code = $this->generateCode($request->email, 'recurve');

        return response()->json([
            'success' => true,
            'message' => 'Password reset link sent',
            "url" => route('reset', ['code' => $code]),
        ]);
    }

    public function reset(ResetRequest $request)
    {
        $code = Cache::get("code.$request->email-recurve");
        
        if ($code != request('code')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid code'
            ], 422);
        }
        // Verifica se o usuário existe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // delete o código do cache
        Cache::forget("code.$request->email-recurve");
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully',
            'data' => $user,
            'token' => $token
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function facebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')
                        ->setHttpClient(new \GuzzleHttp\Client(['verify' => false])) // Para testes
                        ->stateless()
                        ->user();
            
            // Buscar ou criar usuário
            $user = User::updateOrCreate([
                'email' => $facebookUser->getEmail(),
            ], [
                'name' => $facebookUser->getName(),
                'facebook_id' => $facebookUser->getId(),
                'password' => Hash::make(Str::random(16)), // Senha aleatória
            ]);
    
            // Criar token de acesso
            Auth::loginUsingId($user->id);
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
           
            return response()->json([
                'data' => $user,
                'token' => $token,
            ])->withCookie(cookie('usuario', $token, 60));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */

    public function facebookRedirect()
    {
        return Socialite::driver('facebook')
               ->stateless()
               ->redirect();
        return response()->json([
            'url' => Socialite::driver('facebook')->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function googleRedirect()
    {
	// Garantir que a sess�o seja iniciada
    	if (!Session::isStarted()) {
            Session::start();
    	}

        return Socialite::driver("google")->redirect();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function googleCallback()
    {
        $googleUser = Socialite::driver("google")->user();

        $user = User::updateOrCreate(
            ["google_id"=>$googleUser->id],
            [
                "google_id"=>$googleUser->id,
                "name"=>$googleUser->name,
                "email"=>$googleUser->email,
                "password"=>Hash::make(Str::random(12))
            ]
        );
        // Verifica se o usuário já existe
        if (!$user) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }
        // Verifica se o usuário já está autenticado
        if (Auth::check()) {
            return response()->json([
                'message' => 'User already authenticated'
            ], 200);
        }
        // Criar token de acesso
        Auth::loginUsingId($user->id);
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'data' => $user,
            'token' => $token,
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
            return response()->json([
                'message' => 'Logout successful'
            ]);
        } else {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }
    }
    
    
}
