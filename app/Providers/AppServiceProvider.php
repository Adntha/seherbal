<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ChatbotService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register ChatbotService as singleton
        $this->app->singleton(ChatbotService::class, function ($app) {
            return new ChatbotService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
