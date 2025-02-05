<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendVerificationNotification extends SendEmailVerificationNotification
{

    /**
     * Handle the event.
     *
     * @param UserRegistered|Registered $event
     * @return void
     */
    public function handle(UserRegistered|Registered $event): void
    {
        // Send the email
        parent::handle($event);
    }
}
