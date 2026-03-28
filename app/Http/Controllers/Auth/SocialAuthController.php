<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'email', 'profile']) // pide info básica
            ->with(['prompt' => 'select_account'])   // fuerza selector de cuentas
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // --- DEPURACIÓN: escribe en el log para ver qué devuelve Google
            Log::info('Google login payload', (array) $googleUser);

            // Obtener email (seguro)
            $email = $googleUser->getEmail() ?? $googleUser->email ?? null;

            if (!$email) {
                return redirect()->route('login.index')
                    ->withErrors(['email' => 'Google no devolvió un correo. Intenta con otra cuenta.']);
            }

            // Buscar en tu tabla usuarios por email
            $usuario = Usuario::where('email', $email)->first();

            if (!$usuario) {

                return redirect()->route('login.index')->with('error', 'Este correo no está registrado en el sistema. Contacta al administrador de la IE para validar tus credenciales de acceso.');
                // NO crear cuenta: devolver mensaje claro

            }
            // Obtener el token de forma segura comprobando varios sitios posibles

            // Loguear y redirigir
            Auth::login($usuario);
            return redirect()->route('rutarrr1');
        } catch (Exception $e) {
            Log::error('Google login error: ' . $e->getMessage());
            return redirect()->route('login.index')->withErrors([
                'error' => 'Error al iniciar sesión con Google. Intenta de nuevo.'
            ]);
        }
    }
}
