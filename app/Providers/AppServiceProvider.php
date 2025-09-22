<?php

namespace App\Providers;
use App\Services\ChatAIService;
use App\Services\MomoService;
use App\Services\PasswordResetService;
use App\Services\SemanticProcessor;
use App\Services\VnpayService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(ChatAIService::class, function ($app) {
            return new ChatAIService();
        });
        $this->app->singleton(PasswordResetService::class, function ($app) {
            return new PasswordResetService();
        });
        $this->app->singleton(SemanticProcessor ::class, function ($app) {
            return new PasswordResetService();
        });
        $this->app->singleton(VnpayService ::class, function ($app) {
            return new VnpayService();
        });
        $this->app->singleton(MomoService ::class, function ($app) {
            return new MomoService();
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
