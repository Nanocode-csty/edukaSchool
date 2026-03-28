<?php

namespace App\Http\Controllers;

use App\Mail\EnviarCredencialesRepresentante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Pest\Support\Str;

class UsuarioController extends Controller
{
    public function showLogin()
    {
        return view('clogin.usuario');
    }

    public function showLoginPassword()
    {
        return view('clogin.password');
    }

    public function showForgotPassword()
    {
        return view('clogin.recuperar');
    }

    public function verificalogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|max:100'
        ], [
            'email.required' => 'Introduce una dirección de correo o usuario'
        ]);

        $email = trim($request->email);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $usuario = Usuario::where('email', $email)->first();
        } else {
            $usuario = Usuario::where('username', $email)->first();
        }

        if (!$usuario) {
            return back()->withErrors([
                'email' => 'Las credenciales no son válidas'
            ])->withInput();
        }

        // validar estado
        if ($usuario->estado !== 'Activo') {
            return back()->withErrors([
                'email' => 'Tu cuenta está deshabilitada o bloqueada.'
            ]);
        }

        // login con google
        if ($usuario->google_id) {
            Auth::login($usuario);
            $request->session()->regenerate();

            return redirect()->route('rutarrr1');
        }

        // guardar email en sesión para siguiente paso
        session([
            'login_usuario_id' => $usuario->usuario_id,
            'email' => $usuario->email
        ]);

        return redirect()->route('pass');
    }

    public function verificapassword(Request $request)
    {
        // 1️⃣ Validar datos
        $request->validate([
            'password' => 'required|string|max:255',
            'g-recaptcha-response' => 'nullable',
        ], [
            'password.required' => 'Introduce una contraseña',
        ]);

        // 2️⃣ Obtener usuario desde sesión
        $usuarioId = session('login_usuario_id');

        if (!$usuarioId) {
            return redirect()->route('login.index')
                ->withErrors(['email' => 'Sesión de autenticación inválida.']);
        }

        $usuario = Usuario::find($usuarioId);

        if (!$usuario) {
            return redirect()->route('login.index')
                ->withErrors(['email' => 'El usuario no fue encontrado.']);
        }

        // 3️⃣ Crear clave de RateLimiter (usuario + IP)
        $key = 'login:' . $usuario->id . ':' . $request->ip();

        // 4️⃣ Verificar demasiados intentos
        if (RateLimiter::tooManyAttempts($key, 5)) {

            $seconds = RateLimiter::availableIn($key);

            return back()->withErrors([
                'password' => "Demasiados intentos. Intenta nuevamente en $seconds segundos."
            ]);
        }

        // 5️⃣ Verificar contraseña
        if (!password_verify($request->password, $usuario->password_hash)) {

            sleep(1); // ralentiza ataques de fuerza bruta

            RateLimiter::hit($key, 60); // registrar intento fallido

            return back()->withErrors([
                'password' => 'Contraseña incorrecta. Intenta nuevamente.'
            ]);
        }

        // 6️⃣ Verificar reCAPTCHA (solo producción)
        if (!app()->environment('local')) {

            $response = Http::asForm()->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $request->input('g-recaptcha-response'),
                    'remoteip' => $request->ip(),
                ]
            );

            if (!$response->json('success')) {

                RateLimiter::hit($key, 60);

                return back()->withErrors([
                    'g-recaptcha-response' => 'Falló la verificación de reCAPTCHA.'
                ]);
            }
        }

        // 7️⃣ Login correcto → limpiar limitador
        RateLimiter::clear($key);

        // 8️⃣ Iniciar sesión
        Auth::login($usuario);

        // 9️⃣ Regenerar sesión (protección contra session fixation)
        $request->session()->regenerate();

        // 🔟 limpiar sesión temporal
        session()->forget('login_usuario_id');

        // 1️⃣1️⃣ Redirigir
        return redirect()->route('rutarrr1');
    }

    public function enviarContrasenia()
    {

        $correo = session('email');
        $user = Usuario::where('email', $correo)->first();

        if ($user) {
            $nombre = $user->nombres;

            $passwordPlano = Str::random(8);
            $user->password_hash = Hash::make($passwordPlano);
            $user->cambio_password_requerido = 1;
            $user->save();

            Mail::to($correo)
                ->send(new EnviarCredencialesRepresentante(
                    $nombre,
                    $correo,
                    $passwordPlano
                ));
        }
        Auth::logout();
        return redirect()->route('login.index')->with('status', 'Se han enviado las nuevas credenciales a tu correo electrónico.');
    }

    public function salir(Request $request)
    {
        // ✅ Obtiene el usuario autenticado antes de cerrar sesión
        // Esto es importante porque después de Auth::logout(), ya no podrás acceder a Auth::user()
        $usuario = Auth::user();

        // ✅ Si el usuario está autenticado, actualiza la fecha de su última sesión
        if ($usuario) {
            $usuario->ultima_sesion = now();  // Guarda la fecha y hora actual
            $usuario->save();                 // Guarda los cambios en la base de datos
        }

        // ✅ Cierra la sesión del usuario (lo desautentica)
        Auth::logout();

        // ✅ Invalida la sesión actual para evitar que se reutilice
        $request->session()->invalidate();

        // ✅ Regenera el token CSRF para prevenir ataques de falsificación de petición
        $request->session()->regenerateToken();

        // ✅ Redirige al usuario a la vista de login
        return redirect('/login.index')
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }
}
