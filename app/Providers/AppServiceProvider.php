<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\FileUploadService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Gate::define('admin-access', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('instructor-access', function (User $user) {
            return $user->role === 'instructor' || $user->role === 'admin';
        });

        Gate::define('student-access', function (User $user) {
            return in_array($user->role, ['student', 'instructor', 'admin']);
        });
    }
}
