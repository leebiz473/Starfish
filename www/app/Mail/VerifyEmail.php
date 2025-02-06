<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Auth\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Notifications\Messages\SimpleMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    private User $user;

    private string $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        $this->verificationUrl = $this->generateVerificationUrl($user);
    }

    /**
     * Generate the verification URL.
     *
     * @param User $user
     * @return string
     */
    protected function generateVerificationUrl(User $user): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address', 'hello@starfish.envx'),
                config('mail.from.name', 'StarfishEnvx'),
            ),
            subject: Lang::get('Verify Email Address'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.markdown-template',
            with: [
                'level' => 'info',
                'introLines' => [Lang::get('Please click the button below to verify your email address.')],
                'actionText' => Lang::get('Verify Email Address'),
                'actionUrl' => $actionUrl = $this->verificationUrl,
                'outroLines' => [Lang::get('If you did not create an account, no further action is required.')],
                'displayableActionUrl' => str_replace(['mailto:', 'tel:'], '', $actionUrl ?? ''),
                'greeting' => null,
                'salutation' => null,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
