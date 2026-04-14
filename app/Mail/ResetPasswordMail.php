<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $token,
        public string $email,
        public int $expirationTime = 60
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password - ' . config('app.name', 'LMS AI'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reset-password',
            with: [
                'email' => $this->email,
                'resetUrl' => $this->generateResetUrl(),
                'expirationTime' => $this->expirationTime,
            ],
        );
    }

    protected function generateResetUrl(): string
    {
        return url(route(
            'password.reset',
            [
                'token' => $this->token,
                'email' => $this->email,
            ],
            false
        ));
    }
}
