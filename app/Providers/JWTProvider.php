<?php

namespace App\Providers;

use App\JWT;
use App\SimpleJWT;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class JWTProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(JWT::class, function ($_) {
            return new SimpleJWT(Config::get('app.iss'), Config::get('app.aud'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
