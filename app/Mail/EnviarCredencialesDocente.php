<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnviarCredencialesDocente extends Mailable
{
    use Queueable, SerializesModels;


     public $nombre;
    public $correo;
    public $passwordPlano;


    /**
     * Create a new message instance.
     */
    public function __construct($nombre, $correo, $passwordPlano)
    {
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->passwordPlano = $passwordPlano;
    }

    public function build()
    {
        //logo de la marca
        $logoPath = public_path('img_eduka.png');
        //Definimos el remitente, el asunto, la vista blade que será el cuerpo del correo, y pasa los datos necesarios.
        return $this->from('rcroblesro@unitru.edu.pe', 'Eduka Perú Oficial')
            ->subject('Tus credenciales de acceso al sistema Eduka')
            ->view('cemails.credenciales_docente')
            ->with([
                'nombre' => $this->nombre,
                'correo' => $this->correo,
                'password' => $this->passwordPlano,
                'logoPath' => $logoPath,
            ]);
    }
}
