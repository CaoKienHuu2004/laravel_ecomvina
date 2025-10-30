<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;

use App\Models\GiohangModel;
use App\Observers\GioHangObserver;

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
     * Khởi động bất kỳ dịch vụ ứng dụng nào.

     */
    public function boot(): void
    {
        // GiohangModel::observe(GioHangObserver::class); // nếu muốn thay cho trigger database


    }
}
