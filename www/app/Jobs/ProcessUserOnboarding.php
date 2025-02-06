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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

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
            try {
                ProcessWelcomeEmail::dispatch($this->user);
                ProcessEmailVerification::dispatch($this->user)->delay(now()->addSeconds(60));
            }  catch (Throwable $e) {
                Log::error("ProcessUserOnboarding job failed: " . $e->getMessage(), [
                    'user_id' => $this->user->id,
                    'exception' => $e
                ]);
                $this->fail($e);
            }
    }
}
