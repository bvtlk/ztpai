<?php

namespace App\Providers;

use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot(): void
    {
        parent::boot();
        EncryptCookies::except(['user_id', 'role_id', 'username']);
    }

    public function map(): void
    {
        $this->mapApiRoutes();
    }

    protected function mapApiRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));
    }

}
