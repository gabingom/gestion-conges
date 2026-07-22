<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IdentifiantsAgent extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nomComplet,
        public string $identifiant,
        public string $motDePasse,
        public string $lienConnexion
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vos identifiants de connexion — Plateforme de gestion des congés USSEIN',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.identifiants-agent');
    }
}
