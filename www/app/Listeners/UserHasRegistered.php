<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Jobs\ProcessUserOnboarding;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class UserHasRegistered extends SendEmailVerificationNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param UserRegistered|Registered $event
     * @return void
     */
    final public function handle(UserRegistered|Registered $event): void
    {
        ProcessUserOnboarding::dispatch($event->user)->onQueue('emails');
    }
}
