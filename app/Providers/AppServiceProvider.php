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
        $this->app->singleton(SemanticProcessor::class, function ($app) {
            return new SemanticProcessor();
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
     * Khởi động bất kỳ dịch vụ ứng dụng nào.

     */
    public function boot(): void
    {
        //
        // Event::listen(UserRegistered::class, SendWelcomeEmail::class);
        // View::composer('sidebar', function ($view) {
        //     $view->with('categories', Category::all());
        // });
        // Collection::macro('toUpper', function () {
        //     return $this->map(function ($value) {
        //         return Str::upper($value);
        //     });
        // });
        // Validator::extend('phone_number', function ($attribute, $value, $parameters) {
        //     return preg_match('/^0[0-9]{9}$/', $value);
        // });
        // $this->publishes([
        //     __DIR__.'/../config/chat-ai.php' => config_path('chat-ai.php'),
        // ]);
        // Cấu hình các services nếu cần
        // $this->app->make(ChatAIService::class)->setDefaultModel('gpt-4');

        // // Đăng ký event listeners cho password reset
        // Event::listen(PasswordReset::class, function ($event) {
        //     // Xử lý khi password được reset
        // });

        // // Cấu hình view composers
        // View::composer('payment.*', function ($view) {
        //     $view->with('paymentMethods', [
        //         'vnpay' => $this->app->make(VnpayService::class),
        //         'momo' => $this->app->make(MomoService::class)
        //     ]);
        // });
    }
}
