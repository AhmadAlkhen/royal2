<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        $this->app->extend('notification', function ($service) {
            $service->extend('whatsapp', function () {
                return new \App\Channels\WhatsAppChannel();
            });
    
            return $service;
        });
    }
}
