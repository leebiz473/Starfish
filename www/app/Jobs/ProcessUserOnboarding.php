<?php

namespace App\Jobs;

use App\Mail\VerifyEmail;
use App\Mail\WelcomeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessUserOnboarding implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;

    /**
     * Create a new job instance.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new WelcomeMail($this->user));

        if ($this->user instanceof MustVerifyEmail && false === $this->user->hasVerifiedEmail()) {
            Mail::to($this->user->email)->send(new VerifyEmail($this->user));
        }
    }
}
