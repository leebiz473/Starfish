<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRegistered extends Registered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  User  $user
     * @return void
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}
