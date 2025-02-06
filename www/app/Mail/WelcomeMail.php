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
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    private User $user;

    private array $data;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        $this->data = $this->variationsOfWelcomeCopy();

        $this->subject = $this->data['subject'];
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
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.markdown-template',
            with: $this->data,
        );
    }

    public function variationsOfWelcomeCopy(): array
    {
        $appName = config('app.name');
        $appUrl = config('app.url');

        $copy = [
            // Default
            [
                'subject' => sprintf('👋 Welcome to %s', config('app.name')),
                'level' => 'info',
                'introLines' => [Lang::get('Welcome to Starfish.envx')],
                'actionText' => null,
                'actionUrl' => $actionUrl = config('app.url'),
                'outroLines' => [Lang::get('If you did not create an account, no further action is required.')],
                'displayableActionUrl' => str_replace(['mailto:', 'tel:'], '', $actionUrl ?? ''),
                'greeting' => sprintf('Hello, %s',  $this->user->name),
                'salutation' => null,
            ],
            // Friendly & Warm → Great for general user engagement
            [
                'subject' => sprintf('🎉 Welcome to %s – Let’s Get Started!', config('app.name')),
                'level' => 'info',
                'introLines' => [
                    Lang::get('Welcome to Starfish.envx'),
                    '',
                     Lang::get('✅ Complete your profile'),
                     Lang::get('✅ Explore [feature]'),
                     Lang::get('✅ Connect with like-minded people'),
                    '',
                     Lang::get('Click the button below to get started:')
                ],
                'actionText' => Lang::get('Go to Your Dashboard'),
                'actionUrl' => $actionUrl = route('dashboard'),
                'outroLines' => [Lang::get('Need help? Reply to this email—we’d love to assist you!')],
                'displayableActionUrl' => str_replace(['mailto:', 'tel:'], '', $actionUrl ?? ''),
                'greeting' => Lang::get(sprintf('Hello, %s',  $this->user->name)),
                'salutation' => [
                    Lang::get('Cheers,'),
                    Lang::get('The Starfish.envx Team 🚀'),
                ]
            ],
            // Professional & Polished → For corporate or business platforms
            [
                'subject' => Lang::get(
                    sprintf('Welcome to %s – Your Account is Ready!', $appName)
                ),
                'level' => 'info',
                'introLines' => [
                    Lang::get(
                        sprintf('Welcome to %s! We’re excited to have you on board.', $appName)
                    ),
                    '',
                    Lang::get('With your new account, you can:'),
                    Lang::get('	•	Access exclusive features'),
                    Lang::get('	•	Connect with experts in your industry'),
                    Lang::get('	•	Stay updated with the latest insights'),
                    '',
                    Lang::get('Start exploring by clicking the link below:')
                ],
                'actionText' => Lang::get('Access Your Account'),
                'actionUrl' => $actionUrl = $appUrl,
                'outroLines' => [Lang::get('')],
                'displayableActionUrl' => str_replace(['mailto:', 'tel:'], '', $actionUrl ?? ''),
                'greeting' => Lang::get(sprintf('Dear, %s',  $this->user->name)),
                'salutation' => [
                    Lang::get('If you have any questions, feel free to reach out.',),
                    '',
                    Lang::get('Best regards,'),
                    Lang::get(sprintf('The %s Team', $appName)),
                ]
            ],
            // Casual & Fun → If your brand is playful and informal
            [
                'subject' => Lang::get(
                    sprintf('🚀 You’re In! Welcome to %s!', $appName)
                ),
                'level' => 'info',
                'introLines' => [
                    Lang::get(
                        sprintf('Guess what? You just unlocked a whole new world at %s! 🎊', $appName)
                    ),
                    '',
                    Lang::get('Now that you’re in, here’s what you can do:'),
                    Lang::get('⭐ Set up your profile'),
                    Lang::get('⭐ Check out awesome content'),
                    Lang::get('⭐ Connect with cool people'),
                    '',
                    Lang::get('Don’t just sit there—dive in! 👇')
                ],
                'actionText' => Lang::get('Let’s Go!'),
                'actionUrl' => $actionUrl = $appUrl,
                'outroLines' => [''],
                'displayableActionUrl' => str_replace(['mailto:', 'tel:'], '', $actionUrl ?? ''),
                'greeting' => Lang::get(sprintf('Hey, %s',  $this->user->name)),
                'salutation' => [
                    Lang::get('Talk soon,',),
                    '',
                    Lang::get(sprintf('%s Crew 😎', $appName)),
                ]
            ],
            // Minimal & Straight to the Point → If you want a simple, no-fluff email
            [
                'subject' => Lang::get(
                    sprintf('Welcome to %s – Let’s Get Started!', $appName)
                ),
                'level' => 'info',
                'introLines' => [
                    Lang::get(
                        sprintf('Thanks for joining %s! Your account is now active.', $appName)
                    ),
                    '',
                    Lang::get('Click below to log in and start exploring:'),
                ],
                'actionText' => Lang::get('Go to Your Account'),
                'actionUrl' => $actionUrl = $appUrl,
                'outroLines' => [Lang::get('Have questions? Reply to this email—we’re here to help.',)],
                'displayableActionUrl' => str_replace(['mailto:', 'tel:'], '', $actionUrl ?? ''),
                'greeting' => Lang::get(sprintf('Hi, %s',  $this->user->name)),
                'salutation' => [
                    '',
                    Lang::get('Best,',),
                    Lang::get(sprintf('The %s Team', $appName)),
                ]
            ],
            // Community-Focused → Best for platforms with user interactions
            [
                'subject' => Lang::get(
                    sprintf('Welcome, %s! Join the %s] Community 🎉', $this->user->name, $appName)
                ),
                'level' => 'info',
                'introLines' => [
                    Lang::get(
                        sprintf('You’re now part of something special! At %s, we believe in [core value of your platform].', $appName)
                    ),
                    '',
                    Lang::get('Here’s how to get started:'),
                    Lang::get('🌟 Customize your profile'),
                    Lang::get('📢 Join exciting discussions'),
                    Lang::get('🎁 Explore exclusive resource'),
                    '',
                    Lang::get('Let’s get you started:'),
                ],
                'actionText' => Lang::get('Join the Community'),
                'actionUrl' => $actionUrl = $appUrl,
                'outroLines' => [Lang::get('We can’t wait to see what you’ll bring to our community. Welcome aboard! 🚀')],
                'displayableActionUrl' => str_replace(['mailto:', 'tel:'], '', $actionUrl ?? ''),
                'greeting' => Lang::get(sprintf('Hi, %s',  $this->user->name)),
                'salutation' => [
                    Lang::get('Cheers,',),
                    Lang::get(sprintf('The %s Team', $appName)),
                ]
            ],
            // Exclusive & VIP Tone → Makes users feel special
            [
                'subject' => Lang::get(
                    sprintf('🚀 Welcome to %s – You’re in the Inner Circle!', $appName)
                ),
                'level' => 'info',
                'introLines' => [
                    Lang::get('Congratulations! You’ve just joined an exclusive group of people who get access to [mention a key feature or benefit].'),
                    '',
                    Lang::get('Here’s what’s waiting for you:'),
                    Lang::get('🔐 Exclusive content and perks'),
                    Lang::get('🎯 Personalized recommendations'),
                    Lang::get('📢 Early access to new features'),
                    '',
                    Lang::get('Unlock your experience now:'),
                ],
                'actionText' => Lang::get('Access Your VIP Dashboard'),
                'actionUrl' => $actionUrl = route('dashboard'),
                'outroLines' => [Lang::get('We’re thrilled to have you on board. Stay tuned for more surprises!')],
                'displayableActionUrl' => str_replace(['mailto:', 'tel:'], '', $actionUrl ?? ''),
                'greeting' => Lang::get(sprintf('Hey, %s',  $this->user->name)),
                'salutation' => [
                    Lang::get('Cheers,',),
                    Lang::get(sprintf('The %s Team', $appName)),
                ]
            ],
            // Inspirational & Visionary Tone → Motivational and empowering
            [
                'subject' => Lang::get(
                    sprintf('Welcome, %s! A New Journey Begins 🚀', $this->user->name)
                ),
                'level' => 'info',
                'introLines' => [
                    Lang::get(
                        sprintf('Every great journey starts with a single step—and you just took yours! At %s, we believe in empowering people like you to achieve more.', $appName)
                    ),
                    '',
                    Lang::get('Here’s how you can make the most of your experience:'),
                    Lang::get('✨ Connect with like-minded individuals'),
                    Lang::get('📖 Learn and grow with our resources'),
                    Lang::get('🔥 Take action and create something incredible'),
                    '',
                    Lang::get('Let’s start this journey together:'),
                ],
                'actionText' => Lang::get('Start Your Adventure'),
                'actionUrl' => $actionUrl = route('dashboard'),
                'outroLines' => [Lang::get('Need help? Reply to this email—we’d love to assist you!')],
                'displayableActionUrl' => str_replace(['mailto:', 'tel:'], '', $actionUrl ?? ''),
                'greeting' => Lang::get(sprintf('Hi, %s',  $this->user->name)),
                'salutation' => [
                    Lang::get('We’re here to support you every step of the way. Let’s make something amazing happen!',),
                    '',
                    Lang::get('Best,,',),
                    Lang::get(sprintf('The %s Team', $appName)),
                ]
            ],
        ];

        return $copy[mt_rand(0, (count($copy) - 1))];
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
