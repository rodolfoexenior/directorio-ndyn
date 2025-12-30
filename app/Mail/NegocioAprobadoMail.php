<?php

namespace App\Mail;

use App\Models\Negocio;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class NegocioAprobadoMail extends Mailable
{
    use Queueable, SerializesModels;

    // Declaramos la propiedad pública para que esté disponible en la vista Blade
    public $negocio;

    /**
     * El constructor recibe el objeto Negocio desde el controlador.
     */
    public function __construct(Negocio $negocio)
    {
        $this->negocio = $negocio;
    }

    /**
     * Configuramos el asunto del correo de forma dinámica.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Tu negocio "' . $this->negocio->nombre . '" ha sido aprobado!',
        );
    }

    /**
     * Apuntamos a la vista que creaste en resources/views/emails/negocio_aprobado.blade.php
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.negocio_aprobado',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}