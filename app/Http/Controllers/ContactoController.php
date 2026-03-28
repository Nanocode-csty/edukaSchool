<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
    use App\Mail\ContactoRecibidoMailable;
use Illuminate\Support\Facades\Mail;


class ContactoController extends Controller
{

    /*
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2',
            'email' => 'required|email',
            'message' => 'required|string|min:5',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
        ];

        Mail::send([], [], function ($message) use ($data) {
            $logoPath = public_path('img_eduka.png');
            $logoCid = $message->embed($logoPath);

            $message->to('rcroblesro@unitru.edu.pe')
                    ->subject('Eduka | Solicitud de ' . $data['name'])
                    ->from('rcroblesro@unitru.edu.pe', 'Eduka Perú Oficial')
                    ->replyTo($data['email'])
                    ->html('
                        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #000; border-radius: 12px; padding: 30px; background-color: #ffffff; color: #000;">
                            <div style="text-align: center; margin-bottom: 25px;">
                                <img src="' . $logoCid . '" alt="ROMEROS PERÚ" style="max-width: 180px; border-radius: 8px;">
                            </div>

                            <h2 style="border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 20px;">Nueva solicitud del servidor</h2>

                            <p style="margin: 15px 0; font-size: 15px;"><strong>Nombre del remitente:</strong><br>' . e($data['name']) . '</p>
                            <p style="margin: 15px 0; font-size: 15px;"><strong>Correo del remitente:</strong><br>' . e($data['email']) . '</p>
                            <p style="margin: 15px 0; font-size: 15px;"><strong>Mensaje:</strong><br>' . nl2br(e($data['message'])) . '</p>

                            <hr style="margin: 35px 0; border: none; border-top: 1px solid #ddd;">

                            <div style="font-size: 13px; color: #555; line-height: 1.6;">
                                <p><strong>📍 Dirección:</strong> Av. Los Paujiles, Trujillo, Perú</p>
                                <p><strong>📧 Correo:</strong> <a href="mailto:rcroblesro@unitru.edu.pe" style="color: #000; text-decoration: none;">rcroblesro@unitru.edu.pe</a></p>
                                <p><strong>📞 Teléfono:</strong> +51 963 150 918</p>
                                <p><strong>🌐 Sitio web:</strong> <a href="https://romeros-pe.web.app" style="color: #000; text-decoration: none;">romeros-pe.web.app</a></p>
                            </div>

                            <p style="font-size: 12px; text-align: center; color: #aaa; margin-top: 20px;">
                                © ' . date('Y') . ' Eduka Perú. Todos los derechos reservados.
                            </p>
                        </div>
                    ');
        });


        return back()->with('modal_success', '¡El correo ha sido enviado exitosamente!');

    }*/


public function send(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|min:2',
        'email' => 'required|email',
        'message' => 'required|string|min:5',
    ]);

    $data = [
        'name' => $validated['name'],
        'email' => $validated['email'],
        'message' => $validated['message'],
    ];

    // Enviar al administrador
    Mail::to('rcroblesro@unitru.edu.pe')->send(new ContactoRecibidoMailable($data));

    return back()->with('modal_success', '¡El correo ha sido enviado exitosamente!');
}

}
