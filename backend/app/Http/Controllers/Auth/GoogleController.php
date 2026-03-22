<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    #[OA\Get(
        path: '/oauth/google/redirect',
        operationId: 'googleLogin',
        description: 'Inicia el flujo de autenticación OAuth2 con Google',
        summary: 'Redirigir a Google',
        tags: ['Autenticación']
    )]
    #[OA\Response(response: 302, description: 'Redirección a la página de Google')]
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    // 2. Google nos devuelve al usuario aquí
    public function callback()
    {
        try {
            // Obtenemos los datos de Google
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Buscamos si ya existe el usuario
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Si no existe, lo creamos
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(Str::random(16)) // Password aleatoria
                ]);
            } else {
                // Si existe, actualizamos el google_id por si acaso
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            // Generamos el token de Sanctum (para que Vue lo use)
            $token = $user->createToken('auth_token')->plainTextToken;

            // Limpiamos cualquier sesión previa de la web/cookies para evitar conflictos
            Auth::guard('web')->logout();
            
            if (request()->hasSession()) {
                request()->session()->invalidate();
                request()->session()->regenerateToken();
            }

            // Redirigimos al Frontend con el token en la URL
            $frontendUrl = config('services.google.frontend_url');
            return redirect($frontendUrl . "/auth/callback?token=" . urlencode($token));

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en login con Google: ' . $e->getMessage()], 500);
        }
    }
}