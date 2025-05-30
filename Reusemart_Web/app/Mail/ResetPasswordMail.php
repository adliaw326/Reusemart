<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    use Queueable, SerializesModels;

    public $user;
    public $url;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $email = $this->user->EMAIL_PEMBELI ?? $this->user->EMAIL_ORGANISASI ?? $this->user->EMAIL_PENITIP ?? 'sigantenk@gmail.com';
        $this->url = url('/reset-password-customer') . '?email=' . urlencode($email);
            if (!$this->url) {
                // throw new \Exception("Email user tidak ditemukan");
                $this->url = 'HAI GANTENK AWIWOW';
            }
        return $this->subject('Reset Password Akun Anda')
                    ->view('email.reset-password')
                    ->with([
                        'user' => $this->user,
                        'url' => $this->url
                    ]);
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
