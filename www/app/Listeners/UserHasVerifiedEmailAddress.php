<?php

namespace App\Listeners;

use App\Events\ResendEmailVerification;
use App\Jobs\ProcessEmailVerification;
use App\Mail\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class UserHasVerifiedEmailAddress implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(ResendEmailVerification $event): void
    {
        ProcessEmailVerification::dispatch($event->user)->onQueue('emails');
    }
}
