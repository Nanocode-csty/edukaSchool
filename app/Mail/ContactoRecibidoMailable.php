<?php

// app/Mail/ContactoRecibidoMailable.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactoRecibidoMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        // Solo pasa la ruta, sin usar embed() aquí
        $logoPath = public_path('img_eduka.png');

        return $this->from('rcroblesro@unitru.edu.pe', 'Eduka Perú Oficial')
            ->replyTo($this->data['email'])
            ->subject('Eduka | Solicitud de ' . $this->data['name'])
            ->view('cemails.contacto-recibido')
            ->with([
                'data' => $this->data,
                'logoPath' => $logoPath
            ]);
    }
}
