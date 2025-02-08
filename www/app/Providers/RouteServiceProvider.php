<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Http\Controllers\CurrentTeamController;
use Laravel\Jetstream\Http\Controllers\Inertia\ApiTokenController;
use Laravel\Jetstream\Http\Controllers\Inertia\CurrentUserController;
use Laravel\Jetstream\Http\Controllers\Inertia\OtherBrowserSessionsController;
use Laravel\Jetstream\Http\Controllers\Inertia\PrivacyPolicyController;
use Laravel\Jetstream\Http\Controllers\Inertia\ProfilePhotoController;
use Laravel\Jetstream\Http\Controllers\Inertia\TeamController;
use Laravel\Jetstream\Http\Controllers\Inertia\TeamMemberController;
use Laravel\Jetstream\Http\Controllers\Inertia\TermsOfServiceController;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController;
use Laravel\Jetstream\Http\Controllers\TeamInvitationController;
use Laravel\Jetstream\Jetstream;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'guest'])
                ->group(base_path('routes/auth/guest.php'));

            Route::middleware(['web', 'auth'])
                ->group(base_path('routes/auth/user.php'));

            Route::group(['middleware' => config('jetstream.middleware', ['web'])], function () {
                if (Jetstream::hasTermsAndPrivacyPolicyFeature()) {
                    Route::get('/terms-of-service', [TermsOfServiceController::class, 'show'])->name('terms.show');
                    Route::get('/privacy-policy', [PrivacyPolicyController::class, 'show'])->name('policy.show');
                }

                $authMiddleware = config('jetstream.guard')
                    ? 'auth:'.config('jetstream.guard')
                    : 'auth';

                $authSessionMiddleware = config('jetstream.auth_session', false)
                    ? config('jetstream.auth_session')
                    : null;

                Route::group(['middleware' => array_values(array_filter([$authMiddleware, $authSessionMiddleware]))], function () {
                    // User & Profile...
                    Route::get('/user/profile', [UserProfileController::class, 'show'])
                        ->name('profile.show');

                    Route::delete('/user/other-browser-sessions', [OtherBrowserSessionsController::class, 'destroy'])
                        ->name('other-browser-sessions.destroy');

                    Route::delete('/user/profile-photo', [ProfilePhotoController::class, 'destroy'])
                        ->name('current-user-photo.destroy');

                    if (Jetstream::hasAccountDeletionFeatures()) {
                        Route::delete('/user', [CurrentUserController::class, 'destroy'])
                            ->name('current-user.destroy');
                    }

                    Route::group(['middleware' => 'verified'], function () {
                        // API...
                        if (Jetstream::hasApiFeatures()) {
                            Route::get('/user/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
                            Route::post('/user/api-tokens', [ApiTokenController::class, 'store'])->name('api-tokens.store');
                            Route::put('/user/api-tokens/{token}', [ApiTokenController::class, 'update'])->name('api-tokens.update');
                            Route::delete('/user/api-tokens/{token}', [ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');
                        }

                        // Teams...
                        if (Jetstream::hasTeamFeatures()) {
                            Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
                            Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
                            Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
                            Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
                            Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
                            Route::put('/current-team', [CurrentTeamController::class, 'update'])->name('current-team.update');
                            Route::post('/teams/{team}/members', [TeamMemberController::class, 'store'])->name('team-members.store');
                            Route::put('/teams/{team}/members/{user}', [TeamMemberController::class, 'update'])->name('team-members.update');
                            Route::delete('/teams/{team}/members/{user}', [TeamMemberController::class, 'destroy'])->name('team-members.destroy');

                            Route::get('/team-invitations/{invitation}', [TeamInvitationController::class, 'accept'])
                                ->middleware(['signed'])
                                ->name('team-invitations.accept');

                            Route::delete('/team-invitations/{invitation}', [TeamInvitationController::class, 'destroy'])
                                ->name('team-invitations.destroy');
                        }
                    });
                });
            });


            Route::middleware([])->prefix('dev')->group(base_path('routes/dev.php'));
        });
    }
}
