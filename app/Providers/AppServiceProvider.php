<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Socialite::extend('google', function ($app) {
            $config = $app['config']['services.google'];

            return Socialite::buildProvider(\Laravel\Socialite\Two\GoogleProvider::class, $config)
                ->setHttpClient(new Client([
                    'verify' => false, // Ignorar validação SSL
                ]));
        });
    }
}
